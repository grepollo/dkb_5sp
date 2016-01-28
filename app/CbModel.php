<?php

namespace App;

class CbModel
{
    protected $cc;
    protected $cb;

    public function __construct()
    {
        $this->cc= new \CouchbaseCluster(env('CB_HOST', 'couchbase://localhost'));
        $this->cb= $this->cc->openBucket(env('CB_BUCKET', '5sportal'));
    }

    public function counter($docId, $params)
    {
        $id = 1;
        try {
            if (isset($params['initial'])) {
                $resp = $this->cb->counter($docId, $params['value'], ['initial' => $params['initial']] );
            } else {
                $resp = $this->cb->counter($docId, $params['value']);
            }
            if ($resp) {
                $id = $resp->value;
            }
        } catch(\CouchbaseException $e) {
            dd($e->getMessage());
        }

        return $id;
    }

}
