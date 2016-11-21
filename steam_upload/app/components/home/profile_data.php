<?php



if (isset($_GET['get_steamid'])) {
	
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/add_profile.php");

		

		$steamid = $_GET['get_steamid'];
		
		update_profile($steamid);
		
		
		$sql ="
			SELECT *
			FROM $table_steam_profile
			WHERE steamid = ?
		";

        $stmt = $conn->prepare($sql);
        if (!$stmt){ echo "Error:". $stmt->error ; exit; }
        $stmt->bind_param( "i", $steamid);
        $stmt->execute();
        $result = $stmt->get_result();


		if (!$result) {
			error_log("No results",0); 
			exit;
		}
		
		$items = array();
		while($row = mysqli_fetch_array($result)) {
			$items[] = $row;
		}
		
		header('Content-Type: application/json');
		
		$conn->close();
		
		echo json_encode($items);
		exit;
		
		
		
		
	$conn->close();
	
	
}//end if get 




?>