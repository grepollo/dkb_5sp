<?php

namespace App\Http\Controllers\Api;

use App\Person;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;
use Transformers\ReportsTransformer;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
    }

    public function index($reportId, Request $request)
    {
        $reportId = (int)my_decode($reportId);
        $params = $request->all();
        $response = $this->report->individual($reportId);
        $data = ['individual' => [], 'group' => []];
        if (! isset($response['error'])) {
            $data['individual'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
        }
        $response = $this->report->group($reportId);
        if (! isset($response['error'])) {
            $data['group'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
        }

        return response(['data' => $data]);
    }

    public function show($reportId, $id, Request $request)
    {
        $id = my_decode($id);
        $data = $this->report->get('report_'. $id);
        if (! isset($data['error'])) {

            return response($this->report->respondWithItem($data, new ReportTransformer));
        }

        return response(['error' => $data['error']]);
    }

    /**
     * Create new report
     *
     * @param Requests\AddReportRequest|Request $request
     */
    public function store($reportId, Request $request)
    {
        pr(session()->all());
        pr(session(get_token($request)));
        $reportId = my_decode($reportId);
        $validator = \Validator::make($request->all(), [
            'name' => 'bail|required', 'description' => 'required'
        ]);
        if ($validator->fails()) {

            return response(['error' => $validator->errors()->getMessages()]);
        }

        //check if reportname does not exist
        $params = $request->all();
        //get author
        $report = $this->person->get('person_' . $reportId);
        //init default values
        $params['id'] = $this->report->counter('report_counter', ['initial' => 1000, 'value' => 1]);
        $params['person_id'] = (int)$reportId;
        $params['author'] = isset($report['reportname']) ? $report['reportname'] : '';
        $params['type'] = 'report';
        $params['report_type'] = isset($params['report_type']) ? (int)$params['report_type'] : 0;
        $params['is_archive'] = isset($params['is_archive']) ? $params['is_archive'] : 'N';
        $params['created'] = Carbon::now()->toDateTimeString();
        $resp = $this->report->insert('report_' . $params['id'], $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report created.',
                'data' => $this->report->respondWithItem($params, new ReportTransformer)
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
    public function update($userID, $id, Request $request)
    {
        $id = 'report_' . my_decode($id);
        $params = $request->all();
        $params['report_type'] = isset($params['report_type']) ? (int)$params['report_type'] : 0;
        //get all info
        $report = $this->report->get($id);

        $report = array_merge($report, $params);

        $resp = $this->report->update($id, $report);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Report updated.',
                'data' => $this->report->respondWithItem($report, new ReportTransformer )
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
