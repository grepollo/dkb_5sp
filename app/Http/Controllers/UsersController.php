<?php

namespace App\Http\Controllers;

use App\Item;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $role = session('user.role');
        $data['users'] = [];
        $data['limit'] = isset($params['limit']) ? $params['limit'] : 5;
        $data['skip'] = isset($params['skip']) ? $params['skip'] : 0;
        if ($role == 'M') {
            //get users list for the manager
        } else {
            //get all
            $option = [ 'limit' => $data['limit'], 'skip' => $data['skip']];
            $response = $this->person->all($option);

            if (! empty($response)) {
                foreach($response as $row) {
                        //get users report count
                        $reports= $this->report->getReportsByPerson($row['id']);
                        $row['totalIReport'] = 0;
                        $row['totalGReport'] = 0;
                        if (! empty($reports)) {
                            foreach($reports as $srow) {
                                if ($srow['report_type'] == 0) {
                                    $row['totalIReport'] += 1;
                                } else {
                                    $row['totalGReport'] += 1;
                                }
                            }
                        }
                        $data['users'][] = $row;
                }
            }
        }

        return view('users', $data);
    }

    public function show($id, Request $request)
    {
        $id = my_decode($id);
        //get user info
        $data['user'] = $this->person->get('person_'. $id);
        //get user report count
        $reports= $this->report->getReportsByPerson($id);
        $data['totalIReport'] = 0;
        $data['totalGReport'] = 0;
        if (! empty($reports)) {
            foreach($reports as $srow) {
                if ($srow['report_type'] == 0) {
                    $data['totalIReport'] += 1;
                } else {
                    $data['totalGReport'] += 1;
                }
            }
        }
        //get list of individual reports

        //get list of group reports


        return view('users_profile', $data);
    }

    public function autocomplete(Request $request)
    {

    }
}
