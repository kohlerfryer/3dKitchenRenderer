@extends('layouts.page')

@section('content')
	<center>
		<div class="kitchen_dreamer"><img src="images/kitchen_backgrounds/kitchen_1.jpg" height="400px" width="100%"></img></div>
	</center>
	<div style="max-width:900px;width:100%;margin:auto" id="pick_stone_view">

		<div id="stone_type_list" class="evenly">
			<span class="selected list_option"><h3>Granite</h3></span>
			<span class="list_option"><h3>Quartz</h3></span>
			<span class="list_option"><h3>Marble</h3></span>
			<span><h3>Tier:<dropdown class="selected" style="display:inline-block">All</dropdown></h3></span>
		</div>

		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
		<div class="stone_texture_tile" style="width:33%;height:300px;background-color:black;display:inline-block">
		</div>
	</div>

	<div id="stone_info_view" style="max-width:900px;width:100%;margin:auto;" hidden>
		<div style="width:60%;display:inline-table;text-align:center">
			<a href="#" onclick="javascript:return false" id="btn_back" style="color:black;float:left;padding-top:5px"><h3 style="margin-bottom:0px;">Back</h3></a>
			<h2 style="display:inline-block;padding-right:30px">Stone Title</h2>
			<div style="width:100%;height:300px;background-color:black">
			</div>
			<div id="stone_description" style="padding:10px;font-family:helvetica;font-weight:100">
				a;ldjf;alsjfa;lsjfa;ls fa;lksjdf ;lakjs df;lajd fa;jksdf alksjdf a;ljsdf lajsd fl;ajd sf
				;laksdjf ;alksdjf alksdjf a;ldjkf al;skdfj a;ldjf a;ldjsf aldjksf al;ksfj as
				;alkdjf ;alkdjs f;alkjdfalkjsdf a;ldjsf a;ljkdsf ;lajsd fl;adjf
			</div>
		</div>
		<div style="width:34%;display:inline-table;margin-left:3%">
			<center><h2 style="margin-top:0px">Instant Quote</h2></center>
			<form id="instant_quote_form">
			<h4>Name</h4>
			<input id="name" name="name">
			<h4>Email</h4>
			<input id="email" name="email">
			<h4>Phone Number</h4>
			<input id="phone_number" name="phone_number">
			<h4>Square Feet</h4>
			<input id="square_feet" name="square_feet">
			<button style="color:white;font-size:15px;margin-top:20px;background-color:#f67555;border-radius:4px;width:100px;height:40px;border:none">Get Quote</button>
			</form>
		</div>
	</div>

</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
@endsection