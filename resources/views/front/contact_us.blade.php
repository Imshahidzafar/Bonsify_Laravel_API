@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container">
   <div class="col-md-6">
      <div class="well mt-5" style="padding: 20px 30px;">
         <legend class="noborder nomargin">Your Details</legend>
         <fieldset>
            <form method="post" action="{{url('/contact_us')}}">
               {{csrf_field()}}
               @if (session('contact_errors'))
               <div class="alert alert-danger">
                  {{ session('contact_errors') }}
               </div>
               @endif
               @if(session('contact_sucess'))
               <div class="alert alert-success">
                  {{ session('contact_sucess') }}
               </div>
               @endif
               <div class="form-group">
                  <input  name="full_name" placeholder="Full Name"  type="text" required="" >
               </div>
               <div class="form-group">
                  <input  name="email" placeholder="name@email.com" size="30" type="text" required >
               </div>
               <div class="form-group">
                  <input  name="mobile" placeholder="03xxxxxxxxx" size="30" type="text" required >
               </div>
               <div class="form-group">
                  <select  name="contact_subject_id" required>
                     <?php $subject = DB::table('contact_subject')->where('status','=','Active')->get(); ?>
                     <option value="">Message Subject</option>
                     @foreach($subject as $subjects)
                     <option value="{{$subjects->contact_subject_id }}">{{$subjects->subject}}</option>
                     @endforeach
                  </select>
                  <textarea required class=" mt20"  name="message" placeholder="Enter Message Subject" type="text"></textarea>
               </div>
               <div class="form-group">
                  <button type="submit" class="btn btn-primary">Send</button>
               </div>
            </form>
         </fieldset>
      </div>
   </div>
   <div class="col-md-4">
      <div class="well mt-5">
         <h2>Feel free to drop by or call to say Hello!</h2>
         <?php $name= DB::table('system_settings')->where('type','=','system_name')->first();?>
         <p>{{ $name->description}}<br>
            <?php $address= DB::table('system_settings')->where('type','=','address')->first();?>
            {{ $address->description}}<br>
            <?php $number= DB::table('system_settings')->where('type','=','system_number')->first();?>  
            Phone: {{ $number->description}}<br>
            <?php $days= DB::table('system_settings')->where('type','=','working_days')->first();?> 
            {{$days->description}}<br>
            <?php $time= DB::table('system_settings')->where('type','=','working_time')->first();?> 
            {{$time->description}}
         </p>
         <p>Find anything about <b>Automobiles</b>&nbsp;only on {{ $name->description}}</p>
         <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
            <meta itemprop="streetAddress" content="PakWheels.com, Third Floor,Saeed Alam Tower, 37-Commercial Zone, Liberty Market, Gulberg Lahore, Pakistan">
            <meta itemprop="addressLocality" content="Pakistan">
            <meta itemprop="telephone" content="042 111 943 357">
         </span>
         <time itemprop="openingHours" datetime="Mo-Fr 09:00-18:00"></time>
      </div>
   </div>
</div>
@endsection