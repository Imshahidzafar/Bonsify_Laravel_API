@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

  <section>
  <div class="container">
    <div class="well p40">
      <div style="max-width: 580px; margin: 0 auto;">
        <h1 class="text-center">How Do You Want To Sell Your Car?</h1>
        <ul class="pitching-widgets pre-sell-select">
          <li>
            <div class="pull-right">
              <img alt="Post-ad-art" src="https://wsa3.pakwheels.com/assets/post-ad-art-435256a73c8e556f649c9daec7b922df.png" style="margin-top: -30px">
            </div>
            <div class="generic-gray">
              <label class="item styled-control styled-control-radio">
                <input checked="checked" class="package-input input-align pull-left" data-parsley-trigger="change" id="sell_option____used-cars_sell_post-ad_____UsedCars_____AddCar_____From_-_ToggleScreen_" name="sell_option" type="radio" value="/used-cars/sell/post-ad ,UsedCars ,AddCar ,From - ToggleScreen">
                <div class="styled-control-indicator"></div>
                <h3 class="fs22 generic-grey fwl">Post your Ad on PakWheels</h3>
                <ul class="list-unstyled fs14">
                  <li>
                    <i class="fa fa-tick generic-green mr5"></i>
                    Post your Ad for Free in 3 Easy Steps
                  </li>
                  <li>
                    <i class="fa fa-tick generic-green mr5"></i>
                    Get Genuine offers from Verified Buyers
                  </li>
                  <li>
                    <i class="fa fa-tick generic-green mr5"></i>
                    Sell your car Fast at the Best Price
                  </li>
                </ul>
              </label>
            </div>
          </li>
        </ul>
        <div class="text-center mt20">
          <button id="select-sell-option" name="Submit" class="btn btn-success btn-lg" type="submit" style="padding: 10px 50px">
            <a class="generic-red" style="text-decoration: underline;" href="{{url('/car_sale')}}">Continue</a>
          </button>

          <p class="generic-red mt10 fs12">
            * By clicking "Continue" you are agreeing to the 
            <a class="generic-red" style="text-decoration: underline;" href="{{url('/terms')}}">terms of service</a>, 
            <a class="generic-red" style="text-decoration: underline;" href="{{url('/privacy')}}">privacy policy</a>
          </p>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection