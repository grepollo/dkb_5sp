<?php

namespace App;

class Item extends CbModel
{
    protected $type = 'item';

    protected $fillable = ['title', 'description', 'comment', 'is_archive', 'person_id', 'report_id'];

    public function __construct()
    {
        parent::__construct();
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
            $query = \CouchbaseViewQuery::from('reports_items', 'by_report')
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
