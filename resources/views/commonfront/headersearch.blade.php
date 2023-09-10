<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<div class="header_search_area">
    <!-- SEARCH HEADER -->
    <div class="search_ar_go">
    <div class="search-classified-text text-center generic-white">
      <h1>Find  Cars in Pakistan</h1>
      <p>With thousands of cars, we have just the right one for you</p>
    </div>
    </div>
    <form action="{{url('/used_cars')}}" method="POST">
    	{{ csrf_field() }}
    <!-- SEARCH BODY -->
    <div class="search-classified" tabindex="0">
      <div id="used-cars">
        <ul class="list-unstyled search-front clearfix">
          <li class="hchzn-container chzn-container-single">
            <select   tabindex="2" class="chzn-single" name="model_id" >
            <option value="">Car Make or Model</option>
            <?php $make = DB::select("select car_make.car_make_id as make_id,car_model_id as model_id,car_model.name as model_name , car_make.name as make from car_make left join car_model  on car_make.car_make_id = car_model.car_make_id");?>
            @foreach($make as $makes) 
             
             <option value="{{$makes->model_id}}">{{ $makes->make}}</br> {{$makes->model_name}}</option>

             @endforeach
            </select><span
              role="status"
              aria-live="polite"
              class="ui-helper-hidden-accessible"
            ></span>
            
          </li>
          <li class="home-chzn">
            <div
              id="UsedCity_chzn"
              class="chzn-container chzn-container-single"
              style="width: 220px"
            >
              <select  class="chzn-single" tabindex="1" name="city_id" 
                >
                	<option value="">All Cities</option>
                    <?php $cities = db::table('cities')->get();?>
                    @foreach($cities as $city)
                      <option value="{{$city->id}}">{{$city->name}}</option>
                    @endforeach
                </select>
              <div
                class="chzn-drop"
                style="left: -9000px; width: 218px; top: 50px"
              >
                <div class="chzn-search">
                  <input
                    type="text"
                    autocomplete="off"
                    tabindex="-1"
                    style="width: 176px"
                  />
                </div>
              </div>
            </div>
          </li>
          <li class="range-widget">
            <div id="pr-range-filter" tabindex="3" class="pos-rel">
              <span class="pr-text price">Price Range</span>
              <i class="fa fa-sort-down pull-right "></i>
              <div class="pr-range pos-rel" style="display: none">
                <div class="pr-range-container">
                  <div class="pr-input-container clearfix" >
                  	<div class="row">
                  		<div class="col-md-4">
                    <div class="pr-input">
                      <span
                        class="twitter-typeahead"
                        style="position: relative; display: inline-block"
                        ><input
                          id="pr_from"
                          name="pr_to"
                          placeholder="Min in lac"
                          tabindex="4"
                          type="text"
                          value=""
                          class="tt-input"
                          autocomplete="off"
                          spellcheck="false"
                          dir="auto"
                          style="position: relative; vertical-align: top"  />
                        <pre
                          aria-hidden="true"
                          style="
                            position: absolute;
                            visibility: hidden;
                            white-space: pre;
                            font-family: Lato, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            font-weight: 400;
                            word-spacing: 0px;
                            letter-spacing: 0px;
                            text-indent: 0px;
                            text-rendering: auto;
                            text-transform: none;
                          "
                        ></pre>
                        </span
                      >
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="pr-input">
                      <span
                        class="twitter-typeahead"
                        style="position: relative; display: inline-block"
                        ><input
                          id="pr_to"
                          name="pr_from"
                          placeholder="Max in lac"
                          tabindex="5"
                          type="text"
                          value=""
                          class="tt-input"
                          autocomplete="off"
                          spellcheck="false"
                          dir="auto"
                          style="position: relative; vertical-align: top" />
                        <pre
                          aria-hidden="true"
                          style="
                            position: absolute;
                            visibility: hidden;
                            white-space: pre;
                            font-family: Lato, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            font-weight: 400;
                            word-spacing: 0px;
                            letter-spacing: 0px;
                            text-indent: 0px;
                            text-rendering: auto;
                            text-transform: none;

                          "
                        ></pre>
                       </span>
                    </div> 

                </div>
                </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
             
          <li>
            <div id="search-row">
              <button
                type="submit"
                class="btn btn-success btn-lg btn-block onCarSubmit"
                tabindex="6"
              >
                <i class="fa fa-search"></i>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
	</form>

    <!-- SEARCH BUTTON -->
    <form action="{{url('/used_cars')}}" method="POST">
      {{ csrf_field()}}
    <div class="btn-classified-container text-center">
      <button type="submit"
        class="btn btn-sm btn-link-outline btn-classified"
        style="z-index: 5; position: relative"
        >Advanced Filter <i class="fa fa-angle-right"></i
      ></button>
    </div>
  </form>
</div>
<script type="text/javascript">
	$(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
	$('.price').click(function () {
	    $('.pr-range').attr("style", "display:block");
	})
	
</script>
