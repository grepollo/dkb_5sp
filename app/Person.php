<?php

namespace App;

class Person extends CbModel
{
    protected $type = "person";

    protected $fillable = [
        'type', 'username', 'password', 'email', 'first_name', 'last_name', 'gender', 'userimage',
        'role', 'occupation', 'city', 'state', 'country'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function all($params = [])
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

    public function getAssignedPersons($managerID, $params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('manager_users', 'by_manager')
                ->key($managerID)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('manager_users', 'by_manager')
                ->key($managerID);
        }

        $result = [];
        try {
            $res = $this->cb->query($query, null, true);
            if (! empty($res)) {
                $result['data'] = [];
                $count = 0;
                foreach($res['rows'] as $item) {
                    $result['data'][] = $item['value'];
                    $count++;
                }
                $result['totalRecords'] = $count;
            }
        } catch(\CouchbaseException $e) {

            $result['error'] = $e->getMessage();
        }

        return $result;
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
