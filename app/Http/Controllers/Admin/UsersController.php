<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\users;
use App\Models\guest_user;
use App\Models\address;
use App\Models\promocode;
use Carbon;

class UsersController extends Controller
{
     // Get is active Users Data
     public function getUserList(Request $request)
     {
         //  Definreturn jason data id any error occured
         $rt['code'] =  203; 
         $rt['status'] = 'error';
         // Get Request Data
         $data = $request->all();
         if(!empty($data))
         {
             $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
             if(!empty($userdata))
             {
                 
                 // Find count of Pandong Orders
                 $newCountOrder = order:: where('status','placed')->count();
 
                 // Find List Of new Users
                 $NewUserList=users::where(['is_active'=>0,'usertype'=>'User'])->get()->toArray();
                 $newUserCount=0;
                 $newUserArrayList=[];
 
                 //  Find count of New Users
                 if(!empty($NewUserList)){
                     $newUserCount=count($NewUserList);
                     $i=1;
                     foreach($NewUserList as $newU)
                     {
                         $newUserArray['s_no']=$i;
                         $newUserArray['id']=$newU['id'];
                         $newUserArray['name']=$newU['name'];
                         $newUserArray['phone_no']=$newU['phone_no'];
                         $newUserArray['company_name']=$newU['company_name'];
                         $i++;
                         array_push($newUserArrayList,$newUserArray);
                     }
                 }
                
                 //  Find Active users count
                 $activeUsercount=users::where(['is_active'=>1,'usertype'=>'User'])->count();
 
                 //  Find heighest Offers Is runnning
                 $datetime=Carbon\Carbon::now();
                 $activePromocode=promocode::where('start_date','<=',$datetime)->where('end_date','>=',$datetime)->count();
 
                 // makedata to send
                 $data['promocode']=$activePromocode;
                 $data['registerUserCount']=$activeUsercount;
                 $data['newUserList']=$newUserArrayList;
                 $data['newUserCount']=$newUserCount;
                 $data['newCountOrder']=$newCountOrder;
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data']= $data;
             }
             else{
                  // Status 202 Is  used for User not Valid
                   $rt['code'] =  202; 
                   $rt['status'] = 'error';
                 }
         }
         else{
         // Status 201 Is request data is not presetnt
             $rt['code'] =  201; 
             $rt['status'] = 'error';
         }
         return response()->json($rt);
     }

     // Save User Status Active Rejects
     public function saveUserStatus(Request $request)
     {
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        
        if($data!=null)
        {
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
            if(!empty($data))
            {
                if($data['status']=="active")
                {
                     users::where(['id'=>$data['id'],'usertype'=>'User','is_active'=>0 ])->update(['is_active'=>1]);
                }else{
                     users::where(['id'=>$data['id'],'usertype'=>'User','is_active'=>0 ])->update(['is_active'=>2]);
                }

                $rt['code'] =  200; 
                $rt['status'] = 'success';

            }else{
                  // Status 202 Is  used for User not Valid
                   $rt['code'] =  202; 
                   $rt['status'] = 'error';
                 }

        }
        else{
            // Status 201 Is request data is not presetnt
                $rt['code'] =  201; 
                $rt['status'] = 'error';
            }
        return response()->json($rt);
     }


     // Get is active Users Data
     public function getAdminData(Request $request)
     {
         //  Definreturn jason data id any error occured
         $rt['code'] =  203; 
         $rt['status'] = 'error';
         // Get Request Data
         $data = $request->all();
         if(!empty($data))
         {
             $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
             if(!empty($userdata))
             {
                
                $UserDetail['UserName']=$userdata['	name'];
                $UserDetail['email']=$userdata['email'];
                $UserDetail['company_name']=$userdata['company_name'];
                $UserDetail['about_us']=$userdata['about_us'];
                $UserDetail['phone_no']=$userdata['phone_no']; 
                $UserAddress=address::where('users_id',$userdata['id'])->first();
                if(!empty($UserAddress))
                {
                    $UserDetail['address_id']=$UserAddress['id'];
                    $UserDetail['country']=$UserAddress['country'];
                    $UserDetail['address1']=$UserAddress['address1'];
                    $UserDetail['address2']=$UserAddress['address2'];
                    $UserDetail['city']=$UserAddress['city']; 
                    $UserDetail['area']=$UserAddress['area'];
                    $UserDetail['postal_code']=$UserAddress['postal_code']; 
                }
                else{
                    $UserDetail['address_id']=0;
                    $UserDetail['country']="Kuwait";
                    $UserDetail['address1']="";
                    $UserDetail['address2']="";
                    $UserDetail['city']=""; 
                    $UserDetail['area']="";
                    $UserDetail['postal_code']="";  
                }
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data']= $UserDetail;
             }
             else{
                  // Status 202 Is  used for User not Valid
                   $rt['code'] =  202; 
                   $rt['status'] = 'error';
                 }
         }
         else{
         // Status 201 Is request data is not presetnt
             $rt['code'] =  201; 
             $rt['status'] = 'error';
         }
         return response()->json($rt);
     }

