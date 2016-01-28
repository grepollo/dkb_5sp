<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Person;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Authenticate user
     * @param Request $request
     */
    public function attempt(LoginRequest $request)
    {
        $params = $request->only('username', 'password');
        $person = new Person();
        $resp = $person->getUsername($params['username']);
        if (! empty($resp)) {
            if (\Hash::check($params['password'], $resp['password'])) {
                $request->session()->push('user', [
                    'id' => $resp['id'],
                    'username' => $resp['username'],
                    'role' => $resp['role'],
                    'disp_name' => $resp['role'] == 'A' ? 'Administrator' : $resp['first_name'] . ' ' . $resp['last_name'],
                ]);
                $message = 'success';
            } else {
                //check for old hashing
                if (md5('K') == $resp['password']) {
                    //convert old pass to new hashing
                    $resp['password'] = bcrypt($params['password']);
                    $id = 'person_' . $resp['id'];
                    $person->update($id, $resp);
                    $request->session()->push('user', [
                        'id' => $resp['id'],
                        'username' => $resp['username'],
                        'role' => $resp['role'],
                        'disp_name' => $resp['role'] == 'A' ? 'Administrator' : $resp['first_name'] . ' ' . $resp['last_name'],
                    ]);
                    $message = 'success';
                } else {
                    //invalid password
                    $message = 'invalid';
                }
            }
        } else {
            //invalid user
            $message = 'invalid';
        }

        return response(['login_status' => $message]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/');
    }
}
