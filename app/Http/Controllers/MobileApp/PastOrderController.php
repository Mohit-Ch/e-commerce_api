<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\users;
use App\Models\guest_user;
use App\Models\address;
use App\Models\promocode;
use Carbon;
use App\Models\order_detail;
use App\Models\item_deatil;
use App\Models\item_edition;
use App\Models\item_image_content;
use URL;
class PastOrderController extends Controller
{
  
     // Get Pennding Order List 
     public function getPendingOrder(Request $request)
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
                 
                 // Pending Order List 
                 $pendingOrderList = order:: where('status','placed')->get()->toArray();
                 $newOrderArrayList=[];
                 //  Find count of Pandong Orders
                 $newCountOrder=0;
                
                 if ( !empty( $pendingOrderList ) ) {
                     $newCountOrder = count( $pendingOrderList );
                     $i = 1;
                     foreach ( $pendingOrderList as $newOrder ) {
                         $newOrderArray['order_Id'] = $newOrder['id'];
                         $newOrderArray['s_no'] = $i;
                         $newOrderArray['order_no'] = $newOrder['order_no'];
                         $newOrderArray['user_id'] = $newOrder['user_id'];
                         $newOrderArray['amount'] = $newOrder['actual_amount'];
                         $type = $newOrder['type'];
 
                         $newOrderArray['address'] = $newOrder['address'];
                         if ( $type == 'user' ) {
                             $UserOrder = users::where( ['id'=>$newOrderArray['user_id'], 'usertype'=>'User'] )->first();
                             if ( !empty( $UserOrder ) ) {
                                 $newOrderArray['userName'] = $newOrder['name'] == null? $UserOrder['name'] == null?'':$UserOrder['name']:$newOrder['name'];
                                 $newOrderArray['phone_no'] = $newOrder['phone_no'] == null? $UserOrder['phone_no'] == null?'':$UserOrder['phone_no']:$newOrder['phone_no'];
                                 $newOrderArray['usertype'] = 'Registered';
                                 $UserAddress = address::where( ['users_id'=>$newOrderArray['user_id'], 'id'=>$newOrder['address_id']] )->first();
                                }
                             else{
                                 $newOrderArray['userName'] = $newOrder['name'] ;
                                 $newOrderArray['phone_no'] = $newOrder['phone_no'];
                                 $newOrderArray['usertype'] = 'Gest User';
                                 $newOrderArray['address'] = $newOrder['address'];
                             }
                         } else {
                             $UserOrder = guest_user::where( 'id', $newOrderArray['user_id'] )->first();
                             if( !empty( $UserOrder ) ){
                                 $newOrderArray['userName'] = $newOrder['name'] == null? $UserOrder['name'] == null?'':$UserOrder['name']:$newOrder['name'] ;
                                 $newOrderArray['phone_no'] = $newOrder['phone_no'] == null? $UserOrder['phone_no'] == null?'':$UserOrder['phone_no']:$newOrder['phone_no'];
                                 $newOrderArray['usertype'] = 'Gest User';
                                 $newOrderArray['address'] = $newOrder['address'] == null?$UserOrder['address']:$newOrder['address'];
                             }
                             else{
                                 $newOrderArray['userName'] = $newOrder['name'] ;
                                 $newOrderArray['phone_no'] = $newOrder['phone_no'];
                                 $newOrderArray['usertype'] = 'Gest User';
                                 $newOrderArray['address'] = $newOrder['address'];
                             }
                            
                         }
                         $i++;
                         array_push( $newOrderArrayList, $newOrderArray );
                     }
                 }
                 
                 
                 // Find List Of new Users
                 $NewUserList=users::where(['is_active'=>0,'usertype'=>'User'])->get()->toArray();
                 $newUserCount=0;
 
                 //  Find count of New Users
                 if(!empty($NewUserList)){
                     $newUserCount=count($NewUserList);
                 }
                
                 //  Find Active users count
                 $activeUsercount=users::where(['is_active'=>1,'usertype'=>'User'])->count();
 
                 //  Find heighest Offers Is runnning
                 $datetime=Carbon\Carbon::now();
                 $activePromocode=promocode::where('start_date','<=',$datetime)->where('end_date','>=',$datetime)->count();
 
                 //  Find Active users count
                 $conformorder = order:: where('status','conformed')->count();
                 
                 // makedata to send
                 $data['promocode']=$activePromocode;
                 $data['registerUserCount']=$activeUsercount;
                 $data['newUserCount']=$newUserCount;
                 $data['newCountOrder']=$newCountOrder;
                 $data['OrderList']=$newOrderArrayList;
                 $data['conformorder']=$conformorder;
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
