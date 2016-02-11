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
//    $this->cc = new \CouchbaseCluster(env('CB_HOST', 'couchbase://localhost'), 'admin', 'password');
//    $this->cb = $this->cc->openBucket(env('CB_BUCKET', '5sportal'));
//    pr($this->cb->get('person_2'));
    pr(my_encode(112));



});

//Route::get('/', function () {
//    return view('welcome');
//});

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
| API Resource (REST)
|--------------------------------------------------------------------------
|
| This route contains all the resource of the api
*/
Route::post('oauth/access_token', function() {
    $resp = Authorizer::issueAccessToken();
    if ($resp) {
        session()->put($resp['access_token'], session()->get('user'));
    }

    return Response::json($resp);
});

Route::group(['middleware' => ['api', 'oauth'], 'prefix' => 'api'], function () {
    Route::resource('users', 'Api\UsersController', ['except' => ['create', 'edit']]);
    Route::resource('users.reports', 'Api\ReportsController', ['except' => ['create', 'edit']]);
    Route::resource('reports.items', 'Api\ItemsController', ['except' => ['create', 'edit']]);
});
