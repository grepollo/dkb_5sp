<?php

namespace App;

class ItemData extends CbModel
{
    protected $type = 'data';

    protected $fillable = ['item_id', 'person_id', 'media', 'location'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataByItem($itemId, $params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('item', 'item_data')
                ->key($itemId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('item', 'item_data')
                ->key($itemId);
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
