<?php

namespace App\Http\Controllers;

use App\Data;
use App\Group;
use App\Item;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ItemTransformer;
use Transformers\ReportTransformer;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
        $this->item = new Item();
        $this->group = new Group();
        $this->data = new Data();
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
        $data['count_image'] = 0;
        $data['count_audio'] = 0;
        $data['count_video'] = 0;
        //if report type is group, get group members not including the member already selected
        $data['report_group'] = $this->group->get('family_' . $id);
        $data['users'] = $this->person->all()['data'];

        //get item info
        $items = $this->item->respondWithCollection($this->item->getItemsByReport($id)['items'], new ItemTransformer);

        $data['total_items'] = count($items);
        //get first image of the items in data
        foreach($items as $i => $row) {
            $media = $this->data->getDataByItem((int)my_decode($row['id']));
            if (isset($media['items']) && ! empty($media['items'])) {
                //get first image
                $row['image'] = "";
                foreach($media['items'] as $m) {
                    if (strpos($m['media'], 'Image') !== false) {
                        $row['image'] = 'itemShort'.$m['id']. '.png';
                        break;
                    }
                }
            }
            $items[$i] = $row;
        }
        //get count of media items
        foreach($items as $i => $row) {
            $media = $this->data->getDataByItem((int)my_decode($row['id']));
            if (isset($media['items']) && ! empty($media['items'])) {
                foreach($media['items'] as $m) {
                    if (strpos($m['media'], 'Image') !== false) {
                        $data['count_image'] += 1;
                    } elseif (strpos($m['media'], 'Audio') !== false) {
                        $data['count_audio'] += 1;
                    } elseif (strpos($m['media'], 'Video') !== false) {
                        $data['count_video'] += 1;
                    } else {}
                }
            }
        }
        $data['items'] = $items;

        return view('report_details', $data);

    }

    public function autocomplete(Request $request)
    {

    }
}
