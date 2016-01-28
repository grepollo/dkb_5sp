<?php

namespace App;

class Person extends CbModel
{
    protected $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "person";
    }

    public function getUsername($username)
    {
        $query = \CouchbaseViewQuery::from('person', 'username')->key($username);
        $person = [];
        try {
            $res = $this->cb->query($query, null, true);
            if (! empty($res)) {

                foreach($res['rows'] as $item) {
                    $person = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {
            dd($e->getMessage());
        }

        return $person;
    }

    public function update($docId, $data)
    {
        try {
            $resp = $this->cb->replace($docId, $data);


        } catch(\CouchbaseException $e) {

            dd($e->getMessage());
        }

        return $resp;

    }

    public function insert($docId, $data)
    {
        try {
            $resp = $this->cb->upsert($docId, $data);

        } catch(\CouchbaseException $e) {

            dd($e->getMessage());
        }

        return $resp;

    }


}
