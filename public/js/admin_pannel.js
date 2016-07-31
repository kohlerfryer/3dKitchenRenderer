$(document).ready(function(){


    $(document).keypress(
    function(event){
     if (event.which == '13') {
        event.preventDefault();
      }
    });

    $('#btn_sign_out').click(function(){
        $.ajax({
            method: 'GET',
            url: "/auth/logout"
        }).done(function(){window.location = '/'});
    });
    
    $('#add_stone_form').submit(function(e){
        $('#add_stone_section').hide();
        $('#loading_section').show();
        e.preventDefault();
        var form_data = new FormData($(this)[0]);
       if(!$('#stone_type').val() || !$('#stone_description').val() || !$('#stone_price').val() || !$('#stone_quantity').val() || !$('#stone_image').val() || !$('#stone_texture').val() )
       {
        alert('Please speficy all related fields.');
        return false;
       }
        $.ajax({
            method: "POST",
            url: "/admin_panel/add_stone",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            async:true,
        }).done(function(response){
            $('#loading_section').hide();
            $('#add_stone_section').show();
            response = JSON.parse(response);
            if(response.result == 'failure') alert(response.error_message);
            else {
            alert('successfully added');
            location.reload();
            }
        });
    });

    $('#btn_submit').click(function(){
        $('#inventory_management_section').hide();
        $('#loading_section').show();
        if($('#stone_texture').val()) $('#stone_texture_url').val('');
        if($('#stone_image').val()) $('#stone_image_url').val('');
        var form_data = new FormData($('#update_stone_form')[0]);
        $.ajax({
            method: "POST",
            url: "/admin_panel/update_stone",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            async:true,
        }).done(function(response){
            $('#loading_section').hide();
            $('#inventory_management_section').show();
            var response = JSON.parse(response);
            if(response.result == 'success')
            {
            alert('Changes successfully made.');
            location.reload();
            }else alert('an error ocurred');
        });
    });


    $('#inventory_stone_type').change(function(){
        $('#update_stone_form').attr('action', '/admin/inventory_management');
        $('#stone_type').val('');
        $('#stone_id').val('');
        $('#update_stone_form').submit();
    });

    $('#stone_id').change(function(){
        $('#update_stone_form').attr('action', '/admin/inventory_management');
        $('#update_stone_form').submit();
    });

    $('#btn_delete_stone').click(function(){
        var form_data = $('#update_stone_form').serialize();
        $.ajax({
            url: "/admin_panel/delete_stone",
            data: form_data,

        }).done(function(response){
            alert('Stone successfully deleted');
            location.reload();
        }); 
    });

    



});