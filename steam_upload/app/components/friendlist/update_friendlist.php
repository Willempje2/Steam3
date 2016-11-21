<?php

if (isset($_GET['get_steamid'])) {
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/db_update_logger.php");

		$steamid = $_GET['get_steamid'];
		
		
		if(!is_database_updated('steam_friendlist', $steamid, 86400)){
			
			
			//table leeghalen als er verouderde data instaat
            $stmt = $conn->prepare("	DELETE FROM $table_steam_friendlist
							            WHERE steamid = ?");
            if (!$stmt){ echo "Error:". $stmt->error ; exit; }
            $stmt->bind_param( "i", $steamid);
            $stmt->execute();


            //connectie met steam en haal gebruikers namen op
			$api_url = "http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=$api_key&steamid=$steamid&relationship=friend&format=json";
			$json = json_decode(file_get_contents($api_url), true);
			
			// loop door de api ontvangen games en voeg toe
			foreach($json["friendslist"]["friends"] as $friend){
				
				$friend_id = $friend["steamid"];
				
				$sql = "
					INSERT INTO $table_steam_friendlist
					(steamid, friend_id)
					VALUES(?,?)
					";
				$stmt = $conn->prepare($sql);
				if (!$stmt){
						error_log("No results",0);
						exit;
					}
				
				mysqli_stmt_bind_param($stmt, "ii", $steamid, $friend_id);
				mysqli_stmt_execute($stmt);			
				
				$stmt->close();
				
			}
			
			update_log_database('steam_friendlist', $steamid);
		}// end update if
		
	$conn->close();
	
}//end if get 

?>