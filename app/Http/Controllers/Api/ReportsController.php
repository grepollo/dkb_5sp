<?php

namespace App\Http\Controllers\Api;

use App\OauthCustomSession;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
    }

    public function index($userId, Request $request)
    {
        $userId = (int)my_decode($userId);
        $response = $this->report->individual($userId);
        $data = ['individual' => [], 'group' => []];
        if (! isset($response['error'])) {
            $data['individual'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
        }
        $response = $this->report->group($userId);
        if (! isset($response['error'])) {
            $data['group'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
        }

        return response(['data' => $data]);
    }

    public function show($id, Request $request)
    {
        $id = my_decode($id);
        $data = $this->report->get('report_'. $id);
        if (! isset($data['error'])) {

            return response(['data' => $this->report->respondWithItem($data, new ReportTransformer)]);
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
        $id = $this->report->counter('report_counter', ['initial' => 1000, 'value' => 1]);
        $params['person_id'] = (int)$session->person_id;
        $params['author'] = isset($user['username']) ? $user['username'] : '';
        $params['report_type'] = isset($params['report_type']) ? (int)$params['report_type'] : 0;
        $params['is_archive'] = isset($params['is_archive']) ? $params['is_archive'] : 'N';
        $resp = $this->report->insert($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report created.',
                'data' => $this->report->respondWithItem($resp, new ReportTransformer)
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
        $resp = $this->report->update($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report updated.',
                'data' => $this->report->respondWithItem($resp, new ReportTransformer)
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

        $resp = $this->report->delete($id);
        if (! isset($resp['error'])) {

            return response(['success' => 'Report deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
