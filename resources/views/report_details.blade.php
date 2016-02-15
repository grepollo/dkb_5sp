@extends('layout')
@section('content')
<ol class="breadcrumb bc-3" >
    <li> <a href=" {{ url('/dashboard') }}"><i class="fa-home"></i>Dashboard</a> </li>
    <li> <a href="{{ url('/reports') }}"><i class="fa-home"></i>Reports</a> </li>
    <li class="active"> <strong>Report Details</strong> </li>
</ol>
<div class="profile-env">
    <h3>{{ $report['name'] }}</h3>
    <header class="row">

        <div class="col-sm-2">
            <img class="img-responsive img-circle" src="{{ asset('assets/images/report.png') }}">
        </div>

        <div class="col-sm-8">

            <style>
                .profile-info-sections li
                {
                    padding: 0 20px !important;
                }
            </style>
            <ul class="profile-info-sections">
                <li>
                    <div class="profile-stat">
                        <h3>{{ ($report['report_type']==0)?'Individual':'Group' }}</h3>
                        <span>Report</span>
                    </div>
                </li>
                <li>
                    <div class="profile-stat">
                        <h3>{{ $total_items }}</h3>
                        <span>Total Items</span>
                    </div>
                </li>

                <li>
                    <div class="profile-stat">
                        <h3>{{ $count_image }}</h3>
                        <span>Total Images</span>
                    </div>
                </li>
                <li>
                    <div class="profile-stat">
                        <h3>{{ $count_audio }}</h3>
                        <span>Total Audios</span>
                    </div>
                </li>

                <li>
                    <div class="profile-stat">
                        <h3>{{ $count_video }}</h3>
                        <span>Total Videos</span>
                    </div>
                </li>
            </ul>

        </div>
        <div class="col-sm-2">
            <div class="profile-buttons">
                <a class="btn btn-default" href="{{ url('reports/' . $report['id'] . 'items/') }}">
                    <i class="entypo-user-add"></i>
                    Add Item
                </a>
            </div>
        </div>
    </header>

    <section class="profile-info-tabs">

        <div class="row">

            <div class="col-sm-offset-2 col-sm-10">

                <ul class="user-details">
                    <li>
                        <a href="user_profile.php?uid={{ my_encode($report['person_id']) }}">
                            <i class="entypo-suitcase"></i>
                            <strong>Report Owner :</strong> <span><a href="{{ url('/users/' . $report['person_id']) }}">{{ $report['person_name'] }}</a></span>
                        </a>
                    </li>

                    <li>

                        <i class="entypo-calendar"></i>
                        <strong>Created Date :</strong> <span> {{ date('d-m-Y',strtotime($report['created'])) }}</span>

                    </li>
                    <li>

                        <i class="entypo-book-open"></i>
                        <strong>Description :</strong> <span> {{ $report['description'] }}</span>

                    </li>
                </ul>

            </div>

        </div>

    </section>

    <?php if($report['type']==1){

    $sql = 'SELECT * FROM person';
    $rs_u = mysql_query($sql);
    ?>
    <style>
        .select2-search-choice
        {
            padding: 12px 12px 12px 20px !important;
            font-size: 18px !important;
            margin: 1px 0 6px 5px!important;
        }
    </style>
    <hr>
    <h3>Group Members</h3>


    <div class="row">
        <div class="col-sm-10">
            <select name="group_member" id="group_member" class="select2" multiple>
                <?php while($row_u=mysql_fetch_assoc($rs_u)){ ?>
                <option value="{{ $row_u['id'] }}" {{ (in_array($row_u['id'],$member_ary)?"selected":"")?>>{{ $row_u['first_name'].' '.$row_u['last_name'] }}</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-2 post-save-changes">
            <button class="btn btn-green btn-lg btn-block btn-icon" onclick="save_member();" type="button">
                Update
            </button>
        </div>
    </div>
    <?php } ?>

    <hr>
    <h3>Items List</h3>

    <?php /////////////////////// ITEM LIST START //////////////////////////// ?>
    <?php while($row_i = mysql_fetch_assoc($rs_i)){

    /*** get first image ***/
    $sql = 'SELECT id FROM `data` WHERE `item_id`="'.$row_i['id'].'" AND `media` LIKE "I%" LIMIT 1';
    $rs_fi = mysql_query($sql);
    $row_fi = mysql_fetch_assoc($rs_fi);
    $first_img = "itemShort".$row_fi['id'].".png";


    ?>
    <div class="member-entry">

        <a href="item_details.php?iid={{ my_encode($row_i['id']) }}" class="member-img">
            <?php if(getimagesize(MAINLOCATION.'assets/uploads/'.$first_img)){ ?>
            <img src="{{ '../assets/uploads/'.$first_img }}" class="img-rounded" />
            <?php }else{ ?>
            <img src="../assets/images/user.png" class="img-rounded" />
            <?php } ?>
            <i class="entypo-forward"></i>
        </a>

        <div class="member-details">
            <h4>
                <a href="item_details.php?iid={{ my_encode($row_i['id']) }}">{{ $row_i['title'] }}</a>
            </h4>

            <!-- Details with Icons -->
            <div class="row info-list">
                <div class="col-sm-4">
                    <i class="entypo-calendar"></i>
                    {{ $row_i['created'] }}
                </div>
                <div class="clear"></div>
                <div class="col-sm-12">
                    {{ $row_i['description'] }}
                </div>

            </div>
        </div>

    </div>
    <?php }// while over ?>
    <?php /////////////////////// OVER ITEM LIST //////////////////////////// ?>


</div>
@endsection