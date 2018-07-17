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

    Route::get('/', 'User\HomeController@index');

    Route::auth();

    Route::get('/register', 'User\HomeController@register');
    Route::get('/verifyemail', 'User\HomeController@verifyEmail');
    Route::get('/verify/{code}', 'Auth\AuthController@verifyUser')->where('code', '[A-Za-z0-9\-\_\.]+');

    Route::get('/document/ajaxTable', 'User\DocumentController@ajaxTable');
    Route::get('/document/ajax/{id}', 'User\DocumentController@ajax');

    Route::get('/document/download/{id}', 'User\DocumentController@download')->where('id', '[A-Za-z0-9\-\_\.]+');
    Route::get('/document/ajaxCheckFileExits/{id}', 'User\DocumentController@ajaxCheckFileExits')->where('id', '[A-Za-z0-9\-\_\.]+');
    Route::get('/document/ajaxThutuc/{id}', 'User\DocumentController@ajaxThutuc');
    Route::get('/document/ajaxDieuKhoan/{id}', 'User\DocumentController@ajaxDieuKhoan');
    Route::get('/document/ajaxBuyDocument/{id}', 'User\DocumentController@ajaxBuyDocument');
    Route::get('/document/{id}', 'User\DocumentController@document');

    Route::get('/vanbandamua', 'User\DocumentController@documentBuyeds');

    Route::get('/search', 'User\HomeController@search');
    Route::post('/search', 'User\HomeController@search');

    Route::get('/category/{slug}', 'User\DocumentController@documents');

    Route::any('/feedback', 'User\HomeController@feedback');

    Route::any('/hoidap/{id?}', 'User\QAController@qa')->where('id', '[0-9\-\_\.]+');
    Route::get('/hoidaps/ajaxListQA', 'User\QAController@ajaxListQA');
    Route::get('/hoidaps', 'User\QAController@qas');

    Route::any('/thongtincanhan', 'User\HomeController@user');

Route::group(['middleware' => ['auth','roles'], 'roles'=>[STR_ADMIN]], function () {
    Route::get('/admin', 'Admin\HomeController@index');
    Route::get('/admin/upload', 'Admin\HomeController@upload');

    Route::any('/admin/document/edit/{stt}', 'Admin\DocumentController@edit');
    Route::any('/admin/document/edit', 'Admin\DocumentController@edit');
    Route::get('/admin/document/ajax', 'Admin\DocumentController@ajax');
    Route::get('/admin/document', 'Admin\DocumentController@index');
    Route::get('/admin/document/delete/{stt}', 'Admin\DocumentController@delete');

    Route::get('/admin/category', 'Admin\CategoryController@index');
    Route::get('/admin/category/ajax', 'Admin\CategoryController@ajax');
    Route::any('/admin/category/edit/{stt}', 'Admin\CategoryController@edit');
    Route::any('/admin/category/edit', 'Admin\CategoryController@edit'); 
    Route::any('/admin/category/delete/{id}', 'Admin\CategoryController@delete');

    Route::any('/admin/user/{id}', 'Admin\UserController@editUser')->where('id', '[0-9\-\_\.]+');
    Route::get('/admin/user/ajaxListUser', 'Admin\UserController@ajaxListUser');
    Route::get('/admin/user', 'Admin\UserController@index');

    Route::any('/admin/qa/ajaxDelete/{id}', 'Admin\QAController@ajaxDelete')->where('id', '[0-9\-\_\.]+');
    Route::any('/admin/qa/edit/{id}', 'Admin\QAController@edit')->where('id', '[0-9\-\_\.]+');
    Route::any('/admin/qa/edit', 'Admin\QAController@edit');
    Route::get('/admin/qa/ajaxListQA', 'Admin\QAController@ajaxListQA');
    Route::get('/admin/qa', 'Admin\QAController@index');
});

    Route::get('/{slug}', 'User\DocumentController@document');