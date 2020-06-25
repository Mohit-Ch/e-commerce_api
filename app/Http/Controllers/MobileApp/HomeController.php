<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\category;
use App\Models\item_deatil;
use App\Models\item_details_attributes;
use App\Models\item_edition;
use App\Models\item_image_content;
use App\Models\subcategory;

class HomeController extends Controller
{

     // get category List
     public function getCategoryList(Request $request)
     {
        $rt['code'] =  203; 
        $rt['status'] = 'error';
               ///category_media
               $categoryList = category::where('is_deleted',0)->orderBy('category_name', 'asc')->get()->toArray();
               $catList=[];
               if(!empty($categoryList)){                 
                  foreach($categoryList as $cat)
                  {
                      $category["id"]=$cat['id'];
                      $category["ISsubCategory"]=false;
                      $category["Isproduct"]=false;
                      $subcatrory= subcategory::where(['is_deleted'=>0,'category_id'=>$cat['id']])->get()->toArray();
                      if(!empty($subcatrory))
                      {
                        $category["ISsubCategory"]=true;;
                      }
                      else
                      {
                        $Product=  item_deatil:: where(['status'=>0,'category_id'=>$cat['id'],'subcategory_id'=>0])->get()->toArray();
                        if(!empty($Product))
                        {
                            $category["Isproduct"]=true;
                        }
                      }
                      $category["category_name"]=$cat['category_name'];
                      $category["photo"]= url('public/category_media/' . $cat['photo']);
                      array_push($catList,$category);
                  }
              }
                $rt['code'] =  200; 
                $rt['status'] = 'success';
                $rt['data'] = $catList;
        return response()->json($rt);
     }

        // get sub category List
        public function getSubCategoryList(Request $request)
        {
           $rt['code'] =  203; 
           $rt['status'] = 'error';
           // Get Request Data
           $datarequest = $request->all();
           
           if($datarequest!=null)
           {
                  ///sub category List
                  $subcategoryList = subcategory::where(['is_deleted'=>0,'category_id'=>$datarequest['category_id']])->orderBy('category_name', 'asc')->get()->toArray();
                  $catList=[];
                  if(!empty($subcategoryList)){
                     $i=1;
                     foreach($subcategoryList as $cat)
                     {
                         $category["s_no"]=$i;
                         $category["id"]=$cat['id'];
                         $category["Subcategory_name"]=$cat['category_name'];
                         $categorydata = category::where(['is_deleted'=>0 ,'id'=>$cat['category_id']])->first();
                         $category["category_Name"]= $categorydata['category_name'];
                         $category["photo"]= url('public/subcategory_media/' . $cat['photo']);
                         array_push($catList,$category);
                         $i++;
                     }
                 }
                    $rt['code'] =  200; 
                     $rt['status'] = 'success';
                     $rt['data'] = $catList;
           }
           else{
               // Status 201 Is request data is not presetnt
                   $rt['code'] =  201; 
                   $rt['status'] = 'error';
               }
           return response()->json($rt);
        }
 


