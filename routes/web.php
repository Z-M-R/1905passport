<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/user/reg','TestController@reg');  // 用户注册
Route::post('/user/login','TestController@login');  // 用户登录
Route::get('/api/user/list','Api\TestController@userList')->middleware('filter');  // 用户登录


Route::prefix('test/belief')->group(function(){//前台登录页
    Route::any('reg','User\TestController@reg');//后台列表页deinfo
    Route::any('login','User\TestfController@login');//后台列表页deinfo
});