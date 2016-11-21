<?php

if (isset($_GET['get_steamid'])) {

    include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");

    $steamid = $_GET['get_steamid'];
    $steamid_lenght = strlen((string)$steamid);

    if ($steamid_lenght < 18 && $steamid_lenght > 16) {
        $sql = "
			SELECT *
			FROM $table_steam_update
			WHERE steamid=? AND table_name='steam_library'
			;
		";

        $stmt = $conn->prepare($sql);
        if (!$stmt){ echo "Error:". $stmt->error ; exit; }
        $stmt->bind_param( "i", $steamid);
        $stmt->execute();
        $result = $stmt->get_result();

        $result = mysqli_fetch_array($result);
        $conn->close();

        if (!$result) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }
}

?>