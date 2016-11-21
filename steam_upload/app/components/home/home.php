<div id="navbar">
		<a ng-href="#!/" class="nav_item">
			<span>Home</span>
		</a>
           			
		<a ng-href="index.php#!/home/{{steamid_url}}" class="nav_item active">
			<span>Library</span>
		</a>

						
		<a ng-href="index.php#!/friendlist/{{steamid_url}}" class="nav_item">
			<span>Friendlist</span>
		</a>

						
		<a ng-href="index.php#!/recommended/{{steamid_url}}" class="nav_item">
			<span>Recommended</span>
		</a>
								
</div>

<div id="profile_holder">
    <div class="col-sm-4">
    	<img ng-src="{{profile_data.avatarfull}}" alt="Avatar"/>
    </div>
    
    <div class="col-sm-8">
        <ul>
        	<li><h3><a target="_blank" ng-href="{{profile_data.profileurl}}" >{{profile_data.personaname}}</a></h3></li>
            <li><h3>Status: {{real_personastate}}</h3></li>
            <li ng-if="profile_data.personastate == 0"><h3>{{time_till_logoff}}</h3></li>
            <li>This info is updated every hour</li>

            
        </ul>
    </div>
</div>



<div class="loading" ng-class="{'hidden': load_done}">
    <h2> This may take a few seconds... please wait a moment. </h2>
    
    <div class="cs-loader">
      <div class="cs-loader-inner">
        <label>	●</label>
        <label>	●</label>
        <label>	●</label>
        <label>	●</label>
        <label>	●</label>
        <label>	●</label>
      </div>
    </div>

</div>


<div ng-repeat="game in game_data | orderBy:sort_to_int:true" class="game_box">
    <div class="col-sm-8">
        <p><h1><a ng-href="http://store.steampowered.com/app/{{game.appid}}/">{{game.app_name}}</a></h1><p>
        <p>Price: &euro; {{game.price /100 }}<p>
        <p>Playtime: {{(game.playtime_forever / 60) | number:2}} hour<p>
    </div>
    <div class="col-sm-4 price_hour">  	
            <h1>{{((game.price/100) / (game.playtime_forever / 60)) | number:2}} <strong>&euro;/h</strong></h1>
    </div>
</div>