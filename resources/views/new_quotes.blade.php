@extends('layouts.admin')

@section('content')
<section class="content">


  <h1>New Quotes</h1>
    @if(isset($new_quotes))
      Select Quote</br>
      <select>
      @foreach($new_quotes as $new_quote)
        <option>$new_quote->created_at</option>
      @endforeach
      </select>
    @else
      <!--<h4>There are currenlty no new quotes</h4>-->
      <select>
        <option>05/01/2016</option>
      </select>
    @endif
    <h3>Mike Fryer</br>801-850-2359</br>mikefryer@gmail.com</br></br>05/01/2016</br>Total: $22,000</h3></br>
    <h3>Kitchen</h3>
      <ul>
        <li><h4>Countertop 1</h4>
          <ul>
            <li>counter type: island</li>
            <li>stone type: wolf granite
            <li>edge: basic rounded</li>
            <li>Contains sink: No</li>
            <li>Area: 100" squared</li>
            <li>Seams: No</li>
          </ul>
        </li>
        <li><h4>Countertop 2</h4>
          <ul>
            <li>counter type: corner</li>
            <li>stone type: white tree onyx
            <li>edge: basic rounded</li>
            <li>Contains sink: No</li>
            <li>Area: 100" squared</li>
            <li>Seams: No</li>
          </ul>
        </li>
      </ul>
    <h3>Bathroom</h3>
      <ul>
        <li><h4>Countertop 1</h4>
          <ul>
            <li>counter type: island</li>
            <li>stone type: wolf granite
            <li>edge: basic rounded</li>
            <li>Contains sink: No</li>
            <li>Area: 100" squared</li>
            <li>Seams: No</li>
          </ul>
        </li>
      </ul>
</section>

@endsection



