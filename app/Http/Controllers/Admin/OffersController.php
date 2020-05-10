<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\promocode;
use Carbon;
class OffersController extends Controller
{
    // Save promocode   
    public function savepromocode(Request $request)
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
               if($data['stype']=='add')
              {
                   $addpromocode = new promocode;
                   $addpromocode->user_id = $userdata['id'];
                    $addpromocode->amount = $data['amount'];
                    $addpromocode->code = $data['code'];
                    $addpromocode->description = $data['description'];
                    $addpromocode->type = $data['type'];
                    $addpromocode->minOrderAmount = $data['minOrderAmount'];
                    $addpromocode->maxDiscountAmount = $data['maxDiscountAmount'];
                    $sd=Carbon\Carbon::parse($data['start_date']);
                    $sed=Carbon\Carbon::parse($data['end_date']);
                    $addpromocode->start_date = $sd;
                    $addpromocode->end_date = $sed;
                    $addpromocode->save();

                       $rt['code'] =  200; 
                       $rt['status'] = 'success';
                }
                else{
                    if($data['id']!=null)
                    {
                       $categorydata = promocode:: where('id',$data['id'])->first();
                       if(!empty($categorydata))
                       {
                        $sd=Carbon\Carbon::parse($data['start_date']);
                        $sed=Carbon\Carbon::parse($data['end_date']);
                        promocode::where('id',$data['id'])
                        ->update([
                            'amount'=>$data['amount'],
                            'code'=>$data['code'],
                            'description'=>$data['description'],
                            'type'=>$data['type'],
                            'minOrderAmount'=>$data['minOrderAmount'],
                            'maxDiscountAmount'=>$data['maxDiscountAmount'],
                            'start_date'=>$sd,
                            'end_date'=>$sed
                            ]);
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

     // get promocode
     public function getpromocodeData(Request $request)
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
               
               $promocodedata = promocode:: where('id',$datarequest['id'])->first();
               $promocode["id"]=$promocodedata['id'];
               $promocode["amount"]=$promocodedata['amount'];
               $promocode["code"]=$promocodedata['code'];
               $promocode["description"]=$promocodedata['description'];
               $promocode["type"]=$promocodedata['type'];
               $promocode["minOrderAmount"]=$promocodedata['minOrderAmount'];
               $promocode["maxDiscountAmount"]=$promocodedata['maxDiscountAmount'];
               $promocode["start_date"]=$promocodedata['start_date'];
               $promocode["end_date"]=$promocodedata['end_date'];
             

                $rt['code'] =  200; 
                $rt['status'] = 'success';
                $rt['data'] = $promocode;
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
      public function getpromocodeList(Request $request)
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
               
                $promocodeList = promocode::get()->toArray();
                $promoList=[];
                if(!empty($promocodeList)){
                   $i=1;
                   foreach($promocodeList as $prom)
                   {
                      
                       $prmocode["id"]=$prom['id'];
                       $prmocode["amount"]=$prom['amount'];
                        $prmocode["code"]=$prom['code'];
                        $prmocode["description"]=$prom['description'];
                        $prmocode["type"]=$prom['type'];
                        $prmocode["minOrderAmount"]=$prom['minOrderAmount'];
                        $prmocode["maxDiscountAmount"]=$prom['maxDiscountAmount'];
                        $prmocode["start_date"]=$prom['start_date'];
                        $prmocode["end_date"]=$prom['end_date'];
                       array_push($promoList,$prmocode);
                       $i++;
                   }
                  
                   $rt['code'] =  200; 
                   $rt['status'] = 'success';
                   $rt['data'] = $promoList;
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
