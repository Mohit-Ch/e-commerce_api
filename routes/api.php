<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

 /*******************MobileApp And WebApp Auth Api******************/
 /************************** Admin Api******************************/ 
/*********************** Admin Login******************************/ 
 Route::post('/login', 'Auth\LoginController@login');

 /*********************** Admin ******************************/ 
 Route::get('/getDashboard', 'Admin\Dashboard@getDashboardData');
 Route::get('/getPendingOrder', 'Admin\Orders@getPendingOrder');
 Route::get('/getConformOrder', 'Admin\Orders@getAcceptedOrder');
 Route::get('/getDeliveredOrder', 'Admin\Orders@getDeliveredOrder');
 Route::get('/getUserList', 'Admin\UsersController@getUserList');
 Route::post('/saveUserStatus', 'Admin\UsersController@saveUserStatus');
 Route::post('/saveOrderStatus', 'Admin\Orders@saveOrderStatus'); 
 Route::get('/getOrderDetail', 'Admin\Orders@getOrderDetail');
 Route::get('/getAdminData', 'Admin\UsersController@getAdminData');
 Route::post('/saveAdminData', 'Admin\UsersController@saveAdminData');
 Route::get('/getTopBarData', 'Admin\Dashboard@getTopBarData');
 Route::post('/saveCategory', 'Admin\CategoryController@saveCategory');
 Route::get('/getCategoryData', 'Admin\CategoryController@getCategoryData');
 Route::get('/getCategoryList', 'Admin\CategoryController@getCategoryList');
 Route::post('/SaveCategorydelete', 'Admin\CategoryController@SaveCategorydelete'); 
 Route::post('/saveSubCategory', 'Admin\SubCategoryController@saveSubCategory');

 Route::get('/getSubCategoryData', 'Admin\SubCategoryController@getSubCategoryData'); 
 Route::get('/getSubCategoryList', 'Admin\SubCategoryController@getSubCategoryList');
 Route::post('/SaveSubCategorydelete', 'Admin\SubCategoryController@SaveSubCategorydelete'); 
 Route::get('/getSubCategoryIdList', 'Admin\SubCategoryController@getSubCategoryIdList'); 
 Route::post('/saveProduct', 'Admin\ProductController@saveProduct');  
 Route::post('/saveProductImage', 'Admin\ProductController@saveProductImage');  
 Route::get('/getproductData', 'Admin\ProductController@getproductData');  
 Route::get('/getproductList', 'Admin\ProductController@getproductList'); 
 Route::post('/Saveproductdelete', 'Admin\ProductController@Saveproductdelete');   
 Route::get('/getUserActiveList', 'Admin\UsersController@getUserActiveList');
 Route::post('/savepromocode', 'Admin\OffersController@savepromocode');  
 Route::get('/getpromocodeData', 'Admin\OffersController@getpromocodeData');  
 Route::get('/getpromocodeList', 'Admin\OffersController@getpromocodeList'); 

  /************************** Registration Normal******************************/
  Route::post('mobileapp/registration', 'Auth\RegisterController@registration');


   /************************** User login******************************/
   Route::post('mobileapp/Userlogin', 'Auth\LoginController@Userlogin');


   /************************** Mobile App******************************/
   Route::get('mobileapp/getCategoryList', 'MobileApp\HomeController@getCategoryList');
   Route::get('mobileapp/getSubCategoryList', 'MobileApp\HomeController@getSubCategoryList'); 
   Route::get('mobileapp/getproductList', 'MobileApp\HomeController@getproductList');
   Route::get('mobileapp/getproductData', 'MobileApp\HomeController@getproductData');
   Route::get('mobileapp/getproductSearch', 'MobileApp\HomeController@getproductSearch');   
   Route::post('mobileapp/getOrderDetail', 'MobileApp\OrderController@getOrderDetail');  
   Route::get('mobileapp/couponcodeValid', 'MobileApp\OrderController@couponcodeValid');    
   Route::get('mobileapp/getAddressList', 'MobileApp\OrderController@getAddressList'); 
   Route::get('mobileapp/CheckEmailExist', 'MobileApp\OrderController@CheckEmailExist');  
   Route::post('mobileapp/SetReserveQuantity', 'MobileApp\OrderController@SetReserveQuantity');  
   Route::post('mobileapp/placeOrder', 'MobileApp\OrderController@placeOrder');  
   Route::post('mobileapp/RevertReserveQuantity', 'MobileApp\OrderController@RevertReserveQuantity');  
   Route::get('mobileapp/CheckAddressExist', 'MobileApp\OrderController@CheckAddressExist');  

   Route::post('mobileapp/ContectUsemail', 'MobileApp\ContectController@sendemail');   
   Route::get('mobileapp/AboutUsInfo', 'MobileApp\ContectController@AboutUsInfo');

   Route::post('mobileapp/password/email', 'Auth\ForgotPasswordController@getResetToken');
   Route::post('mobileapp/password/forget', 'Auth\ForgotPasswordController@generateNewPassword');
   Route::post('mobileapp/password/reset', 'Auth\ResetPasswordController@reset');

   Route::get('mobileapp/getPastOrder', 'MobileApp\PastOrderController@getPendingOrder');
   /************************** error_log******************************/
   Route::post("/error_log", "Auth\ErrorLogController@ErrorLog");

