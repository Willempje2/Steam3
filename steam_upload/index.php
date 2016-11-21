<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Steam EURO/H</title>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/stylesheet.css" rel="stylesheet">
</head>
<body ng-app="Steam_app" >

	<header id="main_header">
    	<div>
        	<h3>
                <a href="#!/"><img src="assets/images/Steam-icon.png" width="50" height="50" alt="steam calc ico">
                	<strong>Steam Euro / Hour calculator</strong>
                </a>
            </h3>
        </div>
    </header>

	
    
    <div ng-view></div>
    
    
    <div id="footer_spacer">
    <footer id="main_footer">
    © 2016 Copyright Willem Huijben
    </footer>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>

	<!-- Angular bestanden -->
	<script src="assets/js/angular.min.js"></script>
    <script src="assets/js/angular-route.min.js"></script>
    <script src="assets/js/angular-resource.min.js"></script>
	   
	<script src="app/app_module.js"></script>
   	<script src="app/components/home/homeController.js"></script>
    <script src="app/components/sign_in/sign_inController.js"></script>
    <script src="app/components/recommended/recommendedController.js"></script>
    <script src="app/components/friendlist/friendlistController.js"></script>
    
</body>
</html>
