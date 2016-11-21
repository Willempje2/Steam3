( function() {
	// 1. defenitie
	angular.module('Steam_app').controller('friendlistController', friendlistController_fn);
	
	//2. dependencies injecteren
	friendlistController_fn.$inject = ['$scope', '$http', '$routeParams' ];
	
	// 3. implementatie van friendlistController_fn
	function friendlistController_fn($scope, $http, $routeParams) {
		
		//steamid van url in een variable
		var steamid = $routeParams.name;
		$scope.steamid_url = $routeParams.name;
		
		$scope.load_done = false;
		
		function update_friend_data() {
			var url = 'app/components/friendlist/update_friendlist.php';
			$http.get(url + '?get_steamid=' + steamid).success(function() {
				  get_friendlist();
				})
				.error(function(status) {
				  console.error('Repos error', status);
				});
		}
		
		function get_friendlist() {
			var url = 'app/components/friendlist/get_friendlist.php';
			$http.get(url + '?get_steamid=' + steamid).success(function(data) {
				  $scope.friend_list = data;
				  $scope.load_done = true;
				})
				.error(function(status) {
				  console.error('Repos error', status);
				  $scope.load_done = true;
				});
		}
	
		// turn personastate naar string data
		$scope.translate_personastate = function(data_state){
			switch(parseInt(data_state)) {
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
		$scope.timestamp_to_delta_time = function(last_online){
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
			update_friend_data();
		} else {
			console.log('steamid to short or long');
			$scope.load_done = true;
		}


		
		
	};
	
	
})();
