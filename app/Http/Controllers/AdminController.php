<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB;
use File;
use View;
use Auth;

class AdminController extends Controller
{

public function __construct()
{
  $this->middleware('auth');
}


public function page_request($page_request)
{
  return View::make($page_request, ['current_page' => $page_request , 'user_name' => Auth::user()]);
}

public function add_image_to_directory($stone_type)
  {

    if(!Input::file('stone_image')->isvalid()) return -1; 

    $stone_image = array('image' => Input::file('stone_image'));
    $destination_path = 'images/'. $stone_type . '/';
    $extension =  strtolower(Input::file('stone_image')->getClientOriginalExtension());

    if($extension != 'png' && $extension != 'gif' && $extension != 'jpeg' && $extension != 'gif' && $extension != 'tif' && $extension != 'jpg') return -1;  
    $file_name = rand(11111,99999) . '.' . $extension;
    Input::file('stone_image')->move($destination_path, $file_name);
    return $destination_path . $file_name;
  }

  public function add_stone()
  {

    $results = [];
    $results['result'] = 'failure';

    if(!Input::has('stone_name') || !Input::has('stone_type') ||  !Input::has('stone_description') ||  !Input::has('stone_price') ||  !Input::has('stone_quantity'))
    {
      $results['error_message'] = 'please specify all fields';
      return json_encode($results);      
    }

    $stone_name = Input::get('stone_name');
    $stone_type = Input::get('stone_type');
    $stone_description = Input::get('stone_description');
    $stone_price = Input::get('stone_price');
    $stone_quantity = Input::get('stone_quantity');


    $stone_image_url = $this->add_image_to_directory($stone_type);
    if($stone_image_url == -1)
    {
      $results['error_message'] = 'invalid image format';
      return json_encode($results);
    }


    DB::table('stone')->insert(
      array(
      'stone_name' => $stone_name, 
      'stone_type' => $stone_type , 
      'in_stock_quantity' => $stone_quantity ,
      'stone_description' => $stone_description, 
      'stone_picture_url' => $stone_image_url ,
      'stone_price_per_square_foot' =>$stone_price 
      )
    );

    $results['result'] = 'success';
    return json_encode($results);
  }


  public function update_stone()
  {

     $results = [];
     $results['result'] = 'failure';
     //check for authentication

     if(!Input::has('stone_name') || !Input::has('stone_type') ||  !Input::has('stone_description') ||  !Input::has('stone_price') ||  !Input::has('stone_quantity') ||  !Input::has('stone_id'))
     {
        $results['error_message'] = 'please specify all fields';
        return json_encode($results);      
     }

     $stone_name = Input::get('stone_name');
     $stone_type = Input::get('stone_type');
     $stone_description = Input::get('stone_description');
     $stone_price = Input::get('stone_price');
     //dd($stone_price);
     $stone_id = Input::get('stone_id');
     $stone_quantity = Input::get('stone_quantity');


      if(!Input::has('stone_image_url'))
      {
        $stone_image_url = $this->add_image_to_directory($stone_type);
        if($stone_image_url == -1)
        {
          $results['error_message'] = 'invalid image format';
          return json_encode($results);
        }
          $old_stone = DB::table('stone')->where('id', '=', $stone_id)->first();
          File::Delete($old_stone->stone_picture_url);
      }
      else $stone_image_url = Input::get('stone_image_url');

      DB::table('stone')->where('id', '=' , $stone_id)->update(
        array(
        'stone_name' => $stone_name, 
        'stone_type' => $stone_type , 
        'in_stock_quantity' => $stone_quantity ,
        'stone_description' => $stone_description, 
        'stone_picture_url' => $stone_image_url ,
        'stone_price_per_square_foot' => $stone_price 
        )
      );

      $results['result'] = 'success';
      $results['stone_id'] = $stone_id;
      $results['stone_image_url'] = $stone_image_url;
      return json_encode($results);
  }

  public function populate_stone()
  {

    $results = [];
    $results['result'] = 'failure';
    $stone_array = [];
    //authentication
    if(!Input::has('stone_type'))
    {
      $results['error_message'] = 'stone type missing';
      return json_encode($results);       
    }

    $stone_type = Input::get('stone_type');

    $stones = DB::select('select * from stone where stone_type = ?', [$stone_type]);
    foreach($stones as $stone_object)
    { 
      $stone_array[$stone_object->id] = $stone_object->stone_name; 
    }

    $results['result'] = 'success';
    $results['stone_array'] = $stone_array;
    return json_encode($results);
  }

  public function get_stone()
  {

    $results = [];
    $results['result'] = 'failure';
    $stone_array = [];
    //authentication
    if(!Input::has('stone_id'))
    {
      $results['error_message'] = 'Stone Id Field Missing';
      return json_encode($results);       
    }

    $stone_id = Input::get('stone_id');

    $stone = DB::select('select * from stone where id = ?', [$stone_id]);

    $results['result'] = 'success';
    $results['stone'] =(array) $stone;
    return json_encode($results);
  }

  public function delete_stone()
  {

    if(!Input::has('stone_id'))
    {
      $results['error_message'] = 'Stone Id Field Missing';
      return json_encode($results);       
    }

    $stone_id = Input::get('stone_id');
    DB::table('stone')->where('id', '=', Input::get('stone_id'))->delete();
  }
}
