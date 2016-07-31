<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Hash;

class AuthController extends Controller
{
    use ThrottlesLogins;

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'employee_code' => 'max:20',
        ]);
    }

    protected function register()
    {
        $user_authorization = 2;
        $data = array('name' => Input::get('name'), 'email' => Input::get('email'), 'password' => Input::get('password'), 'password_confirmation' => Input::get('password_confirmation'));
        $validator = $this->validator($data);
        if(Input::has('employee_code'))
        {
            $validator->after(function($validator) 
            {
                $employee_code = DB::select('select authentication_code from user_type where authentication_level = ?', [1]);
                if(!Hash::check(Input::get('employee_code'), $employee_code[0]->authentication_code))
                {
                    $validator->errors()->add('employee_code', 'Incorrect employee code.');
                }
            });
            $user_authorization = 1;

        }

        if($validator->fails()){
            return redirect('register')
            ->withErrors($validator)
            ->withInput();
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'authorization' => $user_authorization,
        ]);

        return $this->login();
    }

    public function login()
    {
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')])) {
            // Authentication passed...
            $authorization = DB::select('select authorization from users where email = ?', [Input::get('email')]);
            if($authorization[0]->authorization == '1')
            {
                return redirect('/admin/add_stone');
            }
            else{
                return back();
            }
        }
        return redirect('/login')->with('error', 'Your password or email is incorrect.');
        //authentication failed

    }

    protected function logout()
    {
        Auth::logout();
        return redirect('/');

    }
}
