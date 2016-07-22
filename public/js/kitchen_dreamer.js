$(document).ready(function(){
	$('#stone_info_view #btn_back').click(function(){
		$('#stone_info_view').hide();
		$('#pick_stone_view').show();	
	});

	$('.stone_texture_tile').click(function(){
		$('#stone_info_view #stone_title').html($(this).attr('stone-title'));
		$('#stone_info_view #stone_description').html($(this).attr('stone-description'));
		$('#stone_info_view #stone_image').attr('src', $(this).attr('stone-picture-url'));
		$('#stone_info_view #instock_quantity').html('In Stock Quantity: ' + $(this).attr('in-stock-quantity'));
		$('#stone_info_view #quote_stone_id').val($(this).attr('id'));
		
		$('#pick_stone_view').hide();	
		$('#stone_info_view').show();
	});

	$('#settings_gear').click(function(){
		$('#settings_drop_down').show();
	});

	$('#btn_submit_quote_info').click(function(){
		var square_feet = $('#square_feet').val();
		$('#square_feet').val(square_feet.replace(/\,/g,""));
		var form_data = $('#instant_quote_form').serialize();

		if(!$('#name') || !$('#email') || !$('#phone_number') || !$('#square_feet'))
		{
			alert('Please specify all related fields.');
			return false;
		}
		if(!$('#email').val().includes('@') || !$('#email').val().includes('.com'))
		{
			alert('Please provide a valid email address.');
			return false;
		} 
		if($('#phone_number').val().length < 9 || $('#phone_number').val().match(/[a-z]/i))
		{
			alert('Please provide a valid phone_number with area code.');
			return false;
		} 
		if($('#square_feet').val().match(/[a-z]/i))
		{
			alert('Please provide the area of the desired dimensions in square feet. To calculate the area of a room in square feet, measure the length and width of the room in feet, then multiply these figures together to give an area in ft². \n\nExample: 150');
			return false;
		} 

		$.ajax({
			data: form_data,
			url: '/kitchen_dreamer/get_instant_quote',
			failure: function(){
				alert('sorry an error ocurred');
			},
			success: function(response){
				response = JSON.parse(response);
				$('#instant_quote').html('$ ' + response.estimate);
			}
		});
	});


});