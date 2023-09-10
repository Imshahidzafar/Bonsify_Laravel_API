@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style type="text/css">
   .modal-dialog {
   position:fixed;
   max-width: 100% !important;
   bottom:-30px;
   }  
   .selected{
   color: #fff!important;
   background-color: #b63339;
   }
</style>
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<div class="container mt-5" id="default">
   <div class="search-main vehicle-comparison-page mb0 mt-5">
      <form  action="{{url('/compare_cars')}}"  method="post" >
         {{csrf_field()}}
         
         <script type="text/javascript">var type = "car"; </script>
         <script src="https://wsa1.pakwheels.com/assets/classified/make_model_version_selection_popup-9cc49dcf5bad0fef13f816caf5de7548.js" type="text/javascript"></script>
         <div id="get-car-name" class="modal get-car-name" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:902px;">
               <div class="modal-content">
                  <div class="modal-body clearfix">
                     <div class="col col-3 cat-selection makes pull-left">
                        <div class="header-car-info arrow-right">Make</div>
                       
                        <div class="form-group nomargin">
                           <ul class="fs14 get-listing make-listings ">
                               @foreach($car_make as $make)
                              <li >
                                 <button class="make border-0" id="{{ $make->car_make_id}}" value="{{ $make->car_make_id}}">
                                 <span class="car-make-logo"></span>{{ $make->name}} <i class="fa fa-angle-right"></i>
                                </button>
                              </li>
                               @endforeach
                           </ul>
                        </div>
                       
                     </div>
                     <div class="col col-3 cat-selection models pull-left">
                        <div class="header-car-info arrow-right">Model</div>
                        <div class="form-group nomargin">
                           <ul  >
                              
                              <button class="model"></button> 
                             
                           </ul>
                           
                        </div>
                     </div>
                     <div class="col col-3 cat-selection versions pull-left">
                        <div class="header-car-info arrow-right">Version
                           <span>(Optional)</span>
                        </div>
                        <div class="form-group nomargin version-listings-outer">
                           <ul class="fs14 get-listing version-listings">
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <input id="locale" name="locale" type="hidden">
                     <button class="btn btn-primary submit-button" data-dismiss="modal" disabled="">done</button>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix">
            <ul class="search-fields search-fields3 clearfix mt10 mb0">
               <li>
                  <input id="car_id_0" name="car_id_0" type="hidden">
                  <legend>Select Car-1</legend>
                  
                  <div class="form-group">
                     <div class="input-group">
                        <select class="full-field" id="make-dropdown" name="car_make_id" required>
                           <option>Make </option>
                           @foreach ($car_make as $make)
                           <option id="make1" value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                     <div class="form-group">
                    
                        <div class="input-group">
                           <select class="full-field" id="model-dropdown" name="car_model_id" required><option>First make </option>
                           </select>
                        </div>
                       
                     </div>
                     <div class="form-group">
                           <div class="input-group">
                              <select class="full-field" id="variation-dropdown" name="car_variation_id" required> <option>First model </option></select>
                           </div>
                       
                     </div>
               </li>
               <li>
                  <input id="car_id_1" name="car_id_1" type="hidden">
                  <legend>Select Car-2</legend>
                  <div class="form-group">
                     <div class="input-group">
                        <select class="full-field" id="make2-dropdown" name="car_make_id2" required>
                           <option>Make </option>
                           @foreach ($car_make as $make)
                           <option id="make2" value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                     <div class="form-group">
                    
                        <div class="input-group">
                           <select class="full-field" id="model2-dropdown" name="car_model_id2" required><option>First make </option> 
                           </select>
                        </div>
                       
                     </div>
                     <div class="form-group">
                           <div class="input-group">
                              <select class="full-field" id="variation2-dropdown" name="car_variation_id2" required><option>First model </option></select>
                           </div>
                       
                     </div>
               </li>
               <li>
                  <input id="car_id_2" name="car_id_2" type="hidden">
                  <legend>Select Car-3</legend>
                  <div class="form-group ">
                     <div class="input-group">
                        <select class="full-field" id="make3-dropdown" name="car_make_id3" required>
                           <option>Make </option>
                           @foreach ($car_make as $make)
                           <option id="make3" value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                     <div class="form-group">
                    
                        <div class="input-group">
                           <select class="full-field" id="model3-dropdown" name="car_model_id3" required><option>First make </option>
                           </select>
                        </div>
                       
                     </div>
                     <div class="form-group">
                           <div class="input-group">
                              <select class="full-field" id="variation3-dropdown" name="car_variation_id3" required>
                              <option>First Model </option> </select>
                           </div>
                       
                     </div>
               </li>
            </ul>
            <div id="vehicle_selector_error" class="generic-red fs12 pull-left"></div>
           
         </div>
         <div class="search-functions clearfix">
            <button class=" btn btn-danger clear"> Clear</button>
            <button class=" btn btn-success compare" type="submit"> Compare</button>
           
         </div>
      </form>
   </div>
