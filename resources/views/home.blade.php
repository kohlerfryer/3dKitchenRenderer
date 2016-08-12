<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

	<title>Mountain West Design</title>
	<meta name="description" content="Mountain West Design">
	<meta name="author" content="SitePoint">
	<meta name="viewport" content="width=device-width, maximum-scale=1">


	<link rel="stylesheet" href="css/slider_menu.css">
	<link rel="stylesheet" href="css/main.css">
	<script src="js/jquery.js"></script>
	<script src="js/main.js"></script>
	<script src="js/classie.js"></script>

</head>

<body>
	<div class="main_header">
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
	      <a href="/portfolio">Portfolio</a>
	      <a href="/kitchen_dreamer">Browse</a>
	      <a href="/about_us">About Us</a>
          @if(Auth::guest())
           <a href="/login">Login</a>
          @else
           <a href="/auth/logout">Sign Out</a>
          @endif
	    </nav>
		 <div id="main_page_logo">
		 	<img src="images/mountain_logo.png" width="70px"/>
		 	<img id="words_logo" src="images/mountain_words_logo.png" width="150px"/>
		 </div>
		<div class="navbar_mobile">
			<button id="showRight" >Menu</button>
		</div>
		<div class="navbar">
			<ul>
			<li><a href="#" >13231 Minuteman Dr Draper, UT</a></li>
			<li><a href="#" >801-998-8195</a></li>
			<li><a href="/portfolio" class="navbar_link">Portfolio</a></li>
			<li><a href="/about_us" class="navbar_link">About Us</a></li>
			@if(Auth::guest())
				<li><a href="/register" class="navbar_link">Sign In</a></li>
	        @else
		        <li class="account_drop_down">
		          <a href="#" class="navbar_link" >{{Auth::user()->first_name}}</a>
		          <ul hidden>
		            <li><a class="navbar_link" href="/auth/logout" >Sign Out</a></li>
		          </ul>
		        </li>
			@endif
			</ul>
		</div>

		<center>
			<h1>Affordable Luxury</h2>
			<h2>Find granite, quartz, and marble at a fraction of the cost</h2>
			<button id="btn_get_quote">Get Custom Quote</button>
		</center>

		<div class="kitchen_builder_header">
			<h3>Visualize your kitchen countertops with our custom online tool</h3>
			<button id="btn_customize">Customize</button>
		</div>
	</div>
	<div class="tile_button" onclick="window.location='/kitchen_dreamer?selected_stone_type=quartz'">
		<img src="images/quartz.jpg" width="100%" height="500px" >
		<h1>Quartz</h1>
	</div>
	<div class="tile_button" onclick="window.location='/kitchen_dreamer?selected_stone_type=marble'">
		<img src="images/marble.jpg" width="100%" height="500px" >
		<div class="left">
			<h1 >Marble</h1>
		</div>
	</div>
	<div class="tile_button" onclick="window.location='/kitchen_dreamer?selected_stone_type=granite'">
		<img src="images/granite.jpg" width="100%" height="500px" >
			<h1>Granite</h1>
	</div>
</body>
</html>