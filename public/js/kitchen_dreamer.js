$(document).ready(function(){
	var last_clicked_texture_id = 0;

	$('#stone_info_view #btn_back').click(function(){
		$('#stone_info_view').hide();
		$('#pick_stone_view').show();	
	});

	$('#add_stone_form').submit(function(){
		var form = $(this)[0]; 
		var formData = new FormData(form);
		if(!formData.has('seamless') || !formData.has('shape') || !formData.has('sink'))
		{
			alert('Please provide all counter details.');
			return false;
		}
	});

	$('.stone_texture_tile').click(function(){
		$('#stone_info_view #stone_title').html($(this).attr('stone-title'));
		$('#stone_info_view #stone_description').html($(this).attr('stone-description'));
		$('#stone_info_view #stone_image').attr('src', $(this).attr('stone-picture-url'));
		$('#stone_info_view #instock_quantity').html('In Stock Quantity: ' + $(this).attr('in-stock-quantity'));
		$('#stone_info_view #quote_stone_id').val($(this).attr('id'));
		
		$('#input_stone_id').val($(this).attr('id'));

		$('#pick_stone_view').hide();	
		$('#stone_info_view').show();

		last_clicked_texture_id = $(this).attr('id');
		$('#main_counter_top').attr('src', '/kitchen_dreamer/get_kitchen_counter_layers/' + $(this).attr('id') + '/' + $('#background_image').attr('room_id'));
		$('#main_counter_top').show();


	});

	$('.room').click(function(){

		var picture_url = $(this).attr('picture_url');
		$('#background_image').attr('src', picture_url);
		$('#background_image').attr('room_id', $(this).attr('id'));
		$('#main_counter_top').hide();
		if(last_clicked_texture_id != 0)
		{
			$('#main_counter_top').attr('src', '/kitchen_dreamer/get_kitchen_counter_layers/' + last_clicked_texture_id + '/' + $(this).attr('id'));
			$('#main_counter_top').show();
		}	

	});	

	$('.stone_type').click(function(){
		window.location = $(this).attr('link') + '&selected_room_id=' + $('#background_image').attr('room_id'); 
	});


	$('#btn_submit_quote_info').click(function(){
		$.ajax({
			url: '/kitchen_dreamer/get_instant_quote',
			failure: function(){
				alert('sorry an error ocurred');
			},
			success: function(response){
				response = JSON.parse(response);
				window.location.reload();
				//$('#instant_quote').html('$ ' + response.estimate);
			}
		});
	});

	$('#btn_view_dimensions').click(function(){
		if(!$('input[name=shape]:checked').val())
		{
			alert('Please first selectet countertop type');
			return false;
		}
		$('#add_stone_table').hide();
		$('#dimensions-' + $('input[name=shape]:checked').val()).show();
	});

	$('.btn_hide_dimensions').click(function(){
		$('#dimensions-' + $('input[name=shape]:checked').val()).hide();
		$('#add_stone_table').show();
	});


});