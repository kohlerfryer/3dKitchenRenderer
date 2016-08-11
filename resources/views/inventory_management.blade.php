@extends('layouts.admin')

@section('content')

    <section class="content" id="inventory_management_section">
      <h1>
        Inventory Management
      </h1>
    </br>

     <form id="update_stone_form" action="/admin/update_stone">
        <input name="_token" value="{{ csrf_token() }}" hidden>

         Stone Type </br>
         <select name="stone_type" id="inventory_stone_type" style="width:150px">
          <option value="">select type</option>
          @foreach($stone_types as $stone_type)
            @if(isset($selected_stone_type) && trim($selected_stone_type) == trim($stone_type->type))
             <option value="{{trim($stone_type->type)}}" selected>{{$stone_type->type}}</option>
            @else
             <option value="{{trim($stone_type->type)}}" >{{$stone_type->type}}</option>
            @endif
          @endforeach
         </select></br></br>

          @if (isset($stones))
           Select Stone</br>
           <select id="stone_id" name="stone_id">
            <option>select stone</option>
            @foreach($stones as $stone)
              @if(isset($selected_stone) && trim($selected_stone->id) == trim($stone->id))
               <option value="{{trim($stone->id)}}" selected>{{$stone->stone_name}}</option>
              @else
               <option value="{{trim($stone->id)}}" >{{$stone->stone_name}}</option>
              @endif  
            @endforeach
           </select></br></br>
          @endif

          @if(isset($selected_stone))
                Name</br>
                <input id="stone_name" name="stone_name" value="{{$selected_stone->stone_name}}"></br></br>
                <button type="button" id="btn_delete_stone">Delete Stone</button></br></br>
                Stone Group</br>
                <select name="group" id="stone_group" style="width:150px">
                  @foreach($stone_groups as $stone_group)
                     @if(trim($selected_stone->group_id) == trim($stone_group->id))
                      <option value="{{trim($stone_group->id)}}" selected>{{$stone_group->name}}</option>
                     @else
                      <option value="{{trim($stone_group->id)}}">{{$stone_group->name}}</option>
                     @endif
                  @endforeach
                </select></br></br>
                Price Per Square Foot</br>
                <input id="stone_price" name="stone_price" value="{{$selected_stone->stone_price_per_square_foot}}"></br></br>
                In-stock Quantity</br>
                <input id="stone_quantity" name="stone_quantity" value="{{$selected_stone->in_stock_quantity}}"></br></br>
                Description</br>
                <textarea name="stone_description" id="inventory_stone_description" style="width:400px;height:100px">{{$selected_stone->stone_description}}</textarea></br></br>
                Stone Image</br>
                <img id="inventory_stone_image_sample" style="width:300px;height:200px" src="../{{$selected_stone->stone_picture_url}}"></br>
                <input type="file" name="stone_image" id="stone_image"></br></br>
                Stone Texture</br>
                <img id="inventory_stone_texture_sample" style="width:300px;height:200px" src="../{{$selected_stone->stone_texture_url}}">
                <input type="file" name="stone_texture" id="stone_texture"></br></br>
                <input name="stone_image_url" id="stone_image_url" value="{{$selected_stone->stone_picture_url}}" hidden>
                <input name="stone_texture_url" id="stone_texture_url" value="{{$selected_stone->stone_texture_url}}" hidden>
                <input name="_token" value="{{ csrf_token() }}" hidden>
                <button type="button" id="btn_submit">Submit Changes</button>
          @endif
        </form>
    </section>



@endsection

