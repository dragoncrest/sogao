<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

    Route::get('/', 'HomeController@index');

    Route::auth();

    Route::get('/register', 'HomeController@register');

    Route::get('/document/ajaxTable', 'DocumentController@ajaxTable');
    Route::get('/document/ajax/{id}', 'DocumentController@ajax');

    Route::get('/document/download/{id}', 'DocumentController@download')->where('id', '[A-Za-z0-9\-\_\.]+');
    Route::get('/document/ajaxCheckFileExits/{id}', 'DocumentController@ajaxCheckFileExits')->where('id', '[A-Za-z0-9\-\_\.]+');
    Route::get('/document/ajaxThutuc/{id}', 'DocumentController@ajaxThutuc');
    Route::get('/document/ajaxDieuKhoan/{id}', 'DocumentController@ajaxDieuKhoan');
    Route::get('/document/{id}', 'DocumentController@document');
    Route::get('/document/ajaxBuyDocument/{id}', 'DocumentController@ajaxBuyDocument');

    Route::get('/search', 'HomeController@search');
    Route::post('/search', 'HomeController@search');

    Route::get('/category/{slug}', 'DocumentController@documents');

Route::group(['middleware' => ['auth','roles'], 'roles'=>['admin']], function () {
    Route::get('/admin', 'Admin\HomeController@index');
    Route::get('/admin/upload', 'Admin\HomeController@upload');

    Route::any('/admin/document/edit/{stt}', 'Admin\DocumentController@edit');
    Route::any('/admin/document/edit', 'Admin\DocumentController@edit');
    Route::get('/admin/document/ajax', 'Admin\DocumentController@ajax');
    Route::get('/admin/document/{idCat}', 'Admin\DocumentController@index');
    Route::get('/admin/document/delete/{stt}', 'Admin\DocumentController@delete');

    Route::get('/admin/category', 'Admin\CategoryController@index');
    Route::get('/admin/category/ajax', 'Admin\CategoryController@ajax');
    Route::any('/admin/category/edit/{stt}', 'Admin\CategoryController@edit');
    Route::any('/admin/category/edit', 'Admin\CategoryController@edit'); 
    Route::any('/admin/category/delete/{id}', 'Admin\CategoryController@delete');

    Route::any('/admin/user/{id}', 'Admin\UserController@editUser')->where('id', '[0-9\-\_\.]+');
    Route::get('/admin/user/ajaxListUser', 'Admin\UserController@ajaxListUser');
    Route::get('/admin/user', 'Admin\UserController@index');
});

    Route::get('/{slug}', 'DocumentController@document');