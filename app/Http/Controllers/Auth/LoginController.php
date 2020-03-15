<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Auth;
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
   // protected $redirectTo = '/home';
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) {
        $this->validateLogin($request);
        $Isnotificaion=1;
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'usertype' => 'User', 'is_active' => 0])) {
            $photoURL = url("user_photos");
            $users = $this->guard()->user();
            
            $users->generateToken();            
            $userId = $users->id;
           
            $userData = $users->toArray();
            if ($userData['photo']) {
                $temp = $userData['photo'];
                $userData['photo'] = $photoURL . "/" . $temp;
            }
            return response()->json([
                        'data' => $userData,
                        'code'=> 100,
                        'status' => 'success'
            ]);
        }
         else 
        {
               return response()->json([
                            'message' => 'Login Email or Password are incorrect',
                            'code'=> 101,
                            'status' => 'error'
                ]);
        }
        
        //dd($request);
        return $this->sendFailedLoginResponse($request);
    }
}
