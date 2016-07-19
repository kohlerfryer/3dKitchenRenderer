<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input;
use Auth;

class AuthController extends Controller
{
    use ThrottlesLogins;

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'employee_code' => 'required|max:20',
        ]);
    }

    protected function register()
    {
        $data = array('name' => Input::get('name'), 'email' => Input::get('email'), 'password' => Input::get('password'), 'password_confirmation' => Input::get('password_confirmation'), 'employee_code' => Input::get('employee_code'));
        $validator = $this->validator($data);
        $validator->after(function($validator) 
        {
            if (Input::get('employee_code')!='dontmesswithmike') 
            {
                    $validator->errors()->add('employee_code', 'Incorrect employee code.');
            }
        });
        if($validator->fails()){
            return redirect('register')
            ->withErrors($validator)
            ->withInput();
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $this->login();
    }

    public function login()
    {
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')])) {
            // Authentication passed...
            return redirect('/admin/add_stone');
        }
    }

    protected function logout()
    {
        Auth::logout();
    }
}
