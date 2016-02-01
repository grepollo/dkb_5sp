@extends('layout')
@section('content')
    <ol class="breadcrumb bc-3" >
        <li> <a href="{{ url('dashboard') }}"><i class="fa-home"></i>Dashboard</a> </li>
        <li class="active"> <strong>Users</strong> </li>
    </ol>

    <div class="row">
        <div class="col-md-9 col-sm-7">
            <h2>Users</h2>
        </div>
        <div class="col-md-3 col-sm-5">
            <form class="search-form-full" role="form" method="POST">
                <div class="" style="float:left; width:70%;">
                    <input type="text" id="search-input" class="form-control typeahead" data-remote="{{ url('users/autocomplete') }}" name="s" placeholder="Search by UserName..." />

                </div>
                <div style="float:left">
                    <input style="float:left" class="btn btn-default" type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>
    @foreach($users as $user)
    <div class="member-entry">
        <a href="{{ url('users/' . my_encode($user['id'])) }}" class="member-img">

            @if(file_exists(public_path('assets/images/users/' . $user["userimage"])) && ! empty($user["userimage"]))
            <img src="{{ asset('assets/images/users/' . $user["userimage"]) }}" class="img-rounded" />
            @else
            <img src="{{ asset('assets/images/user.png') }}" class="img-rounded" />
            @endif
            <i class="entypo-forward"></i>
        </a>

        <div class="member-details">
            <h4>
                <a href="{{ url('users/' . my_encode($user['id'])) }}">{{ $user['first_name'].''.$user['last_name'] }}</a>
            </h4>

            <!-- Details with Icons -->
            <div class="row info-list">

                <div class="col-sm-4">
                    <i class="entypo-mail"></i>
                    {{ $user['email'] }}
                </div>

                <div class="col-sm-4">
                    <i class="entypo-doc-text"></i>
                    {{ $user['totalIReport'] }} Individual Reports
                </div>

                <div class="col-sm-4">
                    <i class="entypo-newspaper"></i>
                    {{ $user['totalGReport'] }} Group Reports
                </div>

                @if(session('user.role') =='A')

                @if($user['type']=='M')
                <div class="col-sm-4">
                    <i class="entypo-users"></i>
                    <a href="javascript:;" onclick="showAjaxModal('{{ my_encode($user['id']) }}');">Assigned Users</a>
                </div>
                <div class="col-sm-4">
                    <i class="entypo-cancel-squared"></i>
                    <a href="javascript:;" onclick="revoke_manager('{{ my_encode($user['id']) }}');">Revoke from Manager</a>
                </div>
                @else
                <div class="col-sm-4">
                    <i class="entypo-users"></i>
                    <a href="javascript:;" onclick="if(confirm('Are You Sure to Appoint as a Manager ?')){save_user_list('{{ my_encode($user['id']) }}')}">Appoint as Manager</a>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
            <!-- Pager for search results -->
    <div class="row">
        <div class="col-md-12">
            <ul class="pager">
                @if($skip > 0)
                <li><a href="{{ url('/users?limit=' . $limit .'&skip=' . ($skip - 5)) }}"><i class="entypo-left-thin"></i> Previous</a></li>
                @endif
                @if (count($users) >= $limit)
                <li><a href="{{ url('/users?limit=' . $limit .'&skip=' . ($skip + 5)) }}">Next <i class="entypo-right-thin"></i></a></li>
                @endif
            </ul>
        </div>
    </div>

    <script src="assets/js/typeahead.min.js"></script>
    <script src="assets/js/select2/select2.min.js"></script>
    <script type="text/javascript">

        function save_user_list(id)
        {
            var mem_id = id;
            $.ajax({
                type:"POST",
                dataType:"text",
                data:{save_user : 1,mem_id:mem_id},
                async:true,
                success: function(data){
                    alert('User Appointed as a Manager Successfully');
                    $('#modal-3').modal('hide');
                    window.location.reload();
                }
            });
        }
        function revoke_manager(id)
        {
            $.ajax({
                type:"POST",
                dataType:"text",
                data:{revoke_manager : 1,id:id},
                async:true,
                success: function(data){
                    alert('Manager Rights Revoked Successfully');
                    window.location.reload();
                }
            });
        }
    </script>
@endsection