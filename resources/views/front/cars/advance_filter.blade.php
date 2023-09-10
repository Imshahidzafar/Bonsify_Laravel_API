@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style></style>
<div class="container mt-5">
   <div class="row">
      <div class="col-md-3">
         <div class="accordion-group search-filter-heading">
            <div class="accordion-heading">
               <a class="accordion-toggle">Show Results By:</a>
            </div>
         </div>
         <div class="accordion-group">
            <div class="accordion-heading">
               <div class="well">
                  <span class="accordion-toggle" data-toggle="collapse" href="#collapse_search_key_keyword">
                  Search by Keyword<i class="fa fa-caret-down"></i>
                  </span>
               </div>
               <div id="collapse_search_key_keyword" class="accordion-body collapse in">
                  <div class="accordion-inner">
                     <form  action="{{url('/used_cars')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                           <input class="pr35" id="q" name="q" placeholder="e.g. Honda in Lahore" type="text">
                           <input class="btn btn-primary refine-go" type="submit" readonly="">
                        </div>
                     </form>
                     <input id="query_params" name="query_params" type="hidden" >
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left " data-toggle="collapse" href="#collapse_1">
                  <i class="fa fa-caret-down"></i>Price Range
                  </a>
               </div>
               <div id="collapse_1" class="accordion-body filter_25 collapse in mr-2">
                  <div class="accordion-inner">
                     <form method="post" action="{{ url('/used_cars')}}">
                        {{csrf_field()}}
                        <div class="range-filter clearfix">
                           <span class="twitter-typeahead" style="position: relative; display: inline-block; margin-left: -27px;!important">
                              <input class="rng-hintify tt-hint"  maxlength="10" type="text"   autocomplete="off" tabindex="-1"
                                 style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);" dir="ltr">
                              <input class="rng-hintify tt-input"  id="pr_from" maxlength="10" name="pr_from" placeholder="From" type="text" autocomplete="off" dir="auto" style="position: relative; vertical-align: top; background-color: rgba(0, 0, 0, 0);">
                              <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
                                 <div class="tt-dataset tt-dataset-0"></div>
                              </div>
                           </span>
                           <span class="twitter-typeahead" style="position: relative; display: inline-block;">
                              <input class="rng-hintify tt-hint"  maxlength="10" type="text"  spellcheck="false" tabindex="-1" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);" dir="ltr">
                              <input class="rng-hintify tt-input"  id="pr_to" maxlength="10" name="pr_to" placeholder="To" type="text"   dir="auto" style="position: relative; vertical-align: top; background-color: rgba(0, 0, 0, 0);">
                              <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
                                 <div class="tt-dataset tt-dataset-1"></div>
                              </div>
                           </span>
                           <input class="btn btn-primary pull-left" data-alias="pr" data-max-text="More" data-min-text="Less" data-name="price range" id="pr-go" name="commit" type="submit" value="Go">
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left " data-toggle="collapse" href="#collapse_2">
                  <i class="fa fa-caret-down"></i>Year
                  </a>
               </div>
               <div id="collapse_2" class="accordion-body filter_25 collapse in mr-2">
                  <div class="accordion-inner">
                     <form method="post" action="{{ url('/used_cars')}}">
                        {{csrf_field()}}
                        <div class="range-filter clearfix">
                           <span class="twitter-typeahead" style="position: relative; display: inline-block; margin-left: -27px;!important">
                              <input class="rng-hintify tt-hint"  maxlength="10" type="text"   autocomplete="off" tabindex="-1"
                                 style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);" dir="ltr">
                              <input class="rng-hintify tt-input"  id="year_from" maxlength="10" name="year_from" placeholder="From" type="text" autocomplete="off" dir="auto" required="" style="position: relative; vertical-align: top; background-color: rgba(0, 0, 0, 0);">
                              <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
                                 <div class="tt-dataset tt-dataset-0"></div>
                              </div>
                           </span>
                           <span class="twitter-typeahead" style="position: relative; display: inline-block;">
                              <input class="rng-hintify tt-hint"  maxlength="10" type="text"  spellcheck="false" tabindex="-1" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);" dir="ltr">
                              <input class="rng-hintify tt-input"  id="year_to" maxlength="10" name="year_to" placeholder="To" required="" type="text"   dir="auto" style="position: relative; vertical-align: top; background-color: rgba(0, 0, 0, 0);">
                              <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
                                 <div class="tt-dataset tt-dataset-1"></div>
                              </div>
                           </span>
                           <input class="btn btn-primary pull-left" data-alias="pr" data-max-text="More" data-min-text="Less" data-name="year range" id="pr-go" name="commit" type="submit" value="Go">
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left" data-toggle="collapse" href="#collapse_19" aria-expanded="true">
                  <i class="fa fa-caret-down"></i>City
                  </a>
               </div>
               <div id="collapse_19" class="accordion-body filter_61 collapse in" aria-expanded="true" style="">
                  <div class="accordion-inner">
                     <ul class="list-unstyled ">
                        <?php $cities = db::table('cities')->get();?>
                        @foreach($cities as $city)
                        <li title="Cars for Sale in Pakistan">
                           <label class="filter-check ">
                           <a onclick="city_filter(this.id)" id="{{ $city->id}}" rel="nofollow" title="Cars for Sale in Pakistan">
                           {{ $city->name}}
                           <?php $count_city = db::table('listings')->where('city_id','=',$city->id)->get();
                              $num =sizeof($count_city,1);
                              ?>
                           <span class="pull-right count">({{ $num}})</span>
                           </a>
                           </label>
                        </li>
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left" data-toggle="collapse" href="#collapse_19" aria-expanded="true">
                  <i class="fa fa-caret-down"></i>Color
                  </a>
               </div>
               <div id="collapse_19" class="accordion-body filter_61 collapse in" aria-expanded="true" style="">
                  <div class="accordion-inner">
                     <ul class="list-unstyled ">
                        <?php $colors = db::table('car_colors')->get();?>
                        @foreach($colors as $color)
                        <li title="Cars for Sale in Pakistan">
                           <label class="filter-check ">
                           <a onclick="color_filter(this.id)" id="{{ $color->colors_id}}" rel="nofollow" title="Cars for Sale in Pakistan">
                           {{ $color->name}}
                           <?php $count_color = db::table('listings')->where('color_id','=',$color->colors_id)->get();
                              $num =sizeof($count_color,1);
                              ?>
                           <span class="pull-right count">({{ $num}})</span>
                           </a>
                           </label>
                        </li>
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left " data-toggle="collapse" href="#collapse_10">
                  <i class="fa fa-caret-down"></i>Make
                  </a> 
               </div>
               <?php  $make= db::table('car_make')->get();?>
               <div id="collapse_10" class="accordion-body filter_15 collapse in">
                  <div class="accordion-inner">
                     <ul class="list-unstyled ">
                        @foreach ($make as $makes)
                        <li title="{{$makes->name }} Cars for Sale in Pakistan">
                           <label class="filter-check ">
                              <a  class= "make" onclick="make_click(this.id)"  id="<?php echo $makes->car_make_id ?>" >
                                 <!--  href="{{url('/used_cars/'.$makes->car_make_id)}}" -->
                                 {{ $makes->name}}
                                 <span class="pull-right count">
                                 <?php $makelist = db::table('listings')->where('car_make_id','=',$makes->car_make_id)->get();
                                    $count_make = sizeof($makelist,1); 
                                    ?>
                                 ({{$count_make}})
                                 </span>
                              </a>
                           </label>
                        </li>
                        @endforeach
                     </ul>
                     <div class="clearfix"></div>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left" data-toggle="collapse" href="#collapse_24" aria-expanded="true">
                  <i class="fa fa-caret-down"></i>Seller Type
                  </a>
               </div>
               <div id="collapse_24" class="accordion-body filter_65 collapse in" aria-expanded="true" style="">
                  <div class="accordion-inner">
                     <ul class="list-unstyled ">
                        <li title="Cars for Sale in Pakistan">
                           <label class="filter-check ">
                           <a  id="3" onclick="filter(this.id)"  rel="nofollow" title="Cars for Sale in Pakistan">
                           Individuals
                           <?php $count_list = db::table('listings')->where('created_by','=','3')->get();
                              $num= sizeof($count_list,1);
                              
                              ?>
                           <span class="pull-right count">({{$num}})</span>
                           </a>
                           </label>
                        </li>
                        <li title="Cars for Sale in Pakistan">
                           <label class="filter-check ">
                           <a id="2" onclick="filter(this.id)"  rel="nofollow" title="Cars for Sale in Pakistan">
                           <?php $count_list = db::table('listings')->where('created_by','=','2')->get();
                              $num= sizeof($count_list,1);
                              
                              ?>
                           Dealers
                           <span class="pull-right count"> ({{ $num}})</span>
                           </a>
                           </label>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <div class="well">
            <div class="accordion-group">
               <div class="accordion-heading">
                  <a class="accordion-toggle text-left" data-toggle="collapse" href="#collapse_25" aria-expanded="true">
                  <i class="fa fa-caret-down"></i>Ad Type
                  </a>
               </div>
               <div id="collapse_25" class="accordion-body filter_105 collapse in" aria-expanded="true" style="">
                  <div class="accordion-inner">
                     <ul class="list-unstyled ">
                        <li title="Cars for Sale in Pakistan">
                           <label class="filter-check ">
                           <a href="{{url('/used_cars/feature')}}" rel="nofollow" title="Cars for Sale in Pakistan">
                           Featured Ads
                           <?php $count_list = db::table('listings')->where('feature','=','Yes')->get();
                              $num= sizeof($count_list,1);
                              
                              ?>
                           <span class="pull-right count"> ({{$num}})</span>
                           </a>
                           </label>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <div class="well search-heading">
            <div class="organize-results">
               <div class="col-md-8" data-pjax-enable="">
                  <span class="form-horizontal sort-by">
                     <span class="sort-by-text">Sort By: </span>
                     <select id="sortby" name="sortby" onchange="callPjax('/used-cars/search/-/?sortby=' + $(this).val())">
                        <option value="bumped_at-desc" selected="selected">Updated Date: Recent First</option>
                        <!-- <option value="bumped_at-asc">Updated Date: Oldest First</option>
                           <option value="price-asc">Price: Low to High</option>
                           <option value="price-desc">Price: High to Low</option>
                           <option value="model_year-desc">Model Year: Latest First</option>
                           <option value="model_year-asc">Model Year: Oldest First</option>
                           <option value="mileage-asc">Mileage: Low to High</option>
                           <option value="mileage-desc">Mileage: High to Low</option>-->
                     </select>
                  </span>
               </div>
               <div class="col-md-4 mt5 text-right">
                  <div class="btn-group" data-toggle="buttons-radio">
                     <button type="button" id="list" class="btn btn-sm " onclick="gridView()" >
                     <span class="generic-dark-grey"><i class="fa fa-th-list active"></i> LIST</span>
                     </button>
                     <button type="button" id="grid" class="btn btn-sm " onclick="listView()"  >
                     <span class="generic-dark-grey"><i class="fa fa-th-large"></i> GRID</span>
                     </button>
                  </div>
               </div>
            </div>
         </div>
         @if(empty($cars))
         <div class="well suggestions-noresults search-main">
            <h4>Sorry! We could not find any results against your search criteria.</h4>
            <p>Use the options below to redefine your search.</p>
            <form accept-charset="UTF-8" action="{{url('/used_cars')}}" class="nomargin" method="POST">
               {{csrf_field()}}
               <div style="margin:0;padding:0;display:inline">
                  <input name="utf8" type="hidden" value="✓">
               </div>
               <ul class="search-fields search-fields4 clearfix mt30">
                  <li class="search-input">
                     <input type="text" placeholder="Enter Keywords (optional): e.g. Toyota Corolla, Honda Civic, Suzuki Mehran" name="q" id="home-query">
                  </li>
                  <?php  $cities =db::table('cities')->get();?>
                  <li>
                     <select name="city_id" id="city_id">
                        <option value=""> Cities</option>
                        @foreach($cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                     </select>
                  </li>
                  <li>
                     <?php  $makes =db::table('car_make')->get();?>
                     <select name="car_make_id" >
                        <option value=""> Cities</option>
                        @foreach($makes as $make)
                        <option value="{{$make->car_make_id}}">{{$make->name}}</option>
                        @endforeach
                     </select>
                  </li>
               </ul>
               <div class="search-functions clearfix mt0">
                  <button class="btn btn-success btn-lg pull-right" type="submit">Search</button>
               </div>
            </form>
            @else
            <div class="results" id="results">
            </div>
            @foreach($cars as $car)
            <div class="column oldresults">
               <div class=" grid-date rows ">
                  <div class="well ">
                     <div class="col-md-3 grid-style">
                        <div class="img-box">
                           <div id="gallery-5409463">
                              <ul class="gallery list-unstyled image-gallery page-" >
                                 <li class="total-pictures-bar-outer img-content img-valign"  >
                                    @if($car)
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
                                    @endif
                                 </li>
                              </ul>
                           </div>
                        </div>
                        @if($car->feature=="Yes")
                        <div class="featured-ribbon pointer">
                           <div class="ib lg-popover featured-popover" data-toggle="popover" data-placement="auto right" data-html="true" data-original-title="" title="">
                              <div class="inner">
                                 FEATURED
                                 <i class="fa fa-exclamation-circle"></i>
                              </div>
                           </div>
                        </div>
                        @endif
                     </div>
                     <div class="">
                        <div class="search-title-row">
                           <div class="search-title">
                              <div class="right">
                                 <div class="price-details generic-dark-grey" style="font-size: 18px;
                                    font-weight: 700;">
                                    PKR {{$car->price}} <span>lacs</span>
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
               </div>
            </div>
            @endforeach
         </div>
         @endif
      </div>
   </div>
</div>
<script>
   // tooltip
   $(document).ready(function(){
   $('[data-toggle="popover"]').popover();
   });
   /* list and grid view js*/
   var elements = document.getElementsByClassName("column");
   
   // Declare a loop variable
   var i;
   
   // List View
   function listView() {
    for (i = 0; i < elements.length; i++) {
      elements[i].style.width = "50%";
      elements[i].style.float = "left";
    }
   }
   
   // Grid View
   function gridView() {
    for (i = 0; i < elements.length; i++) {
      elements[i].style.width = "100%";
     
    }
   }
    $('#make-dropdown').on('change', function() {
    alert('');
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
   /*END  list and grid view js*/
   /* ajax call for make id*/
   function make_click(id){
     alert(id);
      var url = "{{URL('/filter_cars')}}";
      
      $.ajax({
           url: url,
           type: "POST",
            dataType:'html',
           data:{
              make_id:id,
              _token:'{{ csrf_token() }}'
           },
           success: function(dataResult){
               
               $('.oldresults').hide();
               $('.results').html(dataResult); 
           }
        });
   
   }
   /* ajax call for individual/dealer*/
    function filter(id){
    
   
      var url = "{{URL('/filter_cars')}}";
      
      $.ajax({
           url: url,
           type: "POST",
            dataType:'html',
           data:{
              roles_id:id,
              _token:'{{ csrf_token() }}'
           },
           success: function(dataResult){
               alert(dataResult);
               $('.oldresults').hide();
               $('.results').html(dataResult); 
           }
        });
   
   }
   /* ajax call for color*/
   function color_filter(id){
   
      var url = "{{URL('/filter_cars')}}";
      
      $.ajax({
           url: url,
           type: "POST",
           dataType:'html',
           data:{
              color_id:id,
              _token:'{{ csrf_token() }}'
           },
           success: function(dataResult){
             
               $('.oldresults').hide();
               $('.results').html(dataResult); 
           }
        });
   
   }
   city_filter
   
   /* ajax call for city*/
   function  city_filter(id){
   
      var url = "{{URL('/filter_cars')}}";
      
      $.ajax({
           url: url,
           type: "POST",
           dataType:'html',
           data:{
              city_id:id,
              _token:'{{ csrf_token() }}'
           },
           success: function(dataResult){
             
               $('.oldresults').hide();
               $('.results').html(dataResult); 
           }
        });
   
   }
   var  clicks = 0;
       function save_add(id){
      
      let span;
     var id_value = id;
     var res = id_value.split("_");
     
    
     var id=res[1];
      
     
     if(clicks=='0'){
        $(".like_"+id).addClass("btn-danger");
        span++;
        clicks += 1;
        alert(clicks);
     }else{
        $(".like_"+id).removeClass("btn-danger");
        span--;
        clicks = 0;
     }
       var url = 'https://dev.eigix.com/sayarat/index.php/save_add/' + id;
         
        $.ajax({
        type:'get',
        url:url,
        dataType:'html',
        data:'_token = <?php echo csrf_token(); ?>',
        
      });
   
   }
</script>
@endsection