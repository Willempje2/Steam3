<?php
	// return true or false als data wel of niet vernieuwd moet worden
	// aka als langer dan X uur geleden is geweest
	function is_database_updated($table_name, $recieved_steamid, $input_time_limit){
		
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		
		//86400
		$time_limit = $input_time_limit;
		$steamid = $recieved_steamid;
		//huidige tijd binnenhalen voor latere vergelijkingen
		date_default_timezone_set("UTC");	
		$current_time_stamp = time() ;
		
		$sql = "
		SELECT last_update
		FROM $table_steam_update
		WHERE table_name= '$table_name' AND steamid = ?
		";

        $stmt = $conn->prepare($sql);
        if (!$stmt){ echo "Error:". $stmt->error ; exit; }
        $stmt->bind_param( "i", $steamid);
        $stmt->execute();
        $result = $stmt->get_result();


        $is_updated = FALSE;
		
		if(!$result){
			$is_updated = FALSE;
		} else {
			$last_update = mysqli_fetch_assoc($result);
			
			//bereken secondes tussen updates
			$time_between_updates = $current_time_stamp - $last_update['last_update'];
			//erg handige log om te kijken hoelang geleden er geupdate is.
			//error_log($current_time_stamp. " - " . $last_update['last_update'] . " = " . $time_between_updates , 0);
			
			if($time_between_updates > $time_limit){$is_updated = FALSE;}
			else {$is_updated = TRUE;}
		}
		
		$conn->close();
		return $is_updated;
		
	}
	
	//vernieuwd de last_upate tijd naar de huidige tijd van ingevoegde table
	function update_log_database($table_name, $recieved_steamid){
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		//include 'app/shared/php_connect.php';
		$steamid = $recieved_steamid;
		$updated_table = $table_name;
		//tijd ophalen en opslaan
		date_default_timezone_set("UTC");	
		$current_time_stamp = time() ;
		// nieuwe timestamp in de database zetten en vernieuwen als al bestaan
		$sql = "
			INSERT INTO $table_steam_update
			  (table_name, last_update, steamid)
			VALUES
			  ('$updated_table', '$current_time_stamp', ?)
			ON DUPLICATE KEY UPDATE
			  last_update = $current_time_stamp
		";

        $stmt = $conn->prepare($sql);
        if (!$stmt){ echo "Error:". $stmt->error ; exit; }
        $stmt->bind_param( "i", $steamid);
        $stmt->execute();

		$conn->close();
	}
	

?>