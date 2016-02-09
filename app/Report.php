<?php

namespace App;

class Report extends CbModel
{
    protected $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "report";
    }

    public function getReportsByPerson($personId, $params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('persons_report', 'by_person')
                ->key($personId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('persons_report', 'by_person')
                ->key($personId);
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

    public function individual($personId, $params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('individual_reports', 'by_person')
                ->key($personId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('individual_reports', 'by_person')
                ->key($personId);
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

    public function group($personId, $params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('group_reports', 'by_person')
                ->key($personId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('group_reports', 'by_person')
                ->key($personId);
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
}
