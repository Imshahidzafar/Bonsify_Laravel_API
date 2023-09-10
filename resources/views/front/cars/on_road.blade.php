@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $cities = DB::table('cities')->get(); ?> 
<div class="conatainer mt-5">
   <div class="well mt20">
      <form accept-charset="UTF-8" action="{{url('/on_road')}}" class="form-horizontal " method="post" >
        {{csrf_field()}}
         <div style="margin:0;padding:0;display:inline"></div>
         <legend class="nomargin noborder">Car Details</legend>
         <?php $car_make=DB::table('car_make')->get(); ?>
         <div class="form-group ">
            <label class="col-md-4" for="city_id"> All makes<span class="text-error">*</span></label>
            <div class="col-md-8">
               <select class="full-field" id="make-dropdown" name="car_make_id" required>
                  <option value="">Make </option>
                  @if(!empty($make))
                   <option id="make1" value="{{ $make }}"> {{ $make->name }}</option>
                  @endif
                  @foreach ($car_make as $make)
                  <option id="make1" value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="form-group">
            <label class="col-md-4" for="city_id"> All model<span class="text-error">*</span></label>
            <div class="col-md-8">
               <div class="input-group">
                  <select class="full-field" id="model-dropdown" name="car_model_id" required>
                     <option value="">First make </option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <label class="col-md-4" > All variation<span class="text-error">*</span></label>
            <div class="col-md-8">
               <div class="input-group">
                  <select class="full-field" id="variation-dropdown" name="car_variation_id" required>
                     <option value="">First model </option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group">
            <label class="col-md-4" for="city_id"> All Cities<span class="text-error">*</span></label>
            <div class="col-md-8">
               <select  name="city_id" required >
                  <option value="">All Cities</option>
                  @foreach($cities as $city)
                  <option value="{{$city->id}}">{{$city->name}}</option>
                  @endforeach
               </select>
               <ul class="parsley-errors-list" id="parsley-id-2957"></ul>
            </div>
         </div>
         <div class="form-group">
            <label class="col-md-4" for="city_id"> I Want to buy in<span class="text-error">*</span></label>
            <div class="col-md-8">
               <select  name="buy_at" required >
                  <option value="4">Just want to check information</option>
                  <option value="1">2 Weeks</option>
                  <option value="2">1 Month</option>
                  <option value="3">2 Month</option>
               </select>
               <ul class="parsley-errors-list" id="parsley-id-0960"></ul>
            </div>
         </div>
         <div class="form-group sell-btn-main">
            <div class="col-md-offset-4 col-md-8">
               <input class="btn btn-primary" name="commit" type="submit" value="Get On Road Price">
            </div>
         </div>
      </form>
      <div class="nomargin generic-light-grey fs12" style="line-height:normal;">
         We at PakWheels respect your privacy and shall never reveal your contact details on the website. By providing these details on PakWheels.com you agree, we or our partner dealers may get in touch with you on the phone to provide you further information and assist you with any transaction.
      </div>
   </div>
</div>
<script>
   $('#make-dropdown').on('change', function() {
   
   var car_make_id = this.value;
   $("#model-dropdown").html('Model');
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
             $("#model-dropdown").append('<option id="model1" value="' + key + '">' + value + '</option>');
         });
         $('#variation-dropdown').html('<option value="">First Select Model</option>');
     }
   });
   });
   
   
   
   $('#model-dropdown').on('change', function() {
   var car_model_id = this.value;
   $("#variation-dropdown").html('Variation');
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
     $("#variation-dropdown").append('<option id="variation1" value="'+key+'">'+value+'</option>');
     });
     }
   });
   }); 
</script>
@endsection