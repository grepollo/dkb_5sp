<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Person;
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

        if (!empty($tables)) {
            foreach ($tables as $table) {
                $table = array_values(get_object_vars($table))[0];
                $data = $this->getData($table);
                if (!empty($data)) {
                    foreach ($data as $row) {
                        $item = (array)$row;
                        $docId = $table . '_' . $item['id'];
                        if ($table == 'person') {
                            $item['role'] = $item['type'];
                            $item['type'] = $table;
                            $myBucket->insert($docId, $item);
                        } elseif ($table == 'report') {
                            $item['report_type'] = $item['type'];
                            $item['type'] = $table;
                            $myBucket->insert($docId, $item);
                        } else {
                            $item['type'] = $table;
                            $myBucket->insert($docId, $item);
                        }

                        echo 'Inserting document: ' . $docId . '<br/>';
                    }
                }
            }
        }
    }

    private function getData($table)
    {
        return DB::select('select * from ' . $table);
    }

    /**
     * Add admin user
     */
    public function addAdmin()
    {
        $person = new Person();
        $data = [
            "id"         => $person->counter('person_counter', ['value' => 1]),
            "first_name" => "admin",
            "last_name"  => "admin",
            "gender"     => "m",
            "username"   => "admin",
            "email"      => "admin@456.com",
            "password"   => bcrypt('admin'),
            "userimage"  => "",
            "country"    => "",
            "created"    => "",
            "occupation" => "",
            "type"       => "person"
        ];

        $person->insert('person_' . $data['id'], $data);

        return response(['Admin added.']);
    }


}
