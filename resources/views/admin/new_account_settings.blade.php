@extends('layout.list_master')

@section('content')
		<!--**********************************
            Chat box End
        ***********************************-->
 <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mb-n5">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <!-- <h4>Hi, welcome back!</h4> -->
                            {{-- <p class="mb-0">Validation</p> --}}
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                        @section('titleBar')
                        <span class="ml-2">Update Profile:</span>
                            @endsection
                          
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
                                    <form  action="{{route('new_profile_update',$data[0]->users_system_id) }}" method="POST" enctype="multipart/form-data">
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
                                                    <span class="input-group-text"> <i class="fa-solid fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="{{$data[0]->first_name}}" name="first_name" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Phone Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa-solid fa-mobile-retro"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="{{$data[0]->mobile}}"  name="mobile" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="text-label">City</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa-solid fa-house"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="{{$data[0]->city}}" name="city" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="text-label">Address</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa-solid fa-location-arrow"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="{{$data[0]->address}}"  name="address" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Email Address/User Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa-solid fa-at"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" value="{{$data[0]->email}}" name="email" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Password</label>
                                            <div class="input-group transparent-append">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div>
                                                <input type="password" class="form-control" id="val-password1" value="{{$data[0]->password}}"" name="password" placeholder="Choose a safe one..">
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
                                                <input type="file" class="custom-file-input" name="image">
                                                {{-- <img height="100px" width="100px" src="uploads/users{{$data[0]->user_image}}"> --}}
                                                <label class="custom-file-label">Choose image</label>
                                            </div>
                                            {{-- <img height="100" width="100" src="{{asset('/uploads/users/$data[0]')}}"->user_image"> --}}
                                            {{-- <img src="http://your.url/img/stuvi-logo.png" class="css-class" alt="alt text"> --}}

                                        </div>
                                       
                                        
                                        <button type="submit" class="btn mr-2 btn-primary">Submit</button>
                                        <button type="submit" class="btn btn-light">cancel</button>
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
		<!-- @include('layout.header.header'); -->

        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
		<!-- @include('layout.sidebar.sidebar'); -->
       
        <!--**********************************
            Sidebar end
        ***********************************-->
		
		<!--**********************************
            Content body start
        ***********************************-->
<!-- @yield('content') -->
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
        
       **********************************
            Footer start
        ***********************************-->
     
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
    <!-- <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/deznav-init.js')}}"></script>
	
     Datatable -->
    <!-- <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script> -->
    <!-- <script src="{{asset('js/plugins-init/datatables.init.js')}}"></script> -->

@endsection