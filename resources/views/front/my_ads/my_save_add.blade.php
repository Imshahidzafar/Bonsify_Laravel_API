@extends('commonfront.inner_page')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style>
   .active{
   border: 1px solid #EAEDEF;
   display: table-cell;  
   }
</style>
<div class="container mt-5">
   <?php  print_r($listings); 
      ?>
   @foreach($listings as $car)
   <?php   
      $cars= db::table('listings')->where('listings_id','=',$car->listings_id)->get();
      
      ?>
   @foreach($cars as $car)
   <div class="well ">
      <div class="col-md-3 grid-style">
         <div class="img-box">
            <div id="gallery-5409463">
               <ul class="gallery list-unstyled image-gallery page-" >
                  <li class="total-pictures-bar-outer img-content img-valign"  >
                     <?php $image = db::table('listings_images')->where('listings_id','=',$car->listings_id)->first(); ?>
                     @if($image)
                     <img  src="{{asset('uploads/listings/'.$image->image_name)}}" style="display: block;">
                     @else
                     <img src="{{asset('uploads/make/default-image.jpg')}}" >
                     @endif
                     <div class="total-pictures-bar fs12">
                        <i class="fas fa-image"></i>
                        <?php $image = db::table('listings_images')->where('listings_id','=',$car->listings_id)->get(); 
                           $image_num = sizeof($image,1); 
                           ?>
                        {{$image_num }}                                
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="">
         <div class="search-title-row">
            <div class="search-title">
               <div class="right">
                  <div class="price-details generic-dark-grey" style="font-size: 18px;
                     font-weight: 700;">
                     PKR @if($car){{$car->price}}@endif <span>lacs</span>
                  </div>
               </div>
               <a href="{{url('/used_cars/'.$car->listings_id)}}" class="car-name ad-detail-path" title="{{$car->listings_title}}" current-index="1" target="_blank">
                  <h1>{{$car->listings_title}} for Sale</h1>
               </a>
            </div>
         </div>
      </div>
      <ul class="list-unstyled search-vehicle-info fs13">
         <li>
            <?php $city = db::table('cities')->where('id','=',$car->city_id)->first();?>  
            {{$city->name}}                  
         </li>
         <li style="float: right">
            @if($car->certified=="Yes")
            <div class="product-logo">                
               <span class="suzuki-certified-logo pull-left mr10"></span>
            </div>
            @endif
         </li>
      </ul>
      <ul class="list-unstyled search-vehicle-info-2 fs13">
         <li>
            <?php   $year = db::table('car_years')->where('car_years_id','=',$car->car_years_id)->first();?>  
            @if($year)
            {{$year->year}} 
            @endif
         </li>
         <li>{{ $car->km}}km</li>
         <li>
            <?php   $engine_type = db::table('engine_type')->where('engine_type_id','=',$car->engine_type_id)->first();?>  
            @if($engine_type)
            {{$engine_type->engine_type}} 
            @endif
         </li>
         <li>
            <?php   $engine_size = db::table('engine_size')->where('engine_size_id','=',$car->engine_size_id)->first();?>  
            @if($engine_type)
            {{$engine_size->size}}cc 
            @endif 
         </li>
         <li>
            <?php   $transmission = db::table('transmission')->where('transmission_id','=',$car->transmission_id)->first();?>  
            @if($transmission)
            {{$transmission->name}}
            @endif                    
         </li>
      </ul>
      <div class="search-bottom clearfix">
         <div class="pull-left dated">Updated <?php echo date("i", strtotime($car->date_modified )) ?>mins ago </div>
         @if(session('id'))
         <?php $fetch=DB::select("select * from save_add where users_id='".session('id')."' and listings_id='".$car->listings_id."'");?>
         <button id="like_<?php echo $car->listings_id?>" onclick="save_add(this.id)" class="btn  like_<?php echo $car->listings_id?> @if($fetch) btn-danger @endif  ">
         <i class="fa fa-heart @if($fetch) btn-danger @endif " aria-hidden="true"></i>
         </button>
         @endif
         <div class="pull-right">
            <?php $user = db::table('users')->where('users_id','=',$car->user_id)->first();?>
            <a href="@if(session('id')){{url('/used_cars/'.$car->listings_id)}}@else {{url('/login')}} @endif " class="btn btn-success phone_number_btn pull-right text-center" data-toggle="popover" data-placement="bottom" title="@if(session('id')){{$user->user_name}}@endif" data-content="@if(session('id')){{ $user->mobile}} Mention Sayarat.com when calling Seller to get a good deal @endif" ><i class="fa fa-phone"></i>Show Phone No.</a>
         </div>
      </div>
   </div>
   @endforeach
   @endforeach
</div>
@endsection