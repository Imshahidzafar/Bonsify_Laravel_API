@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<section class="register_car">
  <div class="container">
    <!-- COnTENT AREA -->
    <form style="display: table;margin: 0 auto;" class="bg-light mt-5 mb-5"   method="post" action="{{url('/register_dealer')}}">
     {{ csrf_field() }}
      @if (session('errors'))
      <div class="alert alert-danger">
        {{ session('errors') }}
      </div> 
      @endif


      @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div> 
      @endif
      <div class="form-group m-3" >
        <a class="btn btn-danger btn-block" ><i class="fab fa-google" style="float: left;"></i>Continue with Google</a>
      </div>
      
      <div class="form-group m-3" >
        <a class="btn btn-primary btn-block" ><i class="fab fa-facebook" style="float: left;"></i>Continue with Facebook</a>
      </div>
   
      <div class="form-group m-3" >
        <a class="btn btn-success btn-block" href="{{url ('/mobile_login')}}" ><i class="fa fa-mobile" style="float: left;"></i>Continue with Mobile</a>
      </div>
  
      <div class="form-group m-3" >
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="user_name" placeholder="Full Name" required="">
      </div>

      <div class="form-group m-3" >
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email Address" required="">
      </div>
     
      <div class="form-group m-3">
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required="">
      </div>
    
      <div class="form-group m-3">
        <input type="password" name="confirm_password" class="form-control" id="exampleInputPassword1" placeholder="Confirm Password" required="">
      </div>
       <div class="form-group m-3" >
        <select name="city_id" required="">
           <?php $city = DB::table('cities')->get(); ?>
           <option value="">Select City</option>
           @foreach($city as $cities)

           <option value="{{$cities->id}}">{{$cities->name}}</option>
           @endforeach
          </select>
      </div>
  
      <div class="form-group m-3">
        <button type="submit" class="btn btn-block btn-primary">Create Account</button>
      </div>
    
      <div class="form-group m-3 mb-5">
        <a  href="#">By clicking the button above, you are agreeing to our Terms of Use.</a>
      </div>

    </form>
    <!-- COnTENT AREA -->
  </div>
</section>
@endsection