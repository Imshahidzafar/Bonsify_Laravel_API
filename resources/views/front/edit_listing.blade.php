@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style type="text/css">
   img {
   max-width: 100px;
  
   border-radius: 50%;
   background-position: center center;
   background-size: cover;
   }

</style>
<style>
input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
   background: red;
}
.remove:hover {
  background: white;
  color: black;
}
</style>
<?php $cities=DB::table('cities')->get(); ?>
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<?php $car_years=DB::table('car_years')->get(); ?>
<?php $car_colors=DB::table('car_colors')->get(); ?>
<?php $states=DB::table('states')->get(); ?>
<?php $result=DB::table('countries')->get(); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="panel">
   <div class="panel-body">
      <h3 class="title-hero">
      </h3>
      <div class="example-box-wrapper">
         <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/update_listing/'.$listings->listings_id)}}">
            {{ csrf_field() }}
            @if (session('list_errors'))
            <div class="alert alert-danger">
               {{ session('list_errors') }}
            </div>
            @endif
            @if(session('list_success'))
            <div class="alert alert-success">
               {{ session('list_success') }}
            </div>
            @endif
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
                  <input type="text" class="input-mask form-control" value="{{ $listings->km }}" name="km" required="" >
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
             <div class="form-group">
                    <label class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-6">
                        <div class="row">
                        @foreach($images as $image)
                            <div class="col-md-4">
                            <img  style="display: table; margin: 0 auto" src="{{asset('uploads/listings/'.$image->image_name)}}">
                            <div style="display: table; margin: 0 auto">
                            <a class="btn-danger " onclick="alert('are you sure')" href= "{{url('/del_listing_image/'.$image->listings_images_id.'/'.$listings->listings_id)}}">Remove </a>
                          </div>
                           </div>
                       
                       @endforeach
                       </div>
                    </div>

            </div>
            <?php $type = db::table('engine_type')->get();?>
            <div class="form-group">
               <label class="col-sm-3 control-label">Engine Type <span class="text-danger">*</span></label>
               <div class="col-sm-6">
                  <select class="form-control" id="country-dropdown" name="country_id">
                     <option value="">Select Type</option>
                     @foreach ($type as $row)
                     <option value="{{ $row->engine_type_id }}" {{ ($row->engine_type_id==$listings->engine_type_id) ? 'selected':'' }}> {{ $row->engine_type }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            <?php $type = db::table('engine_size')->get();?>
            <div class="form-group">
               <label class="col-sm-3 control-label">Engine Size <span class="text-danger">*</span></label>
               <div class="col-sm-6">
                  <select class="form-control" id="country-dropdown" name="country_id">
                     <option value="">Select Size</option>
                     @foreach ($type as $row)
                     <option value="{{ $row->engine_size_id }}" {{ ($row->engine_size_id==$listings->engine_size_id) ? 'selected':'' }}> {{ $row->size }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            <?php $type = db::table('transmission')->get();?>
            <div class="form-group">
               <label class="col-sm-3 control-label"> transmission <span class="text-danger">*</span></label>
               <div class="col-sm-6">
                  <select class="form-control" id="country-dropdown" name="country_id">
                     <option value="">Select transmission</option>
                     @foreach ($type as $row)
                     <option value="{{ $row->transmission_id }}" {{ ($row->transmission_id==$listings->transmission_id) ? 'selected':'' }}> {{ $row->name }}</option>
                     @endforeach
                  </select>
               </div>
            </div>

            


        
           <div class="form-group">
            <label class="col-sm-3 control-label">Image</label>
            <div class="col-sm-6">
                <img alt="Photos" id="upload_image" class="upload-img" src="https://wsa4.pakwheels.com/assets/photos-d7a9ea70286f977064170de1eeb6dca8.svg" style="vertical-align: -5px">
                         <div class="add-photo mb30 input-group control-group increment">
                             <input type="file" id="files" name="files[]" multiple />
                         </div>
            </div>
           </div>
                
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
            <div class="form-group">
               <div class="col-sm-12">
                  <input type="submit" class="btn btn-primary" value="Update" style="display: table; margin: 0 auto;"></button>
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
<script type="text/javascript">
  $(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
           
            "<br/><span class=\"remove\">Remove image</span>" +
             "<br/><input type=\"radio\" name=\"cover_image\" value=\"Yes\" >Cover image</span>"+
            "</span>").insertAfter("#files");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
      console.log(files);
    });
  } else {
    alert("Your browser doesn't support to File API")
  }
});
</script>
@endsection