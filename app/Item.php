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

    public function getItemsByReport($reportId)
    {
        $query = \CouchbaseViewQuery::from('reports_items', 'by_report')->key($reportId);
        $data = [];
        try {
            $response = $this->cb->query($query, null, true);
            if (! empty($response['rows'])) {
                foreach($response['rows'] as $item) {
                    $data[] = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {
            dd($e->getMessage());
        }

        return $data;
    }
}
