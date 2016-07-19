<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;

use App\User;
use Validator;

class AuthenticationController extends Controller
{
    public function register_user()
    {
     $user_data = array('name' => Input::get('name'), 'email' => Input::get('email'), 'password' => Input::get('password'));
     dd($this->validator($user_data)->fails());

    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

}
