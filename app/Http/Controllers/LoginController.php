<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Authenticate user
     * @param Request $request
     */
    public function login(Request $request)
    {
        $params = $request->all();

    }

    public function logout(Request $request)
    {

    }
}
