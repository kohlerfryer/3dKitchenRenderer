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
        $stone_group = '1';
        $stone_type = 'granite';
        $selected_room_id = '1';

        if(Input::has('selected_stone_group')) $stone_group = Input::get('selected_stone_group');
        if(Input::has('selected_stone_type')) $stone_type = Input::get('selected_stone_type');
        if(Input::has('selected_room_id')) $selected_room_id = Input::get('selected_room_id');
        if(Session::get('guest_data')) $page_data['guest_data'] = Session::get('guest_data');
        if(Input::has('user_dimensions')) /*$page_data['user_dimensions'] =*/ dd (Input::get('user_dimensions'));

        $page_data['selected_room'] = DB::select('select * from background_rooms where id = ?', [$selected_room_id])[0];
        $page_data['stone_types'] = DB::select('select * from stone_types');
        $page_data['room_backgrounds'] = DB::select('select * from background_rooms');
        $page_data['stone_groups'] = DB::select('select * from stone_groups');
        $page_data['selected_stone_type'] = $stone_type;

        if(Input::has('selected_stone_group'))
        {
            $stones = DB::table('stone')->where('stone_type', '=', $stone_type)->where('group_id', '=', Input::get('selected_stone_group'))->get();
            $selected_stone_group =  Input::get('selected_stone_group');
            $page_data['selected_stone_group'] = $selected_stone_group;
        }
        else {
            $stones = DB::table('stone')->where('stone_type', '=', $stone_type)->get();
        }
        
        if(isset($stones)) $page_data['stones'] = $stones;
        return view('kitchen_dreamer', $page_data);
    }

    public function get_stone()
    {
        $results = [];
        $results['result'] = 'failure';

        $stone_type = 'granite';
        $stones = [];

        if(Input::has('selected_stone_group')) $stone_group = Input::get('selected_stone_group');
        if(Input::has('selected_stone_type')) $stone_type = Input::get('selected_stone_type');
        if(Input::has('selected_room_id')) $selected_room_id = Input::get('selected_room_id');

        if(isset($stone_group))
        $stones_object = DB::table('stone')->where('stone_type', '=', $stone_type)->where('group_id', '=', $stone_group)->get(); 
        else
        $stones_object = DB::table('stone')->where('stone_type', '=', $stone_type)->get(); 
        
        if(isset($stones_object))
        {
            foreach($stones_object as $stone)
            {
                $stones[$stone->id] = ['id'=> $stone->id, 'stone_description' => $stone->stone_description,
                 'stone_name' => $stone->stone_name, 'in_stock_quantity' => $stone->in_stock_quantity, 'stone_price_per_square_foot' => $stone->stone_price_per_square_foot,
                 'stone_picture_url' => $stone->stone_picture_url, 'stone_texture_url' => $stone->stone_texture_url];
            }
        }

        $results['result'] = 'success';
        $results['stones'] = $stones;
        return json_encode($results);

    }

    /*public function get_quote_dreamer_view()
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
    }*/

    public function add_stone_to_quote()
    {
        $results = [];
        $results['result'] = 'failure';

        $quote_data = [];
        if(Session::has('quote_data')) $quote_data = Session::get('quote_data');
        $stone_object = DB::select('select * from stone where id = ?', [Input::get('stone_id')])[0];
        
        $countertop_data = array('area' => Input::get('countertop_area'), 'stone_name' => $stone_object->stone_name, 'stone_texture_url' => $stone_object->stone_texture_url ,'room_type' => Input::get('room_type'), 'corner_type' => Input::get('corner_type'), 'shape' => Input::get('shape'), 'sink' => Input::get('sink'), 'seamless' => Input::get('seamless'), 'stone_id' => Input::get('stone_id'));
        $quote_data['countertops'][] = $countertop_data;
        Session::put('quote_data', $quote_data);

        $results['result'] = 'success';
        $results['countertops'] = $quote_data;
        return json_encode($results);
    }

    public function delete_from_quote()
    {
        $results = [];
        $results['result'] = 'failure';
        $countertop_id = Input::get('countertop_id');
        $quote_data = Session::get('quote_data');
        if(sizeof($quote_data['countertops']) == 1)
        {   
            Session::forget('quote_data');
            unset($quote_data);
        }
        else { 
            unset($quote_data['countertops'][$countertop_id]);
            Session::put('quote_data', $quote_data); 
        } 

        $results['result'] = 'success';
        if(isset($quote_data))$results['countertops'] = $quote_data;
        return json_encode($results);
    }

    public function get_instant_quote()
    {

        $quote_data = Session::get('quote_data');
        $total_price = 0;
        foreach($quote_data['countertops'] as $countertop_data)
        {
            $stone_object = DB::select('select stone_price_per_square_foot from stone where id = ?', [$countertop_data['stone_id']])[0];
            $countertop_price = $stone_object->stone_price_per_square_foot;
            $countertop_price = $countertop_price * $countertop_data ['area']; 
            if($countertop_data['sink'] == 'yes') $countertop_price += 200; 
            if($countertop_data['seamless'] == 'yes') $countertop_price += 200; 
            $total_price += $countertop_price;
        }
        $quote_data['total_price'] = $total_price;
        if(array_key_exists('quote_id', $quote_data))
        {
          DB::table('quotes')->where('id', '=' , $quote_data['quote_id'])->update(
            array(
              'quote' => json_encode($quote_data)
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

    }

    /*public function get_custom_quote_view()
    {   
        //for now just return initial view
        return View::make('quote_summary');
        //choose room & dimensions & number of counterotops ect....
        //choose countertop view
    }*/

}
