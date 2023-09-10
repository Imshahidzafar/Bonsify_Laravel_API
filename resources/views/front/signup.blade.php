@extends( 'commonfront.inner_main' )
@section( 'title', 'Dashboard' )
@section( 'content' )
@inject( 'helpers', 'App\Classes\Helpers' )

<section class="register_car">
    <div class="container">
        <div class="row">
        
            <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
                <div class="well">
              
                <form class="mt-5 mb-5" method="post" action="{{url('/register_user')}}">
                    <h1 class="mb20">Sign Up</h1>
                    
            {{ csrf_field() }} @if (session('errors'))
            <div class="alert alert-danger">
                {{ session('errors') }}
            </div>
            @endif @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            
            <div class="form-group">
                <a class="btn btn-success btn-block" href="{{url ('/mobile_login')}}"><i class="fa fa-mobile" style="float: left;"></i>Continue with Mobile</a>
            </div>
                    
            <div class="form-group">
                <a class="btn btn-danger btn-block"><i class="fab fa-google" style="float: left;"></i>Continue with Google</a>
            </div>
                    
            <div class="form-group">
                <a class="btn btn-primary btn-block btn-fb"><i class="fab fa-facebook" style="float: left;"></i>Continue with Facebook</a>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="user_name" placeholder="Full Name">
            </div>

            <div class="form-group">
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email Address">
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>

            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control" id="exampleInputPassword1" placeholder="Confirm Password">
            </div>
            
            <div class="form-group">
              <label>
                <span><input checked="checked" type="checkbox">  Send me updates and relevant news.</span>
              </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">Create Account</button>
            </div>

            <div class="form-group mb-5 terms-text">
            By clicking the button above, you are agreeing to our <a href="#">Terms of Use.</a>
            </div>

        </form>
            
            </div>
                </div>
        
        </div>
        <!-- COnTENT AREA -->
        
        <!-- COnTENT AREA -->
    </div>
</section>
@endsection