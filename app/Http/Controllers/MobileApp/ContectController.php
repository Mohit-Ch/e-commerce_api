<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use App\Models\users;
use App\Models\address;
use App\Models\company_detail;
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
        $companydetail = company_detail::get()->toArray();
        $User;
        if(!empty($companydetail) )
        {
            $User['companydetail']=$companydetail;
        }
          
            return response()->json([                        
                        'code'=> 100,
                        'status' => 'success',
                        'data'=>$User
            ]);
      
      
    }

    public function getLogo(Request $request) {
       
            $data;
            $companydetail = company_detail::where( 'Key', 'logo' )->first();
            if(!empty($companydetail)){
                //  $data["logo"]= url('public/company_logo/'.$companydetail['value']);
                $data['logo']=url('company_logo/'.$companydetail['value']);
            }
            else{
              //  $data['logo']="http://golden-handle.com/logo.png";
            }
            return response()->json([                        
                        'code'=> 100,
                        'status' => 'success',
                        'data'=>$data
            ]);
      
      
    }

}
