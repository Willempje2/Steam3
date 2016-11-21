( function() {
	// 1. defenitie
	angular.module('Steam_app').controller('homeController', homeController_fn);
	
	//2. dependencies injecteren
	homeController_fn.$inject = ['$scope', '$http', '$routeParams', '$q' ];
	
	// 3. implementatie van homecontroller_fn
	function homeController_fn($scope, $http, $routeParams, $q) {
			
		// 76561198007202203 = jeroen
		// 76561198011289296 = willem
		
		$scope.load_done = false;
		
		//steamid van url in een variable
		var steamid = $routeParams.name;
		$scope.steamid_url = $routeParams.name;
		
		
		//profiel updaten en ophalen
		function get_profile_data() {
			var url = 'app/components/home/profile_data.php';
			$http.get(url + '?get_steamid=' + steamid).success(function(data) {
				  $scope.profile_data = data[0];		  
				  $scope.real_personastate = translate_personastate( parseInt($scope.profile_data.personastate) );
				  $scope.time_till_logoff = timestamp_to_delta_time( $scope.profile_data.lastlogoff );
				  update_steam_lib();
				})
				.error(function(data, status) {
				  console.error('Repos error', status, data);
				  $scope.load_done = true;
				});
		}
		
		//steam library updaten
		function update_steam_lib() {
			var url = 'app/components/home/steam_library_updater.php';
			$http.get(url + '?get_steamid=' + steamid).success(function() {
				console.log('Updated_steam_lib');
				get_game_data();
			})
			.error(function(data, status) {
				console.error('Repos error', status);
				$scope.load_done = true;
			});
			
		}
		
		// data uit database ophalen
		function get_game_data() {
			var url = 'app/components/home/data_controller.php';
			$http.get(url + '?get_steamid=' + steamid).success(function(data) {
				  $scope.load_done = true;
				  $scope.game_data = data;
				})
				.error(function(data, status) {
				  console.error('Repos error', status, data);
				  $scope.load_done = true;
				});
		}
		


		
		// data.playtime_forever omzetten in een integer
		$scope.sort_to_int = function(data){
			return parseInt(data.playtime_forever);
		};
		
		// turn personastate naar string data
		function translate_personastate(data_state){
			switch(data_state) {
				case 0:
					return "Offline";
				case 1:
					return "Online";
				case 2:
					return "Busy";
				case 3:
					return "Away";
				case 4:
					return "Snooze";
				case 5:
					return "looking to trade";
				case 6:
					return "looking to play";
			} ;
		};
		
		// timestamp naar stukje tekst maken
		function timestamp_to_delta_time(last_online){
			// huidige tijd binnenhalen
			var date_now =  Date.now() / 1000;
			// get total seconds between the times
			var delta = Math.abs(date_now - last_online) ;
			// calculate (and subtract) whole days
			var days = Math.floor(delta / 86400);
			delta -= days * 86400;
			// calculate (and subtract) whole hours
			var hours = Math.floor(delta / 3600) % 24;
			delta -= hours * 3600;
			// calculate (and subtract) whole minutes
			var minutes = Math.floor(delta / 60) % 60;
			delta -= minutes * 60;
			// what's left is seconds
			var seconds = Math.floor(delta) % 60;  // in theory the modulus is not required
			return "Last seen online: " + days + " days, " + hours + " hours, " + minutes + " minutes and " + seconds + " seconds ago ";
		};


		if (steamid.length > 16 && steamid.length < 18) {
			get_profile_data();
		} else {
			console.log('steamid to short or long');
			$scope.load_done = true;
		}

		
	};
	
})();
