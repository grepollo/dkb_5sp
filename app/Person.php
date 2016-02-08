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

        $result = [];
        try {
            $res = $this->cb->query($query, null, true);
            if (! empty($res)) {
                $result['totalRecords'] = $res['total_rows'];
                foreach($res['rows'] as $item) {
                    $result['data'][] = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {

            $result['error'] = $e->getMessage();
        }

        return $result;
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

            $persons['error'] = $e->getMessage();
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

            $person['error'] = $e->getMessage();
        }

        return $person;
    }




}
