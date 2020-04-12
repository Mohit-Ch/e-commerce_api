<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\category; 
use App\Models\subcategory;
class SubCategoryController extends Controller
{
    //

     // Save category
     public function saveSubCategory(Request $request)
     {
        $rt['code'] =  203; 
        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        
        if($data!=null)
        {
            $userdata = users::where(['api_token'=>$data['api_token'],'usertype'=>"Admin"])->first();           
            if( !empty($userdata))
            {
                if($data['type']=='add')
               {
                    $addCategory = new subcategory;
                     $addCategory->category_name = $data['Subcategory'];
                     $addCategory->category_id  = $data['category_id'];
                     $addCategory->save();

                        $rt['code'] =  200; 
                        $rt['status'] = 'success';
                 }
                 else{
                     if($data['id']!=null)
                     {
                        $categorydata = subcategory:: where('id',$data['id'])->first();
                        if(!empty($categorydata))
                        {
                            subcategory::where('id',$data['id'])->update(['category_name'=>$data['Subcategory'],'category_id'=>$data['category_id']]);
                            $rt['code'] =  200; 
                            $rt['status'] = 'success'; 
                        }
                     }
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

      // get Categorydata
      public function getSubCategoryData(Request $request)
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
                $categorydata = subcategory:: where('id',$datarequest['id'])->first();
                $category["id"]=$categorydata['id'];
                $category["category_name"]=$categorydata['category_name'];
                $category["category_id"]= $categorydata['category_id'];

                 $rt['code'] =  200; 
                 $rt['status'] = 'success';
                 $rt['data'] = $category;
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

       // get List
       public function getSubCategoryList(Request $request)
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
                 $categoryList = subcategory::where('is_deleted',0)->get()->toArray();
                 $catList=[];
                 if(!empty($categoryList)){
                    $i=1;
                    foreach($categoryList as $cat)
                    {
                        $category["s_no"]=$i;
                        $category["id"]=$cat['id'];
                        $category["Subcategory_name"]=$cat['category_name'];
                        $categorydata = category::where(['is_deleted'=>0 ,'id'=>$cat['category_id']])->first();
                        $category["category_Name"]= $categorydata['category_name'];
                        array_push($catList,$category);
                        $i++;
                    }
                   
                    $rt['code'] =  200; 
                    $rt['status'] = 'success';
                    $rt['data'] = $catList;
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

       // Post Delete
       public function SaveSubCategorydelete(Request $request)
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
                 $category = subcategory::where('id',$datarequest['id'])->first();
                 if(!empty($category))
                 {
                    subcategory::where('id',$datarequest['id'])->update(['is_deleted'=>1 ]);
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

        // get Sub category list using category id
        public function getSubCategoryIdList(Request $request)
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
                  $categoryList = subcategory::where(['is_deleted'=>0,'category_id'=>$datarequest['id'] ])->get()->toArray();
                  $catList=[];
                  if(!empty($categoryList)){
                     $i=1;
                     foreach($categoryList as $cat)
                     {
                         $category["id"]=$cat['id'];
                         $category["category_name"]=$cat['category_name'];
                         array_push($catList,$category);
                         $i++;
                     }
                    
                     $rt['code'] =  200; 
                     $rt['status'] = 'success';
                     $rt['data'] = $catList;
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
