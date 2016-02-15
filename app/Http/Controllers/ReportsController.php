<?php

namespace App\Http\Controllers;

use App\Item;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ReportTransformer;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
        $this->item = new Item();
    }

    public function index(Request $request)
    {

    }

    public function show($id, Request $request)
    {
        $id = (int) my_decode($id);
        //get report info
        $data['report'] = $this->report->respondWithItem($this->report->get('report_' . $id), new ReportTransformer);
        //get user info
        $user = $this->person->get('person_' . my_decode($data['report']['person_id']));
        $data['report']['person_name'] = (isset($user['first_name']) ? $user['first_name'] : '') . ' ' .
            (isset($user['last_name']) ? $user['last_name'] : '');
        $data['total_items'] = 0;
        $data['count_image'] = 0;
        $data['count_audio'] = 0;
        $data['count_video'] = 0;
        //if report type is group, get group members not including the member already selected
        $data['group_members'] = [];
        $data['members'] = [];
        if ($data['report']['report_type'] == 1) {
            //$data['group_members'][] = [
            //  'person_id' => '',
            //  'person_name => ''
            //];
        }

        //get item info
        $items = $this->item->getItemsByReport($id);
        pr($items);

        return view('report_details', $data);

    }

    public function autocomplete(Request $request)
    {

    }
}
