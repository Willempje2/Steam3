( function() {
	// 1. defenitie
	angular.module('Steam_app').controller('sign_inController', sign_inController_fn);
	
	//2. dependencies injecteren
	sign_inController_fn.$inject = ['$scope', '$location' ];
	
	// 3. implementatie van sign_inController_fn
	function sign_inController_fn($scope, $location) {
			
			//doorsturen met steamid in de url
			$scope.send = function () {
				$location.path('/home/' + $scope.name );
			};
				
	};
	
	
})();
