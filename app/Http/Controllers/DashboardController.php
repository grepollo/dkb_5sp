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
                    $data['total_users'] = count($response);
                    foreach ($response as $row) {
                        $reports = $report->getReportsByPerson($row['id']);
                        if (!empty($response)) {
                            $data['total_report'] += count($reports);
                            //get item of the report
                            foreach ($reports as $srow) {
                                $items = $item->getItemsByReport($srow['id']);
                                $data['total_item'] += count($items);
                            }
                        }
                    }
                    //get current user's report
                    $response = $report->getReportsByPerson(session('user.id'));
                    if (!empty($response)) {
                        $data['total_report'] += count($response);
                        //get item of the report
                        foreach ($response as $row) {
                            $items = $item->getItemsByReport($row['id']);
                            $data['total_item'] += count($items);
                        }
                    }
                }
            } else { //admin
                $response = $person->all([]);
                if (!empty($response)) {
                    //get total reports or their assigned users
                    $data['total_users'] = count($response);
                    foreach ($response as $row) {
                        $reports = $report->getReportsByPerson($row['id']);
                        if (!empty($response)) {
                            $data['total_report'] += count($reports);
                            //get item of the report
                            foreach ($reports as $srow) {
                                $items = $item->getItemsByReport($srow['id']);
                                $data['total_item'] += count($items);
                            }
                        }
                    }
                }
            }
        }

        return view('dashboard', $data);
    }
}
