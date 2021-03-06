<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\guest_user;
use App\Models\address;
use App\Models\promocode;
use Carbon;
use App\Models\order_detail;
use App\Models\item_deatil;
use App\Models\item_edition;
use App\Models\item_image_content;
use App\Models\category;
use App\Models\subcategory;
use App\Models\users;
use App\Models\order;
use DB;
use Mail;

class OrderController extends Controller {
    //
    // Get Pennding Order List

    public function getOrderDetail( Request $request ) {

        $rt['code'] =  203;
        $rt['status'] = 'error';
        $datarequest = $request->all();
        if ( !empty( $datarequest ) ) {

            $productList = [];
            foreach ( $datarequest['cartlist'] as $item ) {
                // dd( $item['itemId'] );
                $ItemDetail = item_deatil:: where( ['status'=>0, 'id'=>$item['itemId']] )->first();
                if ( !empty( $ItemDetail ) ) {
                    $data = [];
                    $data['itemName'] = $ItemDetail['itemName'];
                    $data['EditionType'] = $ItemDetail['EditionType'];
                    $data['id'] = $ItemDetail['id'];
                    $data['OutOfOrder'] = true;
                    $data['OrderQuantity'] = $item['quantity'];
                    $data['EditionId'] = $item['itemeditionId'];
                    $itemedition = item_edition:: where( ['itemDetail_id'=>$ItemDetail['id'], 'id'=>$item['itemeditionId']] )->get()->toArray();
                    if ( !empty( $itemedition ) ) {
                        if ( $itemedition[0]['quantity']<$item['quantity'] ) {
                            $data['OutOfOrder'] = true;
                        } else {
                            $data['OutOfOrder'] = false;
                        }
                        $data['Edition'] = $itemedition;
                    } else {
                        $data['Edition'] = [];

                    }
                    $category = category:: where( 'id', $ItemDetail['category_id'] )->first();
                    if ( !empty( $category ) ) {
                        $data['categoryName'] = $category['category_name'];
                    } else {
                        $data['categoryName'] = '';
                    }

                    if ( $ItemDetail['subcategory_id'] != 0 ) {
                        $Subcategory = subcategory:: where( 'id', $ItemDetail['subcategory_id'] )->first();
                        if ( !empty( $Subcategory ) ) {
                            $data['subcategoryName'] = $Subcategory['category_name'];
                        } else {
                            $data['subcategoryName'] = '';
                        }

                    } else {
                        $data['subcategoryName'] = '';
                    }
                    $ItemImage = item_image_content:: where( ['itemDetail_id'=>$item['itemId'], 'type'=>'Main'] )->first();
                    if ( !empty( $ItemImage ) ) {
                        // $data['imageurl'] = url( 'public/Product_image/'.$ItemImage['imageURL'] );
                        $data['imageurl'] = url( 'Product_image/'.$ItemImage['imageURL'] );
                    } else {
                        $data['imageurl'] = '';
                    }
                    array_push( $productList, $data );
                }

            }

            $rt['code'] =  200;
            $rt['status'] = 'success';
            $rt['data'] = $productList;
        } else {
            // Status 201 Is request data is not presetnt
            $rt['code'] =  201;

            $rt['status'] = 'error';
        }
        return response()->json( $rt );
    }

    public function couponcodeValid( Request $request ) {
        $rt['code'] =  203;

        $rt['status'] = 'error';
        $datarequest = $request->all();
        if ( $datarequest != null ) {

            $mytime = Carbon\Carbon::now();
            $code=strtolower($datarequest['code']);
            $promocodedata = promocode:: whereRaw( 'lower(code) like (?)', [trim(strtolower($code)).'%'] )->whereRaw( '"'.$mytime.'" between `start_date` and `End_date`' )->first();
            if ( !empty( $promocodedata ) ) {
                $promocode['id'] = $promocodedata['id'];
                $promocode['amount'] = $promocodedata['amount'];
                $promocode['code'] = $promocodedata['code'];
                $promocode['description'] = $promocodedata['description'];
                $promocode['type'] = $promocodedata['type'];
                $promocode['minOrderAmount'] = $promocodedata['minOrderAmount'];
                $promocode['maxDiscountAmount'] = $promocodedata['maxDiscountAmount'];
                $promocode['start_date'] = $promocodedata['start_date'];
                $promocode['end_date'] = $promocodedata['end_date'];

                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['data'] = $promocode;
            } else {
                $rt['code'] =  205;

                $rt['status'] = 'success';
                $rt['message'] = 'Applied Code is not Valid';
            }

        } else {
            // Status 201 Is request data is not presetnt
            $rt['code'] =  201;

            $rt['status'] = 'error';
        }
        return response()->json( $rt );
    }

