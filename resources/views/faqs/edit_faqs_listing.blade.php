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
            EDIT FAQs
        </h3>
        <div class="example-box-wrapper">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                action="{{url('/backoffice/update_faqs_listings/'.$faqs->faqs_id)}}">
                {{ csrf_field() }}
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Question</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="question" value="{{ $faqs->question }}" required >
                        <input type="hidden" name="faqs_id" value="{{ $faqs->faqs_id }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Answer</label>
                    <div class="col-sm-6">
                        <textarea name="answer" required rows="10" class="ckeditor" required>{{ $faqs->answer }}</textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" {{ ($faqs->status=='Active') ? 'selected':'' }}> Active</option>
                            <option value="0" {{ ($faqs->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            
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