@extends('layout.list_master')

@section('content')

		<!--**********************************
            Chat box End
        ***********************************-->
 <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            @section('titleBar')
                                <span class="ml-2">Bonsify Dashboard!</span>
                            @endsection
                            {{-- <p class="mb-0">Validation</p> --}}
                        </div>
                    </div> 
                </div>
                <!-- row -->
            <div class="container-fluid">
                <div class="row">
					<div class="col-xl col-md-6">
						<div class="card">
							<div class="card-body p-4">
								<div class="d-inline-block mb-4 ml--12 position-relative donut-chart-sale">
									<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(192, 255, 134)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">4/8</span><svg class="peity" height="110" width="110"><path d="M 55 0 A 55 55 0 0 1 55 110 L 55 100 A 45 45 0 0 0 55 10" data-value="4" fill="rgb(192, 255, 134)"></path><path d="M 55 110 A 55 55 0 0 1 54.99999999999999 0 L 54.99999999999999 10 A 45 45 0 0 0 55 100" data-value="4" fill="rgba(255, 255, 255, 1)"></path></svg>
									<small class="text-primary">
									<span class="btn-icon-left text-primary"><i class="fa fa-file"></i>
                                    </span>
									</small>
									<span class="circle bg-primary"></span>
								</div>
								<h2 class="fs-24 text-black font-w600 mb-0">{{$event_posts}}</h2>
								<span class="fs-14">Total Events</span>
							</div>
						</div>
					</div>
					<div class="col-xl col-md-6 col-sm-6">
						<div class="card">
							<div class="card-body p-4">
								<div class="d-inline-block mb-4 ml--12 position-relative donut-chart-sale">
									<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(255, 195, 210)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">3/8</span><svg class="peity" height="110" width="110"><path d="M 55 0 A 55 55 0 0 1 93.89087296526012 93.89087296526012 L 86.81980515339464 86.81980515339464 A 45 45 0 0 0 55 10" data-value="3" fill="rgb(255, 195, 210)"></path><path d="M 93.89087296526012 93.89087296526012 A 55 55 0 1 1 54.99999999999999 0 L 54.99999999999999 10 A 45 45 0 1 0 86.81980515339464 86.81980515339464" data-value="5" fill="rgba(255, 255, 255, 1)"></path></svg>
									<small class="text-primary">
									<span class="btn-icon-left text-primary"><i class="fa fa-users"></i>
                                    </span>
									</small>
									<span class="circle bg-danger"></span>
								</div>
								<h2 class="fs-24 text-black font-w600 mb-0">{{$users}}</h2>
								<span class="fs-14">Registered Users</span>
							</div>
						</div>
					</div>
					<div class="col-xl col-md-4 col-sm-6">
						<div class="card">
							<div class="card-body p-4">
								<div class="d-inline-block mb-4 ml--12 position-relative donut-chart-sale">
									<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(255, 213, 174)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">5/8</span><svg class="peity" height="110" width="110"><path d="M 55 0 A 55 55 0 1 1 16.109127034739892 93.89087296526012 L 23.180194846605364 86.81980515339464 A 45 45 0 1 0 55 10" data-value="5" fill="rgb(255, 213, 174)"></path><path d="M 16.109127034739892 93.89087296526012 A 55 55 0 0 1 54.99999999999999 0 L 54.99999999999999 10 A 45 45 0 0 0 23.180194846605364 86.81980515339464" data-value="3" fill="rgba(255, 255, 255, 1)"></path></svg>
									<small class="text-primary">
									<span class="btn-icon-left text-primary"><i class="fa fa-shopping-cart"></i>
                                    </span>
									</small>
									<span class="circle bg-warning"></span>
								</div>
								<h2 class="fs-24 text-black font-w600 mb-0">{{$tickets}}</h2>
								<span class="fs-14">Tickets Sold</span>
							</div>
						</div>
					</div>
					<div class="col-xl col-md-4 col-sm-6">
						<div class="card">
							<div class="card-body p-4">
								<div class="d-inline-block mb-4 ml--12 position-relative donut-chart-sale">
									<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(238, 252, 255)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">8/8</span><svg class="peity" height="110" width="110"><path d="M 55 0 A 55 55 0 1 1 54.99 0 L 54.99 10 A 45 45 0 1 0 55 10" data-value="8" fill="rgb(238, 252, 255)"></path></svg>
									<small class="text-primary">
									<span class="btn-icon-left text-primary"><i class="fa fa-shopping-basket"></i>
                                    </span>
									</small>
									<span class="circle bg-info"></span>
								</div>
								<h2 class="fs-24 text-black font-w600 mb-0"> {{$businesses}}</h2>
								<span class="fs-14">Total Businesses</span>
							</div>
						</div>
					</div>
					<div class="col-xl col-md-4 col-sm-6">
						<div class="card">
							<div class="card-body p-4">
								<div class="d-inline-block mb-4 ml--12 position-relative donut-chart-sale">
									<span class="donut1" data-peity="{ &quot;fill&quot;: [&quot;rgb(242, 255, 253)&quot;, &quot;rgba(255, 255, 255, 1)&quot;],   &quot;innerRadius&quot;: 45, &quot;radius&quot;: 10}" style="display: none;">8/8</span><svg class="peity" height="110" width="110"><path d="M 55 0 A 55 55 0 1 1 54.99 0 L 54.99 10 A 45 45 0 1 0 55 10" data-value="8" fill="rgb(242, 255, 253)"></path></svg>
									<small class="text-primary">
									<span class="btn-icon-left text-primary"><i class="fa fa-tags"></i>
                                    </span>
									</small>
									<span class="circle bg-success"></span>
								</div>
								<h2 class="fs-24 text-black font-w600 mb-0">{{$categories}}</h2>
								<span class="fs-14">Total Categories</span>
							</div>
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

@endsection