@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

    <!-- COnTENT AREA -->
    <section class="login-block">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <form class="md-float-material form-material" method="POST" action="{{url('/retrieve_password')}}" style="display: table;margin: 0 auto;">
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
              <div class="auth-box card">
                <div class="card-block">
                  <div class="row m-b-20">
                    <div class="col-md-6">
                      <h3 class="text-center">Reset Password</h3>
                    </div>
                  </div>
                  <div class="form-group form-primary">
                    <input type="text" name="retrive_email" class="form-control" onkeyup="CheckEmail(this.value)" required="" placeholder="Your Email">
                    <span class="form-bar"></span>
                    <p id="error_email" style="color:red;"></p>
                  </div>
                  <div class="row text-left">
                    <div class="col-12">
                    
                      <div class="forgot-phone mt-2">
                        <a href="{{url('/login')}}" class="text-right f-w-600"> Back to Login?</a>
                      </div>
                      
                    </div>
                  </div>
                  <div class="row ">
                    <div class="col-md-12 ">
                      <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20" id="forgotBtn">Reset Password</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- COnTENT AREA -->
    @endsection