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

class MainController extends Controller
{

    public function get_kitchen_counter_layers($stone_id, $room_id)
    {
        $texture_url = DB::select('select texture_layout_url from texture where stone_id = ? and room_id = ?', [$stone_id, $room_id]);
        //echo($room_id);
        return (new Response(File::get(public_path().'/'.$texture_url[0]->texture_layout_url)))->header('content-type', 'image/png');
    }

    public function get_kitchen_dreamer_view()
    {
        $page_data = [];
        $page_data['stone_types'] = DB::select('select * from stone_types');
        if(Session::get('guest_data')) $page_data['guest_data'] = Session::get('guest_data');
        $page_data['room_backgrounds'] = DB::select('select * from background_rooms');
        if(Input::has('selected_room_id')) $page_data['selected_room'] = DB::select('select * from background_rooms where id = ?', [Input::get('selected_room')]);
        else{
            $page_data['selected_room'] = $page_data['room_backgrounds'][1];
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

    public function get_instant_quote()
    {
        $results = [];
        $results['result'] = 'failure';

        if(!Input::has('stone_id') || !Input::has('square_feet') || !Input::has('email') || !Input::has('name') || !Input::has('phone_number'))
        {
            $results['error_message'] = 'Please Specify All Related Fields';
            return json_encode($results);
        }

        $guest_data = array('name' => Input::get('name'), 'email' => Input::get('email'), 'phone_number' => Input::get('phone_number'), 'square_feet' => Input::get('square_feet'));
        Session::put('guest_data', $guest_data);

        $stone = DB::table('stone')->where('id', '=', Input::get('stone_id'))->first();

        $estimate = $stone->stone_price_per_square_foot * (int) Input::get('square_feet');
        $results['result'] = 'success';
        $results['estimate'] = number_format($estimate);
        return json_encode($results);

    }

    public function get_custom_quote_view()
    {   
        //for now just return initial view
        return View::make('quote_summary');
        //choose room & dimensions & number of counterotops ect....
        //choose countertop view
    }

    public function get_login_view()
    {
        if(Auth::guest())return View::make('auth/login');
        
        $authorization = DB::select('select authorization from users where email = ?', [Auth::user()->email]);
        if($authorization[0]->authorization == '1')
        return View::make('add_stone', ['current_page' => 'add_stone' , 'user_name' => Auth::user() , 'stone_types' =>   $page_data['stone_types'] = DB::select('select * from stone_types')]);
        else
        return View::make('home');

    }

    public function get_register_view()
    {
        if(Auth::guest())return View::make('auth/register');
        
        $authorization = DB::select('select authorization from users where email = ?', [Auth::user()->email]);
        if($authorization[0]->authorization == '1')
        return View::make('add_stone', ['current_page' => 'add_stone' , 'user_name' => Auth::user() , 'stone_types' =>   $page_data['stone_types'] = DB::select('select * from stone_types')]);
        else
        return View::make('home');

    }
}
