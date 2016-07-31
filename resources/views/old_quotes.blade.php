@extends('layouts.admin')

@section('content')
<section class="content">

  
  <h1>Previously Viewed Quotes</h1>

    @if(isset($old_quotes))
      Select Quote</br>
      <select>
      @foreach($old_quotes as $old_quote)
        <option>$old_quote->created_at</option>
      @endforeach
      </select>
    @else
      <h4>There are currenlty no old quotes</h4>
    @endif


</section>

@endsection



