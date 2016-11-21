<?php
	// de data(prijs,naam,....) ophalen van de spellen op basis van hun appid
	function update_game_knowledge() {
		include($_SERVER["DOCUMENT_ROOT"] . "/steam/app/shared/php_connect.php");
		
		//zoek alle appid's die in de database staan
		$sql = "
		SELECT appid
		FROM $table_steam_lib
		";
		$results = $conn->query($sql);
		
		while($result = mysqli_fetch_assoc($results)) {
			
			$appid = $result['appid'];
			//zoeken naar appid uit de gebruikers library die hetzelfde is uit de huidige game info database
			$sql = "
			SELECT appid
			FROM $table_steam_game_info
			WHERE appid=$appid
			";
			
			$appid_match_result = $conn->query($sql);
			$appid_match = mysqli_fetch_assoc($appid_match_result);
			
			// als er geen match te vinden is maak dan nieuwe aan
			// als er wel een match is hebben we de info al en hoeft er niks te gebeuren
			if(!$appid_match){			
				$app_name = '';
				$price = 0;
				$currency = '';
				
				// connectie met steam store api voor prijzen
				$api_url ="http://store.steampowered.com/api/appdetails?appids=$appid";
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
			
				//$game = json_decode(file_get_contents($api_url), true);
				$game = json_decode($output , true);
				
				
				
				// als de appid daadwerkelijk een game vind
				if( $game["$appid"]["success"] == 'true' ){
					
					
					if(isset($game["$appid"]["data"]["name"])){
							$app_name = $game["$appid"]["data"]["name"];
					};
					
					if(isset($game["$appid"]["data"]["price_overview"]["initial"])){
						$price = $game["$appid"]["data"]["price_overview"]["initial"];
					} else {
						$price = 0;	
					}
					
					if(isset($game["$appid"]["data"]["price_overview"]["currency"])){
						$currency = $game["$appid"]["data"]["price_overview"]["currency"];
					} else {
						$currency = 'EUR';	
					}
				}	else {
					
					$app_name = '0';
					$price = 0;
					$currency = 'EUR';
					
				}
					
				// invoegen van nieuwe info over games
				// negeren als het al appid al bestaat in database (overbodig maar zekerheid)
				$sql = "
						INSERT IGNORE INTO $table_steam_game_info
						(appid, app_name, price, currency)
						VALUES(?,?,?,?)
						";
				$stmt = $conn->prepare($sql);
				
				if (!$stmt){
						echo "Error";
						exit;
				}
				mysqli_stmt_bind_param($stmt, "isis", $appid, $app_name, $price, $currency);
				mysqli_stmt_execute($stmt);
				
				
			} else {	/*echo 'already in db';*/	}
		}//end while
		$conn->close();
	}//end func
?>