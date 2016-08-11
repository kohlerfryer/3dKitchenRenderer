@extends('layouts.admin')

@section('content')
<section class="content">

  <h1>Previously Viewed Quotes</h1>
    @if(isset($old_quotes))
      Select Quote</br>
      <form id="search_quote_form" action="/admin/old_quotes">
        <select name="selected_quote_id" id="select_quote_input">
          <option>select</option>
          @foreach($old_quotes as $old_quote)
            @if(isset($selected_quote_id) && $old_quote->id == $selected_quote_id)
            <option  value="{{$old_quote->id}}" selected>{{$old_quote->created_at}}</option>
            @else
            <option value="{{$old_quote->id}}">{{$old_quote->created_at}}</option>
            @endif
          @endforeach
        </select>
      </form>
    @else
    <h4>There are currenlty no old quotes</h4>
    @endif
    @if (isset($user_info))
    <h3>{{$user_info->first_name}} {{$user_info->last_name}}</br>{{$user_info->email}}
   
    </br>{{$user_info->phone}}</br></br>Total: ${{$selected_quote->total_price}}</h3></br></h2>
    @endif
    @if (isset($selected_quote))
      @foreach($selected_quote->countertops as $room)
        <h3>{{$room->room_type}}</h3>
        <ul>
          <li><h4>{{$room->stone_name}}</h4>
            <ul>
              <li>counter type: {{$room->room_type}}</li>
              <li>edge: {{$room->corner_type}}</li>
              <li>Contains sink: {{$room->sink}}</li>
              <li>Area: {{$room->area}}" </li>
              <li>Seams: {{$room->seamless}}</li>
              <li>Shape: {{$room->shape}}</li>
            </ul>
          </li>
        </ul>
      @endforeach
    @endif
</section>

@endsection



