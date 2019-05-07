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
})->middleware('CheckloginToken');

//用户接口
Route::get('/api/u','Api\UserApicontroller@userInfo');  //查询一条消息
Route::post('/api/create','Api\UserApicontroller@create'); //添加一条数据
Route::get('/api/apicurl','Api\UserApicontroller@apicurl'); //curl测试(get);
Route::get('/api/apicurlpost','Api\UserApicontroller@apicurlpost'); //curl测试(post)
Route::get('/api/midtime','Api\UserApicontroller@midtime')->middleware('RequestRate'); //中间间

Route::post('/api/adduser','User\UserController@adduser'); //注册地
Route::post('/api/login','User\UserController@login'); //登陆
Route::get('/api/user','User\UserController@user')->middleware(['CheckloginToken','RequestRate']); //用户中心



//资源路由
Route::resource('/index', GoodsController::class);
