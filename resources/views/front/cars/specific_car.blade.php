@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php if($cars){ $city = db::table('cities')->where('id','=',$cars->city_id)->first();}?>
<?php  if($cars){ $engine_type = db::table('engine_type')->where('engine_type_id','=',$cars->engine_type_id)->first();}?>
<?php $year = db::table('car_years')->where('car_years_id','=',$cars->car_years_id)->first();?>
<div class="remove-section mt-4">
   <div class="row">
      <div class="col-md-1">
      </div>
      <div class="col-md-2 ">
         <ul id="scroll-sidebar" class="nav" style="display:block">
            <li class=""><a href="#scroll_car_info"><i class="fa fa-caret-right"></i>Car Info</a></li>
            <li class=""><a href="#scroll_car_detail"><i class="fa fa-caret-right"></i>Car Details</a></li>
            <li class=""><a href="#scroll_seller_comments"><i class="fa fa-caret-right"></i>Seller's Comments</a></li>
            <!-- <li class=""><a href="#scroll_similar_ads"><i class="fa fa-caret-right"></i>Similar Ads</a></li> -->
         </ul>
      </div>
      <div class="col-md-5">
         <div class="well" id="scroll_car_info">
            <h1>{{$cars->listings_title}} ({{$engine_type->engine_type}}) {{$year->year}}</h1>
            <p class="detail-sub-heading">
               <a href="#"><i class="fa fa-map-marker"></i> {{ $city->name}}</a> <span class="time"><i class="fa fa-mobile fs18"></i> <a href="/main/apps" onclick="trackEvents(&quot;UsedCars&quot;,&quot;AppsPage&quot;,&quot;From- Ad Detail&quot;)" target="_blank">Added via Phone</a></span>
            </p>
            <?php $images = db::table('listings_images')->where('listings_id','=',$cars->listings_id)->get();?>
            <div class="mb40 pos-rel">
               <style>
                  .carousel-indicators li{
                  width: 100px!important;
                  height: 106px!important;
                  } 
               </style>
              
               <div id="myCarousel" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                   
                  <div class="m-5">
                     @foreach($images as $image)
                     <ol class="carousel-indicators" style="
                        ">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"><img class="d-block w-100" src="{{asset('uploads/listings/'.$image->image_name)}}" alt="Los Angeles" ></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                     </ol>
                     @endforeach
                  </div>@foreach($images as $image)
                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">
                    
                     <div class="item active">
                        <img class="d-block w-100" src="{{asset('uploads/listings/'.$image->image_name)}}" alt="Los Angeles" >
                     </div>
                    
                  </div>
                  @endforeach
                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left"></span>
                  <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  <span class="sr-only">Next</span>
                  </a>
               </div>
            </div> 
         </div>
         
         <table width="100%" class="table table-bordered text-center table-engine-detail fs16 mt-5">
            <tbody>
               <tr>
                  <td>
                     <span class="engine-icon year"></span>
                     <p><a  title="Year {{$year->year}} Cars for sale in Pakistan">
                        {{$year->year}}</a>
                     </p>
                  </td>
                  <td>
                     <span class="engine-icon millage"></span>
                     <p>{{$cars->km}} km</p>
                  </td>
                  <td>
                     <span class="engine-icon type"></span>
                     <p><a  title="{{$engine_type->engine_type}} Cars for Sale in Pakistan">
                        {{$engine_type->engine_type}}</a>
                     </p>
                  </td>
                  <td>
                     <?php $transmission = db::table('transmission')->where('transmission_id','=',$cars->transmission_id)->first();?>
                     <span class="engine-icon transmission"></span>
                     <p>{{$transmission->name}}</p>
                  </td>
               </tr>
            </tbody>
         </table>
         <ul class="list-unstyled ul-featured clearfix" style="display:block" id="scroll_car_detail">
            <li class="ad-data">{{$cars->register}}</li>
            <li> 
               {{$city->name}}
            </li>
            <li class="ad-data">Color</li>
            <li>
               <?php $color = db::table('car_colors')->where('colors_id','=',$cars->color_id)->first();?>
               {{$color->name}}
            </li>
            <!-- <li class="ad-data">Assembly</li>
               <li>Local</li> -->
            <li class="ad-data">Engine Capacity</li>
            <li>
               <?php $engine_size = db::table('engine_size')->where('engine_size_id','=',$cars->engine_size_id)->first();?>
               {{$engine_size->size}}
               cc
            </li>
            <!--  <li class="ad-data">Body Type</li>
               <li><a  title="Hatchback Cars for sale in Pakistan">Hatchback</a></li>
               -->
            <li class="ad-data">Last Updated:</li>
            <li>{{ $cars->date_modified}}</li>
            <!-- <li class="ad-data">Ad Ref #</li>
               <li>5435858</li> -->
         </ul>
         <h2 class="ad-detail-heading mt30">Car features</h2>
         <ul class="list-unstyled car-feature-list nomargin">
            <li><i class="icon am_fm_radio"></i> AM/FM Radio</li>
            <li><i class="icon air_conditioning"></i> Air Conditioning</li>
            <li><i class="icon cd_player"></i> CD Player</li>
            <li><i class="icon keyless_entry"></i> Keyless Entry</li>
            <li><i class="icon power_locks"></i> Power Locks</li>
            <li><i class="icon power_steering"></i> Power Steering</li>
         </ul>
         <div class="clearfix"></div>
         <div style="height:0; position:relative; top:0px;"></div>
         <h2 class="ad-detail-heading mt30" id="scroll_seller_comments">Seller's Comments</h2>
         <div>
            {!!$cars->description!!}
         </div>
         <div class="mt20">
         </div>
      </div>
      <div class="col-md-2">
         <div class="well price-well pos-rel mb20">
            <div class="price-box" style="">
               <strong class="generic-green">PKR {{$cars->price}} <span>lacs</span></strong>
            </div>
            <button class="btn btn-large btn-block btn-success phone_number_btn" onclick=" @if(!(session('id'))) {{ url('/login')}} @endif";  style="opacity: 1;">
            <i class="fa fa-phone"></i>
            <span class="" style="font-size: -1px;!important">
            @if(session('id'))
            {{$cars->mobile}}
            @else
            <small>Show Phone Number</small>
            @endif
            </span>
            </button>
            @if(session('id'))
            <button class="btn btn-link-outline btn-lg btn-block mt20 send_email_contact_button" data-toggle="modal" data-target="#myMessageBottom">
            <i class="fa fa-envelope mr5"></i> Send Message
            </button>
            @endif
         </div>
      </div>
   </div>
</div>
<!-- Message modal -->
<div class="modal fade" id="myMessageBottom" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-bottom">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Send Message</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body p-4">
            <?php $users= DB::table('users')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/listings_message/'.$cars->listings_id.'/'.$cars->user_id)}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Title<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" readonly  name="listings_title" placeholder="<?php echo $cars->listings_title?>"/>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Your Name<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" readonly  name="" placeholder="<?php  echo session('user_name')?>"/>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Your Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" required="" name="message" ></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Send Message</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection