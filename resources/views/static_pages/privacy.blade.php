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
           Privacy And Policy
        </h3>
        <div class="example-box-wrapper">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                action="{{url('/backoffice/privacyupdate')}}">
                {{ csrf_field() }}
                
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Heading</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="heading" value="{{ $privacy->heading }}" required>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-6">
                        <textarea name="description" required rows="10" class="ckeditor " required>{{htmlspecialchars_decode(html_entity_decode($privacy->description))}}</textarea>
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