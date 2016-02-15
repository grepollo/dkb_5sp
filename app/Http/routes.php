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
    //Reports
    Route::get('/reports/{id}', 'ReportsController@show');
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
        $resp['user'] = session()->get('user');
        //store to custom oauth session
        $data = [
            'id' => $resp['access_token'],
            'person_id' => my_decode($resp['user']['id']),
            'username' => $resp['user']['username'],
            'role' => $resp['user']['role'],
        ];
        \App\OauthCustomSession::create($data);
    }

    return Response::json($resp);
});

//public api
Route::group(['prefix' => 'api'], function(){
    Route::post('account/register', 'Api\AccountController@register');
    Route::post('account/forgot_password', 'Api\AccountController@forgotPassword');
});

Route::group(['middleware' => ['api', 'oauth'], 'prefix' => 'api'], function () {
    Route::resource('users', 'Api\UsersController', ['except' => ['create', 'edit']]);
    Route::get('users/{userId}/reports', 'Api\ReportsController@index');
    Route::resource('reports', 'Api\ReportsController', ['except' => ['index', 'create', 'edit']]);
    Route::get('reports/{reportId}/items', 'Api\ItemsController@index');
    Route::resource('items', 'Api\ItemsController', ['except' => ['index', 'create', 'edit']]);
    Route::get('items/{itemId}/comments', 'Api\ItemCommentsController@index');
    Route::resource('comments', 'Api\ItemCommentsController', ['except' => ['index', 'create', 'edit']]);
    Route::get('items/{itemId}/tags', 'Api\ItemTagsController@index');
    Route::resource('tags', 'Api\ItemTagsController', ['except' => ['index', 'create', 'edit']]);
    Route::get('items/{itemId}/data', 'Api\ItemDataController@index');
    Route::resource('data', 'Api\ItemDataController', ['except' => ['index', 'create', 'edit']]);
});