    // Get is active Users Data
    public function saveAdminData(Request $request)
    {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if(!empty($data))
        {
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
            if(!empty($userdata))
            {
                users::where(['api_token'=>$data['api_token'],'usertype'=>'Admin' ])->update([
                    'name'=>$data['UserName'],
                    'email'=>$data['email'],
                    'company_name'=>$data['company_name'],
                    'about_us'=>$data['about_us'],
                    'phone_no'=>$data['phone_no']]);
              if($data['address_id']!=0){
                    $UserAddress=address::where('id',$data['address_id'])->first();
                    if(!empty($UserAddress))
                    {
                        address::where('id',$data['address_id'])->update([
                            'country'=>$data['country'],
                            'address1'=>$data['address1'],
                            'address2'=>$data['address2'],
                            'city'=>$data['city'],
                            'area'=>$data['area'],
                            'postal_code'=>$data['postal_code']
                        ]);

                        $rt['code'] =  200; 
                        $rt['status'] = 'success';
                    }
             }
             else{
                $address = address::create([
                    'users_id' => $userdata['id'],
                    'country' => $data['country'],
                    'address1'=>$data['address1'],
                    'address2'=>$data['address2'],
                    'city'=>$data['city'],
                    'postal_code'=>$data['postal_code'],
                    'is_default'=>0
                     ]);
                    
                     $rt['code'] =  200; 
                     $rt['status'] = 'success';
                }
            }
            else{
                // Status 202 Is  used for User not Valid
                $rt['code'] =  202; 
                $rt['status'] = 'error';
                }
        }
        else{
        // Status 201 Is request data is not presetnt
            $rt['code'] =  201; 
            $rt['status'] = 'error';
        }
        return response()->json($rt);
    }

    public function getUserActiveList(Request $request)
    {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if(!empty($data))
        {
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
            if(!empty($userdata))
            {
                
                // Find count of Pandong Orders
                $newCountOrder = order:: where('status','placed')->count();

                // Find List Of new Users
                $NewUserList=users::where(['is_active'=>1,'usertype'=>'User'])->get()->toArray();
                $newUserCount=0;
                $newUserArrayList=[];

                //  Find count of New Users
                if(!empty($NewUserList)){
                    $newUserCount=count($NewUserList);
                    $i=1;
                    foreach($NewUserList as $newU)
                    {
                        $newUserArray['s_no']=$i;
                        $newUserArray['id']=$newU['id'];
                        $newUserArray['name']=$newU['name'];
                        $newUserArray['phone_no']=$newU['phone_no'];
                        $newUserArray['company_name']=$newU['company_name'];
                        $i++;
                        array_push($newUserArrayList,$newUserArray);
                    }
                }
               
                //  Find Active users count
                $activeUsercount=users::where(['is_active'=>1,'usertype'=>'User'])->count();

                //  Find heighest Offers Is runnning
                $datetime=Carbon\Carbon::now();
                $activePromocode=promocode::where('start_date','<=',$datetime)->where('end_date','>=',$datetime)->count();

                // makedata to send
                $data['promocode']=$activePromocode;
                $data['registerUserCount']=$activeUsercount;
                $data['newUserList']=$newUserArrayList;
                $data['newUserCount']=$newUserCount;
                $data['newCountOrder']=$newCountOrder;
                $rt['code'] =  200; 
                $rt['status'] = 'success';
                $rt['data']= $data;
            }
            else{
                 // Status 202 Is  used for User not Valid
                  $rt['code'] =  202; 
                  $rt['status'] = 'error';
                }
        }
        else{
        // Status 201 Is request data is not presetnt
            $rt['code'] =  201; 
            $rt['status'] = 'error';
        }
        return response()->json($rt);
    }
}
