<?php

namespace App\Http\Controllers;

use App\Data;
use App\Group;
use App\Item;
use App\ItemComment;
use App\ItemTag;
use App\Person;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Transformers\ItemTransformer;
use Transformers\ReportTransformer;

class ItemsController extends Controller
{
    public function __construct()
    {
        $this->person = new Person();
        $this->report = new Report();
        $this->item = new Item();
        $this->group = new Group();
        $this->data = new Data();
        $this->tag = new ItemTag();
        $this->comment = new ItemComment();
    }

    public function index(Request $request)
    {

    }

    public function show($id, Request $request)
    {
        $id = (int) my_decode($id);
        //get report info
        $data['item'] = $this->item->respondWithItem($this->report->get('item_' . $id), new ItemTransformer);
        //get item tag
        $result = $this->tag->get('tags_' . $id);
        $data['tags'] = ! isset($result['error']) ? $result : [];

        $data['firstImage'] = '';
        //get item medias
        $data['images'] = [];
        $data['audios'] = [];
        $data['videos'] = [];
        $result = $this->data->getDataByItem($id);
        if (isset($result['items']) && ! empty($result['items'])) {
            foreach($result['items']  as $i => $m) {
                if (strpos($m['media'], 'Image') !== false) {
                    $data['images'][] = [
                        'id' => $m['id'],
                        'img_short' => 'itemShort'. $m['id'] . '.png',
                        'img' => $m['media']
                    ];
                    if (empty($data['firstImage'])) {
                        $data['firstImage'] = 'itemShort' . $m['id'] . '.png';
                    }
                } elseif (strpos($m['media'], 'Audio') !== false) {
                    $data['audios'][] = [
                        'id' => $m['id'],
                        'ado' => $m['media']
                    ];
                } elseif (strpos($m['media'], 'Video') !== false) {
                    $data['videos'][] = [
                        'id' => $m['id'],
                        'vdo' => $m['media']
                    ];
                } else {}
            }
        }
        //get item commments
        $result = $this->comment->getCommentsByItem($id);
        $data['comments'] = [];
        if(isset($result['items']) && ! empty($result['items'])) {
            //get user info
            foreach($result['items'] as $item) {
                $user = $this->person->get('person_' . $item['person_id']);
                $item['userimage'] = isset($user['userimage']) ? $user['userimage'] : '';
                $item['first_name'] = isset($user['first_name']) ? $user['first_name'] : '';
                $item['last_name'] = isset($user['last_name']) ? $user['last_name'] : '';
                $data['comments'][] = $item;
            }
        }

        return view('item_details', $data);

    }

    public function update($id, Request $request)
    {
        $params = $request->all();
        $id = mysql_real_escape_string(trim($_REQUEST['id']));
        $comment = mysql_real_escape_string(trim($_REQUEST['comment']));
        $desc = mysql_real_escape_string(trim($_REQUEST['desc']));
        $title = mysql_real_escape_string(trim($_REQUEST['title']));
        $tags = mysql_real_escape_string(trim($_REQUEST['tags']));

        //// delete all old tags
        mysql_query('delete from tags WHERE item_id="'.$id.'"');

        $sql = 'update item set
			title="'.$title.'",
			description="'.$desc.'",
			comment="'.$comment.'"
			WHERE id="'.$id.'"
			';
        mysql_query($sql);

        //// add new tags
        $ary_tag = explode(',',$tags);

        for($i=0;$i<count($ary_tag);$i++)
        {
            $sql = 'insert into tags set tag="'.mysql_real_escape_string(trim($ary_tag[$i])).'" , item_id="'.$id.'"';
            mysql_query($sql);
        }

        exit;
    }

    public function addComment($id, Request $request)
    {
        $comm = mysql_real_escape_string(trim($_REQUEST['comm']));

        if($comm!='')
        {
            $sql = 'insert into item_comment SET
				item_id="'.$iid.'",
				person_id = "'.$_SESSION['user']['id'].'",
				comment = "'.$comm.'",
				created=now()
				';
            mysql_query($sql);
            echo 'ok';
        }
        exit;
    }

    public function uploadImage($id, Request $request)
    {
        $x= mysql_real_escape_string(trim($_POST['x']));
        $y= mysql_real_escape_string(trim($_POST['y']));
        $w= mysql_real_escape_string(trim($_POST['w']));
        $h= mysql_real_escape_string(trim($_POST['h']));
        $img_file = trim($_POST['img_file']);

        $errormsg = array();

        if(empty($errormsg))
        {
            $sql = 'insert into data set
					item_id="'.$iid.'",
					person_id = "'.$_SESSION['user']['id'].'",
					created = now()
					';
            mysql_query($sql);

            $last_item_id = mysql_insert_id();

            if($last_item_id>0)
            {
                $i_desti = '../assets/uploads/Image'.$last_item_id.'.jpg';
                $i_desti_2 = '../assets/uploads/itemShort'.$last_item_id.'.png';
                $i_desti_3 = '../assets/uploads/short'.$last_item_id.'.png';
                $media = 'Image'.$last_item_id.'.jpg';

                crop_image($img_file,$i_desti,$x,$y,$w,$h);
                crop_image($img_file,$i_desti_2,$x,$y,$w,$h);
                crop_image($img_file,$i_desti_3,$x,$y,$w,$h);

                $sql = 'update data set media="'.$media.'" WHERE id="'.$last_item_id.'"';
                mysql_query($sql);
            }

            echo 'ok';
            exit;
        }
        echo $errormsg[0];
        exit;
    }
}
