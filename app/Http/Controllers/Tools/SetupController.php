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
                            $myBucket->upsert($docId, $item);
                        } elseif ($table == 'report') {
                            $item['report_type'] = $item['type'];
                            $item['type'] = $table;
                            $myBucket->upsert($docId, $item);
                        } elseif ($table == 'family') {
                            //do nothing
                        } else {
                            $item['type'] = $table;
                            $myBucket->upsert($docId, $item);
                        }
                        echo 'Inserting document: ' . $docId . '<br/>';
                    }
                    $members = $this->processReportMembers();
                    //delete existing family in the document
                    $query = \CouchbaseViewQuery::from('family', 'by_report');
                    $res = $myBucket->query($query, null, true);
                    foreach ($res['rows'] as $row) {
                        $myBucket->remove($row['id']);
                    }
                    foreach($members as $id => $val) {
                        $tmp = explode('_', $id);
                        $item = [
                            'type' => 'family',
                            'report_id' =>(int) end($tmp),
                            'members' => $val
                        ];
                        $myBucket->upsert($id, $item);
                    }
                }
            }
        }
        return 'Setup done.';
    }

    private function getData($table)
    {
        return DB::select('select * from ' . $table);
    }

    private function processReportMembers()
    {
        $reports = DB::select('select * from report where type = 1');
        $data = [];
        foreach($reports as $report)
        {
            $members = DB::select('select * from family where report_id = ' . $report->id);
            foreach($members as $member) {
                $data['family_' . $report->id][] = $member->id;
            }
        }

       return $data;

    }

    /**
     * Add admin user
     */
    public function addAdmin()
    {
        $person = new Person();
        $data = [
            "id"         => $person->counter('person_counter', ['initial' => 100, 'value' => 1]),
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
            "role"       => "A",
            "type"       => "person"
        ];

        $person->insert('person_' . $data['id'], $data);

        return response(['Admin added.']);
    }


}