</div>
<div class="container" id="comparision">
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

   $('#make2-dropdown').on('change', function() {
   
   var car_make_id = this.value;
   $("#model2-dropdown").html('Model');
   $.ajax({
     url:"{{url('getModel')}}",
     type: "POST",
     data: {
     car_make_id: car_make_id,
     _token: '{{csrf_token()}}' 
     },
     dataType : 'json',
     success: function (result) {
         $('#model2-dropdown').html('<option value="">Select Model</option>');
         $.each(result, function (key, value) {
             $("#model2-dropdown").append('<option id="model2" value="' + key + '">' + value + '</option>');
         });
         $('#variation2-dropdown').html('<option value="">First Select Model</option>');
     }
   });
   });
   
   
   
   $('#model2-dropdown').on('change', function() {
   var car_model_id = this.value;
   $("#variation2-dropdown").html('Variation');
   $.ajax({
     url:"{{url('/getVariation')}}",
     type: "POST",
     data: {
     car_model_id: car_model_id,
     _token: '{{csrf_token()}}' 
     },
     dataType : 'json',
     success: function(result){
     $('#variation2-dropdown').html('<option value="">Select Variation</option>'); 
     $.each(result,function(key,value){
     $("#variation2-dropdown").append('<option id="variation2" value="'+key+'">'+value+'</option>');
     });
     }
   });
   }); 

   $('#make3-dropdown').on('change', function() {
   
   var car_make_id = this.value;
   $("#model3-dropdown").html('Model');
   $.ajax({
     url:"{{url('getModel')}}",
     type: "POST",
     data: {
     car_make_id: car_make_id,
     _token: '{{csrf_token()}}' 
     },
     dataType : 'json',
     success: function (result) {
         $('#model3-dropdown').html('<option value="">Select Model</option>');
         $.each(result, function (key, value) {
             $("#model3-dropdown").append('<option id="model3" value="' + key + '">' + value + '</option>');
         });
         $('#variation3-dropdown').html('<option  value="">First Select Model</option>');
     }
   });
   });
   
   
   
   $('#model3-dropdown').on('change', function() {
   var car_model_id = this.value;
   $("#variation3-dropdown").html('Variation');
   $.ajax({
     url:"{{url('/getVariation')}}",
     type: "POST",
     data: {
     car_model_id: car_model_id,
     _token: '{{csrf_token()}}' 
     },
     dataType : 'json',
     success: function(result){
     $('#variation3-dropdown').html('<option  value="">Select Variation</option>'); 
     $.each(result,function(key,value){
     $("#variation3-dropdown").append('<option id="variation3" value="'+key+'">'+value+'</option>');
     });
     }
   });
   });   

  
   </script>
   <script type="text/javascript">
      $('.clear').on('click', function() {
      alert('');
      
      $('#model3-dropdown').html('');
       $('#variation3-dropdown').html('');
        $('#model2-dropdown').html('');
       $('#variation2-dropdown').html('');
        $('#model-dropdown').html('');
       $('#variation-dropdown').html('');
     
   });
      $('.compare').on('click',function () {
     
      var make_id1=document.getElementById('make1').value;
      var model_id1=document.getElementById('model1').value;
      var variation_id1=document.getElementById('variation1').value;

      var make_id2=document.getElementById('make2').value;
      var model_id2=document.getElementById('model2').value;
      var variation_id2=document.getElementById('variation2').value;

      var make_id3=document.getElementById('make3').value;
      var model_id3=document.getElementById('model3').value;
      var variation_id3=document.getElementById('variation3').value;

      $.ajax({
        url:"{{url('/compare_cars')}}",
        type: "POST",
        data: {
        car_make_id: make_id1,
        car_model_id : model_id1,
        car_variation_id:variation_id1,

        car_make_id2: make_id2,
        car_model_id2 : model_id2,
        car_variation_id2:variation_id2,

        car_make_id3: make_id3,
        car_model_id3 : model_id3,
        car_variation_id3:variation_id3,
        _token: '{{csrf_token()}}' 
        },
        dataType : 'html',
        success: function(result){
          /*$("#default").hide();*/
             $("#comparision").html(result);

        }
      });




      });
   </script>
@endsection