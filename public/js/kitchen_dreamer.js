$(document).ready(function(){
	$('#stone_info_view #btn_back').click(function(){
		$('#stone_info_view').hide();
		$('#pick_stone_view').show();		
	});

	$('.stone_texture_tile').click(function(){
		$('#pick_stone_view').hide();	
		$('#stone_info_view').show();
	});
});