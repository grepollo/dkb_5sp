<?php

namespace App\Http\Controllers\Api;

use App\Person;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;
use Transformers\UsersTransformer;

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
        $params = $request->all();
        $response = $this->report->individual($userId);
        $data = ['individual' => [], 'group' => []];
        if (! isset($response['error'])) {
            foreach ($response['data'] as $item) {
                $item['id'] = my_encode($item['id']);
                $data['individual'][] = $item;
            }
        }
        $response = $this->report->group($userId);
        if (! isset($response['error'])) {
            foreach ($response['data'] as $item) {
                $item['id'] = my_encode($item['id']);
                $data['group'][] = $item;
            }
        }

        return response(['data' => $data]);
    }

    public function show($id, Request $request)
    {
        $id = my_decode($id);
        //get user info
        $data = $this->report->get('report_'. $id);
        if (! isset($data['error'])) {

            return response($this->report->respondWithItem($data, new ReportTransformer));
        }

        return response(['error' => $data['error']]);
    }

    /**
     * Create new user
     *
     * @param Requests\AddUserRequest|Request $request
     */
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'username' => 'bail|required', 'password' => 'required',
            'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|email',
        ]);

        if ($validator->fails()) {

            return response(['error' => $validator->errors()->getMessages()]);
        }

        //check if username does not exist
        $params = $request->all();
        $resp = $this->person->getUsername($params['username']);
        if (! empty($resp)) {

            return response(['error' => 'Username already exist.']);
        }
        //init default values
        $params['id'] = $this->person->counter('person_counter', ['initial' => 1000, 'value' => 1]);
        $params['type'] = 'person';
        $params['role'] = 'U';
        $params['created'] = Carbon::now()->toDateTimeString();

        $resp = $this->person->insert('person_' . $params['id'], $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'User created.',
                'data' => $this->report->respondWithItem($params, new UsersTransformer )
            ]);
        }

        //error occur rollback counter
        $params['id'] = $this->person->counter('person_counter', ['initial' => 1000, 'value' => -1]);

        return response(['error' => $resp['error']]);

    }

    /**
     * Update a user
     *
     * @param         $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $id = 'person_' . my_decode($id);
        $params = $request->all();
        //get all info
        $user = $this->person->get($id);
        $user = array_merge($user, $params);

        $resp = $this->person->update($id, $user);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'User updated.',
                'data' => $this->report->respondWithItem($user, new UsersTransformer )
            ]);
        }

        return response(['error' => $resp['error']]);
    }

    /**
     * Delete a user
     *
     * @param         $id
     * @param Request $request
     */
    public function destroy($id)
    {
        $id = 'person_' . my_decode($id);
        $resp = $this->person->delete($id);
        if (! isset($resp['error'])) {

            return response(['success' => 'User deleted.']);
        }

        return response(['error' => $resp['error']]);
    }
}
