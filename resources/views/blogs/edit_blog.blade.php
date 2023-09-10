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
            Add Blog
        </h3>
        <div class="example-box-wrapper">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                action="{{url('/backoffice/blogupdate')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-6">
                        <input name="image" type="file" id="my_file" style="display: none;"
                            onchange="change_my_image(this)" accept="image/*" />
                        <div id="upload_image" class="upload-img" style="background-image:url('{{asset('uploads/blogs/'.$blog->blog_image)}}');"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Category</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="blogs_categories_id" name="blogs_categories_id" required>
                            <option>Select Category</option>
                            @foreach ($blogs_categories as $category)
                            <option value="{{ $category->blogs_categories_id }}" {{ ($category->blogs_categories_id==$blog->blogs_categories_id) ? 'selected':'' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Blog Title</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="title" value="{{ $blog->heading }}" required>
                        <input type="hidden" name="blogs_id" value="{{ $blog->blogs_id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Blog Sub Title</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="sub_title" value="{{ $blog->sub_title }}" required>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-6">
                        <textarea name="description" required rows="10" class="ckeditor " required>{{htmlspecialchars_decode(html_entity_decode($blog->description))}}</textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" {{ ($blog->status=='Active') ? 'selected':'' }}> Active</option>
                            <option value="0" {{ ($blog->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            
                        </select>
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