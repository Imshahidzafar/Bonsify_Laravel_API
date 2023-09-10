@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<section>
  @if(!empty($cars))
  <?php $car_make = db::table('car_make')->Where('car_make_id','=',$cars->car_make_id)->first()?>
  <?php $car_model = db::table('car_model')->Where('car_model_id','=',$cars->car_model_id)->first()?>
  <?php $car = DB::table('car_reviews')->where('car_model_id','=',$cars->car_model_id)->where('car_make_id','=',$cars->car_make_id)->get();
        $num_car = sizeof($car,1); 
  ?>
   <div class="container">
      <h1>
         {{$car_make->name}} {{$car_model->name }} Car Reviews
      </h1>
      <div class="well review-basic-details">
         <div class="row">
            <div class="col-md-3 col-md-offset-1">
               <div class="overall-rating-text mt5"><strong>Overall Rating</strong></div>
               <span class="rating generic-orange fs12 ">
                  
               </span><br>
               {{ $num_car}} Reviews
               <span> | </span>
               <span class="add-review-link">
               <a href="{{url('/reviews/new')}}" rel="nofollow">Write Review</a>
               </span>  
            </div>
            <div class="col-md-7">
               <ul class="review-rating list-unstyled nomargin">
                  <li>Style
                   
                   @for($i=1; $i <= 5;$i++)
                       @if($i <= $cars->style) 
                        
                        <i class="fa fa-star"></i>
                        @else
                        <i class="fa fa-star-o "></i>
                        @endif
                   @endfor 
                  </li>
                  <li>Comfort @for($i=1; $i <= 5;$i++)
                       @if($i <= $cars->comfort) 
                        
                        <i class="fa fa-star"></i>
                        @else
                        <i class="fa fa-star-o "></i>
                        @endif
                   @endfor </li>
                  <li>Fuel Economy  @for($i=1; $i <= 5;$i++)
                       @if($i <= $cars->fuel_economy) 
                        
                        <i class="fa fa-star"></i>
                        @else
                        <i class="fa fa-star-o "></i>
                        @endif
                   @endfor </li>
                  <li>Performance  @for($i=1; $i <= 5;$i++)
                       @if($i <= $cars->performance) 
                        
                        <i class="fa fa-star"></i>
                        @else
                        <i class="fa fa-star-o "></i>
                        @endif
                   @endfor </li>
                  <li>Value for Money  @for($i=1; $i <= 5;$i++)
                       @if($i <= $cars->money_value) 
                        
                        <i class="fa fa-star"></i>
                        @else
                        <i class="fa fa-star-o "></i>
                        @endif
                   @endfor </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   @endif
</section>
@endsection