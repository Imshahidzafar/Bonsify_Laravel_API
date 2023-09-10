@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style></style>
<div class="container mt-5">
   <div class="row">
      <div class="col-md-3">
         <div class="accordion-group">
            <div class="accordion-heading">
               <div class="well">
                  <span class="accordion-toggle" data-toggle="collapse" href="#collapse_search_key_keyword">
                  Search By Name<i class="fa fa-caret-down"></i>
                  </span>
                  <div id="collapse_search_key_keyword" class="accordion-body collapse in">
                     <div class="accordion-inner">
                        <form  action="{{url('/dealers')}}" method="post">
                           {{csrf_field()}}
                           <div class="row">
                              <input name="user_name" placeholder="e.g Jan Motors" type="text">
                              <input class="btn btn-primary refine-go" type="submit" value="Go">
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         @if(sizeof($dealers,1)<1)
         <div class="well suggestions-noresults search-main">
            <h4>Sorry! We could not find any results against your search criteria.</h4>
            <p>Use the options below to redefine your search.</p>
            <form accept-charset="UTF-8" action="/used-cars/search/-/" class="nomargin" method="get" onsubmit="$('#used-cars-search-btn').click(); return false;">
               <div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓"></div>
            </form>
         </div>
         @else
         @foreach($dealers as $dealer)
         <div class="column ">
            <div class=" grid-date rows ">
               <div class="results" id="results">
               </div>
               <div class="well oldresults">
                  <div class="col-md-3 grid-style" style="padding-left: 13px;
                     padding-right: 38px">
                     <div class="img-box">
                        <div id="gallery-5409463">
                           <ul class="gallery list-unstyled image-gallery page-" >
                              <li class="total-pictures-bar-outer img-content img-valign"  >
                                 @if(!empty($dealer->user_image))
                                 <img  src="{{asset('uploads/users/'.$dealer->user_image)}}" style="display: block;">
                                 @else
                                 <img src="{{asset('uploads/make/default-image.jpg')}}" >
                                 @endif
                              </li>
                           </ul>
                        </div>
                     </div>
                     @if($dealer->feature == "Yes")
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
                  <div class="col-md-2 pull-right" >
                     <?php ; 
                        $sum_stars = db::table('dealers_reviews')->where('dealers_id','=',$dealer->users_id)->sum('stars');
                          
                          
                           if($sum_stars){
                              $total_users = db::table('dealers_reviews')->where('dealers_id','=',$dealer->users_id)->count('dealers_id');
                             
                           
                             $review = round(($sum_stars/$total_users),0,PHP_ROUND_HALF_UP);
                            $loop = 5-$review;
                            
                           }
                        
                             
                        ?>
                     <strong class="generic-dark-grey">@if($sum_stars) {{$review}}   Review @endif</strong>
                     <p>
                        <span class="rating generic-orange fs12 ">
                        @if($sum_stars)
                        @for($i=1;$i<=5;$i++)
                        @if($i <= 5-$review) 
                        <i class="fa fa-star-o "></i>
                        @else
                        <i class="fa fa-star pull-left"></i>
                        @endif   
                        @endfor  
                        @else
                        No Reviews Yet
                        @endif
                        </span>
                     </p>
                  </div>
                  <ul class="list-unstyled search-vehicle-info fs13">
                     <h3>{{$dealer->user_name}} @if($dealer->certified == "Yes")<i class="fa fa-check-circle varified-icon " style="color:#67ae40;"></i>  @endif</h3>
                     <li>
                        <?php $city = db::table('cities')->where('id','=',$dealer->city_id)->first();?>  
                        Delaers - @if($city){{$city->name}}@endif                  
                     </li>
                  </ul>
                  <ul class="list-unstyled search-vehicle-info-2 fs13">
                     <li itemprop="streetAddress"><i class="fa fa-map-marker tlc"></i> {{ $dealer->address}}</li>
                     <li>{{ $dealer->mobile}}</li>
                  </ul>
               </div>
            </div>
         </div>
         @endforeach
         @endif
      </div>
   </div>
</div>
</div>
<script>
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
   /*END  list and grid view js*/
   /* ajax call for make id*/
   
   /* ajax call for color*/
   function color_filter(id){
     alert(id);
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
               alert(dataResult);
               $('.oldresults').hide();
               $('#results').html(dataResult); 
           }
        });
   
   }
   
</script>
@endsection