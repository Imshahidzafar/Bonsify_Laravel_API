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
<div class="container">
   <div class="well mt-5">
      <form method="get" class="form-nomargin">
         <table class="table vehicle-compare-head text-center well">
            <tbody>
               <tr>
                 
                  <td> @if(!empty($car1)) 
                        <a href="/new-cars/suzuki/cultus/">
                        <div class="img-box">
                           <div class="img-content img-valign">
                              @if($car1->car_image)
                              <img alt="Wagon-r" class="lazy" id="" src="{{asset('/uploads/users/'.$car1->car_image)}}">
                              @else
                              <img class="lazy" src="https://dev.eigix.com/sayarat/public/uploads/make/default-image.jpg">
                              @endif
                           </div>
                        </div>
                        <h3 class="nomargin mt10">{{$car1->car_name}}</h3>
                     </a>
                   @endif
                </td>
                  
                  <td>
                     @if(!empty($car2))
                     <a href="/new-cars/suzuki/cultus/">
                        <div class="img-box">
                           <div class="img-content img-valign">
                               @if($car2->car_image)
                              <img alt="Wagon-r" class="lazy" id="" src="{{asset('/uploads/users/'.$car2->car_image)}}">
                              @else
                              <img class="lazy" src="https://dev.eigix.com/sayarat/public/uploads/make/default-image.jpg">
                              @endif
                           </div>
                        </div>
                        <h3 class="nomargin mt10">{{$car2->car_name}}</h3>
                     </a>
                     @endif
                  </td>
                  <td>
                      @if(!empty($car3))
                     <a href="/new-cars/suzuki/wagon-r/">
                        <div class="img-box">
                           <div class="img-content img-valign">
                               @if($car3->car_image)
                              <img alt="Wagon-r" class="lazy" id="" src="{{asset('/uploads/users/'.$car1->car_image)}}">
                              @else
                              <img class="lazy" src="https://dev.eigix.com/sayarat/public/uploads/make/default-image.jpg">
                              @endif
                           </div>
                        </div>
                        <h3 class="nomargin mt10">{{$car3->car_name}}</h3>
                     </a>
                     @endif
                  </td>
               </tr>
               <tr>
                 <!--  <td>
                     <h4 align="right" class="mt10">Select Variant:</h4>
                  </td>
                  <td>
                     <select class="make" name="version1" data-parsley-required="true" data-parsley-trigger="change" id="VersionID_0" disabled="" onchange="this.form.submit();">
                        <option value="3353,707">Hybrid FZ</option>
                     </select>
                  </td>
                  <td>
                     <select class="make" name="version2" data-parsley-required="true" data-parsley-trigger="change" id="VersionID_1" disabled="" onchange="this.form.submit();">
                        <optgroup label="2017 - 2021 ">
                           <option value="3353,707">Hybrid FZ</option>
                        </optgroup>
                     </select>
                  </td> -->
               </tr>
               <tr>
                  <td class="ratings">
                     @if(!empty($car1))
                     <?php  $review1 = db::table('car_reviews')->where('car_make_id','=',$car1->car_make_id)->where('car_model_id','=',$car1->car_model_id)->where('car_variation_id','=',$car1->car_variation_id)->get();
                             $num_review1 =sizeof($review1,1)?>
                     <label class="generic-green nomargin show"><strong class="fs22"></strong></label>
                     <span class="rating generic-orange fs12 ">
                        @for($i=1;$i<=5;$i++)
                        @if($i <= 5-$num_review1) 
                        <i class="fa fa-star-o "></i>
                        @else
                        <i class="fa fa-star pull-left"></i>
                        @endif   
                        @endfor 
                     </span>
                     <div class="clearfix"></div>
                     <a href="{{url('/reviews')}}">{{$num_review1}} Reviews</a>
                     @endif
                  </td>
                  <td class="ratings">
                     @if(!empty($car2))
                        <?php  $review2 = db::table('car_reviews')->where('car_make_id','=',$car2->car_make_id)->where('car_model_id','=',$car2->car_model_id)->where('car_variation_id','=',$car2->car_variation_id)->get();
                                $num_review2 =sizeof($review2,1)?>;
                        <label class="generic-green nomargin show"><strong class="fs22"></strong></label>
                        <span class="rating generic-orange fs12 ">
                           @for($i=1;$i<=5;$i++)
                           @if($i <= 5-$num_review2) 
                           <i class="fa fa-star-o "></i>
                           @else
                           <i class="fa fa-star pull-left"></i>
                           @endif   
                           @endfor 
                        
                     </span>
                     <div class="clearfix"></div>
                     <a href="{{url('/reviews')}}">{{$num_review2}} Reviews</a>
                     @endif
                  </td>
                 <td class="ratings">
                  @if(!empty($car3))
                     <?php  $review3 = db::table('car_reviews')->where('car_make_id','=',$car3->car_make_id)->where('car_model_id','=',$car3->car_model_id)->where('car_variation_id','=',$car3->car_variation_id)->get();
                             $num_review3 =sizeof($review3,1)?>;
                     <label class="generic-green nomargin show"><strong class="fs22"></strong></label>
                     <span class="rating generic-orange fs12 ">
                        @for($i=1;$i<=5;$i++)
                        @if($i <= 5-$num_review3) 
                        <i class="fa fa-star-o "></i>
                        @else
                        <i class="fa fa-star pull-left"></i>
                        @endif   
                        @endfor 
                     </span>
                     <div class="clearfix"></div>
                     <a href="{{url('/reviews')}}">{{$num_review3}} Reviews</a>
                     @endif
                  </td>
               </tr>
               <!-- <tr>
                  <td>             
                  </td>
                  <td>
                     <a href="/used-cars/suzuki-cultus/660" class="show nomargin" data-category-name="" onclick="trackEvents('CarSearch','From - ComparisonDetail','2000 Suzuki Cultus vxl-2')">
                        <h3 class="nomargin fs14">
                           Used Suzuki Cultus For Sale
                        </h3>
                     </a>
                  </td>
                  <td>
                     <a href="/used-cars/suzuki-wagon-r/801" class="show nomargin" data-category-name="" onclick="trackEvents('CarSearch','From - ComparisonDetail','2014 Suzuki Wagon R vx')">
                        <h3 class="nomargin fs14">
                           Used Suzuki Wagon R For Sale
                        </h3>
                     </a>
                  </td>
               </tr> -->
            </tbody>
         </table>
      </form>
   </div>