      // get product List
      public function getproductList(Request $request)
      {
         $rt['code'] =  203; 
         $rt['status'] = 'error';
         // Get Request Data
         $datarequest = $request->all();
         
         if($datarequest!=null)
         {
                // category_media
                $ItemDetail=[];
                if($datarequest['category_id']!=0 && $datarequest['subcategory_id']!=0 ){
                  $ItemDetail = item_deatil:: where(['status'=>0,'category_id'=>$datarequest['category_id'],'subcategory_id'=>$datarequest['subcategory_id']])->orderBy('itemName', 'asc')->get()->toArray();
                }
                else
                {
                    $ItemDetail = item_deatil:: where(['status'=>0,'category_id'=>$datarequest['category_id']])->get()->toArray();
                }
                $productList=[];
                if(!empty($ItemDetail))
                {
                    foreach($ItemDetail as $item)
                    {
                        $data=[];
                       $data["itemName"]=$item["itemName"];
                       $data["EditionType"]=$item["EditionType"];
                       $data["id"]=$item["id"];
                        $itemedition=item_edition:: where(['itemDetail_id'=>$item['id']])->get()->toArray();
                        if(!empty($itemedition))
                        {
                            $data["Edition"]=$itemedition;
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
                        $ItemImage = item_image_content:: where(['itemDetail_id'=>$item['id'],'type'=>'Main'])->first();
                        if(!empty($ItemImage))
                        {
                          // $data["imageurl"]= url('public/Product_image/'.$ItemImage['imageURL']);
                          $data["imageurl"]= url('Product_image/'.$ItemImage['imageURL']);
                        }else{
                           $data["imageurl"]="";
                        }
                        array_push($productList,$data);

                    }
                }
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data'] = $productList;
            
 
         }
         else{
             // Status 201 Is request data is not presetnt
                 $rt['code'] =  201; 
                 $rt['status'] = 'error';
             }
         return response()->json($rt);
      } 

       // get product detail
       public function getproductData(Request $request)
       {
          $rt['code'] =  203; 
          $rt['status'] = 'error';
          // Get Request Data
          $datarequest = $request->all();
          
          if($datarequest!=null)
          {
                 // category_media
                 $ItemDetail = item_deatil:: where('id',$datarequest['id'])->first();
                 $Itemedition = item_edition:: where('itemDetail_id',$datarequest['id'])->get()->toArray();
                 $ItemInfo = item_details_attributes:: where('itemDetail_id',$datarequest['id'])->get()->toArray();
                 $ItemImage = item_image_content:: where('itemDetail_id',$datarequest['id'])->orderBy('type', 'asc')->get()->toArray();
                 $ItemImageList=[];
                 if( !empty($ItemImage) ){
                     foreach($ItemImage as $item)
                     {
 
                         $image=[];
                         $image["itemDetail_id"]=$item["itemDetail_id"];
                         $image["imageURL"]=url('public/Product_image/'.$item["imageURL"]) ; 
                       // $image["imageURL"]=url('Product_image/'.$item["imageURL"]) ; 
                         $image["type"]=$item["type"];
                         $image["id"]=$item["id"];
                         array_push($ItemImageList,$image);
                     }
                 }
 
                 $data['itemDetail']=$ItemDetail;
                 $data['itemedition']=$Itemedition;
                 $data['itemInfo']=$ItemInfo;
                 $data['itemImage']=$ItemImageList;
                  $rt['code'] =  200; 
                  $rt['status'] = 'success';
                  $rt['data'] = $data;
  
          }
          else{
              // Status 201 Is request data is not presetnt
                  $rt['code'] =  201; 
                  $rt['status'] = 'error';
              }
          return response()->json($rt);
       } 

        // get product detail
        public function getproductSearch(Request $request)
        {
            $rt['code'] =  203; 
         $rt['status'] = 'error';
         // Get Request Data
         $datarequest = $request->all();
         
         if($datarequest!=null)
         {
                // category_media
                $ItemDetail = item_deatil:: where('status',0)->Where('itemName', 'like', '%' .$datarequest['searchText']. '%')->get()->toArray();
                $productList=[];
                if(!empty($ItemDetail))
                {
                    foreach($ItemDetail as $item)
                    {
                        $data=[];
                       $data["itemName"]=$item["itemName"];
                       $data["id"]=$item["id"];
                        $ItemImage = item_image_content:: where(['itemDetail_id'=>$item['id'],'type'=>'Main'])->first();
                        if(!empty($ItemImage))
                        {
                           $data["imageurl"]= url('public/Product_image/'.$ItemImage['imageURL']);
                          //$data["imageurl"]= url('Product_image/'.$ItemImage['imageURL']);
                        }else{
                           $data["imageurl"]="";
                        }
                        array_push($productList,$data);

                    }
                }
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data'] = $productList;
            
 
         }
         else{
             // Status 201 Is request data is not presetnt
                 $rt['code'] =  201; 
                 $rt['status'] = 'error';
             }
         return response()->json($rt);
        } 
}
