<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /****** get data user wise *****/
//        $qry_rights = '';
//        $qry_user_rights = '';
//        if($_SESSION['user']['type']=='U')
//        {
//            $qry_rights = ' AND person_id IN ('.$_SESSION['user']['id'].')';
//        }
//        else if($_SESSION['user']['type']=='M')
//        {
//
//            if($_SESSION['user']['type']=='M')
//            {
//                $user_list = get_assigned_user_list($_SESSION['user']['id']);
//
//                if($user_list!='')
//                {
//                    $qry_user_rights = ' AND id IN ('.$user_list.')';
//
//                    $user_list .= ','.$_SESSION['user']['id'];
//                    $qry_rights = ' AND person_id IN ('.$user_list.')';
//                }
//                else
//                {
//                    $qry_user_rights = ' AND 1!=1';
//
//                    $qry_rights = ' AND person_id IN ('.$_SESSION['user']['id'].')';
//                }
//            }
//        }

//        $sql = 'SELECT count(id) as total_users FROM person WHERE 1=1 '.$qry_user_rights;
//        $rs_u = mysql_query($sql);
//        $row_u = mysql_fetch_assoc($rs_u);
        $data['total_users'] = 10;

//        $sql = 'SELECT count(id) as total_report FROM report WHERE 1=1 '.$qry_rights;
//        $rs_r = mysql_query($sql);
//        $row_r = mysql_fetch_assoc($rs_r);
        $data['total_report'] = 20;

//        $sql = 'SELECT count(id) as total_item FROM item WHERE 1=1 '.$qry_rights;
//        $rs_i = mysql_query($sql);
//        $row_i = mysql_fetch_assoc($rs_i);
        $data['total_item'] = 30;


        return view('dashboard', $data);
    }
}
