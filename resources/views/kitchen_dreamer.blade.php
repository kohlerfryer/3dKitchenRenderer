@extends('layouts.page')

@section('content')

	<center>
		<div class="kitchen_dreamer" style="position:relative;width:1000px">
			<img id="background_image" src="{{$selected_room->picture_url}}" room_id="{{$selected_room->id}}" height="500px" width="100%" style="position:absolute;top:0;left:0"/>
			<img id="main_counter_top" src="images/room_backgrounds/kitchen_2_layers.png" style="position:absolute;left:0px;top:0px;width:100%;height:100%" hidden/>
			<img src="images/gear-setting.png" height="25px" width="25px" id="settings_gear" style="position:absolute;left:0;absolute;top:0;margin:4px"/>
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
				<span class="list_option"><h3 class="selected"><a href="?selected_stone_type={{trim($stone_type->type)}}">{{trim($stone_type->type)}}</a></h3></span>
				@else
				<span class="list_option"><h3><a  href="?selected_stone_type={{trim($stone_type->type)}}">{{trim($stone_type->type)}}</a></h3></span>
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
			src="{{$stone->stone_texture_url}}" 
			style="width:32.8%;height:300px;display:inline-block;"
			/>
		@endforeach


	</div>

	<div id="stone_info_view" style="max-width:900px;width:100%;margin:auto;" hidden>
		<div id="stone_summary"style="width:60%;display:inline-table;text-align:center">
			<img id="btn_back" src="images/back_arrow.png" height="50px" width="50px" style="float:left;margin-top:20px"/>
			<h2 id="stone_title" style="display:inline-block;padding-right:30px"></h2>
			<img id="stone_image" style="width:100%;height:300px;" src=""/>
			<div id="stone_description" style="padding-top:10px;padding-bottom:10px;font-family:helvetica;font-weight:100;text-align:left">
			</div>
			<div id="instock_quantity" style="text-align:left">
			</div>
		</div>

		@if(isset($guest_data))
			<div style="width:34%;display:inline-table;margin-left:3%">
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
			</div>
		@else
			<div style="width:34%;display:inline-table;margin-left:3%">
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
			</div>
		@endif
	</div>

</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
@endsection