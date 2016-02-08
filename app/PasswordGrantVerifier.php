<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'username'    => $username,
            'password' => $password,
        ];
        $person = new Person();
        $resp = $person->getUsername($credentials['username']);
        if (! empty($resp)) {
            if (\Hash::check($credentials['password'], $resp['password'])) {
                $auth = true;
            } else {
                //check for old hashing
                if (md5($credentials['password']) == $resp['password']) {
                    //convert old pass to new hashing
                    $resp['password'] = bcrypt($credentials['password']);
                    $id = 'person_' . $resp['id'];
                    $person->update($id, $resp);
                    $auth = true;
                } else {
                    $auth = false;
                }
            }
        } else {
            //invalid user
            $auth = false;
        }

        if ($auth) {
            session()->put('user', [
                'id' => $resp['id'],
                'username' => $resp['username'],
                'role' => $resp['role'],
                'disp_name' => $resp['role'] == 'A' ? 'Administrator' : $resp['first_name'] . ' ' . $resp['last_name'],
            ]);

            return my_decode($resp['id']);
        }

        return false;
    }
}