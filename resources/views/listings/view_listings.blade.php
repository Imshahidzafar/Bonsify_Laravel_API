@extends('common.main')
@section('title','Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<?php $blogs_categories=DB::table('blogs_categories')->get(); ?>
<?php $models=DB::table('car_model')->get(); ?>
<?php $makes=DB::table('car_make')->get(); ?>
<?php $variation=DB::table('car_variation')->get(); ?>
<?php $colors=DB::table('car_colors')->get(); ?>
<?php $years=DB::table('car_years')->get(); ?>

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
                <?php $images =DB::table('listings_images')->where('listings_id','=',$listings[0]->listings_id)->get()?> 
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-6">
                       
                       @foreach($images as $image)
                        <img src="{{asset('uploads/listings/'.$image->image_name)}}">
                       @endforeach
                         
                       
                         
                       
                    </div>
                </div>                  <div class="form-group">
                    <label class="col-sm-3 control-label">Make</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_make_id" name="car_make_id" required disabled="">
                           
                            @foreach ($makes as $category)
                            <option value="{{ $category->car_make_id }}" {{ ($category->car_make_id==$listings[0]->car_make_id) ? 'selected':'' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Model</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_model_id" name="car_model_id" required disabled="">
                           
                            @foreach ($models as $category)
                            <option value="{{ $category->car_model_id }}" {{ ($category->car_model_id==$listings[0]->car_model_id) ? 'selected':'' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
              <div class="form-group">
                    <label class="col-sm-3 control-label">Variation</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_variation_id" name="car_variation_id" required disabled="">
                           
                            @foreach ($variation as $category)
                            <option value="{{ $category->car_variation_id }}" {{ ($category->car_variation_id==$listings[0]->car_variation_id) ? 'selected':'' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Year</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_years_id" name="car_years_id" required disabled="">
                           
                            @foreach ($years as $category)
                            <option value="{{ $category->car_years_id }}" {{ ($category->car_years_id==$listings[0]->car_years_id) ? 'selected':'' }}> {{ $category->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Register</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="register" value="{{ $listings[0]->register }}" readonly   required>
                        
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Exterior Color</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="color_id" name="color_id" required disabled="">
                           
                            @foreach ($colors as $category)
                            <option value="{{ $category->colors_id }}" {{ ($category->colors_id==$listings[0]->color_id) ? 'selected':'' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Mileage km</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="title" value="{{ $listings[0]->km }}"  readonly required>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Price</label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="title" value="{{ $listings[0]->price }}" readonly  required>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-6">
                        <textarea name="description" required rows="10" class="ckeditor" required readonly="">{{htmlspecialchars_decode(html_entity_decode($listings[0]->description))}}</textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required disabled="">
                            <option  {{ ($listings[0]->status=='Active') ? 'selected':'' }}> Active</option>
                            <option  {{ ($listings[0]->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            <option  {{ ($listings[0]->status=='Pending') ? 'selected':'' }}>Pending</option>
                            <option  {{ ($listings[0]->status=='Inreview') ? 'selected':'' }}>Inreview</option>
                            <option  {{ ($listings[0]->status=='Expired') ? 'selected':'' }}>Expired</option>

                        </select>
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