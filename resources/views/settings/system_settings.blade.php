@extends('common.main')
@section('title','Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $blogs_categories=DB::table('blogs_categories')->get(); ?>
<style type="text/css">
    .upload-img {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background-position: center center;
        background-size: cover;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">
            System Settings
        </h3>



        <div class="example-box-wrapper">


            <div class="example-box-wrapper">
            <div class="container lst">


                @if (session('errors'))
                <div class="alert alert-danger">
                  {{ session('errors') }}
                </div> 
                @endif


                @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div> 
                @endif
            </div>

            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                action="{{url('/backoffice/system_update')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-6">
                        <input name="image" type="file" id="my_file" style="display: none;"
                            onchange="change_my_image(this)" accept="image/*" />
                        <div id="upload_image" class="upload-img" style="background-image:url('{{asset('uploads/system/'.$data[5]->description)}}');"></div>
                    </div>
                </div>
                <div class="font-weight-bold">General Setting</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[0]->type }}" value="{{ $data[0]->description }}" required>
                    </div>
                </div>
                <div class="font-weight-bold">Contact Setting</div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[1]->type }}" value="{{ $data[1]->description }}" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Language</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name=" {{ $data[3]->type}}" value="{{ $data[3]->description }}" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Phone</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[2]->type }}" value="{{ $data[2]->description }}" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Address</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[4]->type}}" value="{{ $data[4]->description }}" required>
                    </div>
                </div>
                <div class="font-weight-bold">Email Setting</div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">SMTP Localhost</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[6]->type }}" value="{{ $data[6]->description }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Port</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[7]->type }}" value="{{ $data[7]->description }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">SMTP Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[8]->type }}" value="{{ $data[8]->description }}" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">SMTP Password</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[9]->type }}" value="{{ $data[9]->description }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">API key</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[10]->type }}" value="{{ $data[10]->description }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Currency</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="{{ $data[11]->type }}" value="{{ $data[11]->description }}" required>
                    </div>
                </div>
                
                
               
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" value="Update" style="float: right;"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function change_my_image(abc){
        // alert();
        var input = abc;
         if (input.files && input.files[0]) {
             var reader = new FileReader();
             reader.onload = function (e) {
               // alert(e.target.result);
                 $('#upload_image').css('background', "url('"+e.target.result+"') no-repeat center center");
                 $('#upload_image').css('background-size','cover');
             }
               reader.readAsDataURL(input.files[0]);
        }
    }
   
    $("#upload_image").click(function() {
       $("#my_file").click();
    });
   
    $(document).ready(function() {
        $('#blogs_categories_id').select2();
    });
</script>

@endsection