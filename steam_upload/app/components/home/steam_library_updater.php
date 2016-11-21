<?php
	//binnenhalen van alle steam spellen(id) en speeltijd in de library van recieved_steamid gebruiker
	//function update_my_steam_library($recieved_steamid) {
if (isset($_GET['get_steamid'])) {
		
		
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/db_update_logger.php");
		


		$steamid = $_GET['get_steamid'];
		
		//!is_database_updated('steam_library', $steamid)
		
		
		if(!is_database_updated('steam_library', $steamid, 86400)){
			include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/components/home/game_knowledge_updater.php");
			
			//table leeghalen als er verouderde data instaat
            $stmt = $conn->prepare("	DELETE FROM $table_steam_lib
							WHERE steamid = ?");
            if (!$stmt){ echo "Error:". $stmt->error ; exit; }
            $stmt->bind_param( "i", $steamid);
            $stmt->execute();

            //connectie met steam en haal gebruikers games op
			$api_url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=$api_key&steamid=$steamid&format=json";
			$json = json_decode(file_get_contents($api_url), true);
			
			// loop door de api ontvangen games en voeg toe
			foreach($json["response"]["games"] as $game){
				$app_id = $game["appid"];
				$playtime_forever = $game["playtime_forever"];
				$sql = "
					INSERT INTO $table_steam_lib
					(steamid, appid, playtime_forever)
					VALUES(?,?,?)
					";
				$stmt = $conn->prepare($sql);
				if (!$stmt){
						echo "Error";
						exit;
					}
				
				mysqli_stmt_bind_param($stmt, "iii", $steamid, $app_id, $playtime_forever);
				mysqli_stmt_execute($stmt);
				$stmt->close();
				
				
			}
			update_game_knowledge();
			update_log_database('steam_library', $steamid);
		}// end time if
	$conn->close();
	
	
}//end if get 
?>