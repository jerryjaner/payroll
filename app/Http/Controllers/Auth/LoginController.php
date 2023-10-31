<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){

        $input = $request -> all();
      
        $validator = \Validator::make($request -> all(),[
            
            'username' => 'required|username',
            'password' => 'required'
        ]);


        if(Auth()->attempt(array('username'=> $input ['username'],'password' => $input ['password'])) ){
          
            return redirect()->route('account');
        }
        else
        {
            return redirect()->route('login')->with("login_message","The username or password that you've entered doesn't match any account. ");
        }
        
    }
}
