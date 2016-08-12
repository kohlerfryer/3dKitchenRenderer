$(document).ready(function(){
	var last_clicked_texture_id = 0;

	function repopulate_stone()
	{
		form_data = $('#page_data_form').serialize();
		var url = '/kitchen_dreamer/get_stone';
		$.ajax({
			url:url,
			data:form_data
			}).done(function(response){
			response = JSON.parse(response);
			$('#stone_tiles').html('');
			$.each(response.stones, function(key, stone)
			{
				var html = '';
				html += '<img ';
				html += 'id="'+stone.id+'"';
				html += 'stone-description="'+stone.stone_description+'"';
				html += 'stone-title="'+stone.stone_name+'"';
				html += 'in-stock-quantity="'+ stone.in_stock_quantity +'"';
				html += 'price="'+stone.stone_price_per_squre_foot+'"';
				html += 'stone-picture-url="'+stone.stone_picture_url+'"';
				html += 'class="stone_texture_tile" ';
				html += 'src="'+stone.stone_texture_url+'" ';
				html += '/>';
				$('#stone_tiles').append(html);
			});
		});
	}

	function repopulate_table_quote(countertops_array)
	{
		var html = '';
		html += '<tr>';
		html += '<th style="text-align:middle">';
		html += '<h2>Quote</h2>';
		html += '</th>';
		html += '</tr>';
		if(typeof(countertops_array) != 'undefined')
		{
			$.each(countertops_array.countertops, function(key, countertop_data){
				html += '<tr >';
				html += '<th><a href="#" countertop_id="'+key+'" class="btn_delete_from_quote">Delete</a></th>';
				html += '<th>' + countertop_data.room_type + '</th>';
				html += '<th>' + countertop_data.area + 'ft²</th>';
				html += '<th><img src="'+countertop_data.stone_texture_url+'" height="60px" width="60px" style="border-radius:5px"/></th>"';
				html += '</tr>';
			});
			html += '<tr>';
			html += '<th>';
			html += '<form action="/kitchen_dreamer/get_instant_quote">';
			html += '<button id="btn_get_estimate">Get Estimate</button>';
			html += '</form>';
			html += '</th>';
			html += '</tr>';
		}
		else{
			html += '<tr>';
			html += '<th>No countertops added.</th>';
			html += '</tr>';
		}
		$('#table_quote').html(html);
	}

	$('#stone_info_view #btn_back').click(function(){
		$('#stone_info_view').hide();
		$('#pick_stone_view').show();	
	});

	$('#select_group_type').change(function(){
		var stone_group = $(this).val();
		$('#page_data_form #selected_stone_group').val(stone_group);
		//$('#page_data_form').submit();
		repopulate_stone();
	});

	$('#add_stone_form').submit(function(e){
		e.preventDefault();
		var form = $(this)[0]; 
		var form_data = new FormData(form);
		if(!form_data.has('seamless') || !form_data.has('shape') || !form_data.has('sink') || !$('#input_countertop_dimensions').val())
		{
			alert('Please provide all counter details.');
			return false;
		}
		form_data = $(this).serialize();
		var url = '/kitchen_dreamer/add_stone_to_quote';
		$.ajax({
			url:url,
			data:form_data,
			method:'POST'
		}).done(function(response){
			response = JSON.parse(response);
			repopulate_table_quote(response.countertops);
		});
	});

	$('#stone_tiles').on('click', 'img', function(){
		$('#stone_info_view #stone_title').html($(this).attr('stone-title'));
		$('#stone_info_view #stone_description').html($(this).attr('stone-description'));
		$('#stone_info_view #stone_image').attr('src', $(this).attr('stone-picture-url'));
		$('#stone_info_view #instock_quantity').html('Slabs in our yard: ' + $(this).attr('in-stock-quantity'));
		$('#stone_info_view #quote_stone_id').val($(this).attr('id'));
		$('#stone_info_view #input_stone_price').val($(this).attr('price'));
		$('#text_quick_estimate').html($('#input_countertop_dimensions').val() * $('#input_stone_price').val());
		
		$('#input_stone_id').val($(this).attr('id'));

		$('#pick_stone_view').hide();	
		$('#stone_info_view').show();

		last_clicked_texture_id = $(this).attr('id');
		$('#main_counter_top').attr('src', '/kitchen_dreamer/get_kitchen_counter_layers/' + $(this).attr('id') + '/' + $('#background_image').attr('room_id'));
		$('#main_counter_top').show();


	});

	$('.room').click(function(){

		var picture_url = $(this).attr('picture_url');
		var room_id = $(this).attr('id');
		$('#page_data_form #selected_room_id').val(room_id);
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
		$('#stone_type_list h3').removeClass('selected');
		$(this).parent().addClass('selected');
		var stone_type = $(this).html();
		$('#page_data_form #selected_stone_type').val(stone_type);
		repopulate_stone();
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

	$('#btn_hide_lshape_dimensions').click(function(){
		if($('#height_1_lshape').val() && $('#height_2_lshape').val() && $('#width_1_lshape').val() && $('#width_2_lshape').val())
		{
			//var dimensions = {};
			//dimensions['lshape'] = {};
			//dimensions['lshape']['width'] = $('#width_1_lshape').val();
			//dimensions['lshape']['height'] = $('#height_1_lshape').val();
			//dimensions['lshape']['width_2'] = $('#width_2_lshape').val();
			//dimensions['lshape']['height_2'] = $('#height_2_lshape').val();
			//$('#page_data_form #user_dimensions').val(dimensions);

			var countertop_area_feet_squared = ($('#height_1_lshape').val()/12 * $('#width_1_lshape').val()/12) - ($('#height_2_lshape').val()/12 * $('#width_2_lshape').val()/12);
			countertop_area_feet_squared = countertop_area_feet_squared.toFixed(1);
			$('#input_countertop_dimensions').val(countertop_area_feet_squared);
			$('#dimensions').html($('#width_1_lshape').val() + "'" + ' x ' + $('#height_1_lshape').val() + "'" + ' - ' + $('#width_2_lshape').val() + "'" + ' x ' + $('#height_2_lshape').val() + "'");
			$('#text_quick_estimate').html(countertop_area_feet_squared * $('#input_stone_price').val());
		}
		$('#dimensions-' + $('input[name=shape]:checked').val()).hide();
		$('#add_stone_table').show();
	});

	$('#btn_hide_island_dimensions').click(function(){
		if($('#height_1_island').val() && $('#width_1_island').val())
		{
			//var dimensions = {};
			//dimensions['type'] = 'island';
			//dimensions['width'] = $('#width_1_island').val();
			//dimensions['height'] = $('#height_1_island').val();
			//alert(JSON.stringify(dimensions));
			//$('#page_data_form #user_dimensions').val(JSON.stringify(dimensions));
			//alert($('#page_data_form #user_dimensions').val());


			var countertop_area_feet_squared = ($('#height_1_island').val()/12 * $('#width_1_island').val()/12); 
			countertop_area_feet_squared = countertop_area_feet_squared.toFixed(1);
			$('#input_countertop_dimensions').val(countertop_area_feet_squared);
			$('#dimensions').html($('#width_1_island').val() + "'" + ' x ' + $('#height_1_island').val() + "'");
			$('#text_quick_estimate').html(countertop_area_feet_squared * $('#input_stone_price').val());
		}
		$('#dimensions-' + $('input[name=shape]:checked').val()).hide();
		$('#add_stone_table').show();
	});

	$('.counter_type').change(function(){
		$('#dimensions').html('0" x 0"');
		$('#input_countertop_dimensions').val('');
	});

	$('#table_quote').on('click', 'a', function(e) {
    	e.preventDefault();
    	var countertop_id = $(this).attr('countertop_id');
    	var crf_token = $('#crf_token').val();
		var url = '/kitchen_dreamer/delete_stone_from_quote';
		var form_data = {
			countertop_id: countertop_id,
			_token : crf_token
		};
		$.ajax({
			url:url,
			data:form_data,
			method:'POST'
		}).done(function(response){
			response = JSON.parse(response);
			repopulate_table_quote(response.countertops);
		});
	});


});