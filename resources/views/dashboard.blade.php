@extends('layout')
@section('content')
    <div class="row">
       @if(session('user.role') == 'M' || session('user.role') == 'A')
        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats tile-red">
                <div class="icon"><i class="entypo-users"></i></div>
                <div data-delay="0" data-duration="1500" data-postfix="" data-end="{{ $total_users }}"
                     data-start="0" class="num">{{ $total_users }} </div>
                <h3>Total Users</h3>
            </div>

        </div>
        @endif

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats tile-green">
                <div class="icon"><i class="entypo-chart-bar"></i></div>
                <div data-delay="600" data-duration="1500" data-postfix="" data-end="{{ $total_report }}"
                     data-start="0" class="num">{{ $total_report }} </div>

                <h3>Total Reports</h3>

            </div>

        </div>

        <div class="col-sm-3 col-xs-6">

            <div class="tile-stats tile-blue">
                <div class="icon"><i class="entypo-rss"></i></div>
                <div data-delay="1800" data-duration="1500" data-postfix="" data-end="{{ $total_item }}"
                     data-start="0" class="num">{{ $total_item }} </div>

                <h3>Total Items</h3>
            </div>
        </div>
    </div>
@endsection