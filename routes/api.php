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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//清除cookie。
Route::post('/CleanCookies', function () {
    $cookie = cookie('token', '', -60);
    return response()->json(['message' => '清除成功'])->cookie($cookie);
});
//登录
Route::post('/Login', 'UserController@Login');

/*验证用户*/
Route::group(['middleware' => 'usertokenvalidation'], function () {
    Route::post('/user/Details', 'UserController@Details');
    Route::post('/store/Details', 'StoreController@Details');
    Route::post('/store/UpdateInfo', 'StoreController@UpdateInfo');

});

