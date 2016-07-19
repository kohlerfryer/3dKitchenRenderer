@extends('layouts.admin')

@section('content')

    <section class="content inventory_management_section" id="" >
      <h1>
        Inventory Management
      </h1>
    </br>
     <form id="inventory_management_form">
         Stone Type </br>
         <select name="stone_type" id="inventory_stone_type" style="width:150px">
          <option>select type</option>
          <option value="granite">granite</option>
          <option value="marble">marble</option>
          <option value="onyx">onyx</option>
         </select></br></br>
         <div id="select_stone"></div>
         <div id="stone_info"></div>
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
     </form>

    </section>
  </div>

@endsection

