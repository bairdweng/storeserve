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
    Route::post('/store/ApplyToBeAbusinessMan', 'StoreController@ApplyToBeAbusinessMan');
    Route::post('/store/GetMyStoreInfo', 'StoreController@GetMyStoreInfo');
    Route::post('/store/UpdateInfo', 'StoreController@UpdateInfo');
    Route::post('/store/UploadStoreIcon', 'StoreController@UploadStoreIcon');
    Route::post('/store/AddAndUpdateProductInfo', 'ProductController@AddAndUpdateProductInfo');
    Route::post('/store/GetStoreList', 'StoreController@GetStoreList');
    Route::post('/store/ChangeStroeState', 'StoreController@ChangeStroeState');


    Route::post('/product/UpLoadProductImg', 'ProductController@UpLoadProductImg');
    Route::post('/product/GetProductByStoreId', 'ProductController@GetProductByStoreId');
    Route::post('/product/GetProductByProductId', 'ProductController@GetProductByProductId');
    Route::post('/product/DeleteProductById', 'ProductController@DeleteProductById');
    Route::post('/product/DeleteProductImgById', 'ProductController@DeleteProductImgById');
});

