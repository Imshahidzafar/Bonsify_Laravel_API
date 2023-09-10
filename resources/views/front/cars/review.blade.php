@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')


<?php $car_make=DB::table('car_make')->get(); ?>
<section class="search-main-outer">
    <div id="top-search-heading" class="search-main-head">
      <div class="container">
        <h1>Search Car Reviews</h1>
        <p> Explore user ratings and read opinions</p>
      </div>
    </div>
    
     
    <div class="search-main">
      <form action="{{url('/reviews')}}" method="POST">
          {{ csrf_field() }}
      <ul class="search-fields search-fields3 clearfix mb0">
        <li>
          <select  id="make-dropdown" name="car_make_id" required >
            <option value="">All Makes</option>
             @foreach ($car_make as $make)
              <option value="{{ $make->car_make_id }}"> {{ $make->name }}</option>
            @endforeach
        </select>
        </li>
        <li>
          <select class="" id="model-dropdown" name="car_model_id" required>
              <option value="">All Models</option>                </select>
        </li>
        <li>
          <div class="search-functions mt0">
            <button class="btn btn-success btn-lg btn-block" type="submit">Search</button>
          </div>
        </li>
      </ul>
      </form>
    </div>
 

  <div class="container">
    <div class="well clearfix">
      <a href="{{url('/reviews/new')}}" id="ga-car-write-reviews-home" class="btn btn-danger btn-lg pull-right">Write Review</a>
      <img alt="Write" class="pull-left mr30" src="https://wsa2.pakwheels.com/assets/write-974f4a10d6e3c050e460aaf9ddc7c221.svg" width="52">
      <p class="mt10 fs16">Share first hand experince of your car with other buyers.</p>
    </div>

    <!-- #TODO : Multiple checks for ad to not show -->
   

  </div>
</section>
<script>
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
   </script>
@endsection