<?php


include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/db_update_logger.php");

function update_profile($recieved_steamid){
	

		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");


		$steamid = $recieved_steamid;
		
		//is de database recent geupdate
		if(!is_database_updated('steam_profile', $steamid, 3600)){
			
			
			//table leeghalen als er verouderde data instaat
            $sql = "	DELETE FROM $table_steam_profile WHERE steamid = ? ";

			$stmt = $conn->prepare($sql);
            if (!$stmt){ error_log( "Error in adding profile " ,0); exit; }
            $stmt->bind_param( "i", $steamid);
            $stmt->execute();


            $sql ="
			SELECT *
			FROM $table_steam_profile
			WHERE steamid = ?
		    ";


            //connectie met steam en haal gebruiker op
			$api_url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api_key&steamids=$steamid&format=json";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);

			$players = json_decode($output , true);

			$player = $players["response"]["players"][0];

			// data toekennen aan vars
			$personaname = $player["personaname"];
			$profileurl = $player["profileurl"];
			$avatarfull = $player["avatarfull"];
			$personastate = $player["personastate"];
			$lastlogoff = $player["lastlogoff"];
			
			$sql = "
				INSERT INTO $table_steam_profile
				(steamid, personaname, profileurl, avatarfull, personastate, lastlogoff)
				VALUES(?,?,?,?,?,?)
				";
			$stmt = $conn->prepare($sql);
			if (!$stmt){
					echo "Error";
					exit;
				}
			
			mysqli_stmt_bind_param($stmt, "ssssii", $steamid, $personaname, $profileurl, $avatarfull, $personastate, $lastlogoff);
			mysqli_stmt_execute($stmt);
			$stmt->close();


			update_log_database('steam_profile', $steamid);	
		}// end if upadated
		
		
	$conn->close();
	
	
}//end function




?>