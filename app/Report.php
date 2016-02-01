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

    public function getReportsByPerson($personId)
    {
        $query = \CouchbaseViewQuery::from('persons_report', 'by_person')->key($personId);
        $data = [];
        try {
            $response = $this->cb->query($query, null, true);
            if (! empty($response['rows'])) {
                foreach($response['rows'] as $item) {
                    $data[] = $item['value'];
                }
            }
        } catch(\CouchbaseException $e) {
            $response = [];
            dd($e->getMessage());
        }

        return $data;
    }
}
