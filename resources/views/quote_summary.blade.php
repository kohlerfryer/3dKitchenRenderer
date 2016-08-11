@extends('layouts.page')

@section('content')

	<div id="quote_summary">
		<h2 style="">Quote</h2><h3 class="selected" style="position:absolute;top:10px;right:30px"><a href="/kitchen_dreamer">Edit</a></h3>
			<table style="font-family:helvetica;font-weight:lighter;margin-top:80px">

				@if(isset($quote_data))
							<tr>
								<th>Room</th>
								<th>Stone</th>
								<th>Area</th>
								<th class="hide_on_mobile">Corner</th>
								<th class="hide_on_mobile">Seamless</th>
								<th class="hide_on_mobile">Contains Sink</th>
							</tr>
						@foreach($quote_data['countertops'] as $key => $countertop)
							<tr>
								<th>{{$countertop['room_type']}}</th>
								<th>{{$countertop['stone_name']}}</th>
								<th>{{$countertop['area']}}ft²</th>
								<th class="hide_on_mobile">{{$countertop['corner_type']}}</th>
								<th class="hide_on_mobile">{{$countertop['seamless']}}</th>
								<th class="hide_on_mobile">{{$countertop['sink']}}</th>
							</tr>
						@endforeach
				@endif
			</table>
			<h2 class="selected" align="left" >Estimate: ${{(number_format($quote_data['total_price']))}}</h2>
			<h4 align="left">Note: this is a quick estimate and the actual price may vary.</h4>
			<h3 align="left">Call us for more information at 801-998-8195</h3>
</div>

@endsection