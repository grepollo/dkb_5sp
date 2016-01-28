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
Route::get('/admin', function(){
    $myCluster = new CouchbaseCluster('couchbase://localhost');
    $myBucket = $myCluster->openBucket('5sportal');
$resp = $myBucket->get("admin_1");
    dd((array)$resp->value);

});
Route::get('/test', function() {
$cb = new \App\CbModel();
    $id = $cb->counter('person_counter', ['initial' => 100, 'value' => 1]);
    dd($id);
    $data =
//    $myCluster = new CouchbaseCluster('couchbase://localhost');
//    $myBucket = $myCluster->openBucket('5sportal');
//    $query = CouchbaseViewQuery::from('person', 'username')->key("K");
//    try {
//        $res = $myBucket->query($query, null, true);
//        if (! empty($res)) {
//            $doc = [];
//            foreach($res['rows'] as $item) {
//                $doc[] = $item['value'];
//            }
//            $items = $myBucket->get($doc);
//            foreach ($items as $item) {
//                dd((array)$item->value);
//            }
//        }
//    } catch(CouchbaseException $e) {
//        dd($e->getMessage());
//    }
    $person = new \App\Person();
    $resp = $person->getUsername("K");
    var_dump($resp);

    if (md5('K') == $resp['password']) {
        $resp = \Hash::check('K', $resp['password']);
        dd('test');
        dd('success');
    } else {
        $resp = \Hash::check('K', $resp['password']);
        dd($resp);
        dd('failed');
    }

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
    Route::post('login', 'LoginController@attempt');
    Route::get('logout', 'LoginController@logout');
    Route::get('dashboard', 'DashboardController@index');
});

Route::get('/setup', 'Tools\SetupController@index');
Route::get('/admin', 'Tools\SetupController@addAdmin');


