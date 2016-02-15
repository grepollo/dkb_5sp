<?php

namespace App\Http\Controllers;

use App\Item;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;
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
        $role = session('user.role');
        $data['users'] = [];
        $data['limit'] = isset($params['limit']) ? $params['limit'] : 5;
        $data['skip'] = isset($params['skip']) ? $params['skip'] : 0;
        $option = [ 'limit' => $data['limit'], 'skip' => $data['skip']];
        if ($role == 'M') {
            //get users list for the manager
            $response = $this->person->getAssignedPersons((int)session('user.id'), $option);
            if (isset($response['data']) && !empty($response['data'])) {
                foreach($response['data'] as $row) {
                    //get user info
                    $user = $this->person->get('person_' . $row['assigned_user_id']);
                    $data['users'][] = $this->person->respondWithItem($user, new UserTransformer);
                }
            }
        } else {
            //get all
            $response = $this->person->all($option);
            if (! isset($response['error'])) {
                foreach($response['data'] as $row) {
                        //get users report count
                        $reports= $this->report->getReportsByPerson((int)$row['id']);
                        $totalIReport = 0;
                        $totalGReport = 0;
                        if (! empty($reports['data'])) {
                            foreach($reports['data'] as $srow) {
                                if ($srow['report_type'] == 0) {
                                    $totalIReport += 1;
                                } else {
                                    $totalGReport += 1;
                                }
                            }
                        }
                        $row['totalIReport'] = $totalIReport;
                        $row['totalGReport'] = $totalGReport;
                        $data['users'][] = $row;
                }
                $data['users'] = $this->person->respondWithCollection($data['users'], new UserTransformer);
            }
        }

        return view('users', $data);
    }

    public function show($id, Request $request)
    {
        $id = (int) my_decode($id);
        $data['user'] = $this->person->respondWithItem($this->person->get('person_' . $id), new UserTransformer);
        //get invidvidual reports
        $response = $this->report->individual($id);
        $data['totalIReport'] = 0;
        $data['individual'] = [];
        $item = new Item();
        if (! isset($response['error'])) {
            $data['individual'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
            foreach ($data['individual'] as $i => $row) {
                $items = $item->getItemsByReport((int)my_decode($row['id']));
                $data['individual'][$i]['items'] = $items['totalRecords'];
            }
            $data['totalIReport'] = $response['totalRecords'];
        }
        $response = $this->report->group($id);
        $data['totalGReport'] = 0;
        $data['group']  = [];
        if (! isset($response['error'])) {
            $data['group'] = $this->report->respondWithCollection($response['data'], new ReportTransformer);
            foreach ($data['group'] as $i => $row) {
                $items = $item->getItemsByReport((int)my_decode($row['id']));
                $data['group'][$i]['items'] = $items['totalRecords'];
            }
            $data['totalGReport'] = $response['totalRecords'];
        }

        return view('users_profile', $data);

    }

    public function autocomplete(Request $request)
    {

    }
}
