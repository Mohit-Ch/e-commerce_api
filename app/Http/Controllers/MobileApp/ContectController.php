<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use App\Models\users;
use App\Models\address;
class ContectController extends Controller
{
    //
    public function sendemail(Request $request) {
      
        $data = $request->all();
        if (!empty($data)) {
       
            Mail::send(['html'=>'email'], 
                ['status' => 1,'username'=>$data['Name'],'email'=>$data['Email'],'phone'=>$data['Email'],'message1'=>$data['Message']], function($message) use ($data) {
                 $sub = 'Contact info email ';
                $message->to("admin@gmail.com")->subject($sub);
                $message->from($data['Email'],'data');
              });
            return response()->json([                        
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
        
       
    }

    public function AboutUsInfo(Request $request) {
        $Userdata = users::where( ['usertype'=>'Admin' ] )->first();
        $User;
        if(!empty($Userdata) )
        {
            $AddressListData = address::where( 'users_id', $Userdata['id'] )->first();
            if(!empty($AddressListData)){
                $User['address']=$AddressListData;
            }
            else{
                $User['address']=" ";
            }
            $User['phone_no']= $Userdata["phone_no"];
            $User['about_us']= $Userdata["about_us"];
            
        }
          
            return response()->json([                        
                        'code'=> 100,
                        'status' => 'success',
                        'data'=>$User
            ]);
      
      
    }

}
