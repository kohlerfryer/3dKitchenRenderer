<?php


Route::get('/', function(){return View::make('home');});
Route::get('/quote_summary', function(){return View::make('quote_summary');});//temporary

Route::get('quote_dreamer', 'MainController@get_quote_dreamer_view');

//Route::get('/{page_name}', 'MainController@get_page_request');

Route::get('login', 'Auth\AuthController@get_login_view');
Route::get('register', 'Auth\AuthController@get_register_view');


Route::post('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@logout');
Route::post('auth/register', 'Auth\AuthController@register');

Route::get('admin/{page_request}','AdminController@page_request');
Route::post('admin_panel/add_stone', 'AdminController@add_stone');
Route::get('admin_panel/populate_stone', 'AdminController@populate_stone');
Route::post('admin_panel/update_stone', 'AdminController@update_stone');
Route::get('admin_panel/get_stone', 'AdminController@get_stone');
Route::get('admin_panel/delete_stone', 'AdminController@delete_stone');

Route::get('custom_quote', 'MainController@get_custom_quote_view');

Route::get('kitchen_dreamer', 'MainController@get_kitchen_dreamer_view');
Route::get('kitchen_dreamer/get_instant_quote', 'MainController@get_instant_quote');
Route::get('kitchen_dreamer/get_kitchen_counter_layers/{stone_id}/{room_id}', 'MainController@get_kitchen_counter_layers');
Route::get('kitchen_dreamer/get_stone', 'MainController@get_stone');
Route::post('kitchen_dreamer/delete_stone_from_quote', 'MainController@delete_from_quote');
Route::post('kitchen_dreamer/add_stone_to_quote', 'MainController@add_stone_to_quote');

