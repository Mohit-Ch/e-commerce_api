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
                
                 if ( !empty( $conformOrderList ) ) {
                   // $newCountOrder = count( $pendingOrderList );
                    $i = 1;
                    foreach ( $conformOrderList as $newOrder ) {
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
                        array_push( $conformOrderArrayList, $newOrderArray );
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
                
                 if ( !empty( $OrderList ) ) {
                   // $newCountOrder = count( $pendingOrderList );
                    $i = 1;
                    foreach ( $OrderList as $newOrder ) {
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
                        array_push( $OrderArrayList, $newOrderArray );
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

                    $newOrderArray['address'] = $Order['address'];
                        if ( $Order['type'] == 'user' ) {
                            $UserOrder = users::where( ['id'=>$Order['user_id'], 'usertype'=>'User'] )->first();
                            if ( !empty( $UserOrder ) ) {
                                $newOrderArray['userName'] = $Order['name'] == null? $UserOrder['name'] == null?'':$UserOrder['name']:$Order['name'];
                                $newOrderArray['phone_no'] = $Order['phone_no'] == null? $UserOrder['phone_no'] == null?'':$UserOrder['phone_no']:$Order['phone_no'];
                                $newOrderArray['usertype'] = 'Registered';
                                $UserAddress = address::where( ['users_id'=>$Order['user_id'], 'id'=>$Order['address_id']] )->first();
                               }
                            else{
                                $newOrderArray['userName'] = $Order['name'] ;
                                $newOrderArray['phone_no'] = $Order['phone_no'];
                                $newOrderArray['usertype'] = 'Gest User';
                                $newOrderArray['address'] = $Order['address'];
                            }
                        } else {
                            $UserOrder = guest_user::where( 'id', $Order['user_id'] )->first();
                            if( !empty( $UserOrder ) ){
                                $newOrderArray['userName'] = $Order['name'] == null? $UserOrder['name'] == null?'':$UserOrder['name']:$Order['name'] ;
                                $newOrderArray['phone_no'] = $Order['phone_no'] == null? $UserOrder['phone_no'] == null?'':$UserOrder['phone_no']:$Order['phone_no'];
                                $newOrderArray['usertype'] = 'Gest User';
                                $newOrderArray['address'] = $Order['address'] == null?$UserOrder['address']:$Order['address'];
                            }
                            else{
                                $newOrderArray['userName'] = $Order['name'] ;
                                $newOrderArray['phone_no'] = $Order['phone_no'];
                                $newOrderArray['usertype'] = 'Gest User';
                                $newOrderArray['address'] = $Order['address'];
                            }
                           
                        }

                    // find Item Detail 
                    $OrderDetail = order_detail::where('order_id',$data['id'])->get()->toArray();
                    $orderItemList=[];
                    if(!empty($OrderDetail))
                    {
                      
                        foreach($OrderDetail as $OrderD)
                        {
                             
                            if(!empty($OrderD))
                            {
                                $itemeditiion = item_edition::where('id',$OrderD['itemedition_id'])->first(); 
                                if(!empty($itemeditiion))
                                {
                                    $orderItem['quantity']=$OrderD['quantity'];
                                    $orderItem['price']=$OrderD['price'];
                                    $orderItem['itemEditionName']=$itemeditiion['itemEditionName'];
                                    $item = item_deatil::where('id',$itemeditiion['itemDetail_id'])->first(); 
                                    $orderItem['itemName']=$item['itemName'];

                                    $itemImage = item_image_content::where(['itemDetail_id'=>$itemeditiion['itemDetail_id'],'type'=>'main'])->first();    
                                    if(!empty($itemImage))  {                              
                                    $orderItem['imageURL']= url('public/item_Image/' . $itemImage['imageURL']);
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
