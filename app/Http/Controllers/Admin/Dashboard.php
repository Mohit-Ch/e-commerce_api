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

class Dashboard extends Controller
{
      // Admin Dashboard  Get Data
	public function getDashboardData(Request $request)
    {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if(!empty($data))
        {
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
            if(!empty($data))
            {
                
                // Pending Order List 
                $pendingOrderList = order:: where('status','placed')->get()->toArray();
                $newOrderArrayList=[];
                //  Find count of Pandong Orders
                $newCountOrder=0;
               
                if(!empty($newOrderList)){
                    $newCountOrder= count($newOrderList);
                    $i=1;
                    foreach($newOrderList as $newOrder)
                    {
                        $newOrderArray['order_Id']=$newOrder['id'];
                        $newOrderArray['s_no']=$i;
                        $newOrderArray['order_no']=$newOrder['order_no'];
                        $newOrderArray['user_id']=$newOrder['user_id'];
                        $newOrderArray['amount']=$newOrder['user_id'];
                       $type=$newOrder['type']; 	
                        if($type=='user')
                        {
                        $UserOrder=users::where(['id'=>$newOrderArray['user_id'],'is_active'=>1,'usertype'=>'User'])->first();
                        $newOrderArray['userName']=$UserOrder['name'];
                        $newOrderArray['phone_no']=$UserOrder['phone_no'];
                        $newOrderArray['usertype']='Registered';
                        $UserAddress=address::where(['users_id'=>$newOrderArray['user_id'],'id'=>$newOrder['address_id']])->first();
                        $newOrderArray['address']=$UserAddress['address1'] +" "+ $UserAddress['address2'] +" "+$UserAddress['area'] +" "+$UserAddress['city'] +" "+$UserAddress['country'] +" "+$UserAddress['postal_code'];
                        }
                        else{
                            $UserOrder=guest_user::where('id',$newOrderArray['user_id'])->first();
                            $newOrderArray['userName']=$UserOrder['name'];
                            $newOrderArray['phone_no']=$UserOrder['phone_no'];
                            $newOrderArray['usertype']='Gest User';
                            $newOrderArray['address']=$UserOrder['address'];
                        }
                        $i++;
                        array_push($newCountOrder,$newOrderArray);
                    }
                }
                
                
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
                $activePromocode=promocode::where('start_date','<=',$datetime)->where('end_date','>=',$datetime)->first();

                // makedata to send
                $data['promocode']=$activePromocode;
                $data['registerUserCount']=$activeUsercount;
                $data['newUserList']=$newUserArrayList;
                $data['newUserCount']=$newUserCount;
                $data['newCountOrder']=$newCountOrder;
                $data['OrderList']=$newOrderArrayList;
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
