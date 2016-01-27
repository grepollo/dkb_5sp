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

    $myCluster = new CouchbaseCluster('couchbase://localhost');
    $myBucket = $myCluster->openBucket('5sportal');
    $query = CouchbaseViewQuery::from('person', 'username')->key("K");
    try {
        $res = $myBucket->query($query, null, true);
        if (! empty($res)) {
            $doc = [];
            foreach($res['rows'] as $item) {
                $doc[] = $item['value'];
            }
            $items = $myBucket->get($doc);
            foreach ($items as $item) {
                dd((array)$item->value);
            }
        }
    } catch(CouchbaseException $e) {
        dd($e->getMessage());
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
    //
});

Route::get('/setup', 'Tools\SetupController@index');
