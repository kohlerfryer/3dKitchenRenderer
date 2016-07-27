<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB;
use File;
use View;
use Auth;
use Imagick;
use ImagickPixel;
use Illuminate\Http\Response;
use Storage;

class AdminController extends Controller
{

public function __construct()
{
  $this->middleware('auth');
}


public function page_request($page_request)
{
  $page_data = [];
  $page_data['current_page'] = $page_request;
  $page_data['user_name'] = Auth::user();
  $page_data['stone_types'] = DB::select('select * from stone_types');

  if($page_request == 'inventory_management')
  {
    if(Input::has('stone_type'))
    {
      $page_data['selected_stone_type'] = Input::get('stone_type');
      $page_data['stones'] = DB::select('select * from stone where stone_type = ?',[Input::get('stone_type')]);
    }
    if(Input::has('stone_id')) $page_data['selected_stone'] = DB::table('stone')->where('id', '=', Input::get('stone_id'))->first();
  }

  return View::make($page_request, $page_data);
}

public function add_image_to_directory($stone_type, $image)
  {
    $destination_paths = [];

    if(!$image->isvalid()) return -1; 
    $image_destination_path = 'images/'. $stone_type . '/';
    $image_extension =  strtolower($image->getClientOriginalExtension());
    if($image_extension != 'png' && $image_extension != 'gif' && $image_extension != 'jpeg' && $image_extension != 'gif' && $image_extension != 'tif' && $image_extension != 'jpg') return -1;  
    $image_file_name = rand(11111,99999) . '.' . $image_extension;
    $image->move($image_destination_path, $image_file_name);

    return $image_destination_path . $image_file_name;

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

    $stone_image_url = $this->add_image_to_directory($stone_type, Input::file('stone_image'));
    $stone_texture_url = $this->add_image_to_directory($stone_type, Input::file('stone_texture'));

    if($stone_image_url == -1 || $stone_texture_url == -1)
    {
      $results['error_message'] = 'invalid image format';
      return json_encode($results);
    }


    $stone_id = DB::table('stone')->insertGetId(
      array(
      'stone_name' => $stone_name, 
      'stone_type' => $stone_type , 
      'in_stock_quantity' => $stone_quantity ,
      'stone_description' => $stone_description, 
      'stone_picture_url' => $stone_image_url ,
      'stone_price_per_square_foot' =>$stone_price,
      'stone_texture_url' => $stone_texture_url
      )
    );

    $this->set_textures_kitchen_1($stone_id, $stone_texture_url);


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
     $stone_id = Input::get('stone_id');
     $stone_quantity = Input::get('stone_quantity');


      if(!Input::has('stone_image_url'))
      {
        $stone_image_url = $this->add_image_to_directory($stone_type, Input::file('stone_image'));
        if($stone_image_url == -1)
        {
          $results['error_message'] = 'invalid image format';
          return json_encode($results);
        }
          $old_stone = DB::table('stone')->where('id', '=', $stone_id)->first();
          File::Delete($old_stone->stone_picture_url);
      }
      else $stone_image_url = Input::get('stone_image_url');

      if(!Input::has('stone_texture_url'))
      {
        $stone_texture_url = $this->add_image_to_directory($stone_type, Input::file('stone_texture'));
        if($stone_texture_url == -1)
        {
          $results['error_message'] = 'invalid image format';
          return json_encode($results);
        }
          $old_stone = DB::table('stone')->where('id', '=', $stone_id)->first();
          File::Delete($old_stone->stone_texture_url);
          $this->set_textures_kitchen_1($stone_id, $stone_texture_url);

      }
      else $stone_texture_url = Input::get('stone_texture_url');

      DB::table('stone')->where('id', '=' , $stone_id)->update(
        array(
        'stone_name' => $stone_name, 
        'stone_type' => $stone_type , 
        'in_stock_quantity' => $stone_quantity ,
        'stone_description' => $stone_description, 
        'stone_picture_url' => $stone_image_url ,
        'stone_price_per_square_foot' => $stone_price ,
        'stone_texture_url' => $stone_texture_url
        )
      );

      $results['result'] = 'success';
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
    $stone = DB::table('stone')->where('id', '=', $stone_id)->first();
    File::delete($stone->stone_texture_url);
    File::delete($stone->stone_picture_url);
    DB::table('stone')->where('id', '=', $stone_id)->delete();

    $stone_textures = DB::table('texture')->where('stone_id', '=', $stone_id)->get();
    foreach($stone_textures as $stone_texture)
    {
      File::delete($stone_texture->texture_layout_url);
    }
    DB::table('texture')->where('stone_id', '=', $stone_id)->delete();

  }

    public function set_textures_kitchen_1($stone_id, $stone_texture_url)
    {

      $im = new Imagick($stone_texture_url);
      $im2 = clone $im;
      $im3 = clone $im;
      $image_background = new Imagick();
      $image_background->newImage(900, 500, 'transparent');
      $image_background->setimagebackgroundcolor("transparent");


      $im->adaptiveResizeImage(900,500);
      $im2->cropImage(900,20,0,0);
      $im2->adaptiveResizeImage(900,500);
      $im3->cropImage(20,500,0,0);
      $im3->adaptiveResizeImage(900,500);


      $im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im2->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im3->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);

      /*$controlPoints = array( 
        0, 0, 
        213, 266,

        0, 500,
        142, 290,       //white kitchen

        900, 0,
        753, 266,

        900, 500,
        846, 289
      );*/

      $controlPoints = array( 
        0, 0, 
        621, 324,

        0, 500,
        342, 380,       

        900, 0,
        800, 337,

        900, 500,
        613, 423
      );

      $controlPoints2 = array( 
        0, 0, 
        343, 380,

        0, 500,
        342, 390,       

        900, 0,
        613, 423,

        900, 500,
        613, 433
      );

      $controlPoints3 = array( 
        0, 0, 
        800, 337,

        0, 500,
        613, 423,     

        900, 0,
        800, 347,

        900, 500,
        613, 433
      );

      $im->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
      $im2->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints2, true);
      $im3->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints3, true);

      $image_background->addImage($im);
      $image_background->addImage($im2);
      $image_background->addImage($im3);
      $image_background = $image_background->mergeImageLayers(Imagick::LAYERMETHOD_UNDEFINED);
      $image_background->setFormat("png");

      $image_file_name = rand(11111,99999) . '.png';
      File::put(public_path().'/images/texture_layouts/'.$image_file_name, $image_background);

      $current_texture = DB::table('texture')->where('stone_id', '=', $stone_id)->where('kitchen_id', '=', '1')->first();
      if(isset($current_texture))
      {
        $old_texture = DB::table('texture')->where('stone_id', '=' , $stone_id)->where('kitchen_id', '=', '1')->first();
        File::delete($old_texture->texture_layout_url);
        DB::table('texture')->where('stone_id', '=' , $stone_id)->where('kitchen_id', '=', '1')->update(
          array(
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }
      
      else{
        DB::table('texture')->insert(
          array(
          'stone_id' => $stone_id , 
          'kitchen_id' => '1',
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }


    }

}
