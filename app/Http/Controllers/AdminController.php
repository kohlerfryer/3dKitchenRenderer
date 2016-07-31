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

  if($page_request == 'quotes')
  {
     $new_quotes = DB::table('quotes')->where('viewed', '=', 0)->get();
     if(!empty($new_quotes)) $page_data['new_quotes'] = $new_quotes;
     $old_quotes = DB::table('quotes')->where('viewed', '=', 1)->take(30)->get();
     if(!empty($old_quotes)) $page_data['old_quotes'] = $old_quotes;
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
          $this->set_textures_bathroom_1($stone_id, $stone_texture_url);


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

      $im4 = clone $im;
      $im5 = clone $im;
      $im6 = clone $im;

      $im7 = clone $im;
      $im8 = clone $im;
      $im9 = clone $im;

      $im10 = clone $im;


      $image_background = new Imagick();
      $image_background->newImage(900, 500, 'transparent');
      $image_background->setimagebackgroundcolor("transparent");

      $image_texture = new Imagick('images/room_backgrounds/kitchen_2_layers.png');
      $image_texture->adaptiveResizeImage(900,500);

      $im->adaptiveResizeImage(900,500);
      $im2->cropImage(900,20,0,0);
      $im2->adaptiveResizeImage(900,500);
      $im3->cropImage(20,500,0,0);
      $im3->adaptiveResizeImage(900,500);

      $im4->adaptiveResizeImage(900,500);
      $im5->cropImage(900,20,0,0);
      $im5->adaptiveResizeImage(900,500);
      $im6->cropImage(20,500,0,0);
      $im6->adaptiveResizeImage(900,500);

      $im7->cropImage(450,500,0,0);
      $im7->adaptiveResizeImage(900,500);
      $im8->cropImage(900,20,0,0);
      $im8->adaptiveResizeImage(900,500);
      $im9->cropImage(450,20,0,0);
      $im9->adaptiveResizeImage(900,500);
      $im10->cropImage(450,250,0,0);
      $im10->adaptiveResizeImage(900,500);


      $im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im2->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im3->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im4->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im5->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im6->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im7->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im8->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im9->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im10->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);

      $controlPoints = array( 
        0, 0, 
        618, 323,

        0, 500,
        338, 380,       

        900, 0,
        802, 336,

        900, 500,
        613, 425
      );

      $controlPoints2 = array( 
        0, 0, 
        338, 380,

        0, 500,
        338, 390,       

        900, 0,
        613, 425,

        900, 500,
        613, 438
      );

      $controlPoints3 = array( 
        0, 0, 
        802, 336,

        0, 500,
        613, 425,     

        900, 0,
        802, 344,

        900, 500,
        613, 438
      );

      $controlPoints4 = array( 
        0, 0, 
        370, 301,

        0, 500,
        3, 333,       

        900, 0,
        457, 308,

        900, 500,
        92, 350
      );

      $controlPoints5 = array( 
        0, 0, 
        3, 333,

        0, 500,
        3, 340,       

        900, 0,
        92, 350,

        900, 500,
        92, 357
      );

      $controlPoints6 = array( 
        0, 0, 
        457, 308,

        0, 500,
        92, 350,     

        900, 0,
        457, 313,

        900, 500,
        92, 357
      );

      $controlPoints7 = array( 
        0, 0, 
        521, 289,

        0, 500,
        513, 294,     

        900, 0,
        661, 292,

        900, 500,
        661, 301
      );

      $controlPoints8 = array( 
        0, 0, 
        557, 296,

        0, 500,
        557, 301,     

        900, 0,
        661, 301,

        900, 500,
        661, 306
      );

      $controlPoints9 = array( 
        0, 0, 
        538, 299,

        0, 500,
        538, 303,     

        900, 0,
        558, 296,

        900, 500,
        558, 301
      );

      $controlPoints10 = array( 
        0, 0, 
        506, 290,

        0, 500,
        467, 293,     

        900, 0,
        557, 296,

        900, 500,
        540, 300
      );

      $im->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
      $im2->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints2, true);
      $im3->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints3, true);
      $im4->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints4, true);
      $im5->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints5, true);
      $im6->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints6, true);
      $im7->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints7, true);
      $im8->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints8, true);
      $im9->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints9, true);
      $im10->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints10, true);



      $image_background->addImage($im);
      $image_background->addImage($im2);
      $image_background->addImage($im3);
      $image_background->addImage($im4);
      $image_background->addImage($im5);
      $image_background->addImage($im6);
      $image_background->addImage($im7);
      $image_background->addImage($im8);
      $image_background->addImage($im9);
      $image_background->addImage($im10);
      $image_background->addImage($image_texture);



      $image_background = $image_background->mergeImageLayers(Imagick::LAYERMETHOD_UNDEFINED);
      $image_background->setFormat("png");

      $image_file_name = rand(11111,99999) . '.png';
      File::put(public_path().'/images/texture_layouts/'.$image_file_name, $image_background);

      $current_texture = DB::table('texture')->where('stone_id', '=', $stone_id)->where('room_id', '=', '1')->first();
      if(isset($current_texture))
      {
        $old_texture = DB::table('texture')->where('stone_id', '=' , $stone_id)->where('room_id', '=', '1')->first();
        File::delete($old_texture->texture_layout_url);
        DB::table('texture')->where('stone_id', '=' , $stone_id)->where('room_id', '=', '1')->update(
          array(
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }
      
      else{
        DB::table('texture')->insert(
          array(
          'stone_id' => $stone_id , 
          'room_id' => '1',
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }


    }


public function set_textures_bathroom_1($stone_id, $stone_texture_url)
    {

      $im = new Imagick($stone_texture_url);
      $im2 = clone $im;
      $im3 = clone $im;
      $im4 = clone $im;


      $image_background = new Imagick();
      $image_texture = new Imagick('images/room_backgrounds/bathroom_1_layers.png');
      $image_texture->adaptiveResizeImage(900,500);

      $image_background->newImage(900, 500, 'transparent');
      $image_background->setimagebackgroundcolor("transparent");

      $im->adaptiveResizeImage(900,500);
      $im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im2->rotateImage('transparent',90);
      $im2->adaptiveResizeImage(900,500);
      $im2->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im3->cropImage(450,500,0,0);
      $im3->adaptiveResizeImage(900,500);
      $im3->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
      $im4->cropImage(10000,100,0,0);
      $im4->adaptiveResizeImage(900,500);
      $im4->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);


      $controlPoints = array( 
        0, 0, 
        476, 297,

        0, 500,
        364, 302,       

        900, 0,
        900, 345,

        900, 500,
        900, 416
      );

      $controlPoints2 = array( 
        0, 0, 
        476, 235,

        0, 500,
        476, 297,       

        900, 0,
        900, 220,

        900, 500,
        900, 355
      );

      $controlPoints3 = array( 
        0, 0, 
        369, 234,

        0, 500,
        369, 302,       

        900, 0,
        476, 235,

        900, 500,
        476, 297
      );

      $controlPoints4 = array( 
        0, 0, 
        365, 302,

        0, 500,
        365, 309,       

        900, 0,
        900, 416,

        900, 500,
        900, 437
      );

      $im->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
      $im2->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints2, true);
      $im3->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints3, true);
      $im4->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints4, true);

      $image_background->addImage($im);
      $image_background->addImage($im2);
      $image_background->addImage($im3);
      $image_background->addImage($im4);

      $image_background->addImage($image_texture);
      $image_background = $image_background->mergeImageLayers(Imagick::LAYERMETHOD_UNDEFINED);
      $image_background->setFormat("png");

      $image_file_name = rand(11111,99999) . '.png';
      File::put(public_path().'/images/texture_layouts/'.$image_file_name, $image_background);

      $current_texture = DB::table('texture')->where('stone_id', '=', $stone_id)->where('room_id', '=', '2')->first();
      if(isset($current_texture))
      {
        $old_texture = DB::table('texture')->where('stone_id', '=' , $stone_id)->where('room_id', '=', '2')->first();
        File::delete($old_texture->texture_layout_url);
        DB::table('texture')->where('stone_id', '=' , $stone_id)->where('room_id', '=', '2')->update(
          array(
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }
      
      else{
        DB::table('texture')->insert(
          array(
          'stone_id' => $stone_id , 
          'room_id' => '2',
          'texture_layout_url' => 'images/texture_layouts/'. $image_file_name
          )
        );
      }


    }

}
