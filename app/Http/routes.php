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
    $this->cc = new \CouchbaseCluster(env('CB_HOST', 'couchbase://localhost'), 'admin', 'password');
    $this->cb = $this->cc->openBucket(env('CB_BUCKET', '5sportal'));
    pr($this->cb->get('person_2'));



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


/*
|--------------------------------------------------------------------------
| API Resource
|--------------------------------------------------------------------------
|
| This route contains all the resource of the api
*/
Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});
