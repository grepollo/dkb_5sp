<?php

namespace App\Http\Controllers;

use App\Item;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $person = new Person();
        $report = new Report();
        $item = new Item();
        $role = session('user.role');
        $data = [
            'total_users'  => 0,
            'total_report' => 0,
            'total_item'   => 0,
        ];
        if ($role == 'U') {
            //get data on report and item only
        } else {
            if ($role == 'M') {
                $response = $person->getAssignedPersons(session('user.id'));
                if (!empty($response)) {
                    //get total reports or their assigned users
                    $data['total_users'] = $response['totalRecords'];
                    foreach ($response['data'] as $row) {
                        $reports = $report->getReportsByPerson($row['id']);
                        if (!empty($reports['data'])) {
                            $data['total_report'] += $reports['totalRecords'];
                            //get item of the report
                            foreach ($reports['data'] as $srow) {
                                $items = $item->getItemsByReport($srow['id']);
                                $data['total_item'] += $items['totalRecords'];
                            }
                        }
                    }
                    //get current user's report
                    $response = $report->getReportsByPerson(session('user.id'));
                    if (!empty($response['data'])) {
                        $data['total_report'] += $response['totalRecords'];
                        //get item of the report
                        foreach ($response['data'] as $row) {
                            $items = $item->getItemsByReport($row['id']);
                            $data['total_item'] += $items['totalRecords'];
                        }
                    }
                }
            } else { //admin
                $response = $person->all([]);
                if (!empty($response)) {
                    //get total reports or their assigned users
                    $data['total_users'] = $response['totalRecords'];
                    foreach ($response['data'] as $row) {
                        $reports = $report->getReportsByPerson($row['id']);
                        if (isset($reports['data']) && !empty($reports['data'])) {
                            $data['total_report'] += $reports['totalRecords'];
                            //get item of the report
                            foreach ($reports['data'] as $srow) {
                                $items = $item->getItemsByReport($srow['id']);
                                $data['total_item'] += $items['totalRecords'];
                            }
                        }
                    }
                }
            }
        }

        return view('dashboard', $data);
    }
}
