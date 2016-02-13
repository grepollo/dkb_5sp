<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\OauthCustomSession;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ItemTransformer;
use Transformers\ReportTransformer;

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

    public function show($reportId, $id, Request $request)
    {
        $id = my_decode($id);
        $data = $this->item->get('report_'. $id);
        if (! isset($data['error'])) {

            return response($this->item->respondWithItem($data, new ReportTransformer));
        }

        return response(['error' => $data['error']]);
    }

    /**
     * Create new report
     *
     * @param Requests\AddReportRequest|Request $request
     */
    public function store(Request $request)
    {
        $session = OauthCustomSession::find(get_token($request));

        $validator = \Validator::make($request->all(), [
            'name' => 'bail|required', 'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->getMessages()]);
        }

        //check if reportname does not exist
        $params = $request->all();
        //get author
        $user = $this->person->get('person_' . $session->person_id);
        //init default values
        $id = $this->item->counter('report_counter', ['initial' => 1000, 'value' => 1]);
        $params['person_id'] = $session->person_id;
        $params['author'] = isset($user['username']) ? $user['username'] : '';
        $params['report_type'] = isset($params['report_type']) ? (int)$params['report_type'] : 0;
        $params['is_archive'] = isset($params['is_archive']) ? $params['is_archive'] : 'N';
        $resp = $this->item->insert($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report created.',
                'data' => $this->item->respondWithItem($resp, new ReportTransformer)['data']
            ]);
        }

        //error occur rollback counter
        $params['id'] = $this->person->counter('person_counter', ['initial' => 1000, 'value' => -1]);

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
        $params['report_type'] = isset($params['report_type']) ? (int)$params['report_type'] : 0;
        $resp = $this->item->update($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report updated.',
                'data' => $this->item->respondWithItem($resp, new ReportTransformer)['data']
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
        $id = 'report_' . my_decode($id);

        $resp = $this->item->delete($id);
        if (! isset($resp['error'])) {

            return response(['success' => 'Report deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
