@extends('layouts.page')

@section('content')
	<center style="margin-top:20px">
		<table style="width:100%;padding:20px;background-color:#F5F5F5;font-family:helvetica;font-weight:lighter;max-width:900px;">
			<tr><th style="text-align:middle"><h2>Quote</h2></th></tr>
			@if(Session::has('quote_data'))
				@foreach(Session::get('quote_data')['countertops'] as $key => $countertop)
				<tr>
					<th><a href="/kitchen_dreamer/delete_from_quote/{{$key}}/?selected_stone_type={{$selected_stone_type}}&selected_room_id={{$selected_room->id}}">Delete</a></th>
					<th>{{$countertop['room_type']}}</th>
					<th>200" X 200"</th>
					<th><img src="{{asset('images/granite/15364.jpg')}}" height="40px" width="40px" style="border-radius:5px"/></th>
				</tr>
				@endforeach
				<tr>
					<th>
						<form action="/kitchen_dreamer/get_instant_quote">
							<button  style="color:white;font-size:20px;margin-top:20px;background-color:#f67555;border-radius:4px;height:50px;border:none">Get Estimate</button>
						</form>
					</th>
				</tr>
			@else
			<tr><th>No Countertops added</th></tr>
			@endif
		</table>
	</center>

	<center>
		<div class="kitchen_dreamer" style="position:relative;width:100%;max-width:900px;max-height:500px">
			<img id="background_image" src="{{asset($selected_room->picture_url)}}" room_id="{{$selected_room->id}}" style="position:absolute;top:0;left:0;right:0;bottom:0;max-width:900px;max-height:500px;width:100%;height:100%"/>
			<img id="main_counter_top" src="{{asset('images/room_backgrounds/kitchen_2_layers.png')}}"  style="position:absolute;top:0;left:0;right:0;bottom:0;max-width:900px;max-height:500px;width:100%;height:100%" hidden/>
			<img  src="{{asset('images/gear-setting.png')}}" height="25px" width="25px" id="settings_gear" style="position:absolute;left:0;absolute;top:0;margin:4px"/>
			<ul class="top-level-menu" id="settings_drop_down" style="position:absolute;" >
			    <li>
			        <a href="#" style="border:solid 1px;color:white;border-color:white;background:transparent">Settings</a>
			        <ul class="second-level-menu">
			            <li>
			                <a class="kitchen-settings" href="#">Room</a>
			                <ul class="third-level-menu kitchen_settings">
			                	@foreach($room_backgrounds as $room_background)
			                    <li><a class="kitchen-settings room" id="{{$room_background->id}}" picture_url="{{$room_background->picture_url}}" href="#">{{$room_background->name}}</a></li>
								@endforeach	
				                </ul>
			            </li>			         
			            <li>
			                <a class="kitchen-settings" href="#">Floor</a>
			                <ul class="third-level-menu kitchen_settings">
			                    <li><a class="kitchen-settings" href="#">wood</a></li>
			                    <li><a class="kitchen-settings" href="#">white tile</a></li>
			                    <li><a class="kitchen-settings" href="#">black tile</a></li>
			                </ul>
			            </li>
			        </ul>
			    </li>
			</ul>
		</div>
	</center>
	<div style="max-width:900px;width:100%;margin:auto" id="pick_stone_view">

		<div id="stone_type_list" class="evenly page">
			@foreach ($stone_types as $stone_type)
				@if (trim($stone_type->type) == trim($selected_stone_type))
				<span class="list_option"><h3 class="selected"><a class="stone_type" link="?selected_stone_type={{trim($stone_type->type)}}">{{trim($stone_type->type)}}</a></h3></span>
				@else
				<span class="list_option"><h3><a  class="stone_type" link="?selected_stone_type={{trim($stone_type->type)}}">{{trim($stone_type->type)}}</a></h3></span>
				@endif
			@endforeach
			<span><h3>Tier:<dropdown class="selected" style="display:inline-block">All</dropdown></h3></span>
		</div>

		@foreach ($stones as $stone)
			<img
			id="{{$stone->id}}"
			stone-description="{{$stone->stone_description}}"
			stone-title="{{$stone->stone_name}}"
			in-stock-quantity="{{$stone->in_stock_quantity}}"
			price="{{$stone->stone_price_per_square_foot}}"
			stone-picture-url="{{$stone->stone_picture_url}}"
			class="stone_texture_tile" 
			src="{{asset($stone->stone_texture_url)}}" 
			/>
		@endforeach


	</div>

	<div id="stone_info_view" style="max-width:900px;width:100%;margin:auto;" hidden>
		<div id="stone_summary"style="width:50%;display:inline-table;text-align:center">
			<img id="btn_back" src="{{asset('images/back_arrow.png')}}" src="" height="50px" width="50px" style="float:left;margin-top:20px"/>
			<h2 id="stone_title" style="display:inline-block;padding-right:30px"></h2>
			<img id="stone_image" style="width:100%;height:300px;" src=""/>
			<div id="stone_description" style="padding-top:10px;padding-bottom:10px;font-family:helvetica;font-weight:100;text-align:left">
			</div>
			<div id="instock_quantity" style="text-align:left">
			</div>
		</div>
		<div style="width:47%;display:inline-table;text-align:middle">
				<center>
					<h2 style="display:inline-block;">Add Counter to Quote</h2></br>
				</center>
				<form id="add_stone_form" action="/kithen_dreamer/add_stone_to_quote" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input  name="stone_id" value="" id="input_stone_id" style="display:none">
					<div id="add_stone_dimensions" style="position:relative">
						<div id="dimensions-island" hidden>
							<center>
								<h3>Dimensions</h3>
							</center>
							  <div style="width:330px;position:relative;margin:auto;padding-bottom:60px">
							    <div style="width:100px;height:200px;background-color:black;margin:auto">
							    </div>
							    <input style="position:absolute;top:90px;left:5px;width:100px" placeholder="height">
							    <input style="position:absolute;bottom:30px;left:115px;width:100px;" placeholder="width">
								<button type="button" class="btn_hide_dimensions" style="position:absolute;bottom:0;left:0;width:100px;">Return</button>
							  </div>
						</div>
						<div id="dimensions-l-shape" hidden>
							  <div style="width:330px;position:relative;margin:auto;padding-bottom:70px;padding-top:40px">
							    <div style="position:absolute;left:0px;width:120px;height:100px;background-color:black;">
							    </div>
							   <div style="width:100px;height:200px;background-color:black;margin:auto">
							    </div>
							    <input style="position:absolute;top:170px;left:5px;width:100px" placeholder="height">
							    <input style="position:absolute;bottom:35px;left:115px;width:100px;" placeholder="width">    
							    <input style="position:absolute;bottom:150px;left:225px;width:100px;" placeholder="Height">
							    <input style="position:absolute;top:5px;left:50px;width:100px;" placeholder="Width">
							    <input style="position:absolute;top:80px;left:10px;width:100px;" placeholder="Height">
								<button type="button" class="btn_hide_dimensions" style="position:absolute;bottom:0;left:0;width:100px;">Return</button>

							  </div>
						</div>
					</div>
					<table align="right" id="add_stone_table" >
						<tr>
							<th>
								<h4 style="display:inline-block">Room</h4>
							</th>
							<th>
								<select name="room_type" type="select" style="width:150px;height:20px;display:inline-block">
									<option>Kitchen</option>
									<option>Bathroom</option>
									<option>Basement</option>
								</select>
							</th>
						</tr>
						<tr>
							<th>
								<h4 style="display:inline-block">Edge Type</h4>
							</th>
							<th>
								<select name="corner_type" id="input1" type="select" style="width:150px;height:20px;display:inline-block">
									<option>Basic Rounded</option>
								</select>
							</th>
						</tr>
						<tr>
							<th>
								<h4>Type</h4>
							</th>
							<th>
								<span style="display:inline-block">Island</span><input class="counter_type" type="radio" name="shape" value="island" style="width:15px;height:15px;display:inline-block;box-shadow:none">
								<span style="display:inline-block">L-Shape</span><input class="counter_type" type="radio" name="shape" value="l-shape" style="width:15px;height:15px;display:inline-block;box-shadow:none">
							</th>
						</tr>
						<tr>
							<th>
								<h4 style="display:inline-block">Contains Sink?</h4>
							</th>
							<th>
								<span  style="display:inline-block">Yes</span><input type="radio" name="sink" value="yes" style="width:15px;height:15px;display:inline-block;box-shadow:none">
								<span  style="display:inline-block">No</span><input type="radio" name="sink" value="no" style="width:15px;height:15px;display:inline-block;box-shadow:none">
							</th>
						</tr>
						<tr>
							<th>
								<h4 style="display:inline-block">Seamless?</h4>
							</th>
							<th>
								<span style="display:inline-block">Yes</span><input type="radio" name="seamless" value="yes" style="width:15px;height:15px;display:inline-block;box-shadow:none">
								<span  style="display:inline-block">No</span><input type="radio" name="seamless" value="no" style="width:15px;height:15px;display:inline-block;box-shadow:none">
							</th>
						</tr>
						<tr>
							<th>
								<button type="button" id="btn_view_dimensions" style="background-color:#F5F5F5;border:grey solid 1px;color:black;width:110px;height:30px">Dimensions</button>
							</th>
							<th>
								<h4 style="display:inline-block">0' x 0'</h4>
							</th>
						</tr>
						<tr>
							<th>
								<h4>Quick Estimate:</h4>
							</th>
							<th>
								<h4>$500</h4>
							</th>
						</tr>
						<tr>
							<th>
								<button style="color:white;font-size:17px;margin-top:20px;background-color:#f67555;border-radius:4px;height:40px;border:none">Add to Quote</button>
							</th>
						</tr>
					</table>
			</form>
		</div>

		@if(isset($guest_data))
			<!--<div style="width:34%;display:inline-table;margin-left:3%">
				<center><h2 style="margin-top:0px">Quick Quote</h2></center>
				<form id="instant_quote_form">
				<h4>Name</h4>
				<input id="name" name="name" value="{{$guest_data['name']}}">
				<h4>Email</h4>
				<input for="email" id="email" name="email" value="{{$guest_data['email']}}">
				<h4>Phone Number</h4>
				<input id="phone_number" name="phone_number" value="{{$guest_data['phone_number']}}">
				<h4>Square Feet</h4>
				<input id="square_feet" name="square_feet" value="{{$guest_data['square_feet']}}">
				<input id="quote_stone_id" name="stone_id" type="hidden">
				<button type="button" id="btn_submit_quote_info" style="color:white;font-size:15px;margin-top:20px;background-color:#f67555;border-radius:4px;width:100px;height:40px;border:none">Get Quote</button>
				<span id="instant_quote" style="margin-top:33px;float:right"></span> 
				</form>
			</div>-->
		@else
			<!--<div style="width:34%;display:inline-table;margin-left:3%">
				<center><h2 style="margin-top:0px">Instant Quote</h2></center>
				<form id="instant_quote_form">
				<h4>Name</h4>
				<input id="name" name="name">
				<h4>Email</h4>
				<input for="email" id="email" name="email">
				<h4>Phone Number</h4>
				<input id="phone_number" name="phone_number">
				<h4>Square Feet</h4>
				<input id="square_feet" name="square_feet">
				<input id="quote_stone_id" name="stone_id" type="hidden">
				<button type="button" id="btn_submit_quote_info" style="color:white;font-size:15px;margin-top:20px;background-color:#f67555;border-radius:4px;width:100px;height:40px;border:none">Get Quote</button>
				<span id="instant_quote" style="margin-top:33px;float:right"></span> 
				</form>
			</div>-->
		@endif
	</div>
@endsection