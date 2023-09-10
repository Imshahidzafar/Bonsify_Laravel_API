@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container" style="margin-top: 50px;">
   <div class="registration-page registration-panel">
      <div class="row">
         <div class="col-md-6 registration-span">
            <div class="well infobip-well">
               <div>
                  <img alt="Infobip-sms" class="mb20" height="100" src="https://wsa3.pakwheels.com/assets/infobip/infobip-sms-16254b56961c3f9e508d4697b7bd2c6d.png">
               </div>
               <!--  @if (session('errors'))
                <div class="alert alert-danger">
                  {{ session('errors') }}
                </div> 
                @endif
 -->
               <h3>Enter Your Mobile Number</h3>
               <p class="mb20">Please enter a valid number where an SMS can be sent to verify your number. This number will be used to log you into PakWheels.</p>
               <form accept-charset="UTF-8" action="{{url('/login_mobile')}}" class="form-horizontal nomargin"  method="post" >
                  {{ csrf_field() }}
                  <input class="prefilled parsley-success"  name="mobile" placeholder="e.g 03xxxxxxxxx" type="text" >
                  <ul class="parsley-errors-list" ></ul>
                  <button type="submit" class="btn btn-block btn-success" >Next</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection