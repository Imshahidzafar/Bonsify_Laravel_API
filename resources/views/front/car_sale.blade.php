@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
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
<div class="remove-section">
   <section class="nopad">
      <div class="well text-center p30 fwl">
         <h1 class="nomargin fs28 fwb">
            Sell your Car With 3 Easy &amp; Simple Steps!
         </h1>
         <p class="fs16" style="color: #070707;">It's free and takes less than a minute</p>
         <div class="sell-header-list">
            <img alt=" Enter Your Car Information" src="https://wsa1.pakwheels.com/assets/sell-icons/car-221614dec8c0f3717dede556a5daad01.svg"> Enter Your Car Information
            <img alt=" Upload Photos" src="https://wsa1.pakwheels.com/assets/sell-icons/photos-708994063564767acaca738e1261f90d.svg"> Upload Photos
            <img alt=" Enter Your Selling Price" src="https://wsa4.pakwheels.com/assets/sell-icons/tag-3ba531fca999b37f89be28609fe9e9c0.svg"> Enter Your Selling Price
         </div>
      </div>
   </section>
   <section>
      <div class="container">
         <form   class="form-horizontal"  role="form"  enctype="multipart/form-data" method="post" action="{{ url('/car_sale_submit') }}"  >
            {{ csrf_field() }}
            @if (session('post_errors'))
            <div class="alert alert-danger">
               {{ session('post_errors') }}
            </div>
            @endif
            @if(session('post_success'))
            <div class="alert alert-success">
               {{ session('post_success') }}
            </div>
            @endif
            <div class="linked-fields pos-rel sell-form-main">
               <div class="well">
                  <fieldset class="nomargin sell-form">
                     <legend class="nomargin noborder">Car Information
                        <span class="sell-mandatory">(All fields marked with <label>*</label> are mandatory)</span>
                     </legend>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> Country</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select id="country-dropdown" name="country_id" disabled>
                                 <option value="Qatar">Qatar</option>
                                 <option value="">Select Country</option>
                                 @foreach ($result as $row)
                                 <option value="{{ $row->id }}"> {{ $row->name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">State</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select class="" id="state-dropdown" name="state_id" disabled>
                                 <option value="Qatar">Qatar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> City</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select  id="city-dropdown" name="city_id" disabled>
                                 <option value="Qatar">Qatar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> Make</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select class="" id="make-dropdown" name="car_make_id" required>
                                 <option>Select </option>
                                 @foreach ($car_make as $make)
                                 <option value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> Model</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select class="" id="model-dropdown" name="car_model_id" required>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Variation</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <select class="" id="variation-dropdown" name="car_variation_id" required> </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Year</label>
                        <div class="col-md-5">
                           <select  name="car_years_id" required="">
                              <option value="">Select Year</option>
                              @foreach ($years as $row)
                              <option value="{{ $row->car_years_id }}"> {{ $row->year }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Exterior color</label>
                        <div class="col-md-5">
                           <select class="" id="country-dropdown" name="color_id" required="">
                              <option value="">Select color</option>
                              @foreach ($colors as $row)
                              <option value="{{ $row->colors_id }}"> {{ $row->name }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> Register In</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <input name="register" required="" placeholder=" Register In" type="text" value="" >
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage"> Mileage <span class="text-error">*</span> <span id="mileage_unit">(km)</span></label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <span class="input-group-addon fs16">KM</span>
                              <input  id="mileage_text" maxlength="6" name="km" placeholder="Mileage" type="text" required="">
                           </div>
                          
                        </div>
                        <div class="col-md-4">
                           <div class="form-hint">
                              <img alt="Hint" class="hint-img" src="https://wsa3.pakwheels.com/assets/hint-b154680e2e42ff019ef2709a2d7f372a.svg">
                              We don't allow promotional messages that are not relevant to the ad
                           </div>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Price</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <input name="price" required="" placeholder="Price" type="text" value="" >
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Description</label>
                        <div class="col-md-9">
                           <div class="col-sm-6">
                             <div class="input-group">
                              <textarea name="description"  rows="10" class="ckeditor  " required></textarea>
                            </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                  </fieldset>
               </div>

               <div class="well">
                  <legend class="nomargin noborder">
                     Upload Photos
                     <span class="text-error"></span>
                  </legend>
                  <div class="upload-image-hint mt20 pos-rel" id="img_upload_tr">
                     <div id="plupload_uploader">
                        <div id="img_upload">
                           <div id="moreUploads">
                              <span id="uploadify_limit_reached" class="uploadify_help" onclick="$(this).hide(); $('#uploadify_limit_help').fadeIn(500);">
                              <img alt="Error-msg-arrow" style="background-image:url('https://wsa4.pakwheels.com/assets/error-msg-arrow-44fdb1d2522ee92c4d83a54fd63919e0.png');">
                              Sorry, you have reached the maximum number of pictures allowed.
                              </span>
                              <div id="container">
                                 
                                 <img alt="Photos" id="upload_image" class="upload-img" src="https://wsa4.pakwheels.com/assets/photos-d7a9ea70286f977064170de1eeb6dca8.svg" style="vertical-align: -5px">
                                 <div class="add-photo mb30 input-group control-group increment">
                                     <input type="file" id="files" name="files[]" multiple  accept="*/" required="" />
                                 </div>
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="row fs13 generic-gray add-photo-info mt40 mb30">
                     <div class="col-md-4 col-md-offset-1">
                        <i class="fa fa-check-circle-o"></i> 
                        <strong class="generic-basic">Adding at least 8 pictures</strong> improves the chances for a quick sale.
                     </div>
                     <div class="col-md-5 col-md-offset-1">
                        <i class="fa fa-check-circle-o"></i>
                        <strong class="generic-basic">Adding clear Front, Back and Interior pictures</strong> of your car increases the quality of your Ad and gets you noticed more.
                     </div>
                     <div class="col-md-5 col-md-offset-4 mt30">
                        <i class="fa fa-check-circle-o"></i> 
                        <strong class="generic-basic">Photos should be</strong>  in 'jpeg, jpg, png, gif' format only.
                     </div>
                  </div>
               </div>
               <div class="well">
                  <fieldset class="nomargin sell-form">
                     <legend class="nomargin noborder">Contact  Information
                     </legend>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Mobile</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <span class="input-group-addon fs16"><i class="fa fa-mobile"></i></span>
                              <input name="mobile" placeholder="mobile" type="text" value="" required="" >
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Secondary Mobile</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <input name="sec_mobile" placeholder="sec_mobile" type="text" value="" required="" >
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                  </fieldset>
               </div>
               <div class="well">
                   
                  <fieldset class="nomargin sell-form">
                     <legend class="nomargin noborder">Car  Information
                     </legend>
                      <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Title</label>
                        <div class="col-md-5">
                           <div class="input-group">
                              <input name="listings_title" placeholder="Add Title" type="text" value="" required="" >
                           </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <?php $type = db::table('engine_type')->get();?>
                    <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Engine Type</label>
                        <div class="col-md-5">
                           <select  name="engine_type_id" required="">
                              <option value="">Select Type</option>
                              @foreach ($type as $row)
                              <option value="{{ $row->engine_type_id }}"> {{ $row->engine_type }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                     <?php $type = db::table('engine_size')->get();?>
                    <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">Engine Size</label>
                        <div class="col-md-5">
                           <select  name="engine_size_id" required="">
                              <option value="">Select Size</option>
                              @foreach ($type as $row)
                              <option value="{{ $row->engine_size_id }}"> {{ $row->size }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>
                      <?php $type = db::table('transmission')->get();?>
                    <div class="form-group">
                        <label class="col-md-3" for="hpi_mileage">transmission</label>
                        <div class="col-md-5">
                           <select  name="transmission_id" required="">
                              <option value="">Select transmission</option>
                              @foreach ($type as $row)
                              <option value="{{ $row->transmission_id }}"> {{ $row->name }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                     </div>

                    
                  </fieldset>
               </div>
              
                  <fieldset>
                     <div class="form-group text-right mb30">
                        <div class="col-md-12">
                           <button type="submit" class="btn btn-success btn-lg fs16" id="submit_form">SUBMIT &amp; CONTINUE</button>
                        </div>
                     </div>
                  </fieldset>
               </div>
            
         </form>
      </div>
   </section>
</div>
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
   
   
   
   $('#country-dropdown').on('change', function() {
   var country_id = this.value;
   $("#state-dropdown").html('');
   $.ajax({
     url:"{{url('/getState')}}",
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
     url:"{{url('/getCity')}}",
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
   
   /*
   <!-- CAR INFO SCRIPT -->*/
   
   $('#make-dropdown').on('change', function() {
   
   var car_make_id = this.value;
   $("#model-dropdown").html('');
   $.ajax({
     url:"{{url('getModel')}}",
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
     url:"{{url('/getVariation')}}",
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
   
   function change_my_image(abc){
        // alert();
        var input = abc;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

                reader.onload = function (e) {
                    $('#upload_image')
                        .attr('src', e.target.result);
                };
            reader.readAsDataURL(input.files[0]);
         }
    }

    $("#upload_image").click(function() {
       $("#my_file").click();
    });
   
   
   
</script>
<script type="text/javascript">
   $(document).ready(function() {
   
     $(".success").click(function(){ 
         var html = $(".clone").html();
         $(".increment").after(html);
     });
   
     $("body").on("click",".btn-danger",function(){ 
         $(this).parents(".control-group").remove();
     });
   
   });
   
</script>
@endsection