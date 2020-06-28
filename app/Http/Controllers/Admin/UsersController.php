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
use App\Models\company_detail;
use Image;

class UsersController extends Controller {
    // Get is active Users Data

    public function getUserList( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $userdata = users::where( ['api_token'=>$data['api_token'], 'usertype'=>'Admin'] )->first();
            if ( !empty( $userdata ) ) {

                // Find count of Pandong Orders
                $newCountOrder = order:: where( 'status', 'placed' )->count();

                // Find List Of new Users
                $NewUserList = users::where( ['is_active'=>0, 'usertype'=>'User'] )->get()->toArray();
                $newUserCount = 0;
                $newUserArrayList = [];

                //  Find count of New Users
                if ( !empty( $NewUserList ) ) {
                    $newUserCount = count( $NewUserList );
                    $i = 1;
                    foreach ( $NewUserList as $newU ) {
                        $newUserArray['s_no'] = $i;
                        $newUserArray['id'] = $newU['id'];
                        $newUserArray['name'] = $newU['name'];
                        $newUserArray['phone_no'] = $newU['phone_no'];
                        $newUserArray['company_name'] = $newU['company_name'];
                        $i++;
                        array_push( $newUserArrayList, $newUserArray );
                    }
                }

                //  Find Active users count
                $activeUsercount = users::where( ['is_active'=>1, 'usertype'=>'User'] )->count();

                //  Find heighest Offers Is runnning
                $datetime = Carbon\Carbon::now();
                $activePromocode = promocode::where( 'start_date', '<=', $datetime )->where( 'end_date', '>=', $datetime )->count();

                //  Find Active users count
                $conformorder = order:: where( 'status', 'conformed' )->count();

                // makedata to send
                $data['promocode'] = $activePromocode;
                $data['registerUserCount'] = $activeUsercount;
                $data['newUserList'] = $newUserArrayList;
                $data['newUserCount'] = $newUserCount;
                $data['newCountOrder'] = $newCountOrder;
                $data['conformorder'] = $conformorder;
                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['data'] = $data;
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

    // Save User Status Active Rejects

    public function saveUserStatus( Request $request ) {
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();

        if ( $data != null ) {
            $userdata = users::where( ['api_token'=>$data['api_token'], 'usertype'=>'Admin'] )->first();
            if ( !empty( $data ) ) {
                if ( $data['status'] == 'active' ) {
                    users::where( ['id'=>$data['id'], 'usertype'=>'User', 'is_active'=>0 ] )->update( ['is_active'=>1] );
                } else {
                    users::where( ['id'=>$data['id'], 'usertype'=>'User', 'is_active'=>0 ] )->update( ['is_active'=>2] );
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

    // Get is active Users Data

    public function getAdminData( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {

            $userdata = users::where( ['api_token'=>$data['api_token'], 'usertype'=>'Admin'] )->first();
            if ( !empty( $userdata ) ) {
                $companydetail = company_detail::get()->toArray();
                $UserDetail['UserName'] = $userdata['	name'];
                $UserDetail['email'] = $userdata['email'];
                $UserDetail['company_name'] = $userdata['company_name'];
                foreach ( $companydetail as $cdetail ) {
                    if ( $cdetail['Key'] == 'aboutus' ) {
                        $UserDetail['about_us'] =  $cdetail['value'];
                    }
                    if ( $cdetail['Key'] == 'phone1' ) {
                        $UserDetail['phone_no'] =  $cdetail['value'];
                    }
                    if ( $cdetail['Key'] == 'phone2' ) {
                        $UserDetail['phone_no2'] =  $cdetail['value'];
                    }
                    if ( $cdetail['Key'] == 'address1' ) {
                        $UserDetail['address1'] =  $cdetail['value'];
                    }
                    if ( $cdetail['Key'] == 'postalcode' ) {
                        $UserDetail['postal_code'] =  $cdetail['value'];
                    }
                    if ( $cdetail['Key'] == 'logo' ) {
                        // $UserDetail['logo'] = url( 'public/company_logo/'.$cdetail['value'] );
                        $UserDetail['logo'] =  url( 'company_logo/'.$cdetail['value'] );
                    }
                }

                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['data'] = $UserDetail;
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

    // Get is active Users Data

    public function saveAdminData( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $image = $request->file('image');
            $userdata = users::where( ['api_token'=>$data['api_token'], 'usertype'=>'Admin'] )->first();
            if ( !empty( $userdata ) ) {
                if(!empty($image))
                   {
                        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                        
                            $destinationPath = public_path('/company_logo');
                            $img = Image::make($image->getRealPath());
                            $img->resize(1000, 1000, function ($constraint) {
                                $constraint->aspectRatio();
                                })->save($destinationPath.'/'.$input['imagename']);
                         company_detail::where( ['Key'=>'logo' ] )->update( [ 'value'=>$input['imagename']] );
                    }
                   
                company_detail::where( ['Key'=>'aboutus' ] )->update( [ 'value'=>$data['about_us']] );
                company_detail::where( ['Key'=>'phone1' ] )->update( ['value'=>$data['phone_no']] );
                company_detail::where( ['Key'=>'phone2' ] )->update( ['value'=>$data['phone_no']] );
                company_detail::where( ['Key'=>'address1' ] )->update( ['value'=>$data['address1']] );
                company_detail::where( ['Key'=>'postalcode' ] )->update( ['value'=>$data['postal_code']] );
               

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

    public function getUserActiveList( Request $request ) {
        //  Definreturn jason data id any error occured
        $rt['code'] =  203;

        $rt['status'] = 'error';
        // Get Request Data
        $data = $request->all();
        if ( !empty( $data ) ) {
            $userdata = users::where( ['api_token'=>$data['api_token'], 'usertype'=>'Admin'] )->first();
            if ( !empty( $userdata ) ) {

                // Find count of Pandong Orders
                $newCountOrder = order:: where( 'status', 'placed' )->count();

                // Find List Of new Users
                $NewUserList = users::where( ['is_active'=>1, 'usertype'=>'User'] )->get()->toArray();
                $newUserCount = 0;
                $newUserArrayList = [];

                //  Find count of New Users
                if ( !empty( $NewUserList ) ) {
                    $newUserCount = count( $NewUserList );
                    $i = 1;
                    foreach ( $NewUserList as $newU ) {
                        $newUserArray['s_no'] = $i;
                        $newUserArray['id'] = $newU['id'];
                        $newUserArray['name'] = $newU['name'];
                        $newUserArray['phone_no'] = $newU['phone_no'];
                        $newUserArray['company_name'] = $newU['company_name'];
                        $i++;
                        array_push( $newUserArrayList, $newUserArray );
                    }
                }

                //  Find Active users count
                $activeUsercount = users::where( ['is_active'=>1, 'usertype'=>'User'] )->count();

                //  Find heighest Offers Is runnning
                $datetime = Carbon\Carbon::now();
                $activePromocode = promocode::where( 'start_date', '<=', $datetime )->where( 'end_date', '>=', $datetime )->count();

                //  Find Active users count
                $conformorder = order:: where( 'status', 'conformed' )->count();

                // makedata to send
                $data['promocode'] = $activePromocode;
                $data['registerUserCount'] = $activeUsercount;
                $data['newUserList'] = $newUserArrayList;
                $data['newUserCount'] = $newUserCount;
                $data['newCountOrder'] = $newCountOrder;
                $data['conformorder'] = $conformorder;
                $rt['code'] =  200;

                $rt['status'] = 'success';
                $rt['data'] = $data;
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

  
}
