@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<link
    rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css"
  />

  <link rel="stylesheet" href="{{asset('front/assets/css/starrr.css')}}" />
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<?php $years=DB::table('car_years')->get(); ?> 
<div class="container">
   <h1>Write Review</h1>
   <div class="well">
      <form action="{{url('post_review')}}" class="form-horizontal" method="post" >
        {{csrf_field()}}
         @if (session('review_errors'))
            <div class="alert alert-danger">
               {{ session('review_errors') }}
            </div>
            @endif
            @if(session('review_sucess'))
            <div class="alert alert-success">
               {{ session('review_sucess') }}
            </div>
            @endif
         <div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓"><input name="authenticity_token" type="hidden" value="YDq47M/mX9h7JPgdAn+nsm9qg3IcFxoK8ZDDUQT+5HQ="></div>
         <fieldset class="nomargin">
            <legend class="noborder">Car Information</legend>
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
         </fieldset>
         <fieldset>
            <legend class="nomargin">Your Opinion</legend>
            <div class="form-group">
               <label class="col-md-4">Rating<span class="text-error">*</span> </label>
               <div class="col-md-8">
                  <table class="rate-block">
                     <tbody>
                        <tr>
                           <td>Style</td>
                           <td>
                              <span class="star-rating-control">
                                 <div class="rating-cancel" style="display: block;"><a title="Cancel Rating"></a></div>
                                 @for($i=1;$i<=5;$i++)
                                  <div class="style_star star-rating rater-1 star star-rating-applied star-rating-live" id="style_<?php echo $i?>"><a title="Poor">1</a></div>
                                 @endfor

                                
                              </span>
                              
                           </td>
                           <ul class="parsley-errors-list" id="parsley-id-multiple-car_reviewstyle_rating"></ul>
                        </tr>
                        
                        <tr>
                           <td>Comfort</td>
                           <td>
                           <div class='starrr' id='star1'></div>
                              <div>&nbsp;
                                 <span class='your-choice-was' style='display: none;'>
                                 Your rating was <span class='choice'></span>.
                                 </span>
                              </div>

                           </td>
                           <ul class="parsley-errors-list" id="parsley-id-multiple-car_reviewcomfort_rating"></ul>
                        </tr>
                        <tr>
                           <td>Performance</td>
                           <td>
                              <span class="star-rating-control">
                                 <div class="rating-cancel" style="display: block;"><a title="Cancel Rating"></a></div>
                                 <div class="star-rating rater-2 star star-rating-applied star-rating-live" id="car_review_performance_rating_1"><a title="Poor">1</a></div>
                                 <div class="star-rating rater-2 star star-rating-applied star-rating-live" id="car_review_performance_rating_2"><a title="Fair">2</a></div>
                                 <div class="star-rating rater-2 star star-rating-applied star-rating-live" id="car_review_performance_rating_3"><a title="Average">3</a></div>
                                 <div class="star-rating rater-2 star star-rating-applied star-rating-live" id="car_review_performance_rating_4"><a title="Good">4</a></div>
                                 <div class="star-rating rater-2 star star-rating-applied star-rating-live" id="car_review_performance_rating_5"><a title="Excellent">5</a></div>
                              </span>
                              
                           </td>
                           <ul class="parsley-errors-list" id="parsley-id-multiple-car_reviewperformance_rating"></ul>
                        </tr>
                        <tr>
                           <td>Value for Money</td>
                           <td>
                              <span class="star-rating-control">
                                 <div class="rating-cancel" style="display: block;"><a title="Cancel Rating"></a></div>
                                 <div class="star-rating rater-3 star star-rating-applied star-rating-live" id="car_review_value_for_money_rating_1"><a title="Poor">1</a></div>
                                 <div class="star-rating rater-3 star star-rating-applied star-rating-live" id="car_review_value_for_money_rating_2"><a title="Fair">2</a></div>
                                 <div class="star-rating rater-3 star star-rating-applied star-rating-live" id="car_review_value_for_money_rating_3"><a title="Average">3</a></div>
                                 <div class="star-rating rater-3 star star-rating-applied star-rating-live" id="car_review_value_for_money_rating_4"><a title="Good">4</a></div>
                                 <div class="star-rating rater-3 star star-rating-applied star-rating-live" id="car_review_value_for_money_rating_5"><a title="Excellent">5</a></div>
                              </span>
                              
                           </td>
                           <ul class="parsley-errors-list" id="parsley-id-multiple-car_reviewvalue_for_money_rating"></ul>
                        </tr>
                        <tr>
                           <td>Fuel Economy</td>
                           <td>
                              <span class="star-rating-control">
                                 <div class="rating-cancel" style="display: block;"><a title="Cancel Rating"></a></div>
                                 <div class="star-rating rater-4 star star-rating-applied star-rating-live" id="car_review_fuel_economy_rating_1"><a title="Poor">1</a></div>
                                 <div class="star-rating rater-4 star star-rating-applied star-rating-live" id="car_review_fuel_economy_rating_2"><a title="Fair">2</a></div>
                                 <div class="star-rating rater-4 star star-rating-applied star-rating-live" id="car_review_fuel_economy_rating_3"><a title="Average">3</a></div>
                                 <div class="star-rating rater-4 star star-rating-applied star-rating-live" id="car_review_fuel_economy_rating_4"><a title="Good">4</a></div>
                                 <div class="star-rating rater-4 star star-rating-applied star-rating-live" id="car_review_fuel_economy_rating_5"><a title="Excellent">5</a></div>
                              </span>
                            
                           </td>
                           <ul class="parsley-errors-list" id="parsley-id-multiple-car_reviewfuel_economy_rating"></ul>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="form-group">
               <label class="col-md-4">Review Title<span class="text-error">*</span></label>
               <div class="col-md-8">
                  <input  maxlength="100" name="title" size="100" type="text" >
               </div>
            </div>
            <div class="form-group">
               <label class="col-md-4">Your Review<span class="text-error">*</span></label>
               <div class="col-md-8">
                  <textarea cols="40"  placeholder="Exterior:
                     Interior (Features, Space &amp; Comfort):
                     Fuel Economy:
                     Ride Quality &amp; Handling:
                     Final Words:" rows="20" style="color: grey; font-style: italic;" name="review"></textarea>
                  <ul class="parsley-errors-list" id="parsley-id-8660"></ul>
                  <p id="remaining_chars_info"><span>
                     Enter at least (<strong>250</strong>) more characters.</span>
                  </p>
               </div>
            </div>
            <div class="form-group">
               <div class="col-md-offset-4 col-md-8">
                  <select id="car_review_familiarity" name="experience_id" >
                     <option value="">You know this car because...</option>
                     <option value="1">I owned this car.</option>
                     <option value="2">I did not own this car, but have driven this car.</option>
                     <option value="3">I have neither owned nor driven this car.</option>
                  </select>
                  <ul class="parsley-errors-list" id="parsley-id-2047"></ul>
               </div>
            </div>
            <div class="form-group sell-btn-main">
<div class="col-md-offset-4 col-md-8">
<input type="submit" class="btn btn-primary btn-lg fs16" value="Submit Review">
</div>
</div>
         </fieldset>
   </div>
</div>

</form>
</div>
</div>
<script>
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
  /* $('.style_star').click(function(){
    alert(this.id);
    var value = this.id;
    var id = value.split('_');

    alert(id[1]);
   })*/
 

  

</script>

  <script src="{{asset('front/assets/js/starrr.js')}}"></script>
  <script>
    $('#star1').starrr({
      change: function(e, value){
        if (value) {
          $('.your-choice-was').show();
          $('.choice').text(value);
        } else {
          $('.your-choice-was').hide();
        }
      }
    });

    var $s2input = $('#star2_input');
    $('#star2').starrr({
      max: 10,
      rating: $s2input.val(),
      change: function(e, value){
        $s2input.val(value).trigger('input');
      }
    });
  </script>
@endsection