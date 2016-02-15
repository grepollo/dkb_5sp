@extends('layout')
@section('content')
<ol class="breadcrumb bc-3" >
    <li> <a href=" {{ url('/dashboard') }}"><i class="fa-home"></i>Dashboard</a> </li>
    <li> <a href="{{  url('/users') }}"><i class="fa-home"></i>Users</a> </li>
    <li class="active"> <strong>User Profile</strong> </li>
</ol>
<div class="profile-env">
    <header class="row">
        <div class="col-sm-2">
            @if(file_exists(public_path('assets/images/users/' . $user["userimage"])) && ! empty($user["userimage"]))
            <img src="{{ asset('/assets/images/users/' . $user["userimage"] ) }}" class="img-responsive img-circle" />
            @else
            <img class="img-responsive img-circle" src=" {{ asset('/assets/images/user.png') }}">
            @endif
        </div>

        <div class="col-sm-7">

            <ul class="profile-info-sections">
                <li>
                    <div class="profile-name">
                        @if($user['role']=='M')
                        <h3>
                            <img width="25" class="img-circle" alt="" src="{{ asset('/assets/images/user.png') }}">
                            {{  $user['first_name'].''.$user['last_name'] }}
                        </h3>
                        <span>Manager</span>
                        @else
                        <h3>
                            {{ $user['first_name'].''.$user['last_name'] }}
                        </h3>
                        <span>{{ $user['occupation'] }}</span>
                        @endif
                    </div>
                </li>
                <li>
                    <div class="profile-stat">
                        <h3>{{ $totalIReport }}</h3>
                        <span>Individual Reports</span>
                    </div>
                </li>

                <li>
                    <div class="profile-stat">
                        <h3>{{ $totalGReport }}</h3>
                        <span>Group Reports</span>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <section class="profile-info-tabs">

        <div class="row">

            <div class="col-sm-offset-2 col-sm-10">

                <ul class="user-details">
                    <li>
                        <i class="entypo-location"></i>
                        {{  $user['country'] }}
                    </li>

                    <li>
                        <i class="entypo-suitcase"></i>
                        Works as <span>{{ $user['occupation'] }}</span>
                    </li>

                    <li>
                        <i class="entypo-calendar"></i>
                        Since <span> {{ date('d-m-Y',strtotime($user['created']))  }}</span>
                    </li>
                </ul>

            </div>

        </div>

    </section>
    <h3>Reports</h3>
    <div class="col-md-12">

        <ul class="nav nav-tabs bordered"><!-- available classes "bordered", "right-aligned" -->
            <li class="active">
                <a href="#indi" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-home"></i></span>
                    <span class="hidden-xs">Individual Reports</span>
                </a>
            </li>
            <li>
                <a href="#group" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-user"></i></span>
                    <span class="hidden-xs">Group Reports</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="indi">
            @foreach($individual as $row)
                <div class="member-entry">

                        <div class="member-details">
                            <h4>
                                <a href="{{ url('/reports/' . $row['id']) }}">{{ $row['name'] }}</a>
                            </h4>

                            <!-- Details with Icons -->
                            <div class="row info-list">

                                <div class="col-sm-4">
                                    <i class="entypo-calendar"></i>
                                    Created Date : {{ date('d-m-Y',strtotime($row['created'])) }}
                                </div>

                                <div class="col-sm-4">
                                    <i class="entypo-doc-text"></i>
                                    Total Items : {{ $row['items'] }}
                                </div>

                                <div class="col-sm-4">
                                    <i class="entypo-newspaper"></i>
                                    Type : {{ 'Individual' }}
                                </div>

                            </div>
                        </div>

                    </div>
            @endforeach
            </div>
            <div class="tab-pane" id="group">
            @foreach($group as $row)
                    <div class="member-entry">

                        <div class="member-details">
                            <h4>
                                <a href="{{ url('/reports/' . $row['id']) }}">{{ $row['name'] }}</a>
                            </h4>

                            <!-- Details with Icons -->
                            <div class="row info-list">

                                <div class="col-sm-4">
                                    <i class="entypo-calendar"></i>
                                    Created Date : {{ date('d-m-Y',strtotime($row['created'])) }}
                                </div>

                                <div class="col-sm-4">
                                    <i class="entypo-doc-text"></i>
                                    Total Items : {{ $row['items'] }}
                                </div>

                                <div class="col-sm-4">
                                    <i class="entypo-newspaper"></i>
                                    Type : {{ 'Group' }}
                                </div>

                            </div>
                        </div>

                    </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection