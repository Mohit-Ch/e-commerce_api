<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Transformers\Json;
use Illuminate\Support\Facades\Hash;
use App\User;
use Mail;
use DB;
use Carbon;
use App\Models\ExceptionHandle;
use Exception;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getResetToken(Request $request)
    {   
      
        $this->validate($request, ['email' => 'required|email']);  
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(Json::response(null, trans('passwords.user')), 400);
            }
            $token = $this->broker()->createToken($user);
            return response()->json(Json::response(['token' => $token]));
    }

    public function generateNewPassword(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
       // if ($request->wantsJson()) {

            $user = User::where(['email'=> $request->input('email'),'is_active'=>1])->first();           
            $p1=mt_rand(1, 9);
            $p2=mt_rand(1, 9);
            $p3=mt_rand(1, 9);
            $p4=mt_rand(1, 9);
            $p5=mt_rand(1, 9);
            $p6=mt_rand(1, 9);
            $password=(int)($p1.$p2.$p3.$p4.$p5.$p6);
            $hashed_random_password = Hash::make($password);
           
            $this->sendRadomPasswordMail($user->name,$user->email,$password);
       
            $user->password=$hashed_random_password;
            $user->save();
            return response()->json(Json::response(['status' => 'success']));
        // }else{
        //     return response()->json(array("error"=>'Not Valid Format')); 
        // }
    }

    public function sendRadomPasswordMail($name,$email,$password){
        $data = array('name'=>$name,'email'=>$email,'password'=>$password,'status'=>2);
   
        Mail::send(['html'=>'email'], $data, function($message) use($email,$name) {
           $message->to($email,$name)->subject
              (trans('password reset'));
           $message->from('admin@goldenhandle.com','Golden Handle');
        });
        return true;
    }

}
