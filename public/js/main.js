$(document).ready(function(){

      var menuRight = document.getElementById( 'cbp-spmenu-s2' );
      var showRight = document.getElementById( 'showRight' );
      var body = document.body;
      var menu_activated = false;

      $('#close_navbar').click(function(event){
      		event.stopPropagation();
      		showRight.onclick();
      		$('#close_navbar').hide();
      });

      $('#cbp-spmenu-s2').click(function(event){
      	event.stopPropagation();
      });

      showRight.onclick = function(e) {
      	e.stopPropagation();
        classie.toggle( this, 'active' );
        classie.toggle( menuRight, 'cbp-spmenu-open' );
        $('#close_navbar').show();
        menu_activated = true;

      };	

        $(body).click(function(){
        	if(menu_activated == true){
        		 classie.toggle( menuRight, 'cbp-spmenu-open' );
        		 menu_activated = false;
        	}
      	});

});