( function () {
	
var app = angular.module('Steam_app', [
	'ngRoute'
]);

app.config(['$routeProvider', '$locationProvider',
  function($routeProvider, $locationProvider) {
    $routeProvider.
    when('/', {
      templateUrl: 'app/components/sign_in/sign_in.html',
      controller: 'sign_inController'
    })
	.when('/home/:name', {
      templateUrl: 'app/components/home/home.php',
      controller: 'homeController'
    })
	.when('/friendlist/:name', {
      templateUrl: 'app/components/friendlist/friendlist.html',
      controller: 'friendlistController'
    })
	.when('/recommended/:name', {
      templateUrl: 'app/components/recommended/recommended.html',
      controller: 'recommendedController'
    });
    $locationProvider.html5Mode(false).hashPrefix('!');
  }]);


})();

