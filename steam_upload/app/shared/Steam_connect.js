( function () {

	angular.module('Steam_app').factory('steam_factory', ['$http', '$q', function($http, $q) {
		
		var factory = {};
		/*
		factory.getsteamdata_fn = function(){
			var deferred = $q.defer();
			
			
			$http.jsonp('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=35B81AAF141F8665B53E262DB9D12E6D&steamids=76561197960435530' + '&format=json&jsonp=amazing')
			.success(function() {
				//deferred.resolve(data.results);
			})
			.error(function() {
				//deferred.reject();
			});
			
			
				
		}
		*/
		
		return factory;
		
		
	}]);
})();