<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    /**
     * Migrate existing mysql db to couchbase
     *
     */
    public function index()
    {

        $myCluster = new \CouchbaseCluster('couchbase://localhost');
        $myBucket = $myCluster->openBucket('5sportal');
        $tables = DB::select('show tables');
        if (! empty($tables)) {
            foreach($tables as $table) {
                $table = array_values(get_object_vars($table))[0];
                $data = $this->getData($table);
                if (! empty($data)) {
                    foreach($data as $row) {
                        $item = (array)$row;
                        $docId = $table . '_' . $item['id'];
                        $item['type'] = $table;
                        $myBucket->insert($docId, $item);
                        echo 'Inserting document: '. $docId . '<br/>';
                    }
                }
            }
        }
    }

    private function getData($table)
    {
        return DB::select('select * from ' . $table);
    }
}
