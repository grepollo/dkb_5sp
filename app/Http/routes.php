<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/test', function() {
    $cc= new \CouchbaseCluster(env('CB_HOST', 'couchbase://localhost'));
    $cb = $cc->openBucket('5sportal');
    $cb->enableN1qlQuery(['http://192.168.10.10:8091']);
    dd($cb);
    $query = \CouchbaseN1qlQuery::fromString('SELECT * from 5sportal LIMIT 3');
    $res = $model->cb->query($query);
    dd($res);

    $person = new \App\Person();

    $resp = $person->getAssignedPersons(2);
    dd($resp);

});

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::post('/login', 'LoginController@attempt');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/lockscreen', 'LoginController@lockscreen');
    Route::get('/dashboard', 'DashboardController@index');
    //Users
    Route::get('/users', 'UsersController@index');
    Route::get('/users/{id}', 'UsersController@show');
});

Route::get('/setup', 'Tools\SetupController@index');
Route::get('/admin', 'Tools\SetupController@addAdmin');


