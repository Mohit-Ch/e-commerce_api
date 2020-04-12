<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\category;
use Image;
use File;
class CategoryController extends Controller
{
    //

     // Save category
     public function saveCategory(Request $request)
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
                if($data['type']=='add')
               {
                   if(!empty($image))
                   {
                        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                        
                            $destinationPath = public_path('/category_media');
                            $img = Image::make($image->getRealPath());
                            $img->resize(1000, 1000, function ($constraint) {
                                $constraint->aspectRatio();
                                })->save($destinationPath.'/'.$input['imagename']);
                    }
                    else{
                        $input['imagename']="";  
                    }
                            $addCategory = new category;
                            $addCategory->category_name = $data['category'];
                            $addCategory->photo = $input['imagename'];
                            $addCategory->save();

                        $rt['code'] =  200; 
                        $rt['status'] = 'success';
                 }
                 else{
                     if($data['id']!=null)
                     {
                        $categorydata = category:: where('id',$data['id'])->first();
                        if(!empty($categorydata))
                        {
                            if($categorydata['photo']==null || $categorydata['photo']=="")
                            {
                                if(!empty($image))
                                {
                                     $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                                     
                                         $destinationPath = public_path('/category_media');
                                         $img = Image::make($image->getRealPath());
                                         $img->resize(1000, 1000, function ($constraint) {
                                             $constraint->aspectRatio();
                                             })->save($destinationPath.'/'.$input['imagename']);
                                 }
                                 else{
                                     $input['imagename']="";  
                                 }
                                 category::where('id',$data['id'])->update(['category_name'=>$data['category'],'photo'=>$input['imagename'] ]);
                                 $rt['code'] =  200; 
                                 $rt['status'] = 'success';      

                            }else{
                                if(!empty($image))
                                {
                                    if($categorydata['photo']!=null && $categorydata['photo']!="")
                                    { 
                                        $destinationPath = public_path('category_media\\');
                                        $image_path = $destinationPath.$categorydata['photo']; 
                                       //dd($image_path); // Value is not URL but directory file path
                                            if(File::exists($image_path)) {
                                                File::delete($image_path);
                                            }

                                    }
                                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                                     
                                    $destinationPath = public_path('/category_media');
                                    $img = Image::make($image->getRealPath());
                                    $img->resize(1000, 1000, function ($constraint) {
                                        $constraint->aspectRatio();
                                        })->save($destinationPath.'/'.$input['imagename']);
                                        category::where('id',$data['id'])->update(['category_name'=>$data['category'],'photo'=>$input['imagename'] ]);
                                        $rt['code'] =  200; 
                                        $rt['status'] = 'success';      
                                }
                                else{
                                    category::where('id',$data['id'])->update(['category_name'=>$data['category']]);
                                    $rt['code'] =  200; 
                                    $rt['status'] = 'success'; 
                                }
                            }

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
      public function getCategoryData(Request $request)
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
                $categorydata = category:: where('id',$datarequest['id'])->first();
                $category["id"]=$categorydata['id'];
                $category["category_name"]=$categorydata['category_name'];
                $category["photo"]= url('category_media/' . $categorydata['photo']);

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
       public function getCategoryList(Request $request)
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
                 $categoryList = category::where('is_deleted',0)->get()->toArray();
                 $catList=[];
                 if(!empty($categoryList)){
                    $i=1;
                    foreach($categoryList as $cat)
                    {
                        $category["s_no"]=$i;
                        $category["id"]=$cat['id'];
                        $category["category_name"]=$cat['category_name'];
                        $category["photo"]= url('category_media/' . $cat['photo']);
                        array_push($catList,$category);
                        $i++;
                    }
                }
               
 
                  $rt['code'] =  200; 
                  $rt['status'] = 'success';
                  $rt['data'] = $catList;
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
       public function SaveCategorydelete(Request $request)
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
                 $category = category::where('id',$datarequest['id'])->first();
                 if(!empty($category))
                 {
                    category::where('id',$datarequest['id'])->update(['is_deleted'=>1 ]);
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
