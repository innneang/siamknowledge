<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*Student specific route Middleware*/
    Route::group(['middleware' => ['Checkuser:Student', 'Checkprivilege']], function () {
        Route::get('/viewmycourse/{id}', 'JobController@manage')->name('viewmycoursepage'); //need implement view code
        Route::get('/viewmycourse/{id}/select/{tutorid}', 'JobController@selecttutor')->name('selecttutor');
        Route::get('/viewmycourse/{id}/profile/{tutorid}', 'JobController@viewprofile')->name('tutorprofile');
        Route::get('/dup/{id}/{num?}/{every?}', 'JobController@dup')->name('dup');
        Route::get('/edit/{id}', 'JobController@edit')->name('edit');
        Route::post('/edit/{id}', 'JobController@updatecourse');
    });
/*Student Middleware*/
    Route::group(['middleware' => ['Checkuser:Student']], function () {
        Route::get('/addcourse', function(){return view('student-addcourse');})->name('addcourse');
        Route::post('/addcourse', 'JobController@addcourse');
        Route::get('/addcredit', 'CreditController@studentcredit')->name('addcredit');
        Route::post('/addcredit', 'CreditController@addcredit');
        Route::post('/confirmcredit', 'CreditController@confirmcredit');
        Route::get('/viewmycourse', 'JobController@viewmycourse')->name('viewmycourse');
        Route::get('/updateprofile', 'RegisterController@myprofile');
        Route::put('/updateprofile', 'RegisterController@updatemyprofile');
        //Support need implement!
        //Route::get('/support/{courseid}' ,'SupportController@contact');
        //Route::post('/support/{courseid}', 'SupportController@submit');
    });
/*Tutor Middleware*/
    Route::group(['middleware'=>['Checkuser:Tutor']], function(){
        Route::get('/course/{id}/uninterest', 'JobController@uninterest');
        Route::get('/course/{id}/interest', 'JobController@interest');
        Route::get('/course', 'JobController@showcourse')->name('course');
        Route::get('/course/{id}', 'JobController@showcoursepage')->name('courseinfo');
        Route::get('/answered', 'JobController@tutoranswered')->name('tutoranswered');
        Route::get('/verify/{id}', 'JobController@verify')->name('verify');
        Route::get('/verify/{courseid}/{code}', 'JobController@doverify')->name('doverify');
        Route::get('/cancel/{courseid}/', 'JobController@cancel')->name('cancel');
        Route::get('/myprofile', 'RegisterController@myprofiletutor');
        Route::put('/myprofile', 'RegisterController@updatemyprofiletutor');
        Route::get('/earning', 'CreditController@earning');
    });
/*Admin middleware*/
    Route::group(['middleware' => ['Admin']], function () {
        Route::get('/admin/credit/approve/{id}', 'CreditController@approvecredit')->name('approvecredit');
        Route::get('/admin', 'AdminController@dashboard')->name('admin');
        Route::get('/admin/search/{keyword}', 'AdminController@search');
        Route::get('/admin/coursesearch/{keyword}', 'AdminController@coursesearch');
        Route::get('/admin/profileedit/{id}', 'AdminController@profileedit');
        Route::post('/admin/profileedit/{id}', 'RegisterController@updatemyprofileadmin')->name('updateprofileadmin');
        Route::get('/admin/profileedit/{id}/del', 'AdminController@profiledelete')->name('updateprofileadmindel');
        Route::get('/admin/creditdel/{id}', 'AdminController@creditlogdel')->name('creditdel');
    });
/*Guest middleware*/
    Route::group(['middleware' => ['Guest']], function () {
        /*Register */
            Route::get('/studentregister', function(){return view('register');})->name('register');
            Route::post('/studentregister','RegisterController@registerStudent');
            Route::post('/tutorregister', 'RegisterController@registerTutor');
            Route::get('/tutorregister', function(){return view('register');});
            Route::get('/login', function(){return view('login');})->name('login');
            Route::post('/login', 'RegisterController@login');
        /*End Register */
    });
/*Not protected route*/
    Route::get('/', 'JobController@forward')->name('welcome');
    Route::get('/mail', 'EmailController@verify');
    Route::get('/logout', function(){
        Sentinel::logout();
        return redirect()->route('welcome');
    });
/*End not protected route*/
/*-------------------------*/