</div>
<div class="container ">
   <div class="col-md-12">
      <h2>Compare Specifications</h2>
      <div class="details">
         <table class="table table-bordered table-striped table-styled well nomargin">
            <tbody>
               <tr>

                  <td>Overall Length</td>
                  <td>@if($car1) {{$car1->overall_length }} mm @endif</td>
                  <td>@if($car2) {{$car2->overall_length }} mm @endif</td>
                  <td>@if($car3) {{$car3->overall_length }} mm @endif</td>
               </tr>
               <tr>
                  <td>Overall Width</td>
                   <td>@if($car1) {{$car1->overall_width }} mm @endif</td>
                  <td>@if($car2) {{$car2->overall_width }} mm @endif</td>
                  <td>@if($car3) {{$car3->overall_width }} mm @endif</td>
               </tr>
               <tr>
                  <td>Overall Height</td>
                   <td>@if($car1) {{$car1->overall_height }} mm @endif</td>
                  <td>@if($car2) {{$car2->overall_height }} mm @endif</td>
                  <td>@if($car3) {{$car3->overall_height}} mm @endif</td>
               </tr>
               <tr>
                  <td>Wheel Base</td>
                   <td>@if($car1) {{$car1->wheel_bus }} mm @endif</td>
                  <td>@if($car2) {{$car2->wheel_bus }} mm @endif</td>
                  <td>@if($car3) {{$car3->wheel_bus }} mm @endif</td>
               </tr>
               <tr>
                  <td>Ground Clearance</td>
                  <td>@if($car1) {{$car1->ground_clearnce }} mm @endif</td>
                  <td>@if($car2) {{$car2->ground_clearnce }} mm @endif</td>
                  <td>@if($car3) {{$car3->ground_clearnce }} mm @endif</td>
               </tr>
               <tr>
                  <td>Kerb Weight</td>
                   <td>@if($car1) {{$car1->kerb_weight }} KG @endif</td>
                  <td>@if($car2) {{$car2->kerb_weight }} KG @endif</td>
                  <td>@if($car3) {{$car3->kerb_weight }} KG @endif</td>
               </tr>

            </tbody>
         </table>
         <table class="table table-bordered table-striped table-styled well nomargin mt-1  more" style="overflow: auto;" id="more_new_car_specs">
            <tbody>
               <tr>
                  <td>Fuel Tank Capacity</td>
                  <td>@if($car1) {{$car1->fuel_tank_capacity }} L @endif</td>
                  <td>@if($car2) {{$car2->fuel_tank_capacity }} L @endif</td>
                  <td>@if($car3) {{$car3->fuel_tank_capacity }} L @endif</td>
               </tr>
               <tr>
                  <td>Mileage Overall</td>
                  <td>@if($car1) {{$car1->mileage_overall }}  KM/L @endif</td>
                  <td>@if($car2) {{$car2->mileage_overall }}  KM/L @endif</td>
                  <td>@if($car3) {{$car3->mileage_overall }}  KM/L @endif</td>
               </tr>
               <tr>
                  <td>Mileage Highway </td>
                  <td>@if($car1) {{$car1->mileage_highway }} KM/L  @endif</td>
                  <td>@if($car2) {{$car2->mileage_highway }}  KM/L @endif</td>
                  <td>@if($car3) {{$car3->mileage_highway }}  KM/L @endif</td>
               </tr>
                <tr>
                  <td>Mileage City </td>
                  <td>@if($car1) {{$car1->mileage_city }} KM/L  @endif</td>
                  <td>@if($car2) {{$car2->mileage_city }}  KM/L @endif</td>
                  <td>@if($car3) {{$car3->mileage_city }}  KM/L @endif</td>
               </tr>
                <tr>
                  <td>No. of Doors </td>
                  <td>@if($car1) {{$car1->doors }}doors   @endif</td>
                  <td>@if($car2) {{$car2->doors }}  doors @endif</td>
                  <td>@if($car3) {{$car3->doors }} doors  @endif</td>
               </tr>
                <tr>
                  <td>Displacement </td>
                  <td>@if($car1) {{$car1->displacement }} cc  @endif</td>
                  <td>@if($car2) {{$car2->displacement }}  cc @endif</td>
                  <td>@if($car3) {{$car3->displacement }}  cc @endif</td>
               </tr>
               <tr>
                  <td>Power </td>
                  <td>@if($car1) {{$car1->power }}Hp@rpm   @endif</td>
                  <td>@if($car2) {{$car2->power }} Hp@rpm  @endif</td>
                  <td>@if($car3) {{$car3->power }} Hp@rpm  @endif</td>
               </tr>
               <tr>
                  <td>Torque </td>
                  <td>@if($car1) {{$car1->torque }} Nm@rpm  @endif</td>
                  <td>@if($car2) {{$car2->torque }} Nm@rpm  @endif</td>
                  <td>@if($car3) {{$car3->torque }} Nm@rpm  @endif</td>
               </tr>
                <tr>
                  <td>Transmission Type </td>
                  <td>@if($car1) {{$car1->transmision_type }}   @endif</td>
                  <td>@if($car2) {{$car2->transmision_type }}   @endif</td>
                  <td>@if($car3) {{$car3->transmision_type }}   @endif</td>
               </tr>
                <tr>
                  <td>Gear Speed </td>
                  <td>@if($car1) {{$car1->gear_speed }}   @endif</td>
                  <td>@if($car2) {{$car2->gear_speed }}   @endif</td>
                  <td>@if($car3) {{$car3->gear_speed }}   @endif</td>
               </tr>
                <tr>
                  <td>Minimum Turning Radius</td>
                  <td>@if($car1) {{$car1->min_turning_radius }}m   @endif</td>
                  <td>@if($car2) {{$car2->min_turning_radius }}m   @endif</td>
                  <td>@if($car3) {{$car3->min_turning_radius }}m   @endif</td>
               </tr>
                <tr>
                  <td>Valve Mechanism</td>
                  <td>@if($car1) {{$car1->valve_mechanism }}   @endif</td>
                  <td>@if($car2) {{$car2->valve_mechanism }}   @endif</td>
                  <td>@if($car3) {{$car3->valve_mechanism }}   @endif</td>
               </tr>
                <tr>
                  <td>Comparison Ratio</td>
                  <td>@if($car1) {{$car1->comparison_ratio }}   @endif</td>
                  <td>@if($car2) {{$car2->comparison_ratio }}   @endif</td>
                  <td>@if($car3) {{$car3->comparison_ratio }}   @endif</td>
               </tr>
                <tr>
                  <td>No OF Cylinder</td>
                  <td>@if($car1) {{$car1->no_cylinder }}   @endif</td>
                  <td>@if($car2) {{$car2->no_cylinder }}   @endif</td>
                  <td>@if($car3) {{$car3->no_cylinder }}   @endif</td>
               </tr>
                <tr>
                  <td>Cylinder Configuration</td>
                  <td>@if($car1) {{$car1->cylinder_configuration }}   @endif</td>
                  <td>@if($car2) {{$car2->cylinder_configuration }}   @endif</td>
                  <td>@if($car3) {{$car3->cylinder_configuration }}   @endif</td>
               </tr>
                <tr>
                  <td>Valves Cylinder </td>
                  <td>@if($car1) {{$car1->valves_cylinder }}   @endif</td>
                  <td>@if($car2) {{$car2->valves_cylinder }}   @endif</td>
                  <td>@if($car3) {{$car3->valves_cylinder }}   @endif</td>
               </tr>
                <tr>
                  <td>Fuel Type</td>
                  <td>@if($car1) {{$car1->fuel_type }}   @endif</td>
                  <td>@if($car2) {{$car2->fuel_type }}   @endif</td>
                  <td>@if($car3) {{$car3->fuel_type }}   @endif</td>
               </tr>
               <tr>
                  <td>Fuel System</td>
                  <td>@if($car1) {{$car1->fuel_system }}   @endif</td>
                  <td>@if($car2) {{$car2->fuel_system }}   @endif</td>
                  <td>@if($car3) {{$car3->fuel_system }}   @endif</td>
               </tr>
                <tr>
                  <td>Front Suspension</td>
                  <td>@if($car1) {{$car1->front_suspension }}   @endif</td>
                  <td>@if($car2) {{$car2->front_suspension }}   @endif</td>
                  <td>@if($car3) {{$car3->front_suspension }}   @endif</td>
               </tr>
               <tr>
                  <td>Rear Suspension</td>
                  <td>@if($car1) {{$car1->rear_suspension }}   @endif</td>
                  <td>@if($car2) {{$car2->rear_suspension }}   @endif</td>
                  <td>@if($car3) {{$car3->rear_suspension }}   @endif</td>
               </tr>
               <tr>
                  <td>Steering Type</td>
                  <td>@if($car1) {{$car1->steering_type }}   @endif</td>
                  <td>@if($car2) {{$car2->steering_type }}   @endif</td>
                  <td>@if($car3) {{$car3->steering_type }}   @endif</td>
               </tr>
               <tr>
                  <td>Power Assisted</td>
                  <td>@if($car1) {{$car1->power_assisted }}   @endif</td>
                  <td>@if($car2) {{$car2->power_assisted }}   @endif</td>
                  <td>@if($car3) {{$car3->power_assisted }}   @endif</td>
               </tr>
                <tr>
                  <td>Front Brakes</td>
                  <td>@if($car1) {{$car1->front_brakes }}   @endif</td>
                  <td>@if($car2) {{$car2->front_brakes }}   @endif</td>
                  <td>@if($car3) {{$car3->front_brakes }}   @endif</td>
               </tr>
               
                <tr>
                  <td>Rear Brakes</td>
                  <td>@if($car1) {{$car1->rear_brakes }}   @endif</td>
                  <td>@if($car2) {{$car2->rear_brakes }}   @endif</td>
                  <td>@if($car3) {{$car3->rear_brakes }}   @endif</td>
               </tr>
                <tr>
                  <td>Wheel Type</td>
                  <td>@if($car1) {{$car1->wheel_type }}   @endif</td>
                  <td>@if($car2) {{$car2->wheel_type }}   @endif</td>
                  <td>@if($car3) {{$car3->wheel_type }}   @endif</td>
               </tr>
               <tr>
                  <td>Wheel Size</td>
                  <td>@if($car1) {{$car1->wheel_size }}   @endif</td>
                  <td>@if($car2) {{$car2->wheel_size }}   @endif</td>
                  <td>@if($car3) {{$car3->wheel_size }}   @endif</td>
               </tr>
               <tr>
                  <td>Tyres</td>
                  <td>@if($car1) {{$car1->tyres }}   @endif</td>
                  <td>@if($car2) {{$car2->tyres }}   @endif</td>
                  <td>@if($car3) {{$car3->tyres }}   @endif</td>
               </tr>
               <tr>
                  <td>PCD</td>
                  <td>@if($car1) {{$car1->pcd }}   @endif</td>
                  <td>@if($car2) {{$car2->pcd }}   @endif</td>
                  <td>@if($car3) {{$car3->pcd }}   @endif</td>
               </tr>
            </tbody>
         </table>
         <div>
            <a href="javascript:" class="pull-right"  id="more-specs" style="display: none;">More Specifications <i class="fa fa-chevron-circle-down"></i></a>
            <a href="javascript:" class="pull-right" id="less-specs" style=""> Less Specifications <i class="fa fa-chevron-circle-up "></i></a>
         </div>
         <div class="clearfix"></div>
      </div>
   </div>
