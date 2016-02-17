<?php

namespace App;

class Group extends CbModel
{
    protected $type = 'family';

    protected $fillable = ['members', 'report_id'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getGroupsByReport($reportId, $params = [])
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('family', 'by_report')
                ->stale(1)
                ->key($reportId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('family', 'by_report')
                ->stale(1)
                ->key($reportId);
        }

        $result = [];
        try {
            $res = $this->cb->query($query, null, true);
            if (! empty($res)) {
                $result['items'] = [];
                $count = 0;
                foreach($res['rows'] as $item) {
                    $result['items'][] = $item['value'];
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
