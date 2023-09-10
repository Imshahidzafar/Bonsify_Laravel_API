@extends('commonfront.main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<?php $car_make=DB::table('car_make')->orderBy('name', 'asc')->get(); ?>

<?php $car_model=DB::table('car_model')->orderBy('name', 'asc')->get(); ?>
<?php $car_variation=DB::table('car_variation')->orderBy('name', 'asc')->get(); ?>

<?php $car_body=DB::table('car_body')->orderBy('name', 'asc')->get(); ?>
<?php $cities=DB::table('cities')->orderBy('name', 'asc')->get(); ?>
<!-- REGISTER CAR -->
<section class="register_car">
  <div class="container">
    <div class="home-widgets row suzuki-home">
      <div class="home-widgets-title">
        <h3>Sell Your Car on Sayarat and Get the Best Price</h3>
      </div>
      <div class="col-md-6 line or">
        <h2>Post your Ad on Sayarat</h2>
        <ul class="home_list">
          <li>
            <i class="far fa-check-circle"></i>Post your Ad for Free in 3
            Easy Steps
          </li>
          <li>
            <i class="far fa-check-circle"></i>Get Genuine offers from
            Verified Buyers
          </li>
          <li>
            <i class="far fa-check-circle"></i>Sell your car Fast at the
            Best Price
          </li>
        </ul>
        <a class="btn btn-danger btn-lg mt5 sign-in-comp" href="{{url('/car_sale_intro')}}">Post Your Ad</a
        >
      </div>
      <div class="col-md-6">
        <h2>Try Sayarat Sell It For Me</h2>
        <ul class="home_list">
          <li>
            <i class="far fa-check-circle"></i>Dedicated Sales Expert to
            Sell your Car
          </li>
          <li>
            <i class="far fa-check-circle"></i>We Bargain for you and share
            the Best Offer
          </li>
          <li>
            <i class="far fa-check-circle"></i>We ensure Safe &amp; Secure
            Transaction
          </li>
        </ul>
        <a class="btn btn-secondary btn-lg mt5 sign-in-comp" href="{{url('/login')}}">Register Your Car</a>
      </div>
    </div>
  </div>
</section>
<!-- REGISTER CAR -->

<!-- BROWSE USED CAR -->
<section class="browse">
  <div class="container">
    <h2>Browse Used Cars</h2>
    <div class="browse_car">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item active" role="presentation">
          <button class="nav-link" id="pills-Make-tab" data-bs-toggle="pill" data-bs-target="#pills-Make" type="button" role="tab" aria-controls="pills-Make" aria-selected="false"> Make </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-Body-tab" data-bs-toggle="pill" data-bs-target="#pills-Body" type="button" role="tab" aria-controls="pills-Body" aria-selected="false" > Body Type </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-City-tab" data-bs-toggle="pill" data-bs-target="#pills-City" type="button" role="tab" aria-controls="pills-City" aria-selected="false"> City </button>
        </li>
      </ul>
      
      <div class="tab-content" id="pills-tabContent">   
        <div class="tab-pane fade active show" id="pills-Make" role="tabpanel" aria-labelledby="pills-Make-tab">
          <div class="make_content owl-carousel owl-theme">
            <?php $car_makes=DB::table('car_make')->limit(10)->orderBy('name', 'asc')->get(); ?>
            @foreach ($car_makes as $key => $items)
            <div class="category_item item">
              <a href="/used-cars/cars-on-installments/517565" title="Cars on Installments for sale in Pakistan">
                <img alt="Cars on Installments" height="50" loading="lazy" src="https://wsa1.pakwheels.com/assets/browse-more/car-ctg-cars-on-installments-e4a6d1bbbd523692670cbcb719fd155c.svg" /> {{$items->name}}
              </a>
            </div>
            @endforeach
          </div>
        </div>

        <div class="tab-pane fade" id="pills-Body" role="tabpanel" aria-labelledby="pills-Body-tab">
          <div id="browesBTSlider" class="carousel slide lazy-slider" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
              <ul class="browse-listing  item active clearfix" style="min-height: 280px;">
                <?php $car_bodys=DB::table('car_body')->limit(10)->orderBy('name', 'asc')->get(); ?>
                @foreach ($car_bodys as $key => $items)
                <li class="col-sm-2">
                    <a href="/used-cars/sedan/107775" title="{{$items->name}} for sale in Pakistan">
                      <img alt="{{$items->name}}" height="50" loading="lazy" src="https://wsa4.pakwheels.com/assets/browse-more/car-bt-sedan-2206d93ee33f728fded0d4cb3905b7b6.svg">
                      {{$items->name}}
                    </a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>  
        </div>

        <div class="tab-pane fade" id="pills-City" role="tabpanel" aria-labelledby="pills-City-tab">
          <div id="browesCTSlider" class="carousel slide lazy-slider" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
              <ul class="browse-listing browse-auto-link item active clearfix" style="min-height: 280px;">
                <?php $cities=DB::table('cities')->limit(10)->orderBy('name', 'asc')->get(); ?>
                @foreach ($cities as $key => $items)
                <li class="col-sm-2">
                    <a href="/search/city/{{$items->name}}" title="Cars for sale in {{$items->name}}">
                      {{$items->name}}
                    </a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- BROWSE USED CAR -->

<!-- EXPLORE AREA -->
<section class="explore">
  <div class="container">
    <h2>Explore Products by PakWheels</h2>
    <div class="row our-product-widget">
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/used-cars/pakwheels-certified/"
          onclick="trackEvents('CarSure','ViewCertifiedCars','From - Home')"
        >
          <div class="img">
            <img
              alt=""
              src="https://wsa2.pakwheels.com/assets/pw-certified-e59a8598eee3baaa3bb51f2c2769a78f.svg"
              style="margin: 12px 0"
              title=""
              width="120"
            />
          </div>
          <div class="desc">
            <h3>PakWheels Certified Cars</h3>
            <p>Highest Quality Cars with Warranty from PakWheels.</p>
          </div>
        </a>
      </div>
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/products/pakwheels-inspection"
          id="certify-car-index"
          onclick="trackEvents('CarSure','ViewInspectionProductPage','From - Home')"
        >
          <div class="img">
            <img
              alt="PakWheels Car Inspection"
              src="https://wsa2.pakwheels.com/assets/pw-inspection-849cb6ced4920cd19181e3317126618d.svg"
              title="PakWheels Car Inspection"
              width="70"
            />
          </div>
          <div class="desc">
            <h3>
              PakWheels <span class="generic-red">Car Inspection</span>
            </h3>
            <p>200+ points inspection to guarantee your Peace of Mind.</p>
          </div>
        </a>
      </div>
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/auction-sheet-verification/"
          onclick="trackEvents('AuctionSheetVerification','ViewProductPage','From - Home')"
        >
          <div class="img">
            <img
              alt="Auction Sheet"
              src="https://wsa4.pakwheels.com/assets/auction-sheet-logo-ebf19d855fc7b2ff5ec2a081cc5b370c.svg"
              style="margin: 12px 0"
              title="Auction Sheet"
              width="120"
            />
          </div>
          <div class="desc">
            <h3>Auction Sheet Verification</h3>
            <p>
              Buy Japanese cars with Full Confidence with PakWheels Verfied
              Auction Sheet.
            </p>
          </div>
        </a>
      </div>
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/products/pakwheels-sell-it-for-me/"
          onclick="trackEvents('SellItForMe','View Product Page','From - Home')"
        >
          <div class="img">
            <img
              alt="PakWheels Sell It For Me"
              src="https://wsa2.pakwheels.com/assets/sifm-logo-20640083c4572b654a079a4e97c78cb1.svg"
              title="PakWheels Sell It For Me"
              width="75"
            />
          </div>
          <div class="desc">
            <h3>PakWheels Sell It For Me</h3>
            <p>Let PakWheels sell your car Hassle Free for you.</p>
          </div>
        </a>
      </div>
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/car-finance/"
          target="_blank"
          onclick="trackEvents('CarFinance','From-Home','From - Home')"
        >
          <div class="img">
            <img
              alt="Car Finance"
              src="https://wsa4.pakwheels.com/assets/car-finance-logo-332d555f940565ca849f05b9fc30eeab.svg"
              title="Car Finance"
              width="70"
            />
          </div>
          <div class="desc">
            <h3>Car Finance</h3>
            <p>Compare and apply for loan for New or Used Cars</p>
          </div>
        </a>
      </div>
      <div class="col-md-6 mb20">
        <a
          class="well show"
          href="/car-insurance/"
          target="_blank"
          onclick="trackEvents('CarInsurance','From-Home','OpenProductPage')"
        >
          <div class="img">
            <img
              alt="Car Insurance"
              src="https://wsa2.pakwheels.com/assets/car-insurance-logo-cd66a03c66ca7871229920995ff4c629.svg"
              title="Car Insurance"
              width="70"
            />
          </div>
          <div class="desc">
            <h3>Car Insurance</h3>
            <p>Compare and apply for car insurance for a safer ride</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- EXPLORE AREA -->
<!-- MANAGED AREA -->
<section class="managed">
  <div class="container">
    <h2>
      Managed by PakWheels
      <div id="type-sell-it-for-me-home" class="clear-link">
        <a
          href="/used-cars/search/-/direct_1/"
          class="more-link"
          rel="nofollow"
          >View All Managed By PakWheels Cars</a
        >
      </div>
    </h2>
    <div class="slider">
      <div class="row feature_2 owl-carousel owl-theme">
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                >
                  <img
                    alt="New"
                    class="lazy-car-slider pic"
                    data-original="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    rel="nofollow"
                    src="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    title="Toyota Voxy X 2015 for Sale"
                  />
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                  rel="nofollow"
                  >Toyota Voxy 2015</a
                >
              </h3>
              <div class="generic-green">PKR 4,400,000</div>
              <div class="generic-gray">Karachi</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                >
                  <img
                    alt="New"
                    class="lazy-car-slider pic"
                    data-original="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    rel="nofollow"
                    src="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    title="Toyota Voxy X 2015 for Sale"
                  />
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                  rel="nofollow"
                  >Toyota Voxy 2015</a
                >
              </h3>
              <div class="generic-green">PKR 4,400,000</div>
              <div class="generic-gray">Karachi</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                >
                  <img
                    alt="New"
                    class="lazy-car-slider pic"
                    data-original="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    rel="nofollow"
                    src="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    title="Toyota Voxy X 2015 for Sale"
                  />
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                  rel="nofollow"
                  >Toyota Voxy 2015</a
                >
              </h3>
              <div class="generic-green">PKR 4,400,000</div>
              <div class="generic-gray">Karachi</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                >
                  <img
                    alt="New"
                    class="lazy-car-slider pic"
                    data-original="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    rel="nofollow"
                    src="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    title="Toyota Voxy X 2015 for Sale"
                  />
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                  rel="nofollow"
                  >Toyota Voxy 2015</a
                >
              </h3>
              <div class="generic-green">PKR 4,400,000</div>
              <div class="generic-gray">Karachi</div>
            </div>
          </div>
        </div>
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                >
                  <img
                    alt="New"
                    class="lazy-car-slider pic"
                    data-original="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    rel="nofollow"
                    src="https://cache1.pakwheels.com/ad_pictures/5043/Slide_toyota-voxy-x-28-2015-50432216.jpg"
                    title="Toyota Voxy X 2015 for Sale"
                  />
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a
                  href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781"
                  rel="nofollow"
                  >Toyota Voxy 2015</a
                >
              </h3>
              <div class="generic-green">PKR 4,400,000</div>
              <div class="generic-gray">Karachi</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- MANAGED AREA -->
    
<!-- MANAGED AREA -->
<section class="managed">
  <div class="container">
    <h2>
      Featured Used Cars for Sale
      <div id="type-sell-it-for-me-home" class="clear-link">
        <a
          href="/used-cars/search/-/direct_1/"
          class="more-link"
          rel="nofollow"
          >View all managed by Sayarat cars</a
        >
      </div>
    </h2>

    <div class="slider">
      <div class="row feature owl-carousel owl-theme">
        <?php $listings_featured=DB::table('listings')->where('status',"Active")->where('featured',"Yes")->limit(10)->orderBy('listings_id', 'desc')->get(); ?>
        @foreach ($listings_featured as $key => $items)
        <div class="col-md-3 item">
          <div class="cards">
            <div class="featured-ribbon">
              <div class="inner">FEATURED</div>
            </div>
            <div class="img-box">
              <div class="img-content img-valign">
                <a href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781" >
                  <img alt="" class="lazy-car-slider pic" data-original="https://cache3.pakwheels.com/ad_pictures/5492/Slide_honda-civic-oriel-1-8-i-vtec-cvt-2021-54927145.jpg" rel="nofollow" src="https://cache3.pakwheels.com/ad_pictures/5492/Slide_honda-civic-oriel-1-8-i-vtec-cvt-2021-54927145.jpg" title=""/>
                </a>
              </div>
            </div>
            <div class="cards-content">
              <h3 class="nomargin truncate">
                <a href="/used-cars/toyota-voxy-2015-for-sale-in-karachi-4989781" rel="nofollow">
                  @foreach ($car_make as $category)
                      @if ($category->car_make_id == @$items->car_make_id)
                        {{ @$category->name }}
                      @endif
                  @endforeach

                  @foreach ($car_model as $category)
                      @if ($category->car_model_id == @$items->car_model_id)
                        {{ @$category->name }}
                      @endif
                  @endforeach

                  @foreach ($car_variation as $category)
                      @if ($category->car_variation_id == @$items->car_variation_id)
                        {{ @$category->name }}
                      @endif
                  @endforeach
                </a>
              </h3>
              <div class="generic-green">QAR {{ @$items->price }}</div>
              <div class="generic-gray">
                @foreach ($cities as $category)
                    @if ($category->id == @$items->city_id)
                      {{ @$category->name }}
                    @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
  </div>
</section>
<!-- MANAGED AREA -->

<!-- NEW CAR -->
<section class="new_car">
  <div class="container">
    <h2>Featured New Cars</h2>
    <div class="browse_car">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link active"
            id="pills-home-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-home"
            type="button"
            role="tab"
            aria-controls="pills-home"
            aria-selected="true"
          >
            Popular
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-profile-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-profile"
            type="button"
            role="tab"
            aria-controls="pills-profile"
            aria-selected="false"
          >
            Upcoming
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-contact-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-contact"
            type="button"
            role="tab"
            aria-controls="pills-contact"
            aria-selected="false"
          >
            Newly Launched
          </button>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div
          class="tab-pane fade show active"
          id="pills-home"
          role="tabpanel"
          aria-labelledby="pills-home-tab"
        >
          Maker
        </div>
        <div
          class="tab-pane fade"
          id="pills-profile"
          role="tabpanel"
          aria-labelledby="pills-profile-tab"
        >
          Maker 2
        </div>
        <div
          class="tab-pane fade"
          id="pills-contact"
          role="tabpanel"
          aria-labelledby="pills-contact-tab"
        >
          Maker 3
        </div>
      </div>
    </div>
  </div>
</section>
<!-- NEW CAR -->

<!-- COMPARISONS-->
<section class="comparisons">
  <div class="container">
    <h2>
      Car Comparisons
      <div class="clear-link mr320">
        <a
          href="/new-cars/compare/"
          class="more-link"
          id="Home-compare-car"
          title="Car Comparisons"
          >All car comparisons</a
        >
      </div>
    </h2>
    <div class="well p10 clearfix mr320">
      <div class="">
        <div
          class="car-comparisons-view"
          id="Home-main-comparison"
          title="Changan Alsvin vs Proton Saga"
        >
          <a
            href="/new-cars/compare/proton-saga--2-vs-changan-alsvin"
            class="clearfix"
          >
            <div class="car-view-1 pull-left">
              <div class="thumb">
                <img
                  class="lazy"
                  data-original="https://cache2.pakwheels.com/system/car_generation_pictures/5366/medium/Changan-Alsvin-cover.jpg?1607326303"
                  src="https://cache2.pakwheels.com/system/car_generation_pictures/5366/medium/Changan-Alsvin-cover.jpg?1607326303"
                  style="display: inline"
                />
              </div>
              <h3 class="nomargin text-center">Changan Alsvin</h3>
            </div>
            <div class="car-view-1 pull-right">
              <div class="thumb">
                <img
                  class="lazy"
                  data-original="https://cache2.pakwheels.com/system/car_generation_pictures/5372/medium/proton-cover.jpg?1607404323"
                  src="https://cache2.pakwheels.com/system/car_generation_pictures/5372/medium/proton-cover.jpg?1607404323"
                  style="display: inline"
                />
              </div>
              <h3 class="nomargin text-center">Proton Saga</h3>
            </div>
            <div class="car-view-vs">VS</div>
          </a>
          <a
            href="/new-cars/compare/proton-saga--2-vs-changan-alsvin"
            class="btn btn-block btn-link-outline"
          >
            View Comparison
          </a>
        </div>
        <ul
          class="car-comparisons-list list-unstyled pull-right text-center"
          id="Home-comparison-list"
        >
          <li title="Honda" cityvshonda="" grace="" hybrid="">
            <a
              href="/new-cars/compare/honda-city-vs-honda-grace--2"
              title="Honda City vs Honda Grace Hybrid"
            >
              Honda City
              <div>
                <span class="car-view-vs">VS</span>
              </div>
              Honda Grace Hybrid
            </a>
          </li>
          <li title="Prince" pearlvssuzuki="" alto="">
            <a
              href="/new-cars/compare/suzuki-alto-vs-prince-pearl"
              title="Prince Pearl vs Suzuki Alto"
            >
              Prince Pearl
              <div>
                <span class="car-view-vs">VS</span>
              </div>
              Suzuki Alto
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!-- COMPARISONS-->

<!-- BRAND SECTION -->
<section class="brand">
  <div class="container">
    <h2>Brands</h2>
    <div class="brand_slider owl-carousel owl-theme">
      
      <?php $car_makes=DB::table('car_make')->limit(20)->orderBy('name', 'asc')->get(); ?>
      @foreach ($car_makes as $key => $items)
      <div class="brand_slider_item">
        <div class="heading">
          <a href="/new-cars/toyota/" id="amk_toyota" title="{{$items->name}} Cars in Pakistan" >
            <img alt="{{$items->name}}" height="65" loading="lazy" src="https://cache4.pakwheels.com/system/car_manufacturers/manufacturers/000/000/042/resized/Tyota.png"/>
            <h3 class="nomargin">{{$items->name}}</h3>
          </a>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>
<!-- BRAND SECTION -->
@endsection