</div>
<div class="container">
   <div class="mb40 vehicle-comparison-detail">
      <h2>Compare Features</h2>
      <div class="details">
         <table class="table table-bordered table-striped table-styled nomargin well">
            <tbody>
               <tr>
                  <td>Air Conditioner</td>
                   @if($car1)
                      @if($car1->air_conditioner=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car2)
                      @if($car2->air_conditioner=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->air_conditioner=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Power Windows</td>
                   @if($car1)
                      @if($car1->power_window=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                  
                      @if($car2)
                      @if($car2->power_window=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                  
                   @if($car3)
                      @if($car3->power_window=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Power Steering</td>
                   @if($car1)
                      @if($car1->power_steering=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->power_steering=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->power_steering=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Anti-Lock Braking System</td>
                    @if($car1)
                      @if($car1->anti_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->anti_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->anti_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Air-Bags (Driver | Passenger)</td>
                  @if($car1)
                      @if($car1->air_bags_driver=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->air_bags_driver=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->air_bags_driver=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Leather Seats</td>
                  @if($car1)
                      @if($car1->leather_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->leather_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->leather_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Cruise Control</td>
                   @if($car1)
                      @if($car1->curise=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->curise=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->curise=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Keyless Entry</td>
                   @if($car1)
                      @if($car1->keyless=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->keyless=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->keyless=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
            </tbody>
         </table>
         <table class="table table-bordered table-striped table-styled well nomargin mt-1" style="overflow: hidden;" id="more_new_car_features" >
            <tbody>
               <tr>
                  <td>CD Player</td>
                   @if($car1)
                      @if($car1->cd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->cd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->cd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Power Door Locks</td>
                   @if($car1)
                      @if($car1->power_door_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->power_door_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->power_door_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Traction Control</td>
                   @if($car1)
                      @if($car1->traction_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->traction_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->traction_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Immobilizer</td>
                  @if($car1)
                      @if($car1->immobilizer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->immobilizer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->immobilizer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Cup Holders</td>
                   @if($car1)
                      @if($car1->cup_holder=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->cup_holder=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->cup_holder=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Folding Rear-Seat</td>
                  @if($car1)
                      @if($car1->folding_seat=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->folding_seat=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->folding_seat=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Rear Wiper</td>
                  @if($car1)
                      @if($car1->rear_wiper=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->rear_wiper=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->rear_wiper=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Alloy Wheels</td>
                   @if($car1)
                      @if($car1->alloy_wheel=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->alloy_wheel=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->alloy_wheel=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Remote Boot/Fuel-Lid</td>
                   @if($car1)
                      @if($car1->remote_boot=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->remote_boot=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->remote_boot=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Steering Adjustment</td>
                   @if($car1)
                      @if($car1->stering_adjust=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->stering_adjust=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->stering_adjust=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Tachometer</td>
                   @if($car1)
                      @if($car1->tachometer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->tachometer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->tachometer=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
                <tr>
                  <td>Front Fog Lights</td>
                   @if($car1)
                      @if($car1->front_fog=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->front_fog=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->front_fog=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
                <tr>
                  <td>Defogger</td>
                   @if($car1)
                      @if($car1->defogger=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->defogger=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->defogger=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Power Seats</td>
                   @if($car1)
                      @if($car1->power_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->power_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->power_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Power Mirrors</td>
                   @if($car1)
                      @if($car1->power_mirrors=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->power_mirrors=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->power_mirrors=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Cassette Player</td>
                   @if($car1)
                      @if($car1->casete_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->casete_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->casete_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Sun-Roof | Moon-Roof</td>
                   @if($car1)
                      @if($car1->sun_roof=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->sun_roof=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->sun_roof=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
                <tr>
                  <td>Cool Box</td>
                   @if($car1)
                      @if($car1->coolbox=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->coolbox=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->coolbox=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
                <tr>
                  <td>DVD Player</td>
                   @if($car1)
                      @if($car1->dvd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->dvd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->dvd_player=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Central Locking</td>
                   @if($car1)
                      @if($car1->central_locking=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->central_locking=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->central_locking=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Seat Entertainment</td>
                   @if($car1)
                      @if($car1->seat_entertainment=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->seat_entertainment=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->seat_entertainment=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Climate Control</td>
                   @if($car1)
                      @if($car1->climate_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->climate_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->climate_control=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Rear AC Vents</td>
                   @if($car1)
                      @if($car1->AC_vents=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->AC_vents=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->AC_vents=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Front Speakers</td>
                   @if($car1)
                      @if($car1->front_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->front_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->front_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Rear Speakers</td>
                   @if($car1)
                      @if($car1->rear_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->rear_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->rear_speakers=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>USB</td>
                   @if($car1)
                      @if($car1->usb=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->usb=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->usb=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Heated Seats</td>
                   @if($car1)
                      @if($car1->heated_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->heated_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->heated_seats=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Steering Switches</td>
                   @if($car1)
                      @if($car1->steering_switches=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->steering_switches=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->steering_switches=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Front Camera</td>
                   @if($car1)
                      @if($car1->front_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->front_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->front_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Rear Camera</td>
                   @if($car1)
                      @if($car1->rear_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->rear_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->rear_camera=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Arm Rest</td>
                   @if($car1)
                      @if($car1->arm_rest=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->arm_rest=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->arm_rest=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Child Lock</td>
                   @if($car1)
                      @if($car1->child_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->child_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->child_lock=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Heater</td>
                   @if($car1)
                      @if($car1->heater=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->heater=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->heater=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
                <tr>
                  <td>Multi Info</td>
                   @if($car1)
                      @if($car1->multi_info=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->multi_info=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->multi_info=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Navigation</td>
                   @if($car1)
                      @if($car1->navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               <tr>
                  <td>Optional Navigation</td>
                   @if($car1)
                      @if($car1->optional_navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                   @if($car2)
                      @if($car2->optional_navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
                    @if($car3)
                      @if($car3->optional_navigation=="Yes")
                        <td><i class="fa fa-check"></i></td>
                      @else 
                        <td><i class="fa fa-times"></i></td>
                     @endif
                   @endif
               </tr>
               




            </tbody>
         </table>
         <div>
            <a href="javascript:" class="pull-right"  id="more-feature" style="display: none;"> More Features <i class="fa fa-chevron-circle-down"></i></a>
            <a href="javascript:" class="pull-right"  id="less-feature" style=""> Less Features <i class="fa fa-chevron-circle-up"></i></a>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function(){
     $('#more-specs').show();
     $('.more').hide();
     $('#less-specs').hide();

     $('#more-feature').show();
     $('#more_new_car_features').hide();
     $('#less-feature').hide();

   });
   $('#more-specs').click(function(){
       $('.more').show();
      $('#less-specs').show();
      $('#more-specs').hide();
   });
   $('#less-specs').click(function(){
      $('.more').hide();
      $('#less-specs').hide();
      $('#more-specs').show();
   });
    $('#more-feature').click(function(){
     $('#more_new_car_features').show();
      $('#less-feature').show();
      $('#more-feature').hide();
   });
     $('#less-feature').click(function(){
     $('#more_new_car_features').hide();
      $('#less-feature').hide();
      $('#more-feature').show();
   });


   </script>
@endsection