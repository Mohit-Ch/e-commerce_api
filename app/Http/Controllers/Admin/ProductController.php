<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\category;
use App\Models\item_deatil;
use App\Models\item_details_attributes;
use App\Models\item_edition;
use App\Models\item_image_content;
use App\Models\subcategory;
use Image;
use File;
class ProductController extends Controller
{
    // Save product
    public function saveProduct(Request $request)
    {
       $rt['code'] =  203; 
       $rt['status'] = 'error';
      
       // Get Request Data
       $data = $request->all();       
       if($data!=null)
       {
           $itemdetailid=$data['id'];
           $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();           
           if( !empty($userdata))
           {
            if($data['id']==0)
            {
                $additem = new item_deatil;
                $additem->itemName = $data['ProductName'];
                $additem->category_id = $data['categoryId'];
                $additem->subcategory_id = $data['subCategoryId'];
                $additem->user_id = $userdata['id'];
                $additem->EditionType = $data['EditionType'];
                $additem->aboutItem = $data['Description'];
                $additem->save();

                $itemdetailid=$additem->id;
            }
            else
            {
                item_deatil::where('id',$data['id'])->update(
                    ['itemName'=>$data['ProductName'],
                    'category_id'=>$data['categoryId'],
                    'subcategory_id'=>$data['subCategoryId'],
                    'EditionType'=>$data['EditionType'],
                    'aboutItem'=>$data['Description'] ]);
            }

            if($data['EditionType']==false)
            {
                if($data['editionId']==0){
                    $additemEdit = new item_edition;
                    $additemEdit->itemDetail_id = $itemdetailid;
                    $additemEdit->itemEditionName = $data['editionName'];
                    $additemEdit->price = $data['price'];
                    $additemEdit->quantity = $data['quantity'];
                    $additemEdit->remark = $data['remark'];
                    $additemEdit->save();
                }
                else{
                    item_edition::where('id',$data['editionId'])->update(
                        ['itemEditionName'=>$data['editionName'],
                        'price'=>$data['price'],
                        'quantity'=>$data['quantity'],
                        'remark'=>$data['remark'] ]);

                }

            }
            else{
                if(!empty($data['editionList']))
                {
                    foreach($data['editionList'] as $edit)
                    {
                        if($edit['id']==0){
                            $additemEdit = new item_edition;
                            $additemEdit->itemDetail_id = $itemdetailid;
                            $additemEdit->itemEditionName = $edit['EditionName'];
                            $additemEdit->price = $edit['price'];
                            $additemEdit->quantity = $edit['quantity'];
                            $additemEdit->remark = $edit['Remark'];
                            $additemEdit->save();
                        }
                        else{
                            item_edition::where('id',$edit['id'])->update(
                                ['itemEditionName'=>$edit['editionName'],
                                'price'=>$edit['price'],
                                'quantity'=>$edit['quantity'],
                                'remark'=>$edit['remark'] ]);
        
                        }
                    }
                }
            }

            if(!empty($data['imageList']))
            {
                foreach($data['imageList'] as $edit)
                {  $destinationPath = public_path('temp_image');
                    $destinationPathnew = public_path('Product_image').'\\'.$edit['imageName'];
                    $full_path_before=$destinationPath.'\\'.$edit['imageName'];
                    if($edit['id']==0){
                        $additemEditi = new item_image_content;
                        $additemEditi->itemDetail_id = $itemdetailid;
                        $additemEditi->imageURL = $edit['imageName'];
                        $additemEditi->type = $edit['image_type'];
                        $additemEditi->imageString = $edit['imageUrl'];
                        $additemEditi->save();
                        if (File::exists($full_path_before)){
                            File::move($full_path_before,$destinationPathnew);
                         }
                    }
                    else{
                        item_image_content::where('id',$edit['id'])->update(
                            ['imageURL'=>$edit['imageName'],
                            'type'=>$edit['image_type'],
                            'imageString'=>$edit['imageUrl']]);
                        if (!File::exists($destinationPathnew)){
                            if (File::exists($full_path_before)){
                                File::move($full_path_before,$destinationPathnew);
                             }
                         }
    
                    }
                }
            }

            if(!empty($data['additionList']))
                {
                    foreach($data['additionList'] as $edit)
                    {
                        if($edit['id']==0){
                            $additemEdit = new item_details_attributes;
                            $additemEdit->itemDetail_id = $itemdetailid;
                            $additemEdit->AttributeKey = $edit['key'];
                            $additemEdit->AttributeValue = $edit['value'];
                            $additemEdit->save();
                        }
                        else{
                            item_details_attributes::where('id',$edit['id'])->update(
                                ['AttributeKey'=>$edit['key'],
                                'AttributeValue'=>$edit['value']]);
        
                        }
                    }
                }

            if(!empty($data['removeEdition']))
            {
                foreach($data['editionList'] as $edit)
                {
                        item_edition::where('id',$edit)->update(
                            ['is_deleted'=>1 ]);
                }
            }
            if(!empty($data['RemoveImage']))
            {
                foreach($data['RemoveImage'] as $edit)
                {
                    item_image_content::where('id',$edit)->delete();
                }
            }
            if(!empty($data['removeAdditions']))
            {
                foreach($data['removeAdditions'] as $edit)
                {
                    item_details_attributes::where('id',$edit)->delete();
                }
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

     // Save Product Image intempfolder
     public function saveProductImage(Request $request)
     {
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
       
        if($data!=null)
        {
            $image = $request->file('image');
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();           
            if( !empty($userdata))
            {
               
                   if(!empty($image))
                   {
                        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                        
                            $destinationPath = public_path('/temp_image');
                            $img = Image::make($image->getRealPath());
                            $img->resize(1000, 1000, function ($constraint) {
                                $constraint->aspectRatio();
                                })->save($destinationPath.'/'.$input['imagename']);
                    }
                   
                   
                    $data['imagepath']=url($destinationPath.'/'.$input['imagename']);
                    $data['imageName']=$input['imagename'];
                    $rt['code'] =  200; 
                    $rt['status'] = 'success';
                    $rt['data'] = $data;
                 
                

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

      // get Categorydata
      public function getproductData(Request $request)
      {
         $rt['code'] =  203; 
         $rt['status'] = 'error';
         // Get Request Data
         $datarequest = $request->all();
         
         if($datarequest!=null)
         {
             
             $userdata = users::where(['api_token'=>$datarequest['api_token'],'usertype'=>"Admin"])->first();           
             if( !empty($userdata))
             {
                ///category_media
                $ItemDetail = item_deatil:: where('id',$datarequest['id'])->first();
                $Itemedition = item_edition:: where('itemDetail_id',$datarequest['id'])->get()->toArray();
                $ItemInfo = item_details_attributes:: where('itemDetail_id',$datarequest['id'])->get()->toArray();
                $ItemImage = item_image_content:: where('itemDetail_id',$datarequest['id'])->get()->toArray();

                $data['itemDetail']=$ItemDetail;
                $data['itemedition']=$Itemedition;
                $data['itemInfo']=$ItemInfo;
                $data['itemImage']=$ItemImage;
                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data'] = $data;
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

       // get Categorydata
       public function getproductList(Request $request)
       {
          $rt['code'] =  203; 
          $rt['status'] = 'error';
          // Get Request Data
          $datarequest = $request->all();
          
          if($datarequest!=null)
          {
              
              $userdata = users::where(['api_token'=>$datarequest['api_token'],'usertype'=>"Admin"])->first();           
              if( !empty($userdata))
              {
                 ///category_media
                 $ItemDetail = item_deatil:: where('status',0)->get()->toArray();
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
                            $data["imageurl"]=$ItemImage["imageString"];
                         }else{
                            $data["imageurl"]="";
                         }
                         array_push($productList,$data);

                     }
                 }
                
 
                // $data['productList']=$productList;
                  $rt['code'] =  200; 
                  $rt['status'] = 'success';
                  $rt['data'] = $productList;
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

       // Post Delete
       public function Saveproductdelete(Request $request)
       {
          $rt['code'] =  203; 
          $rt['status'] = 'error';
          // Get Request Data
          $datarequest = $request->all();
          
          if($datarequest!=null)
          {
              
              $userdata = users::where(['api_token'=>$datarequest['api_token'],'usertype'=>"Admin"])->first();           
              if(!empty($userdata))
              {
                 ///category_media
                 $category = item_deatil::where('id',$datarequest['id'])->first();
                 if(!empty($category))
                 {
                    item_deatil::where('id',$datarequest['id'])->update(['status'=>1 ]);
                    $rt['code'] =  200; 
                    $rt['status'] = 'success';
                 }
                 else{
                    $rt['code'] =  204; 
                    $rt['status'] = 'error';
                 }
               
 
                 
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


}
