<?php

namespace App;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class CbModel
{
    public $cc;
    public $cb;

    public function __construct()
    {
        $this->cc= new \CouchbaseCluster(env('CB_HOST', 'couchbase://localhost'));
        $this->cb= $this->cc->openBucket(env('CB_BUCKET', '5sportal'));
    }

    public function respondWithItem($item, $callback)
    {
        $fractal = new Manager();
        $resource = new Item($item, $callback);
        $rootScope = $fractal->createData($resource);

        return $rootScope->toArray();
    }

    public function respondWithCollection($collection, $callback)
    {
        $fractal = new Manager();
        $resource = new Collection($collection, $callback);
        $rootScope = $fractal->createData($resource);

        return $rootScope->toArray();
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
            $resp['error'] = $e->getMessage();
        }

        return $id;
    }

    public function update($docId, $data)
    {
        try {
            $resp = (array)$this->cb->replace($docId, $data);


        } catch(\CouchbaseException $e) {

            $resp['error'] = $e->getMessage();
        }

        return $resp;

    }

    public function insert($docId, $data)
    {
        try {
            $resp = (array)$this->cb->upsert($docId, $data);

        } catch(\CouchbaseException $e) {

            $resp['error'] = $e->getMessage();
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

            $resp['error'] = $e->getMessage();
        }

        return $resp;

    }

    public function delete($docId)
    {
        try {
            $resp = (array)$this->cb->remove($docId);

        } catch(\CouchbaseException $e) {

            $resp['error'] = $e->getMessage();
        }

        return $resp;

    }

}
