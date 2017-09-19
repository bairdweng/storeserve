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
date_default_timezone_set('PRC');
error_reporting(E_ALL ^ E_NOTICE);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//清除cookie。
Route::post('/CleanCookies', function () {
    $cookie = cookie('token', '', -60);
    return STJsonResultData(['message' => '清除成功'])->cookie($cookie);
});
//登录
Route::post('/Login', 'UserController@Login');

/*验证用户*/
Route::group(['middleware' => 'usertokenvalidation'], function () {
    Route::post('/user/Details', 'UserController@Details');
    Route::post('/store/Details', 'StoreController@Details');
    Route::post('/store/UpdateInfo', 'StoreController@UpdateInfo');
    Route::post('/store/AddAndUpdateProductInfo', 'ProductController@AddAndUpdateProductInfo');
    Route::post('/store/UpLoadProductImg', 'ProductController@UpLoadProductImg');
    Route::post('/store/GetProductByStoreId', 'ProductController@GetProductByStoreId');
    Route::post('/store/GetProductByProductId', 'ProductController@GetProductByProductId');
    Route::post('/store/DeleteProductById', 'ProductController@DeleteProductById');
    Route::post('/store/DeleteProductImgById', 'ProductController@DeleteProductImgById');
});

