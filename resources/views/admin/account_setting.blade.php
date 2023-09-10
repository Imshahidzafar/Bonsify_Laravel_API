<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Fito - Fitness Bootstrap Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/favicon.png')}}">
    <!-- Datatable -->
    <link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

</head>

<body>

		<!--**********************************
            Chat box End
        ***********************************-->
 <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, welcome back!</h4>
                            {{-- <p class="mb-0">Validation</p> --}}
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                            {{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Validation</a></li> --}}
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    
                 
                    <div class="col-lg-12">
                        <div class="card">
                            
                            <div class="card-body">
                                <div class="basic-form">
                                    <form class="form-valide-with-icon" action="#" method="post" >
                                        {{-- <div class="form-group">
                                            <label class="text-label">First Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" name="first_name" placeholder="Enter a username..">
                                            </div>
                                        </div> --}}
                                        <div class="form-group">
                                            <label class="text-label">Full Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="" name="first_name" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Phone Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value=""  name="mobile" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="text-label">City</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="" name="city" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="text-label">Address</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                {{-- <input type="text" class="form-control" id="val-username1" value="" name="address" placeholder="Enter a username.."> --}}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Email Address/User Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="" name="email_address" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Password</label>
                                            <div class="input-group transparent-append">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div>
                                                <input type="password" class="form-control" id="val-password1" value="" name="password" placeholder="Choose a safe one..">
                                                <div class="input-group-append show-pass">
                                                    <span class="input-group-text"> <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" value="">
                                                <label class="custom-file-label">Choose image</label>
                                            </div>
                                        </div>
                                       
                                        
                                        <button type="submit" class="btn mr-2 btn-primary">Submit</button>
                                        <button type="submit" class="btn btn-light">cencel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
					
					
					
                </div>
            </div>
        </div>
					

		
		
		<!--**********************************
            Header start
        ***********************************-->
		@include('layout.header.header');

        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
		@include('layout.sidebar.sidebar');
       
        <!--**********************************
            Sidebar end
        ***********************************-->
		
		<!--**********************************
            Content body start
        ***********************************-->
@yield('content')
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        {{-- <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2020</p>
            </div>
        </div> --}}
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        
        <!--**********************************
            Footer start
        ***********************************-->
        {{-- <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2020</p>
            </div>
        </div> --}}
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/deznav-init.js')}}"></script>
	
    <!-- Datatable -->
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>

</body>
</html>