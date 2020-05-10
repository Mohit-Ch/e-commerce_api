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
use App\Models\order_detail;
use App\Models\item_deatil;
use App\Models\item_edition;
use App\Models\item_image_content;
use URL;

class Orders extends Controller
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
    
     // Get Accepted Order List 
     public function getAcceptedOrder(Request $request)
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
                 
                 // Conformed Order List 
                 $conformOrderList = order:: where('status','conformed')->get()->toArray();
                 $conformOrderArrayList=[];
                 //  Find count of Pandong Orders
                 $newCountOrder=order:: where('status','placed')->count();
                
                 if(!empty($newOrderList)){
                     $i=1;
                     foreach($conformOrderList as $Order)
                     {
                         $OrderArray['order_Id']=$Order['id'];
                         $OrderArray['s_no']=$i;
                         $OrderArray['order_no']=$Order['order_no'];
                         $OrderArray['user_id']=$Order['user_id'];
                         $OrderArray['amount']=$Order['user_id'];
                        $type=$Order['type']; 	
                         if($type=='user')
                         {
                         $UserOrder=users::where(['id'=>$OrderArray['user_id'],'is_active'=>1,'usertype'=>'User'])->first();
                         $newOrderArray['userName']=$UserOrder['name'];
                         $newOrderArray['phone_no']=$UserOrder['phone_no'];
                         $newOrderArray['usertype']='Registered';
                         $UserAddress=address::where(['users_id'=>$OrderArray['user_id'],'id'=>$Order['address_id']])->first();
                         $newOrderArray['address']=$UserAddress['address1'] +" "+ $UserAddress['address2'] +" "+$UserAddress['area'] +" "+$UserAddress['city'] +" "+$UserAddress['country'] +" "+$UserAddress['postal_code'];
                         }
                         else{
                             $UserOrder=guest_user::where('id',$OrderArray['user_id'])->first();
                             $OrderArray['userName']=$UserOrder['name'];
                             $OrderArray['phone_no']=$UserOrder['phone_no'];
                             $OrderArray['usertype']='Gest User';
                             $OrderArray['address']=$UserOrder['address'];
                         }
                         $i++;
                         array_push($conformOrderArrayList,$OrderArray);
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
                 $data['OrderList']=$conformOrderArrayList;
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

     
     // Get Delivered Order List 
     public function getDeliveredOrder(Request $request)
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
                 
                 // Conformed Order List 
                 $OrderList = order:: where('status','delivered')->get()->toArray();
                 $OrderArrayList=[];
                 //  Find count of Pandong Orders
                 $newCountOrder=order:: where('status','placed')->count();
                
                 if(!empty($newOrderList)){
                     $i=1;
                     foreach($OrderList as $Order)
                     {
                         $OrderArray['order_Id']=$Order['id'];
                         $OrderArray['s_no']=$i;
                         $OrderArray['order_no']=$Order['order_no'];
                         $OrderArray['user_id']=$Order['user_id'];
                         $OrderArray['amount']=$Order['user_id'];
                        $type=$Order['type']; 	
                         if($type=='user')
                         {
                         $UserOrder=users::where(['id'=>$OrderArray['user_id'],'is_active'=>1,'usertype'=>'User'])->first();
                         $newOrderArray['userName']=$UserOrder['name'];
                         $newOrderArray['phone_no']=$UserOrder['phone_no'];
                         $newOrderArray['usertype']='Registered';
                         $UserAddress=address::where(['users_id'=>$OrderArray['user_id'],'id'=>$Order['address_id']])->first();
                         $newOrderArray['address']=$UserAddress['address1'] +" "+ $UserAddress['address2'] +" "+$UserAddress['area'] +" "+$UserAddress['city'] +" "+$UserAddress['country'] +" "+$UserAddress['postal_code'];
                         }
                         else{
                             $UserOrder=guest_user::where('id',$OrderArray['user_id'])->first();
                             $OrderArray['userName']=$UserOrder['name'];
                             $OrderArray['phone_no']=$UserOrder['phone_no'];
                             $OrderArray['usertype']='Gest User';
                             $OrderArray['address']=$UserOrder['address'];
                         }
                         $i++;
                         array_push($OrderArrayList,$OrderArray);
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
                 $data['OrderList']=$OrderArrayList;
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

      // Save Order Status Active Rejects
      public function saveOrderStatus(Request $request)
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
                 if($data['status']=="conformed")
                 {
                    order::where(['id'=>$data['id'],'status'=>'placed' ])->update(['status'=>'conformed']);
                 }else if($data['status']=="cancelled")
                 {
                    order::where('id',$data['id'])->update(['status'=>'cancelled']);
                 }else if($data['status']=="delivered")
                 {
                    order::where('id',$data['id'])->update(['status'=>'delivered']);
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


      // Get Order Detail 
     public function getOrderDetail(Request $request)
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
                 
                 // Conformed Order List 
                 $Order = order:: where('id',$data['id'])->first();
                if(!empty($Order)){
                    $oredertail['id']=$Order['id'];
                    $oredertail['order_no']=$Order['order_no'];
                    $oredertail['user_id']=$Order['user_id'];
                    $oredertail['description']=$Order['description'];
                    $oredertail['status']=$Order['status'];
                    $oredertail['type']=$Order['type'];

                    $oredertail['Discount']=$Order['Discount'];
                    $oredertail['product_amount']=$Order['product_amount'];
                    $oredertail['actual_amount']=$Order['actual_amount'];

                    // find UserData 
                    if($Order['type']=='user')
                    {
                    $UserOrder=users::where(['id'=>$OrderArray['user_id'],'is_active'=>1,'usertype'=>'User'])->first();
                    $oredertail['userName']=$UserOrder['name'];
                    $oredertail['phone_no']=$UserOrder['phone_no'];
                    $oredertail['usertype']='Registered';
                    $UserAddress=address::where(['users_id'=>$OrderArray['user_id'],'id'=>$Order['address_id']])->first();
                    $oredertail['address']=$UserAddress['address1'] +" "+ $UserAddress['address2'] +" "+$UserAddress['area'] +" "+$UserAddress['city'] +" "+$UserAddress['country'] +" "+$UserAddress['postal_code'];
                    }
                    else{
                        $UserOrder=guest_user::where('id',$OrderArray['user_id'])->first();
                        $oredertail['userName']=$UserOrder['name'];
                        $oredertail['phone_no']=$UserOrder['phone_no'];
                        $oredertail['usertype']='Gest User';
                        $oredertail['address']=$UserOrder['address'];
                    }

                    // find Item Detail 
                    $OrderDetail = order_detail::where('order_id',$data['id'])->get()->toArray();
                    $orderItemList=[];
                    if(!empty($OrderDetail))
                    {
                      
                        foreach($OrderDetail as $OrderD)
                        {
                             $orderItem="";
                            if(!empty($OrderD))
                            {
                                $itemeditiion = item_edition::where('id',$OrderD['itemedition_id'])->first(); 
                                if(!empty($itemeditiion))
                                {
                                    $orderItem['quantity']=$OrderD['quantity'];
                                    $orderItem['price']=$OrderD['price'];
                                    $orderItem['itemEditionName']=$itemeditiion['itemEditionName'];
                                    $item = item_edition::where('id',$itemeditiion['itemDetail_id'])->first(); 
                                    $orderItem['itemName']=$item['itemName'];

                                    $itemImage = item_image_content::where(['itemDetail_id'=>$itemeditiion['itemDetail_id'],'type'=>'main'])->first();    
                                    if(!empty($itemImage))  {                              
                                    $orderItem['imageURL']= url('item_Image/' . $itemImage['imageURL']);
                                    }
                                    else
                                    {
                                        $orderItem['imageURL']="";
                                    }
                                    array_push($orderItemList,$orderItem);
                                }
                            }
                        }
                    }
                 $oredertail['itemList']=$orderItemList;
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data']= $oredertail;
                }
                else{
                    $rt['code'] =  203; 
                    $rt['status'] = 'error';
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
}
