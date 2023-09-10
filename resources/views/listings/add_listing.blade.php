@extends('common.main')

@section('title','Brands')

@section('content')
@inject('helpers', 'App\Classes\Helpers')

<style type="text/css">
.upload-img {
width: 200px;
height: 200px;
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
}
.remove:hover {
  background: white;
  color: black;
}
</style>

<?php $cities=DB::table('city')->get(); ?>
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<?php $result=DB::table('countries')->get(); ?>
<?php $colors=DB::table('car_colors')->get(); ?>
<?php $years=DB::table('car_years')->get(); ?> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">
           Add Listing
        </h3>
           
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

            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/post_listing')}}">
                {{ csrf_field() }}
                

  
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Country <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                      <select class="form-control" id="country-dropdown" name="country_id">
                            <option value="">Select Country</option>
                    
                            @foreach ($result as $row)
                            <option value="{{ $row->id }}"> {{ $row->name }}</option>
                            @endforeach
                    </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">State <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                       <select class="form-control" id="state-dropdown" name="state_id">
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">City <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                       <select class="form-control" id="city-dropdown" name="city_id">
                      </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Make <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="make-dropdown" name="car_make_id" required>
                            <option>Select </option>
                            @foreach ($car_make as $make)
                            <option value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Model <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="model-dropdown" name="car_model_id" required>
                           
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Variation <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="variation-dropdown" name="car_variation_id" required>
                            
                        </select>
                    </div>
                </div>
               
                <div class="form-group">
                    <label class="col-sm-3 control-label">Year<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                      <select class="form-control" required="" id="country-dropdown" name="car_years_id">
                            <option value="">Select Year</option>
                    
                            @foreach ($years as $row)
                            <option value="{{ $row->car_years_id }}"> {{ $row->year }}
                        
                            </option>
                            @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Exterior Color<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                      <select class="form-control" required="" id="country-dropdown" name="color_id">
                            <option value="">Select color</option>
                    
                            @foreach ($colors as $row)
                            <option value="{{ $row->colors_id }}"> {{ $row->name }}
                        
                            </option>
                            @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Registered In <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" required="" class="input-mask form-control" name="register" required >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mileage (km) <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="km" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Price <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="price" required >
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Description <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <textarea name="description"  rows="10" class="ckeditor " required =""></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="mobile" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> Secondary Mobile </label>
                    <div class="col-sm-6">
                        <input type="text" class="input-mask form-control" name="sec_mobile" required >
                    </div>
                </div>
                <div class="form-group" >
                <div class=" "style="display: table;margin: 0 auto;" >
                  <legend class="nomargin noborder">
                     Upload Photos
                     <span class="text-error"></span>
                  </legend>
                  <div class="upload-image-hint mt20 pos-rel" id="img_upload_tr">
                     <div id="plupload_uploader">
                        <div id="img_upload">
                           <div id="moreUploads">
                              
                              <div id="container">
                                 
                                 <img alt="Photos" id="upload_image" class="upload-img" src="https://wsa4.pakwheels.com/assets/photos-d7a9ea70286f977064170de1eeb6dca8.svg" width="50px" height="50px" style="vertical-align: -5px">
                                 <div class="add-photo mb30 input-group control-group increment">
                                     <input type="file" id="files" name="files[]" multiple  required="" />
                                 </div>
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               </div>
             
               
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="status" name="status" required>
                            <option value="1"> Active</option>
                            <option value="Inactive"> Inactive</option>
                            <option value="Pending">Pending</option>
                            <option value="Expired">Expired</option>
                            <option value="Inreview">Inreview</option>
                            
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" value="Add" style="float: right;"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
      $(".btn-success").click(function(){ 
          var lsthmtl = $(".clone").html();
          $(".increment").after(lsthmtl);
      });
      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".hdtuto control-group lst").remove();
      });
    });
</script>



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

<!-- CAR INFO SCRIPT -->

<script>
$(document).ready(function() {
    $('#make-dropdown').on('change', function() {
    
    var car_make_id = this.value;
    $("#model-dropdown").html('');
    $.ajax({
        url:"{{url('/backoffice/getModel')}}",
        type: "POST",
        data: {
        car_make_id: car_make_id,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function (result) {
            $('#model-dropdown').html('<option value="">Select Model</option>');
            $.each(result, function (key, value) {
                $("#model-dropdown").append('<option value="' + key + '">' + value + '</option>');
            });
            $('#variation-dropdown').html('<option value="">First Select Model</option>');
        }
     });
    });



    $('#model-dropdown').on('change', function() {
    var car_model_id = this.value;
    $("#variation-dropdown").html('');
    $.ajax({
        url:"{{url('/backoffice/getVariation')}}",
        type: "POST",
        data: {
        car_model_id: car_model_id,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function(result){
        $('#variation-dropdown').html('<option value="">Select Variation</option>'); 
        $.each(result,function(key,value){
        $("#variation-dropdown").append('<option value="'+key+'">'+value+'</option>');
        });
        }
     });
    });
});
</script>
<script>
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