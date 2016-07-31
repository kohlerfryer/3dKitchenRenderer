@extends('layouts.page')

@section('content')
	<script>
		$(document).ready(function(){
			$('#btn_enter_step_2').click(function(){
				$('#quote_summary_step_1').hide();
				$('#edit_room').show();
			});
		});
	</script>
		
		<center>
			<div id="quote_summary_step_1" style="width:800px;position:relative;padding-bottom:80px;" >
				<h2>Welcome to our online custom quote generator.</h2>
				<h4>Select a room in which you wish to put a new countertop</h4>
				<select id="select_room" style="width:150px;height:100px;font-size:30px">
					<option>Bathroom</option>
					<option>Kitchen</option>
					<option>Basement</option>
				</select>
				<button id="btn_enter_step_2" style="width:110px;height:30px" onclick="">Add Room</button>
			</div>
			<div id="quote_summary_step_2" style="width:600px;position:relative;padding-bottom:80px;" hidden>
				<h2>Rooms</h2>
				<table style="width:100%;padding:20px;background-color:#F5F5F5;font-family:helvetica;font-weight:lighter">
					<tr style="margin-bottom:20px">
						<th></th>
						<th>Room</th>
						<th>countertops</th>
					</tr>
					<tr>
						<th><a href="#">Edit</a></th>
						<th>Bathroom</th>
						<th>2</th>
					</tr>
					<tr>
						<th><a href="#">Edit</a></th>
						<th>Kitchen</th>
						<th>1</th>
					</tr>
					<!--<tr>
						<th><a href="#">Edit</a></th>
						<th>Basement</th>
						<th>1</th>
					</tr>
					<tr>
						<th><a href="#">Edit</a></th>
						<th>Kitchen</th>
						<th>3</th>
					</tr>
						<th><a href="#">Edit</a></th>
						<th>Master Bathroom</th>
						<th>1</th>
					</tr>-->
				</table>
				<select id="select_room" style="width:150px;height:100px;font-size:30px;position:absolute;right:130px;bottom:0px;width:110px;height:30px">
					<option>Bathroom</option>
					<option>Kitchen</option>
					<option>Basement</option>
				</select>
				<button style="position:absolute;left:0px;bottom:0px;width:110px;height:30px">Get Quote</button>
				<button style="position:absolute;right:0px;bottom:0px;width:110px;height:30px">Add Room</button>
			</div>

			<div id="edit_room" style="width:700px;position:relative;padding-bottom:80px;" hidden>
				<h2>Kitchen</h2> 
				<!--<select>
					<option>select type</option>
				</select>-->
				<table style="width:100%;padding:20px;background-color:#F5F5F5;font-family:helvetica;font-weight:lighter;margin-top:20px">
					<!--<tr><th>No Countertops selected</th></tr>-->
					<tr>
						<th><a href="#">Edit</a></th>
						<th>Island 1</th>
						<th>200" X 200"</th>
						<th><img src="images/granite/15364.jpg" height="40px" width="40px" style="border-radius:5px"/></th>
					</tr>
					<tr>
						<th><a href="#">Edit</a></th>
						<th>South Wall</th>
						<th>800" X 200"</th>
						<th><img src="images/granite/65542.jpg" height="40px" width="40px" style="border-radius:5px"/></th>
					</tr>
				</table>
				<button style="position:absolute;left:0px;bottom:0px;width:110px;height:30px">Done</button>
				<button style="width:220px;height:30px;position:absolute;right:0px;bottom:0px;">Add Another Countertop</button>
			</div>

		<div id="" hidden>
			
			<div style="width:650px;position:relative;padding-bottom:60px" hidden>
				<h2>Kitchen</h2>
				<table style="width:100%;padding:20px;background-color:#F5F5F5;font-family:helvetica;font-weight:lighter">
					<tr>
						<th><a href="#">Edit</a></th>
						<th>Island</th>
						<th>300" X 500"</th>
						<th><img src="images/granite/15364.jpg" width="40px" height="40px" style="border-radius:3px"/> </th>
					</tr>
						<th><a href="#">Edit</a></th>
						<th>L-shaped Countertop</th>
						<th>300" X 500" -- 300" X 200"</th>
						<th><img src="images/granite/65542.jpg" width="40px" height="40px" style="border-radius:3px"/> </th>
					</tr>
				</table>
				<button style="position:absolute;left:0px;bottom:0px;width:140px;height:30px">Add Countertop</button>
				<button style="position:absolute;right:0px;bottom:0px;width:160px;height:30px">Return to Summary</button>
		</div>
				<form id="" >
				<h4 style="display:inline-block">Countertop Dimensions</h4></br>
				<input style="display:inline-block;width:90px" placeholder="Length"> X 
				<input style="display:inline-block;width:90px" placeholder="Width"></br>
				<h4 style="display:inline-block">Contains Sink?</h4></br>
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">Yes 
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">No </br>
				<h4 style="display:inline-block">Seamless?</h4></br>
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">Yes 
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">No </br>
				<h4 style="display:inline-block">Is it an island?</h4></br>
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">Yes 
				<input type="radio" style="width:15px;height:15px;display:inline-block;box-shadow:none">No 
				<h4>Edge</h4></br>
				<select type="select" style="width:150px">
					<option>select edge</option>
				</select></br>


				<button type="button" id="btn_submit_quote_info" style="color:white;font-size:15px;margin-top:20px;background-color:#f67555;border-radius:4px;width:150px;height:40px;border:none">Choose Countertop</button>
				<span id="instant_quote" style="margin-top:33px;float:right"></span> 
				</form>
			</div>
		</div>
	</center>

@endsection