    public function SetReserveQuantity( Request $request ) {

        $rt['code'] =  203;
        $rt['status'] = 'error';
        $datarequest = $request->all();
        if ( !empty( $datarequest ) ) {

            $productList = [];
            foreach ( $datarequest['cartlist'] as $item ) {
                $ItemDetail = item_deatil:: where( ['status'=>0, 'id'=>$item['itemId']] )->first();
                if ( !empty( $ItemDetail ) ) {
                    $itemedition = item_edition:: where( ['itemDetail_id'=>$ItemDetail['id'], 'id'=>$item['itemeditionId']] )->first();
                    if ( !empty( $itemedition ) ) {
                        if ( $itemedition['quantity'] >= $item['quantity'] ) {
                            $updatequantity = $itemedition['quantity']-$item['quantity'];
                            item_edition::where( 'id', $itemedition['id'] )->update(
                                ['quantity'=>$updatequantity,
                                'reserveq'=>$item['quantity']]
                            );
                        }
                    }
                }
            }

            $rt['code'] =  200;
            $rt['status'] = 'success';
        } else {
            $rt['code'] =  201;
            $rt['status'] = 'error';
        }
        return response()->json( $rt );

    }

    // Get Pennding Order List

    public function getAddressList( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $userdata = users::where( ['api_token'=>$data['api_token']] )->first();
            if ( !empty( $userdata ) ) {

                // Get Address List
                $AddressListData = address::where( 'users_id', $userdata['id'] )->get()->toArray();

                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['data'] = $AddressListData;
            } else {
                // Status 202 Is  used for User not Valid
                $rt['code'] =  202;

                $rt['status'] = 'error';
            }
        } else {
            // Status 201 Is request data is not presetnt
            $rt['code'] =  201;

            $rt['status'] = 'error';
        }
        return response()->json( $rt );
    }

    public function CheckEmailExist( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $userdata = users::where( ['email'=>$data['email']] )->first();
            if ( !empty( $userdata ) ) {

                $rt['code'] =  203;

                $rt['status'] = 'error';
                $rt['message'] = 'Email Is already taken!';
            } else {
                // Status 202 Is  used for User not Valid
                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['message'] = '';
            }
        } else {
            // Status 201 Is request data is not presetnt
            $rt['code'] =  201;

            $rt['status'] = 'error';
        }
        return response()->json( $rt );
    }

    public function placeOrder( Request $request ) {
        $rt['code'] =  203;
        $rt['status'] = 'error';
        $datarequest = $request->all();
        if ( !empty( $datarequest ) ) {

            $userId = 0;
            $type = 'gest';
            $AddressId = 0;
            try {
                if ( $datarequest['api_token'] != '' ) {
                    DB::beginTransaction();

                    $Userdata = users::where( ['api_token'=>$datarequest['api_token']] )->first();
                    $userId = $Userdata['id'];
                    $type = 'user';
                    if ( $datarequest['addressId'] == 0 ) {

                        $address = address::create( [
                            'users_id' =>  $userId,
                            'country' => $datarequest['country'],
                            'address1' => $datarequest['address1'],
                            'city' => $datarequest['city'],
                            'postal_code' => $datarequest['postal_code'],
                            'name' => $datarequest['name'],
                            'email' => $datarequest['email'],
                            'phone_no' => $datarequest['Phone'],
                            'company_name'=>$datarequest['companyName']
                        ] );

                        $AddressId = $address['id'];
                    } else {
                        address::where( 'id', $datarequest['addressId'] )->update(
                            ['country' => $datarequest['country'],
                            'address1' => $datarequest['address1'],
                            'city' => $datarequest['city'],
                            'postal_code' => $datarequest['postal_code'],
                            'name' => $datarequest['name'],
                            'email' => $datarequest['email'],
                            'phone_no' => $datarequest['Phone'],
                            'company_name'=>$datarequest['companyName']]
                        );
                        $AddressId = $datarequest['addressId'];
                    }
                } else {
                    if ( $datarequest['createLogin'] == true ) {
                        $Userdata1 = users::where( ['email'=>$datarequest['email']] )->first();
                        if(!empty($Userdata1))
                        {
                            DB::commit();
                            $rt['code'] =  206;
                            $rt['status'] = 'error';
                            $rt['message'] = 'Email already taken. please try another email!';  
                            return response()->json( $rt );

                        }
                        $user = users::create( [
                            'name' => $datarequest['name'],
                            'email' => $datarequest['email'],
                            'password' => bcrypt( $datarequest['password'] ),
                            'phone_no' => $datarequest['Phone'],
                            'is_active' => 1,
                            'usertype' => 'User',
                            'user_Name'=>$datarequest['email'],
                            'company_name'=>$datarequest['companyName']
                        ] );
                        $userId = $user['id'];
                        $type = 'user';
                        if ( $datarequest['addressId'] == 0 ) {

                            $address = address::create( [
                                'users_id' =>  $userId,
                                'country' => $datarequest['country'],
                                'address1' => $datarequest['address1'],
                                'city' => $datarequest['city'],
                                'postal_code' => $datarequest['postal_code'],
                                 'name' => $datarequest['name'],
                                'email' => $datarequest['email'],
                                'phone_no' => $datarequest['Phone'],
                                'company_name'=>$datarequest['companyName']
                            ] );

                            $AddressId = $address['id'];
                        } else {
                            address::where( 'id', $datarequest['addressId'] )->update(
                                ['country' => $datarequest['country'],
                                'address1' => $datarequest['address1'],
                                'city' => $datarequest['city'],
                                'postal_code' => $datarequest['postal_code'],
                                'name' => $datarequest['name'],
                                'email' => $datarequest['email'],
                                'phone_no' => $datarequest['Phone'],
                                'company_name'=>$datarequest['companyName']]
                            );
                            $AddressId = $datarequest['addressId'];
                        }
                    } else
                    if ( $datarequest['createLogin'] == false && $datarequest['api_token'] == '' ) {
                        $Address = $datarequest['address1'].', '.$datarequest['city'].', '.$datarequest['country'].', '.$datarequest['postal_code'];
                        $guest_user = guest_user::create( [
                            'name' => $datarequest['name'],
                            'email' => $datarequest['email'],
                            'phone_no' => $datarequest['Phone'],
                            'company_name'=>$datarequest['companyName'],
                            'address'=>$Address
                        ] );
                        $userId = $guest_user['id'];
                        $type = 'gest';
                    }
                }

                $CodeId = 0;
                if ( $datarequest['couponcode'] != '' ) {
                    $Coupondata = promocode::where( ['code'=>$datarequest['couponcode']] )->first();
                    $CodeId = $Coupondata['id'];
                }

                // Check Item in cart quantity
                if(!empty($datarequest['cartList']))
                {
                    foreach ( $datarequest['cartList'] as $item ) {

                        $ItemDetail = item_deatil:: where( ['status'=>0, 'id'=>$item['itemId']] )->first();
                        if ( !empty( $ItemDetail ) ) {
                            $itemedition = item_edition:: where( ['itemDetail_id'=>$ItemDetail['id'], 'id'=>$item['itemeditionId']] )->where('quantity','<',$item['quantity'])->first();
                            if ( !empty( $itemedition ) ){
                                DB::commit();
                                $rt['code'] =  205;
                                $rt['status'] = 'error';
                                $rt['message'] = 'one  product have not enough quantity available to place order';  
                                return response()->json( $rt );
                            }
                        }
                    }

                }

                // Insert Order in Ordertable
                // PatterOfOrderNo is GH and 10 digit of rendam alpha numeri string
                $Address = $datarequest['address1'].', '.$datarequest['city'].', '.$datarequest['country'].', '.$datarequest['postal_code'];
                $Order_no = 'GH'.str_random( 10 );
                $order = order::create( [
                    'order_no' => $Order_no,
                    'user_id' =>  $userId,
                    'promocode_id' => $CodeId,
                    'description'=>$datarequest['AdditionalInfo'],
                    'type'=> $type,
                    'status'=>'placed',
                    'product_amount'=> ( float )$datarequest['total'],
                    'Discount'=>( float )$datarequest['discount'],
                    'actual_amount'=>( float )$datarequest['subtotal'],
                    'address_id'=> $AddressId,
                    'deviceId'=>$datarequest['deviceId'],
                    'name'=>$datarequest['name'],
                    'email'=>$datarequest['email'],
                    'phone_no'=>$datarequest['Phone'],
                    'company_name'=>$datarequest['companyName'],
                    'address'=>$Address,
                ] );
                $OrderId = $order['id'];

                // update reserve quantity to zero
                foreach ( $datarequest['cartList'] as $item ) {

                    $ItemDetail = item_deatil:: where( ['status'=>0, 'id'=>$item['itemId']] )->first();
                    if ( !empty( $ItemDetail ) ) {
                        $itemedition = item_edition:: where( ['itemDetail_id'=>$ItemDetail['id'], 'id'=>$item['itemeditionId']] )->first();
                        if ( !empty( $itemedition ) ) {
                            $orderdetail =  order_detail::create( [
                                'order_id' =>  $OrderId,
                                'itemedition_id' =>  $itemedition['id'],
                                'quantity' =>$item['quantity'],
                                'price'=> $itemedition['price']
                            ] );

                            item_edition::where( 'id', $itemedition['id'] )->update(
                                ['quantity'=>$itemedition['quantity']-$item['quantity']]
                            );

                        }
                    }
                }
                DB::commit();
                $this->getOrderDetailmail($OrderId, $datarequest['email'], $datarequest['name']);
                $rt['code'] =  200;
                $rt['status'] = 'success';
                $rt['orderId'] = $Order_no;
            } catch ( \Exception $e ) {
                DB::rollback();
                // something went wrong
                $rt['code'] =  201;
                $rt['status'] = 'error';
                $rt['error'] =  $e;
            }
        } else {
            $rt['code'] =  203;
            $rt['status'] = 'error';
        }
        return response()->json( $rt );

    }

    public function RevertReserveQuantity( Request $request ) {

        $rt['code'] =  203;
        $rt['status'] = 'error';
        $datarequest = $request->all();
        if ( !empty( $datarequest ) ) {

            $productList = [];
            foreach ( $datarequest['cartlist'] as $item ) {
                $ItemDetail = item_deatil:: where( ['status'=>0, 'id'=>$item['itemId']] )->first();
                if ( !empty( $ItemDetail ) ) {
                    $itemedition = item_edition:: where( ['itemDetail_id'=>$ItemDetail['id'], 'id'=>$item['itemeditionId']] )->first();
                    if ( !empty( $itemedition ) ) {
                        if ( $itemedition['quantity'] >= $item['quantity'] ) {
                            $updatequantity = $itemedition['quantity']+$item['quantity'];
                            $reserveQuantity = $itemedition['reserveq']-$item['quantity']<0?0:$itemedition['reserveq']-$item['quantity'];
                            item_edition::where( 'id', $itemedition['id'] )->update(
                                ['quantity'=>$updatequantity,
                                'reserveq'=>$reserveQuantity]
                            );
                        }
                    }
                }
            }

            $rt['code'] =  200;
            $rt['status'] = 'success';
        } else {
            $rt['code'] =  201;
            $rt['status'] = 'error';
        }
        return response()->json( $rt );

    }

    public function CheckAddressExist( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $userdata = users::where( ['api_token'=>$data['api_token']] )->first();
            if ( !empty( $userdata ) ) {

                // Get Address List
                $AddressListData = address::where( 'users_id', $userdata['id'] )->get()->toArray();
                if(!empty( $ItemDetail ))
                {
                    $rt['data'] = true;
                }
                else{
                    $rt['data'] = false;
                }
                $rt['code'] =  200;
                $rt['status'] = 'success';
                
            } else {
                // Status 202 Is  used for User not Valid
                $rt['code'] =  202;

                $rt['status'] = 'error';
            }
        } else {
            // Status 201 Is request data is not presetnt
            $rt['code'] =  201;

            $rt['status'] = 'error';
        }
        return response()->json( $rt );
    }

      // Get Order Detail 
      public function getOrderDetailmail($request, $email ,$name)
      {
          
        
          
           
         
             
              
                  // Conformed Order List 
                  $Order = order:: where('id',$request)->first();
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
                     $OrderDetail = order_detail::where('order_id',$request)->get()->toArray();
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
                  
                  $oredertail['discount']=(string)($oredertail['actual_amount']-$oredertail['product_amount']);
                  
                  $data = array('name'=>$name,'email'=>$email,'orderdetail'=>$oredertail,'status'=>3);
                  //dd($data);
                  Mail::send(['html'=>'email'], $data, function($message) use($email,$name) {
                     $message->to($email,$name)->subject
                        (trans('Order Detail'));
                     $message->from('manreshyadav@gmail.com','Jain Hardware support');
                  });
                  
                 // dd($data);
                 }
                 
              
              
          
          return true;
      }
}
