<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\users;
use Carbon;
use DB;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return users::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user) {
        $user->generateToken();
        return response()->json(['data' => $user->toArray()], 201);
    }

    public function registration(Request $request) {
        $rt['status'] = 'error';
        $host = $request->getHttpHost();
        $data = $request->all();
        $v = Validator::make($data, [
                    'email' => 'required',
                    'password' => 'required',
                    'name' => 'required',
                    'phone_no' => 'required',
        ]);


        if ($v->fails()) {
            $rt['code'] = '101';
            $rt['status'] = 'error';
            $rt['message'] = $v->errors()->toJson();
        } else {
            $user = users::where(["email"=> $data['email']])->first();
            if(!empty($user)&& $user!=null){
                $rt['code'] = '101';
                $rt['status'] = 'error';
                $rt['message'] = 'Email is already taken';
                return response()->json($rt);
            }
            else 
            {
                
                            $user = users::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => bcrypt($data['password']),
                                'phone_no' => $data['phone_no'],
                                'is_active' => 0,
                                'usertype' => 'User',
                                'user_Name'=>$data['email']
                    ]);
                    $currenttime = Carbon\Carbon::now();
                        $cdt = encrypt($currenttime);
                   
                    if ($user) {
                        $rt['code'] = '100';
                        $rt['status'] = 'success';
                        $rt['data'] = $user;
                    }
                
            }
          
        }
        return response()->json($rt);
    }

}
