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
use App\Models\category;
use App\Models\subcategory;
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
            $userdata;
             if($data['api_token']!="")
             {
             $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();
             }
              // Get order 
              $OrderList;
              $productList=[];
              if(!empty($userdata))
              {
                $OrderList = order:: where(['user_id'=>$userdata['id'],'deviceId'=>$data['deviceId']])->get()->toArray();
              }else{
                $OrderList = order:: where(['deviceId'=>$data['deviceId']])->get()->toArray();
              }
              if(empty($OrderList))
              {
                $rt['code'] =  202; 
                $rt['status'] = 'error';
                $rt['message'] = 'No previous order peresnt';
              }
              else{
                   // find OrderDetail
                   foreach ( $OrderList as $List )
                   {
                        $OrderDetailList = order_detail:: where('order_id',$List['id'])->get()->toArray();
                        if(!empty($OrderDetailList))
                        {
                            foreach ( $OrderDetailList as $ListDetail ){
                                $itemEdition = item_edition:: where('id',$ListDetail['itemedition_id'])->first();
                                if(!empty($itemEdition))
                                {
                                    $item = item_deatil:: where('id',$itemEdition['itemDetail_id'])->first();
                                    $itemimage = item_image_content:: where(['itemDetail_id'=>$itemEdition['itemDetail_id'],'type'=>'Main'])->first();

                                    $data=[];
                                    $data["itemName"]=$item["itemName"];
                                    $data["EditionType"]=$item["EditionType"];
                                    $data["id"]=$item["id"];
                                    $data["quantity"]=$ListDetail["quantity"];
                                    $data["price"]=$ListDetail["price"];
                                    if(!empty($itemEdition))
                                    {
                                        $data["Edition"]=$itemEdition;
                                    }
                                    else{
                                        $data["Edition"]=[]; 
                                    }
                                    $category=category:: where('id',$item['category_id'])->first();
                                    if(!empty($category))
                                    {
                                        $data["categoryName"]=$category['category_name'];
                                    }
                                    else{
                                        $data["categoryName"]="";
                                    }
                                
                                    if($item['subcategory_id']!=0)
                                    {
                                        $Subcategory=subcategory:: where('id',$item['subcategory_id'])->first();
                                        if(!empty($Subcategory))
                                        {
                                        $data["subcategoryName"]=$Subcategory['category_name'];
                                        }else{
                                            $data["subcategoryName"]="";
                                        }
            
                                    }else{
                                        $data["subcategoryName"]="";
                                    }
                                    if(!empty($itemimage))
                                    {
                                    // $data["imageurl"]= url('public/Product_image/'.$itemimage['imageURL']);
                                    $data["imageurl"]= url('Product_image/'.$itemimage['imageURL']);
                                    }else{
                                        $data["imageurl"]="";
                                    }
                                    array_push($productList,$data);
                                }
                                
                            }
                        
                        }
                   }
            }
            
                 
               
               
               
                 $rt['data']=$productList;
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
            }
             else{
                  // Status 202 Is  used for User not Valid
                   $rt['code'] =  202; 
                   $rt['status'] = 'error';
                 }
         
         return response()->json($rt);
     }
}
