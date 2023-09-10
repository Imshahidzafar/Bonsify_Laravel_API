@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container" style="margin-top: 50px;">
   <div class="registration-page registration-panel">
      <div class="row">
         <div class="col-md-6 registration-span">
            <div class="well infobip-well">
               <h3>Enter Your Code</h3>
               <p class="mb30">Please enter the Code {{ session('verify_code')}}</p>
               <form accept-charset="UTF-8" action="{{url('/verify_users_token')}}" class="form-horizontal nomargin" method="post">
                  {{ csrf_field() }}
                  <!-- @if (session('errors'))
                  <div class="alert alert-danger">
                     {{ session('errors') }}
                  </div>
                  @endif -->
                  <input class="prefilled" name="verify_code" style="width: 50%;" type="text" placeholder="0 0 0 0 0 0 0" >
                  <ul class="parsley-errors-list" ></ul>
                  <button type="submit" class="btn btn-block btn-success">Next</button>
               </form>
               <p class="resend text-align-left" id="didnt-get-code" style="display: none;text-align:left;">Didn’t get the code?</p>
               <p class="resend " id="resend-pin-block" style="display: none;text-align:left;">
                  <a id="resend-pin-btn" class="btn-link nomargin" onclick="handleResendPin();" href="#">
                  Resend Code via SMS
                  </a>
               </p>
               <p class="resend" id="pin-status-block" style="display: none;text-align:left;">
                  Code has been sent successfully
               </p>
               <div class="resend" id="wait-before-resend" style="display:none;text-align:left;">
                  You can request to resend code in <span id="counter">30 seconds</span>.
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection