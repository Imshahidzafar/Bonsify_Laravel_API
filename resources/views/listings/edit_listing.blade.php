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
<?php $cities=DB::table('cities')->get(); ?>
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<?php $states=DB::table('states')->get(); ?>
<?php $result=DB::table('countries')->get(); ?>
 <?php $car_years=DB::table('car_years')->get(); ?>
 <?php $car_colors=DB::table('car_colors')->get(); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">
           Add Listing
        </h3>

        <div class="example-box-wrapper">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/updatelist/'.$listings->listings_id)}}">
                {{ csrf_field() }}

                 <div class="form-group">
                    <label class="col-sm-3 control-label">Country <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                      <select class="form-control" id="country-dropdown" name="country_id">
                            <option value="">Select Country</option>
                    
                            @foreach ($result as $row)
                            <option value="{{ $row->id }}" {{ ($row->id==$listings->country_id) ? 'selected':'' }}> {{ $row->name }}</option>
                            @endforeach
                    </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">State <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                    <select class="form-control" id="state-dropdown" name="state_id">
                        <option value="">Select state</option>
                       @foreach ($states as $row)
                            <option value="{{ $row->id }}" {{ ($row->id==$listings->state_id) ? 'selected':'' }}> {{ $row->name }}</option>
                            @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">City <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                       <select class="form-control" id="city-dropdown" name="city_id">
                        <option value="">Select city</option>
                       @foreach ($cities as $row)
                            <option value="{{ $row->id }}" {{ ($row->id==$listings->city_id) ? 'selected':'' }}> {{ $row->name }}</option>
                            @endforeach
                    </select>
                    </div>
                </div>
               <div class="form-group">
                    <label class="col-sm-3 control-label">Make <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_make_id" name="car_make_id" required>
                            <option>Select </option>
                            @foreach ($car_make as $make)
                            <option value="{{ $make->car_make_id }}"  {{ ($make->car_make_id==$listings->car_make_id) ? 'selected':'' }}> {{ $make->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Model <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_model_id" name="car_model_id" required>
                            <option>Select Model</option>
                            @foreach ($car_model as $model)
                            <option value="{{ $model->car_model_id }}" {{ ($model->car_model_id==$listings->car_model_id) ? 'selected':'' }} > {{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Variation <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="car_variation_id" name="car_variation_id" required>
                            <option>Select Variation</option>
                            @foreach ($car_variation as $variation)
                            <option value="{{ $variation->car_variation_id }}" {{ ($variation->car_variation_id==$listings->car_variation_id) ? 'selected':'' }} > {{ $variation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                  <div class="form-group">
               <label class="col-sm-3 control-label">Year <span class="text-danger">*</span></label>
               <div class="col-sm-6">
                  <select class="form-control"  name="car_years_id" required>
                     <option>Select Year</option>
                     @foreach ($car_years as $years)
                     <option value="{{ $years->car_years_id }}" {{ ($years->car_years_id==$listings->car_years_id) ? 'selected':'' }} > {{ $years->year }}</option>
                     @endforeach
                  </select>
               </div>
            </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Registered In <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" value="{{ $listings->register }}" name="register" required >
                    </div>
                </div>
               <div class="form-group">
               <label class="col-sm-3 control-label">Exterior Color</label>
               <div class="col-sm-6">
                  <select class="form-control"  name="color_id" required>
                     <option>Select color</option>
                     @foreach ($car_colors as $colors)
                     <option value="{{ $colors->colors_id }}" {{ ($colors->colors_id==$listings->color_id) ? 'selected':'' }} > {{ $colors->name }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mileage (km) <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" value="{{ $listings->km }}" name="km" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Price <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" value="{{ $listings->price }}" class="input-mask form-control" name="price" required >
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-6">
                        <textarea name="description" required rows="10" class="ckeditor " required>{{ htmlspecialchars_decode(html_entity_decode($listings->description))}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" value="{{ $listings->mobile }}" class="input-mask form-control" name="mobile" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> Secondary Mobile </label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" value="{{ $listings->sec_mobile }}" name="sec_mobile" required >
                    </div>
                </div>
                
                
             
                @if($status == "Pending")
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                             <option  value="Active" {{ ($listings->status=='Active') ? 'selected':'' }}> Active</option>
                             <option value="Inactive" {{ ($listings->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            <option value="Pending" {{ ($listings->status=='Pending') ? 'selected':'' }}>Pending</option>
                             <option value="Expire" {{ ($listings->status=='Expire') ? 'selected':'' }}>Expire</option>
                              <option value="Deleted" {{ ($listings->status=='Deleted') ? 'selected':'' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                   
                @elseif($status == "Active")
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            
                             <option value="Inactive" {{ ($listings->status=='Inactive') ? 'selected':'' }}>In Active</option>
                            
                             <option value="Expire" {{ ($listings->status=='Expire') ? 'selected':'' }}>Expire</option>
                              <option value="Deleted" {{ ($listings->status=='Deleted') ? 'selected':'' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                @elseif($status == "Inactive")
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            
                             <option value="Active" {{ ($listings->status=='Active') ? 'selected':'' }}>Active</option>
                            
                             <option value="Expire" {{ ($listings->status=='Expire') ? 'selected':'' }}>Expire</option>
                              <option value="Deleted" {{ ($listings->status=='Deleted') ? 'selected':'' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                 @elseif($status == "Expired")
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            
                             <option value="Renew" {{ ($listings->status=='Renew') ? 'selected':'' }}>Renew</option>
                            
                              <option value="Deleted" {{ ($listings->status=='Deleted') ? 'selected':'' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                 @elseif($status == "Inreview")
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">


                        <select class="form-control" id="status" name="status" required>
                            
                             <option value="Permanent" {{ ($listings->status=='Inreview') ? 'selected':'' }}>Permanent Delete</option>
                            
                              <option value="Deleted" {{ ($listings->status=='Deleted') ? 'selected':'' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" value="Add" style="float: right;"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#country-dropdown').on('change', function() {
    var country_id = this.value;
    $("#state-dropdown").html('');
    $.ajax({
        url:"{{url('/backoffice/getState')}}",
        type: "POST",
        data: {
        country_id: country_id,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function (result) {
            $('#state-dropdown').html('<option value="">Select State</option>');
            $.each(result, function (key, value) {
                $("#state-dropdown").append('<option value="' + key + '">' + value + '</option>');
            });
            $('#city-dropdown').html('<option value="">First Select State</option>');
        }
     });
    });



    $('#state-dropdown').on('change', function() {
    var state_id = this.value;
    $("#city-dropdown").html('');
    $.ajax({
        url:"{{url('/backoffice/getCity')}}",
        type: "POST",
        data: {
        state_id: state_id,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function(result){
        $('#city-dropdown').html('<option value="">Select City</option>'); 
        $.each(result,function(key,value){
        $("#city-dropdown").append('<option value="'+key+'">'+value+'</option>');
        });
        }
     });
    });
});
</script>


<!-- <script type="text/javascript" src="{{asset('assets/citypopup/location.js')}}"></script>
 --><script type="text/javascript">
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