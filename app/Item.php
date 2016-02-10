<?php

namespace App;

class Item extends CbModel
{
    protected $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "item";
    }

    public function getItemsByReport($reportId, $params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('reports_items', 'by_report')
                ->key($reportId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('reports_items', 'by_report')->key($reportId);
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
