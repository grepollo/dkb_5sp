@extends('layout')
@section('content')
<ol class="breadcrumb bc-3" >
    <li> <a href=" {{ url('/dashboard') }}"><i class="fa-home"></i>Dashboard</a> </li>
    <li> <a href="{{ url('/items') }}"><i class="fa-home"></i>Items</a> </li>
    <li class="active"> <strong>Item Details</strong> </li>
</ol>
<div class="profile-env">

    <header class="row">

        <div class="col-sm-2">
            @if(file_exists(public_path('assets/uploads/' . $firstImage)))
            <img height="80" src="{{ asset('assets/uploads/' . $firstImage) }}" class="img-responsive img-circle" />
            @else
            <img height="80" src="{{ asset('assets/images/user.png') }}" class="img-responsive img-circle" />
            @endif
        </div>
        <div class="col-sm-7">

            <ul class="profile-info-sections">
                <li>
                    <div class="profile-name">
                        <strong>
                            <h3>{{ $item['title'] }}</h3>
                        </strong>
                    </div>
                </li>

                <li>
                    <div class="profile-stat">
                        <h3>{{ date('d-m-Y',strtotime($item['created'])) }}</h3>
                        <span>Created Date</span>
                    </div>
                </li>
            </ul>

        </div>
        <div class="col-sm-3">
            <div class="profile-buttons">
                <a href="javascript:;" onclick="jQuery('#modal-3').modal('show', {backdrop: 'static'});" class="btn btn-default">
                    <i class="entypo-user-add"></i>
                    Add Media
                </a>
            </div>
        </div>
    </header>

    <section class="profile-info-tabs">

        <div class="row">

            <div class="col-sm-offset-2 col-sm-10">

                <ul class="user-details">
                    <li>

                        <i class="entypo-calendar"></i>
                        <strong>Comments : </strong><span> {{ $item['comment'] }}</span>

                    </li>
                    <li>

                        <i class="entypo-book-open"></i>
                        <strong>Description : </strong><span> {{ $item['description'] }}</span>

                    </li>
                </ul>

            </div>

        </div>

    </section>

    <div class="row">
        <div class="col-sm-10">
            <input type="text" class="form-control input-lg" id="title" name="title" placeholder="Item title" value="{{ $item['title'] }}" />
        </div>
        <div class="col-sm-2 post-save-changes">
            <button type="button" onClick="updateItem({{ $item['id'] }});" class="btn btn-green btn-lg btn-block btn-icon">
                Update
            </button>
        </div>
    </div>

    <br>
    <div class="row">
        <!-- Metabox :: Featured Image -->
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Description</h3>
                    </div>
                </div>
                <div class="panel-body no-padding">
                    <textarea class="form-control autogrow" id="desc" name="desc" placeholder="Item Description">{{ trim($item['description']) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <!-- Metabox :: Featured Image -->
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Comment</h3>
                    </div>
                </div>
                <div class="panel-body no-padding">
                    <textarea class="form-control autogrow" id="comment" name="comment" placeholder="Item Comments">{{ trim($item['comment']) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Item Tags</h3>
                    </div>
                </div>
                <div class="panel-body">
                    <input type="text" id="tags" name="tags" value="{{ isset($tags['tags']) ? $tags['tags'] : '' }}" class="form-control tagsinput" />
                </div>
            </div>
        </div>
    </div>

    <br>

    @if (! empty($images))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Images</h3>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="gallery-env">
                        <div class="row">
                            @foreach($images as $image)
                            <div class="col-sm-2 col-xs-2" id="{{ $image['id'] }}">
                                <article class="image-thumb">
                                    <a href="{{ asset('assets/uploads/'. $image['img']) }}" class="myfancybox" rel="group">
                                        <img src="{{ asset('assets/uploads/'. $image['img_short']) }}" height="130" width="130"/>
                                    </a>
                                    <div class="image-options">
                                        <a href="javascript:" class="delete" onClick="delete_image('{{  $image['id'] }}','{{  $image['img'] }}');"><i class="entypo-cancel"></i></a>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (! empty($videos))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Videos</h3>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="gallery-env">
                        <div class="row">
                            @foreach($videos as $video)
                            <div class="col-sm-2 col-xs-2" id="{{ $video['id'] }}">
                                <article class="image-thumb">
                                    <a href="{{ asset('assets/uploads/' .$video['vdo']) }}" target="_blank">
                                        <strong>{{ $video['vdo'] }}</strong>
                                    </a>
                                    <div class="image-options">
                                        <a href="javascript:" class="delete" onClick="delete_image('{{ $video['id'] }}','{{ $video['vdo'] }}');"><i class="entypo-cancel"></i></a>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (! empty($audios))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Audios</h3>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="gallery-env">
                        <div class="row">
                            @foreach($audios as $audio)
                            <div class="col-sm-2 col-xs-2" id="{{ $audio['id'] }}">
                                <article class="image-thumb">
                                    <a href="{{ asset('assets/uploads/' . $audio['ado']) }}" target="_blank" class="html5lightbox">
                                        <strong>{{ $audio['ado'] }}</strong>
                                    </a>
                                    <div class="image-options">
                                        <a href="javascript:" class="delete" onClick="delete_image('{{ $audio['id'] }}','{{ $audio['ado'] }}');"><i class="entypo-cancel"></i></a>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>Add Comment</h3>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-10">
                        <input type="text" class="form-control input-lg" id="user_comment" name="user_comment" placeholder="Enter Comment" />
                    </div>
                    <div class="col-sm-2 post-save-changes">
                        <button type="button" onClick="save_comment();" class="btn btn-green btn-lg btn-block btn-icon">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-primary">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3>
                            Item Comments
                        </h3>
                    </div>
                </div>

                <div class="panel-body no-padding">
                    <!-- List of Comments -->
                    <ul class="comments-list">
                        <!-- Comment Entry -->
                        @foreach($comments as $comment)
                        <li>
                            <div class="comment-checkbox">
                                @if(file_exists(public_path('assets/uploads/' . $comment['userimage'])) && ! empty($comment['userimage']))
                                <img src="{{ asset('assets/images/users/' . $comment['userimage']) }}" class="img-responsive img-circle" />
                                @else
                                <img class="img-responsive img-circle" src=" {{ asset('assets/images/user.png') }}">
                                @endif
                            </div>

                            <div class="comment-details">
                                <div class="comment-head">
                                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $comment['first_name'].' '.$comment['last_name'] }}</strong>
                                </div>

                                <p class="comment-text">
                                    {{ $comment['comment'] }}
                                </p>

                                <div class="comment-footer">

                                    <div class="comment-time">
                                        {{ date('d / m / Y h:i A',strtotime($comment['created'])) }}
                                    </div>
                                </div>

                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-width" id="modal-3">
    <div class="modal-dialog" style="width: 70%">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Media</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="uploadForm" action="ajax_upload.php" method="post">
                    <input type="hidden" id="x" name="x" />
                    <input type="hidden" id="y" name="y" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="field-1">Image</label>

                        <div class="col-sm-8">
                            <div id="targetLayer"></div>
                            <input name="userImage" id="userImage" type="file" class="inputFile" />
                            <input style="display:none;" type="submit" value="Submit" class="btnSubmit" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onClick="check_validation()" class="btn btn-info">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.css') }}">
<script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/js/jcrop/jquery.Jcrop.min.css') }}">
<script src="{{ asset('assets/js/jcrop/jquery.Jcrop.min.js') }}"></script>

<script>
    $(".myfancybox").fancybox();
    var opts = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function updateItem(id)
    {
        if($("input#title").val()=='')
        {
            toastr.error("Please, Enter Item Title.", "Requeried Field Error", opts);
            $("input#title").focus();
            return false;
        }
        $ans=confirm("Do you update the item informations?");
        if($ans){
            $.ajax({
                method: 'POST',
                data: {
                    id: id,
                    title: $("input#title").val(),
                    desc: $("textarea#desc").val(),
                    comment: $("textarea#comment").val(),
                    tags: $("input#tags").val(),
                    updateit: 1
                },

                error: function(response)
                {
                    toastr.error(response, "Error", opts);
                },
                success: function(response)
                {
                    toastr.success("Item Successfully Updated", "Success", opts);
                }
            });
        }
    }

    function delete_image(id,name)
    {
        var ans=confirm("Are you Sure to Delete.?");
        if(ans){
            $.ajax({
                method: 'POST',
                url:'data/ajax_delete_image.php',
                data: {
                    id: id,
                    name: name,
                    deleteit: 1
                },
                error: function(response)
                {
                    toastr.error(response, "Error", opts);
                },
                success: function(response)
                {
                    $('#'+id).hide(200);
                    toastr.success("Item Data Successfully Deleted", "Success", opts);
                }
            });
        }
        return false;
    }

    var img_file ='';

    function check_validation()
    {
        if($('#userImage').val()=='')
        {
            toastr.error("Please, Select Image.", "Required", opts);
            $('#userImage').focus();
            return false;
        }

        $.ajax({
            method: 'POST',
            data: {
                x: $("#x").val(),
                y: $("#y").val(),
                w: $("#w").val(),
                h: $("#h").val(),
                img_file: img_file,
                uploadit: 1
            },
            error: function(response)
            {
                alert(response);
            },
            success: function(response)
            {
                if(response=='ok')
                {
                    //toastr.success("Item Saved Successfully", "Success", opts);
                    //$('#frm')[0].reset();
                    alert("Media Successfully Saved");
                    window.location.reload();
                }
                else
                {
                    toastr.error(response, "Required", opts);
                    $('#frm')[0].reset();
                }
            }
        });
    }
    /*** over check validation */

    $('.inputFile').change(function(e) {
        $('.btnSubmit').trigger('click');
    });

    $("#uploadForm").on('submit',(function(e) {
        e.preventDefault();
        $.ajax({
            url: "ajax_upload.php",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                img_file='images/'+data;
                $("#targetLayer").html('<img id="jcrop-4" src="images/'+data+'"/>');
                reset_setting();
            },
            error: function()
            {
            }
        });
    }));

    function reset_setting()
    {
        $('#jcrop-4').Jcrop({
            aspectRatio: 1,
            onSelect: updateCoords,
            setSelect: [ 50,50, 200, 200 ]
        });

        function updateCoords(c)
        {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        };
    }

    function save_comment()
    {
        var comm = $('#user_comment').val();
        if(comm=='')
        {
            toastr.error('Please, Enter Comment', "Required", opts);
            $('#user_comment').focus();
            return false;
        }

        $.ajax({
            method: 'POST',
            data: {
                comm: comm,
                save_comment: 1
            },
            success: function(response)
            {
                if(response=='ok')
                {
                    //toastr.success("Item Saved Successfully", "Success", opts);
                    //$('#frm')[0].reset();
                    alert("Comment Successfully Saved");
                    window.location.reload();
                }
            }
        });
    }
</script>
@endsection