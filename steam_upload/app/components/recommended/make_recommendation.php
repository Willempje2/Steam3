<?php

	if (isset($_GET['get_steamid'])) {

        include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");

        $steamid = $_GET['get_steamid'];

        $steamid_lenght = strlen((string)$steamid);


        if ($steamid_lenght < 18 && $steamid_lenght > 16) {

            $sql = "
                SELECT *
                FROM $table_steam_lib
                ;
            ";

            $result = $conn->query($sql);
            if (!$result) {
                error_log("No results", 0);
                exit;
            }

            $voorkeuren = array();
            $most_played_game = array();
            // items in logische volgorde zetten
            $items = array();
            while ($row = mysqli_fetch_array($result)) {
                $items[] = $row;
                if ($row['playtime_forever'] != 0) {
                    if (!array_key_exists($row['steamid'], $voorkeuren)) {
                        $voorkeuren += array($row['steamid'] =>
                            array($row['appid'] => $row['playtime_forever'])
                        );
                    } else {
                        $voorkeuren[$row['steamid']] += array($row['appid'] => $row['playtime_forever']);
                    }

                    if (!array_key_exists($row['steamid'], $most_played_game)) {
                        $most_played_game += array($row['steamid'] => $row['playtime_forever']);
                    } elseif ($most_played_game[$row['steamid']] < $row['playtime_forever']) {
                        $most_played_game[$row['steamid']] = $row['playtime_forever'];
                    }
                }
            }


            foreach ($voorkeuren as $steamids => $games) {
                foreach ($games as $appids => $playtime) {
                    $voorkeuren[$steamids][$appids] = ($voorkeuren[$steamids][$appids] / $most_played_game[$steamids]) * 5;
                }
            }

            require_once($_SERVER["DOCUMENT_ROOT"] . "/steam/app/components/recommended/calc_recommended.php");


            $re = new Recommend();
            $ratings = $re->getRecommendations($voorkeuren, $steamid);
            //error_log( print_R($ratings, true) , 0 );
            $conn->close();
            header('Content-Type: application/json');


            //error_log( print_R($re->getRecommendations($voorkeuren, $steamid), true) , 0 );
            echo json_encode($ratings);
            exit;

        }// end if string length
		
	}//end if get 


?>