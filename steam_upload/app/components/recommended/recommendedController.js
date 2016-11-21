( function() {
	// 1. defenitie
	angular.module('Steam_app').controller('recommendedController', recommendedController_fn);
	
	//2. dependencies injecteren
	recommendedController_fn.$inject = ['$scope', '$http', '$routeParams' ];
	
	// 3. implementatie van recommendedController_fn
	function recommendedController_fn($scope, $http, $routeParams) {

		//steamid van url in een variable
		var steamid = $routeParams.name;
		$scope.steamid_url = $routeParams.name;
		$scope.update_warning = true;

		//profiel updaten en ophalen
		function get_recommended() {
			var url = 'app/components/recommended/make_recommendation.php';
			$http.get(url + '?get_steamid=' + steamid).success(function (data) {
				var new_array = [];
				for (var prop in data) {
					//if (data[prop] > 1) {
						new_array.push({
							app_id: prop,
							app_rating: data[prop]
						});
						//console.log( "Rating van:" + prop + " Heeft score van : " + data[prop] );
					//}
				}
				$scope.recommended_data = new_array;
			})
				.error(function (data, status) {
					console.error('Repos error', status, data);
				});
		}


		function library_update_check() {
			var url = 'app/shared/test_db_update.php';
			$http.get(url + '?get_steamid=' + steamid).success(function (data) {
				if (data == 'true') {
					get_recommended();
				} else {
					$scope.update_warning = false;
				}
			})
				.error(function (data, status) {
					console.error('Repos error', status, data);
				});
		}

		if (steamid.length > 16 && steamid.length < 18) {
			library_update_check();
		} else {
			$scope.update_warning = false;

		}
	};

})();
