$(document).ready(function(){

    $('#btn_sign_out').click(function(){
        $.ajax({
            method: 'GET',
            url: "/auth/logout"
        }).done(function(){window.location = '/'});
    });
    
    $('#add_stone_form').submit(function(e){
        e.preventDefault();
        var form_data = new FormData($(this)[0]);

       if(!$('#stone_type').val() || !$('#stone_description').val() || !$('#stone_price').val() || !$('#stone_quantity').val() || !$('#stone_image').val() )
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
            async:false,
        }).done(function(response){

            //alert(response);
            response = JSON.parse(response);
            if(response.result == 'failure') alert(response.error_message);
            else {
            alert('successfully added');
            location.reload();
            }
            

        });

    });


    /*$('.sidebar-menu li').click(function(){
        $('.sidebar-menu .active').removeClass('active');
        $(this).addClass('active');
        $('.content').hide();
        $('.' + $(this).attr('id')).show();
    });*/

    $('#inventory_stone_type').change(function(){
        $('#stone_info').html('');
        var stone_type = $(this).val();
        var form_data = {
            stone_type:stone_type
        };

        $.ajax({

            url: "/admin_panel/populate_stone",
            data: form_data,

        }).done(function(response){

            response = JSON.parse(response);
            if(response.result == 'failure') alert(response.error_message);

            else{

                var html = 'Stone Name </br><select id="inventory_select_stone" style="width:150px"><option>select stone</option>';
                $.each(response.stone_array, function(id, stone_name){
                    
                    html +='<option value="'+id+'">'+stone_name+'</option>';
                });
                html += '</select>';
                $('#select_stone').html(html);

                $('#inventory_select_stone').change(function(){
                    var stone_id = $(this).val();
                    var form_data = {
                        stone_id : stone_id
                    }
                    $.ajax({
                        method:'GET',
                        url: "/admin_panel/get_stone",
                        data: form_data,

                    }).done(function(response){
                        
                        response = JSON.parse(response);

                        var html = '</br><button type="button" id="btn_delete_stone">Delete Stone</button></br>';
                        html += '</br>Stone Price Per Square Foot</br>';
                        html += '<input name="stone_price" id="inventory_stone_price" value="'+response.stone[0].stone_price_per_square_foot+'"></br></br>';
                        html += 'In Stock Quantity</br>';
                        html += '<input name="stone_quantity" id="inventory_stone_quantity" value="'+response.stone[0].in_stock_quantity+'"></br></br>';
                        html += 'Stone Description</br>';
                        html += '<textarea name="stone_description" id="inventory_stone_description" style="width:400px;height:100px">'+response.stone[0].stone_description+'</textarea></br></br>';
                        html += 'Stone Image</br>';
                        html += '<img id="inventory_stone_image_sample" style="width:300px;height:200px" src="../'+response.stone[0].stone_picture_url+'">';
                        html += '<input type="file" name="stone_image" id="inventory_stone_image"></br></br>';
                        html += 'Stone Texture</br>';
                        html += '<input type="file" name="stone_image_texture" id="inventory_stone_image_texture"></br></br>';
                        html += '<input name="stone_id" id="inventory_stone_id" value="'+response.stone[0].id+'"hidden>';
                        html += '<input name="stone_image_url" id="inventory_stone_image_url" value="'+response.stone[0].stone_picture_url+'"hidden>';
                        html += '<input name="stone_name"  value="'+response.stone[0].stone_name+'"hidden>';
                        html += '<button type="submit">Submit Changes</button>';
                        $('#stone_info').html(html);

                        $('#inventory_management_form').unbind();

                        $('#inventory_management_form').submit(function(e){
                           e.preventDefault();
                           if($('#inventory_stone_image').val()) $('#inventory_stone_image_url').val('');
                           var form_data = new FormData($(this)[0]);
                           if(!$('#inventory_select_stone').val() || !$('#inventory_stone_type').val() || !$('#inventory_stone_description').val() || !$('#inventory_stone_price').val() || !$('#inventory_stone_quantity').val())
                           {
                            alert('Please speficy all related fields.');
                            return false;
                           }

                            $.ajax({

                                method: "POST",
                                url: "/admin_panel/update_stone",
                                data: form_data,
                                cache: false,
                                contentType: false,
                                processData: false,

                            }).done(function(response){

                                //alert(response);
                                response = JSON.parse(response);
                                if(response.result == 'failure') alert(response.error_message);
                                else{
                                alert('updated successfully');  
                                $('#inventory_stone_id').val(response.stone_id);
                                $('#inventory_stone_image_url').val(response.stone_image_url); 
                                $('#inventory_stone_image_sample').attr('src', '../'+response.stone_image_url);     
                                }

                            });
                            return false;

                        });

                        $('#btn_delete_stone').click(function(){
                            var stone_id = $('#inventory_select_stone').val();
                            $.ajax({
                                url: "/admin_panel/delete_stone",
                                data: { stone_id : stone_id }
                            }).done(function(){
                                alert('Image deleted successfully!');
                                location.reload()
                            });
                        });


                    });
                });

            }

        });
    });
});