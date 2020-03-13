<?php

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


Route::prefix('v1')->group(function () {
  Route::get('index', function () {
        return response(['greeting'=>'Welcome to Take and Make Api Services'], 200)->header('Content-Type', 'application/json');
  });


  //get
  Route::get('payment-status', 'Api\V1\BusinessController@getPaymentStatus');
  Route::get('faq', 'Api\V1\ApiController@faqList');
  Route::get('settings', 'Api\V1\ApiController@settings');
  Route::get('banner', 'Api\V1\ApiController@banner');
  Route::get('category-list', 'Api\V1\CategoryController@getAllCategory');
  Route::get('country-list', 'Api\V1\LocationController@getAllCountry');
  Route::get('plan-list', 'Api\V1\PlanController@getAllPlan');


  //post
  Route::post('send-otp', 'Api\V1\UserController@sendOTP');
  Route::post('plan-upgrade', 'Api\V1\PlanController@upgradePlan');
  Route::post('submit-plan-upgrade', 'Api\V1\BusinessController@upgradeCurrentPlan');
  Route::post('state-list', 'Api\V1\LocationController@getAllState');
  Route::post('city-list', 'Api\V1\LocationController@getAllCity');
  Route::post('register', 'Api\V1\UserController@register');
  Route::post('login', 'Api\V1\UserController@login');
  Route::post('register-login', 'Api\V1\UserController@registerLogin');
  Route::post('activate-account', 'Api\V1\UserController@activateAccount');
  Route::post('forgot-password', 'Api\V1\UserController@forgotPassword');
  Route::post('reset-password', 'Api\V1\UserController@resetPassword');
  Route::post('update-profile', 'Api\V1\UserController@updateProfile');
  Route::post('register-business', 'Api\V1\BusinessController@registerBusiness');
  Route::post('update-business', 'Api\V1\BusinessController@updateBusiness');
  Route::post('opening-hours', 'Api\V1\BusinessController@manageworkingHours');
  Route::post('search-business', 'Api\V1\BusinessController@getBusinessProfiles');
  Route::post('business-detail', 'Api\V1\BusinessController@getBusinessDetails');
  Route::post('create-product', 'Api\V1\ProductController@registerProduct');
  Route::post('edit-product', 'Api\V1\ProductController@updateProduct');
  Route::post('product-list', 'Api\V1\ProductController@getProductList');
  Route::post('product-detail', 'Api\V1\ProductController@getProductDetail');
  Route::post('remove-product', 'Api\V1\ProductController@removeProduct');
  Route::post('plan-detail', 'Api\V1\PlanController@getPlanDetail');
  Route::post('register-activity', 'Api\V1\ActivityController@reigsterActivity');
  Route::post('users-bookmark', 'Api\V1\ActivityController@getUserBookmarkList');
  Route::post('business-filter', 'Api\V1\BusinessController@businessFilter');
  Route::post('search-hint', 'Api\V1\ActivityController@searchHint');
  Route::post('verify-user-activation', 'Api\V1\UserController@verifyUserActivation');
  Route::post('report-spam', 'Api\V1\ActivityController@reportSpam');
  Route::post('add-rating', 'Api\V1\ActivityController@addRating');
  Route::post('feeback', 'Api\V1\ApiController@registerFeedback');


  //put

  //delete

});
