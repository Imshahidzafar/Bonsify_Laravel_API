 
 
 
 		<div class="deznav">
            <div class="deznav-scroll">
			
				<ul class="metismenu" id="menu">
					<li><a href="{{ url('/dashboard')  }}" class="has-arrow ai-icon" aria-expanded="false"><i class="fa-solid fa-gauge"></i><span class="nav-text">Dashboard</span></a></li>
                    
 					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-user"></i>
							<span class="nav-text">Users</span>
						</a>
                        <ul aria-expanded="false">
						
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href="{{ url('/new_manage_users')  }}">Manage Users</a></li>
							{{-- <li><a href="{{ url('/new_manage_usercategories') }}">User Categories</a></li> --}}
							
						</ul>
                    </li>

					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							
							<i class="fa-solid fa-house-user"></i>
							
							<span class="nav-text">Events</span>
						</a>
                        <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							{{-- <li><a href="{{ url('/dashboard')  }}">Dashboard</a></li> --}}
							<li><a href="{{ url('/new_manage_event_posts') }}">Manage Events</a></li>
							{{-- <li><a href="{{ url('/new_manage_addresses') }}">Events Address</a></li> --}}
							<li><a href="{{ url('/new_manage_event_tags') }}">Event Tags</a></li>
							<li><a href="{{ url('/new_event_categories') }}">Event Categories</a></li>
							<li><a href="{{ url('/new_event_type') }}">Event Type</a></li>
							<li><a href="{{ url('/new_manage_dress_codes') }}">Dress Codes</a></li>
							{{-- <li><a href="personal-record.html">Personal Record</a></li> --}}
							<li><a href="{{ url('/new_manage_event_tickets') }}">Events Ticket purchase</a></li>
						</ul>
                    </li>

					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							
							<i class="fa fa-briefcase" aria-hidden="true"></i>

							
							<span class="nav-text">Business</span>
						</a>
                        <ul aria-expanded="false">
							<li><a href="{{ url('/new_all_business') }}">Manage Business</a></li>
							<li><a href="{{ url('/new_business_type') }}">Business Type</a></li>
						</ul>
                    </li>    

					<li><a href="{{ url('/new_reports')  }}" class="has-arrow ai-icon" aria-expanded="false"><i class="fa fa-briefcase"></i><span class="nav-text">Reports</span></a></li>

					<li><a href="{{ url('/new_refund_requests')  }}" class="has-arrow ai-icon" aria-expanded="false"><i class="fa fa-briefcase"></i><span class="nav-text">Refund Requests</span></a></li>

                    <!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-list-check"></i>
							<span class="nav-text">Manage Sales</span>
						</a>
                        <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href="#">Manage Sales</a></li>
							
							
						</ul>
                    </li> -->

					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-user"></i>
							<span class="nav-text">Account Setting</span>
						</a>
                        <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href=" {{ url('new_account_settings') }}">Account Settings</a></li>
						</ul>
                    </li>

                    <!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-bars-progress"></i>
							<span class="nav-text">Manage System</span>
						</a>
                        <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href="{{ url('/new_manage_categories') }}">Categories</a></li>
							<li><a href="{{ url('/new_manage_eventtypes') }}">Event Types</a></li>
							<li><a href="{{ url('/new_manage_dresscodes') }}">Dress Codes</a></li>
							<li><a href="{{ url('/new_manage_tags') }}">Tags</a></li>
							{{-- <li><a href="new_personal-record.html">Personal Record</a></li> --}}
						</ul>
                    </li> -->
                    <!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-credit-card"></i>
							<span class="nav-text">Payments Management</span>
						</a>
                         <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href="{{ url('/new_manage_cards') }}">Card</a></li>
							<li><a href="{{ url('/new_manage_conncash') }}">Conncash</a></li>
							<li><a href="{{ url('/new_request_payment') }}">Requested Payments</a></li>
							{{-- <li><a href="food-menu.html">Tags</a></li> --}}
							{{-- <li><a href="personal-record.html">Personal Record</a></li> --}}
						</ul> -->
                    <!-- </li> -->
					<!-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							{{-- <i class="flaticon-381-networking"></i> --}}
							<i class="fa-solid fa-gear"></i>
							 <span class="nav-text">Account Setting</span> 
						</a>
                        <ul aria-expanded="false">
							{{-- <li><a href="index.html">Dashboard</a></li> --}}
							<li><a href=" {{ url('new_account_settings') }}">Account Settings</a></li>
							
						</ul>
                    </li> -->
					
                    {{-- <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-television"></i>
							<span class="nav-text">Apps</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="./app-profile.html">Profile</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                                <ul aria-expanded="false">
                                    <li><a href="./email-compose.html">Compose</a></li>
                                    <li><a href="./email-inbox.html">Inbox</a></li>
                                    <li><a href="./email-read.html">Read</a></li>
                                </ul>
                            </li>
                            <li><a href="./app-calender.html">Calendar</a></li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
                                <ul aria-expanded="false">
                                    <li><a href="./ecom-product-grid.html">Product Grid</a></li>
									<li><a href="./ecom-product-list.html">Product List</a></li>
									<li><a href="./ecom-product-detail.html">Product Details</a></li>
									<li><a href="./ecom-product-order.html">Order</a></li>
									<li><a href="./ecom-checkout.html">Checkout</a></li>
									<li><a href="./ecom-invoice.html">Invoice</a></li>
									<li><a href="./ecom-customers.html">Customers</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-notepad"></i>
							<span class="nav-text">Forms</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="./form-element.html">Form Elements</a></li>
                            <li><a href="./form-wizard.html">Wizard</a></li>
                            <li><a href="./form-editor-summernote.html">Summernote</a></li>
                            <li><a href="form-pickers.html">Pickers</a></li>
                            <li><a href="form-validation-jquery.html">Jquery Validate</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-network"></i>
							<span class="nav-text">Table</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="table-bootstrap-basic.html">Bootstrap</a></li>
                            <li><a href="table-datatable-basic.html">Datatable</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-layer-1"></i>
							<span class="nav-text">Pages</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="./page-register.html">Register</a></li>
                            <li><a href="./page-login.html">Login</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                                <ul aria-expanded="false">
                                    <li><a href="./page-error-400.html">Error 400</a></li>
                                    <li><a href="./page-error-403.html">Error 403</a></li>
                                    <li><a href="./page-error-404.html">Error 404</a></li>
                                    <li><a href="./page-error-500.html">Error 500</a></li>
                                    <li><a href="./page-error-503.html">Error 503</a></li>
                                </ul>
                            </li>
                            
                        </ul>
                    </li>
                </ul> --}}
				
				{{-- <div class="copyright">
					<p><strong>Fito Fitness Admin Dashboard</strong> ©All Rights Reserved</p>
					<p>by DexignZone</p>
				</div> --}}
			</div>
        </div>