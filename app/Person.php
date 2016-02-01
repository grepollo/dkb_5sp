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

    public function all($params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('person', 'username')->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('person', 'username');
        }

        $data = [];
        try {
            $res = $this->cb->query($query, null, true);
            if (! empty($res)) {

                foreach($res['rows'] as $item) {
                    $data[] = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {
            dd($e->getMessage());
        }

        return $data;
    }

    public function getAssignedPersons($managerID)
    {
        $query = \CouchbaseViewQuery::from('manager_users', 'by_manager')->key($managerID);
        $persons = [];
        try {
            $response = $this->cb->query($query, null, true);
            if (! empty($response['rows'])) {
                foreach($response['rows'] as $item) {
                    $persons[] = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {
            $persons = [];
            dd($e->getMessage());
        }

        return $persons;
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
