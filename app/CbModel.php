<?php

namespace App;

class CbModel
{
    public $cc;
    public $cb;

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

    public function update($docId, $data)
    {
        try {
            $resp = $this->cb->replace($docId, $data);


        } catch(\CouchbaseException $e) {

            dd($e->getMessage());
        }

        return $resp;

    }

    public function insert($docId, $data)
    {
        try {
            $resp = $this->cb->upsert($docId, $data);

        } catch(\CouchbaseException $e) {

            dd($e->getMessage());
        }

        return $resp;

    }

    public function get($docId)
    {
        $resp = [];
        try {
            $resp = $this->cb->get($docId);
            $resp = (array)$resp->value;
        } catch(\CouchbaseException $e) {

            dd($e->getMessage());
        }

        return $resp;

    }

}
