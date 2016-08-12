@extends('layouts.page')

@section('content')
	<center>
		<form id="page_data_form" action="/kitchen_dreamer" hidden>
			<input name="selected_stone_type" id="selected_stone_type" value="{{$selected_stone_type}}">
			<input name="selected_room_id" id="selected_room_id" value="{{$selected_room->id}}">

			@if(isset($selected_stone_group))
				<input name="selected_stone_group" id="selected_stone_group" value="{{$selected_stone_group}}">
			@else
				<input name="selected_stone_group" id="selected_stone_group">
			@endif

			@if(isset($user_dimensions))
				<input name="island_dimensions" value='{{user_dimensions}}' id='user_dimensions'>
			@else
				<input name="island_dimensions" id="island_dimensions" value=''>
			@endif


		</form>
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
					<span class="list_option"><h3 class="selected"><a class="stone_type">{{trim($stone_type->type)}}</a></h3></span>
				@else
					<span class="list_option"><h3><a  class="stone_type">{{trim($stone_type->type)}}</a></h3></span>
				@endif
			@endforeach
			<span>
				<select id="select_group_type">
				<option value="">all groups</option>
				@foreach($stone_groups as $stone_group)
					@if(isset($selected_stone_group ) && $stone_group->id == $selected_stone_group)
						<option value="{{$stone_group->id}}" selected>{{$stone_group->name}}</option>
					@else
						<option value="{{$stone_group->id}}">{{$stone_group->name}}</option>
					@endif
				@endforeach
				</select>
			</span>
		</div>

		<div id="stone_tiles">
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

	</div>

	<div id="stone_info_view" style="max-width:900px;width:100%;margin:auto;" hidden>
		<div id="stone_summary">
			<img id="btn_back" src="{{asset('images/back_arrow.png')}}" src="" height="50px" width="50px" style="float:left;margin-top:20px"/>
			<h2 id="stone_title" style="display:inline-block;padding-right:30px"></h2>
			<img id="stone_image" style="width:100%;height:300px;" src=""/>
			<div id="stone_description">
			</div>
			<div id="instock_quantity">
			</div>
		</div>
		<div style="display:inline-table;">
				<center>
					<h2>Add Counter to Quote</h2></br>
				</center>
				<form id="add_stone_form">
					<input type="hidden" name="_token" id="crf_token" value="{{ csrf_token() }}">
					<input  name="stone_id" value="" id="input_stone_id" style="display:none">
					<input style="display:none" value="" id="input_stone_price">
					<center>
						<div id="add_stone_dimensions" style="position:relative;width:100%;padding-left:50px;padding-bottom:20px">
							<div id="dimensions-island" hidden>
								<center>
									<h3>Dimensions</h3>
								</center>
								  <input style="display:none" value="" id="input_countertop_dimensions" name="countertop_area">
								  <div style="width:330px;position:relative;margin:auto;padding-bottom:80px">
								    <div style="width:100px;height:200px;background-color:black;margin:auto">
								    </div>
								    @if(isset($user_dimensions['island']))
								   	 	<input style="position:absolute;top:90px;left:5px;width:100px" id="height_1_island" placeholder="height" value="$user_dimensions['island'][height]">
									    <input style="position:absolute;bottom:30px;left:115px;width:100px;"id="width_1_island"  placeholder="width" value="$user_dimensions['island'][width]">
								    @else
									    <input style="position:absolute;top:90px;left:5px;width:100px" id="height_1_island" placeholder="height">
									    <input style="position:absolute;bottom:30px;left:115px;width:100px;"id="width_1_island"  placeholder="width">
								  	@endif
									<button type="button" id="btn_hide_island_dimensions" style="position:absolute;bottom:0;left:0;width:100px;height:30px">Return</button>

								  </div>
							</div>
							<div id="dimensions-l-shape"  hidden>
								  <div style="width:330px;position:relative;margin:auto;padding-bottom:90px;padding-top:40px">
								    <div style="position:absolute;left:0px;width:120px;height:100px;background-color:black;">
								    </div>
								   <div style="width:100px;height:200px;background-color:black;margin:auto">
								    </div>
								    @if(isset($user_dimensions['lshape']))
									    <input style="position:absolute;bottom:180px;left:225px;width:100px;" id="height_1_lshape" placeholder="Height" value="$user_dimensions['lshape'][height]">
									    <input style="position:absolute;top:5px;left:50px;width:100px;" id="width_1_lshape" placeholder="Width" value="$user_dimensions['lshape'][width]">
									    <input style="position:absolute;bottom:100px;left:10px;width:100px;" id="height_2_lshape" placeholder="Height" value="$user_dimensions['lshape'][height_2]">
									    <input style="position:absolute;top:150px;left:10px;width:100px" id="width_2_lshape" placeholder="Width" value="$user_dimensions['lshape'][width_2]">
									@else
									    <input style="position:absolute;bottom:180px;left:225px;width:100px;" id="height_1_lshape" placeholder="Height">
									    <input style="position:absolute;top:5px;left:50px;width:100px;" id="width_1_lshape" placeholder="Width">
									    <input style="position:absolute;bottom:100px;left:10px;width:100px;" id="height_2_lshape" placeholder="Height">
									    <input style="position:absolute;top:150px;left:10px;width:100px" id="width_2_lshape" placeholder="Width">
									@endif
									<button type="button" id="btn_hide_lshape_dimensions" style="position:absolute;bottom:0;left:0;width:100px;height:30px">Return</button>

								  </div>
							</div>
						</div>
					</center>
					<table align="center" id="add_stone_table" >
						<tr>
							<th>
								<h4 style="display:inline-block">Room</h4>
							</th>
							<th>
								<select name="room_type" type="select" style="width:150px;height:20px;display:inline-block">
									<option>Kitchen</option>
									<option>Basement</option>
									<option>Room 1</option>
									<option>Room 2</option>
									<option>Room 3</option>
									<option>Patio</option>
									<option>Bathroom 1</option>
									<option>Bathroom 2</option>
									<option>Bathroom 3</option>
									<option>Bathroom 4</option>
								</select>
							</th>
						</tr>
						<tr>
							<th>
								<h4 style="display:inline-block">Edge Type</h4>
							</th>
							<th>
								<select name="corner_type" id="input1" type="select" style="width:150px;height:20px;display:inline-block">
									<option>Straight</option>
									<option>Bevel</option>
									<option>Bullnose</option>
									<option>Specialty</option>
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
								<h4 style="display:inline-block" id="dimensions">0' x 0'</h4>
							</th>
						</tr>
						<tr>
							<th>
								<h4>Quick Estimate:</h4>
							</th>
							<th>
								<h4 id="text_quick_estimate">$0</h4>
							</th>
						</tr>
						<tr>
							<th colspan="2">
								<button id="btn_add_to_quote">Add to Quote</button>
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
			<table id="table_quote" style="width:100%;max-width:900px">
				<tr><th style="text-align:middle"><h2>Quote</h2></th></tr>
				@if(Session::has('quote_data'))
					@foreach(Session::get('quote_data')['countertops'] as $key => $countertop)
					<tr>
						<th><a href="#" countertop_id="{{$key}}" class="btn_delete_from_quote">Delete</a></th>
						<th>{{$countertop['room_type']}}</th>
						<th>{{$countertop['area']}}ft²
						<th><img src="{{$countertop['stone_texture_url']}}" height="60px" width="60px" style="border-radius:5px"/></th>
					</tr>
					@endforeach
					<tr>
						<th colspan="3">
							<form action="/kitchen_dreamer/get_instant_quote">
								<button  id="btn_get_estimate">Get Estimate</button>
							</form>
						</th>
					</tr>
				@else
				<tr><th>No Countertops added</th></tr>
				@endif
			</table>
		</div>
@endsection