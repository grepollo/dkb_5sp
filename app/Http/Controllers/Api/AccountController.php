<?php

namespace App\Http\Controllers\Api;

use App\Person;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;
use Transformers\UserTransformer;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
    }

    /**
     * Register new account
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
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
        $params['role'] = 'U';
        $params['password'] = bcrypt($params['password']);
        $resp = $this->person->insert($id, $params);
        if (! isset($resp['error'])) {
            return response([
                'success' => 'Account created.',
                'data' => $this->person->respondWithItem($resp, new UserTransformer)
            ]);
        }

        //error occur rollback counter
        $params['id'] = $this->person->counter('person_counter', ['initial' => 1000, 'value' => -1]);

        return response(['error' => $resp['error']]);
    }

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request)
    {

    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {

    }

}
