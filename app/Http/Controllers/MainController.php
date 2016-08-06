<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB;
use View;
use Session;
use Imagick;
use ImagickPixel;
use Illuminate\Http\Response;
use File;
use Auth;
use DateTime;

class MainController extends Controller
{


    public function __construct()
    {
      $this->middleware('auth');
    }

    public function get_kitchen_counter_layers($stone_id, $room_id)
    {
        $texture_url = DB::select('select texture_layout_url from texture where stone_id = ? and room_id = ?', [$stone_id, $room_id]);
        return (new Response(File::get(public_path().'/'.$texture_url[0]->texture_layout_url)))->header('content-type', 'image/png');
    }

    public function get_kitchen_dreamer_view()
    {
        $page_data = [];
        $page_data['stone_types'] = DB::select('select * from stone_types');
        if(Session::get('guest_data')) $page_data['guest_data'] = Session::get('guest_data');
        $page_data['room_backgrounds'] = DB::select('select * from background_rooms');
        if(Input::has('selected_room_id')) $page_data['selected_room'] = DB::select('select * from background_rooms where id = ?', [Input::get('selected_room_id')])[0];
        else{
            $page_data['selected_room'] = $page_data['room_backgrounds'][0];
        }
        if(Input::has('selected_stone_type'))
        {
            $stone_type = Input::get('selected_stone_type');
            $page_data['selected_stone_type'] = $stone_type;
            $stones = DB::select('select * from stone where stone_type = ?', [$stone_type]);
            if(isset($stones)) $page_data['stones'] = $stones;
        }
        else{
            $page_data['selected_stone_type'] = 'granite';
            $stones = DB::select('select * from stone where stone_type = ?', ['granite']);
            if(isset($stones)) $page_data['stones'] = $stones;
        }

        return view('kitchen_dreamer', $page_data);
    }

    public function get_quote_dreamer_view()
    {
        $page_data = [];
        $page_data['stone_types'] = DB::select('select * from stone_types');
        if(Session::get('guest_data')) $page_data['guest_data'] = Session::get('guest_data');

        if(Input::has('selected_stone_type'))
        {
            $stone_type = Input::get('selected_stone_type');
            $page_data['selected_stone_type'] = $stone_type;
            $stones = DB::select('select * from stone where stone_type = ?', [$stone_type]);
            if(isset($stones)) $page_data['stones'] = $stones;
        }
        else{
            $page_data['selected_stone_type'] = 'granite';
            $stones = DB::select('select * from stone where stone_type = ?', ['granite']);
            if(isset($stones)) $page_data['stones'] = $stones;
        }

        return view('quote_dreamer', $page_data);
    }

    public function add_stone_to_quote()
    {
        $quote_data = [];
       
        if(Session::has('quote_data')) $quote_data = Session::get('quote_data');
        $quote_data['countertops'][] = array('room_type' => Input::get('room_type'), 'corner_type' => Input::get('corner_type'), 'shape' => Input::get('shape'), 'sink' => Input::get('sink'), 'seamless' => Input::get('seamless'), 'stone_id' => Input::get('stone_id'));
        Session::put('quote_data', $quote_data);
        return $this->get_kitchen_dreamer_view();
    }

    public function delete_from_quote($countertop_id)
    {
        $quote_data = Session::get('quote_data');
        if(sizeof($quote_data['countertops']) == 1)
        {     //var_dump($quote_data);
              Session::forget('quote_data');
        }
        else { 
            unset($quote_data['countertops'][$countertop_id]);
            Session::put('quote_data', $quote_data); 
        } 

        return $this->get_kitchen_dreamer_view();
    }

    public function get_instant_quote()
    {

        $quote_data = Session::get('quote_data');
        if(array_key_exists('quote_id', $quote_data))
        {
          DB::table('quotes')->where('id', '=' , $quote_data['quote_id'])->update(
            array(
              'quote' => json_encode($quote_data['countertops'])
            )
          );
        }
        else{
            $date = new DateTime();
            $quote_data['quote_id'] = DB::table('quotes')->insertGetId(
              array(
              'user_email' => Auth::user()->email, 
              'created_at' =>  $date->format('Y-m-d'),
              'quote' => json_encode($quote_data),
              'viewed' => false
              )
            );
        }
         Session::put('quote_data', $quote_data);
         return View::make('quote_summary', ['quote_data' => $quote_data]);


/*
        if(!Input::has('stone_id') || !Input::has('square_feet') || !Input::has('email') || !Input::has('name') || !Input::has('phone_number'))
        {
            $results['error_message'] = 'Please Specify All Related Fields';
            return json_encode($results);
        }

        $guest_data = array('name' => Input::get('name'), 'email' => Input::get('email'), 'phone_number' => Input::get('phone_number'), 'square_feet' => Input::get('square_feet'));
        Session::put('guest_data', $guest_data);

        $stone = DB::table('stone')->where('id', '=', Input::get('stone_id'))->first();

        $estimate = $stone->stone_price_per_square_foot * (int) Input::get('square_feet');
        
        $results['estimate'] = number_format($estimate);
        */


    }

    public function get_custom_quote_view()
    {   
        //for now just return initial view
        return View::make('quote_summary');
        //choose room & dimensions & number of counterotops ect....
        //choose countertop view
    }

}
