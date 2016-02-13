<?php

namespace App\Http\Controllers\Api;

use App\OauthCustomSession;
use App\Person;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\UserTransformer;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $session = OauthCustomSession::find(get_token($request));
        if ($session->role == 'U') {
            return response(['error' => 'User not authorize to this resource.']);
        }
        $data['items'] = [];
        $data['totalRecords'] = 0;
        $data['limit'] = isset($params['limit']) ? $params['limit'] : 5;
        $data['skip'] = isset($params['skip']) ? $params['skip'] : 0;
        //get all
        $option = ['limit' => $data['limit'], 'skip' => $data['skip']];
        $response = $this->person->all($option);
        if (!isset($response['error'])) {
            $data['totalRecords'] = $response['totalRecords'];
            foreach ($response['data'] as $row) {
                //get users report count
                $reports = $this->report->getReportsByPerson($row['id']);
                $row['id'] = my_encode($row['id']);
                $row['totalIReport'] = 0;
                $row['totalGReport'] = 0;
                if (!empty($reports['data'])) {
                    foreach ($reports['data'] as $srow) {

                        if ($srow['report_type'] == 0) {
                            $row['totalIReport'] += 1;
                        } else {
                            $row['totalGReport'] += 1;
                        }

                    }
                }
                $data['items'][] = $row;
            }

            return response(['data' => $data]);
        } else {

            return response(['error' => $response['error']]);
        }
    }

    public function show($id, Request $request)
    {
        $id = (int)my_decode($id);
        //get user info
        $data = $this->person->get('person_' . $id);
        if (!isset($data['error'])) {
            //get user report count
            $reports = $this->report->individual($id);
            $data['totalIReport'] = isset($reports['data']) ? count($reports['data']) : 0;
            $reports = $this->report->group($id);
            $data['totalGReport'] = isset($reports['data']) ? count($reports['data']) : 0;
            return response(['data' => $this->report->respondWithItem($data, new UserTransformer)]);
        }

        return response(['error' => $data['error']]);
    }

    /**
     * Create new user
     *
     * @param Requests\AddUserRequest|Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username'   => 'bail|required', 'password' => 'required',
            'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|email',
        ]);

        if ($validator->fails()) {

            return response(['error' => $validator->errors()->getMessages()]);
        }

        //check if username does not exist
        $params = $request->all();
        $resp = $this->person->getUsername($params['username']);
        if (!empty($resp)) {

            return response(['error' => 'Username already exist.']);
        }
        //init default values
        $id = $this->person->counter('person_counter', ['initial' => 1000, 'value' => 1]);
        $params['type'] = 'person';
        $params['role'] = 'U';
        $params['password'] = bcrypt($params['password']);

        $resp = $this->person->insert($id, $params);
        if (!isset($resp['error'])) {
            return response([
                'success' => 'User created.',
                'data'    => $this->report->respondWithItem($params, new UserTransformer)
            ]);
        }

        //error occur rollback counter
        $id = $this->person->counter('person_counter', ['initial' => 1000, 'value' => -1]);

        return response(['error' => $resp['error']]);

    }

    /**
     * Update a user
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $params = $request->all();
        $resp = $this->person->update($id, $params);
        if (!isset($resp['error'])) {
            return response([
                'success' => 'User updated.',
                'data'    => $this->report->respondWithItem($resp, new UserTransformer)
            ]);
        }

        return response(['error' => $resp['error']]);
    }

    /**
     * Delete a user
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $id = 'person_' . my_decode($id);
        $resp = $this->person->delete($id);
        if (!isset($resp['error'])) {

            return response(['success' => 'User deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
