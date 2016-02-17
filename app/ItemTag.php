<?php

namespace App;

class ItemTag extends CbModel
{
    protected $type = 'tags';

    protected $fillable = ['tag', 'item_id'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getTagsByItem($itemId, $params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $skip = isset($params['skip']) ? $params['skip'] : 0;
        if (isset($params['limit'])) {
            $query = \CouchbaseViewQuery::from('item', 'item_tags')
                ->stale(1)
                ->key($itemId)
                ->limit($limit)->skip($skip);
        } else {
            $query = \CouchbaseViewQuery::from('item', 'item_tags')
                ->stale(1)
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
