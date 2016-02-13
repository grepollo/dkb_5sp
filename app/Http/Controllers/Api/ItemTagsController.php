<?php

namespace App\Http\Controllers\Api;

use App\ItemTag;
use App\OauthCustomSession;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ItemTagTransformer;

class ItemTagsController extends Controller
{
    public function __construct()
    {
        $this->model = new ItemTag();
    }

    public function index($itemId, Request $request)
    {
        $itemId = (int)my_decode($itemId);
        $params = $request->all();

        $data['items'] = [];
        $data['totalRecords'] = 0;
        $data['limit'] = isset($params['limit']) ? $params['limit'] : 5;
        $data['skip'] = isset($params['skip']) ? $params['skip'] : 0;
        //get all
        $option = ['limit' => $data['limit'], 'skip' => $data['skip']];
        $response = $this->model->getTagsByItem($itemId, $option);
        if (!isset($response['error'])) {
            $response['items'] = $this->model->respondWithCollection($response['items'], new ItemTagTransformer);
            return response(['data' => $response]);
        } else {

            return response(['error' => $response['error']]);
        }
    }

    public function show($id, Request $request)
    {
        $id = my_decode($id);
        $data = $this->model->get('tags_'. $id);
        if (! isset($data['error'])) {

            return response(['data' => $this->model->respondWithItem($data, new ItemTagTransformer)]);
        }

        return response(['error' => $data['error']]);
    }

    /**
     * Create new report
     *
     * @param Requests\AddItemRequest|Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validator = \Validator::make($request->all(), [
            'comment' => 'bail|required', 'item_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->getMessages()]);
        }
        //init default values
        $id = $this->model->counter('tag_counter', ['initial' => 1000, 'value' => 1]);
        $params['item_id'] = (int)my_decode($params['item_id']);
        $resp = $this->model->insert($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Tag created.',
                'data' => $this->model->respondWithItem($resp, new ItemTagTransformer)
            ]);
        }

        //error occur rollback counter
        $params['id'] = $this->model->counter('tag_counter', ['initial' => 1000, 'value' => -1]);

        return response(['error' => $resp['error']]);

    }

    /**
     * Update a report
     *
     * @param         $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $params = $request->all();
        $resp = $this->model->update($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Tag updated.',
                'data' => $this->model->respondWithItem($resp, new ItemTagTransformer)
            ]);
        }

        return response(['error' => $resp['error']]);
    }

    /**
     * Delete a report
     *
     * @param         $id
     * @param Request $request
     */
    public function destroy($id)
    {
        $id = 'tags_' . my_decode($id);
        $resp = $this->model->delete($id);
        if (! isset($resp['error'])) {

            return response(['success' => 'Tag deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
