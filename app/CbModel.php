<?php

namespace App;

use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class CbModel
{
    protected $cc;
    protected $cb;
    protected $type;
    protected $fillable = [];

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
        return $rootScope->toArray()['data'];
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

    public function update($id, $data)
    {
        $id = my_decode($id);
        $docId = $this->type . '_' . $id;
        //get old records
        $info = $this->cb->get($docId);
        $data = array_replace((array)$info->value, $this->fill($data));
        $data['updated'] = Carbon::now()->toDateTimeString();
        try {
            $resp = (array)$this->cb->replace($docId, $data);
            if (empty($resp['error'])) {
                $resp = $data;
            }
        } catch(\CouchbaseException $e) {

            $resp['error'] = $e->getMessage();
        }

        return $resp;

    }

    public function insert($id, $data)
    {
        $docId = $this->type . '_' . $id;
        $data = $this->fill($data);
        $data['id'] = $id;
        $data['type'] = $this->type;
        $data['created'] = Carbon::now()->toDateTimeString();
        try {
            $resp = (array)$this->cb->upsert($docId, $data);
            if (empty($resp['error'])) {
                $resp = $data;
            }
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

    /**
     * Fill array of data base on fillable fields
     * @param $data
     *
     * @return array
     */
    private function fill($data)
    {
        $filtered =  array_intersect_key($data, array_flip($this->fillable));

//        foreach($filtered as $field => $val) {
//            if (empty($val)) { pr($field, false);
//                unset($filtered[$field]);
//            }
//        }

        return $filtered;
    }

}
