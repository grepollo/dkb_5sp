<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\OauthCustomSession;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ItemTransformer;

class ItemsController extends Controller
{
    public function __construct()
    {
        $this->item = new Item();
    }

    public function index($reportId, Request $request)
    {
        $reportId = (int)my_decode($reportId);
        $params = $request->all();

        $data['items'] = [];
        $data['totalRecords'] = 0;
        $data['limit'] = isset($params['limit']) ? $params['limit'] : 5;
        $data['skip'] = isset($params['skip']) ? $params['skip'] : 0;
        //get all
        $option = ['limit' => $data['limit'], 'skip' => $data['skip']];
        $response = $this->item->getItemsByReport($reportId, $option);
        if (!isset($response['error'])) {
            $response['items'] = $this->item->respondWithCollection($response['items'], new ItemTransformer);
            return response(['data' => $response]);
        } else {

            return response(['error' => $response['error']]);
        }
    }

    public function show($id, Request $request)
    {
        $id = my_decode($id);
        $data = $this->item->get('item_'. $id);
        if (! isset($data['error'])) {

            return response(['data' => $this->item->respondWithItem($data, new ItemTransformer)]);
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
        $session = OauthCustomSession::find(get_token($request));
        $params = $request->all();
        $validator = \Validator::make($request->all(), [
            'title' => 'bail|required', 'description' => 'required', 'report_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->getMessages()]);
        }
        //init default values
        $id = $this->item->counter('item_counter', ['initial' => 1000, 'value' => 1]);
        $params['person_id'] = (int)$session->person_id;
        $params['report_id'] = (int)my_decode($params['report_id']);
        $params['is_archive'] = isset($params['is_archive']) ? $params['is_archive'] : 'N';
        $resp = $this->item->insert($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Item created.',
                'data' => $this->item->respondWithItem($resp, new ItemTransformer)
            ]);
        }

        //error occur rollback counter
        $params['id'] = $this->item->counter('item_counter', ['initial' => 1000, 'value' => -1]);

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
        $resp = $this->item->update($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Item updated.',
                'data' => $this->item->respondWithItem($resp, new ItemTransformer)
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
        $id = 'item_' . my_decode($id);
        $resp = $this->item->delete($id);
        if (! isset($resp['error'])) {

            return response(['success' => 'Item deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
