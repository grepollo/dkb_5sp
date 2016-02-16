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
                        <a href="{{ url('users/' . $report['person_id'] ) }}">
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

    @if($report['report_type'] == 1)
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
                @foreach($users as $user)
                <option value="{{ $user['id'] }}" {{ (in_array($user['id'], $report_group['members']) ? "selected" : "") }}>{{ $user['first_name'].' '.$user['last_name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 post-save-changes">
            <button class="btn btn-green btn-lg btn-block btn-icon" onclick="save_member();" type="button">
                Update
            </button>
        </div>
    </div>
    @endif

    <hr>
    <h3>Items List</h3>

    <?php /////////////////////// ITEM LIST START //////////////////////////// ?>
    @foreach($items as $item)
    <div class="member-entry">
        <a href="{{ url('/items/' . $item['id']) }}" class="member-img">
            @if(file_exists(public_path('assets/uploads/' . $item["image"])) && ! empty($item["image"]))
            <img src="{{ asset('assets/uploads/' . $item['image']) }}" class="img-rounded" />
            @else
            <img src="{{ asset('assets/images/user.png') }}" class="img-rounded" />
            @endif
            <i class="entypo-forward"></i>
        </a>

        <div class="member-details">
            <h4>
                <a href="{{ url('/items/' . $item['id']) }}">{{ $item['title'] }}</a>
            </h4>

            <!-- Details with Icons -->
            <div class="row info-list">
                <div class="col-sm-4">
                    <i class="entypo-calendar"></i>
                    {{ $item['created'] }}
                </div>
                <div class="clear"></div>
                <div class="col-sm-12">
                    {{ $item['description'] }}
                </div>

            </div>
        </div>

    </div>
    @endforeach
    <?php /////////////////////// OVER ITEM LIST //////////////////////////// ?>
</div>
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script>
    function save_member()
    {
        $.ajax({
            url : "/members",
            type:"POST",
            dataType:"text",
            data: {ids : $('#group_member').val()},
            async:true,
            success: function(data){
                alert('Group Member Updated Successfully');
            }
        });
    }
</script>
@endsection