@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
	<section class="login-block ">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12" >
						<form class="md-float-material form-material" method="POST" action="{{url('/reset_password/update_password/'.$data)}}" style="display: table;margin: 0 auto;" >
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
										<div class="col-md-12">
											<h3 class="text-center">Change Password</h3>
										</div>
									</div>
									@if(session('user_name'))
									<div class="form-group form-primary">
										<input type="password"  name="old_password" id="old_password" class="form-control" required="" placeholder="Old Password">
										<span class="form-bar"></span>
									</div>
									@endif
									<div class="form-group form-primary">
										<input type="password"  name="new_password" id="new_password" class="form-control" required="" placeholder="New Password">
										<span class="form-bar"></span>
									</div>
									<div class="form-group form-primary">
										<input type="password" name="confirm_password" onkeyup="macth_password(this.id)" class="form-control" required="" placeholder="Confirm Password" id="confirm_password" >
										<span class="form-bar"></span>
									</div>
									<label id="lbl_error_pass" style="color: red; display:none;">Password did not match, please check your confirm password.</label>
    							
									<div class="row ">
										<div class="col-md-12">
											<button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20"  disabled id="upd_password" >Save password</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
@endsection
		<script>
	    function macth_password(){
	        var new_pass = jQuery('#new_password').val();
	        var conf_pass = jQuery('#confirm_password').val();
	        if(new_pass == conf_pass){
	            jQuery('#upd_password').prop( "disabled", false );
	            jQuery('#lbl_error_pass').hide();
	        } else if(new_pass != conf_pass){
	            jQuery('#upd_password').prop( "disabled", true );
	            jQuery('#lbl_error_pass').show();
	        }
	    }
	    
	</script>
