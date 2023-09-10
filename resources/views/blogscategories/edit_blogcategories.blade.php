@extends('common.main')
@section('title','Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
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
            Add Blog Category
        </h3>
        <div class="example-box-wrapper">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                action="{{url('/backoffice/blogsupdate_categories')}}">
                {{ csrf_field() }}
                
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="name" value="{{ $blogs_categories->name }}" required>
                        <input type="hidden" name="blogs_categories_id" value="{{ $blogs_categories->blogs_categories_id }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" {{ ($blogs_categories->status=='Active') ? 'selected':'' }}> Active</option>
                            <option value="0" {{ ($blogs_categories->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            
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
</script>
@endsection