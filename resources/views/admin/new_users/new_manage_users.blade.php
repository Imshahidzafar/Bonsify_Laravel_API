@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>
        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles mb-n5">
					<ol class="breadcrumb">
                    @section('titleBar')
                        <span class="ml-2">Manage Users</span>
                            @endsection
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">
                            
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <br>
                @if(session('success'))
                <div class="alert alert-success">
                   {{ session('success') }}
                </div>
                @endif
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                           <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>ID</th>
                             <th>User Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th>Total Conncash</th>
                            {{-- <th>Date Added</th> --}}
                            <th style="width: 80px; height:80px;">Profile Picture</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($users as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->users_id  }}" data-toggle="modal" data-target="#addMoreTraining" onClick="show_users_id(this.id)" >view details </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->users_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >active </a></button> 
                                        <button class="dropdown-item" type="button"><a id="{{ $items->users_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >inactive </a></button>                                      
                                     
</div>
                                </div>
                            </td>
                            <td>{{ $items->users_id }}</td>
                            <td>{{ $items->user_name }}</td>
                            <td>{{ $items->first_name }}</td>
                            <td>{{ $items->email }}</td>
                            <td>{{ $items->account_type }}</td>
                            <td>{{ $items->total_conncash }}</td>
                            {{-- <td>{{date_format(date_create($items->date_added ), 'd-m-y')}}</td> --}}
                                   
                             <td>  @if($items->profile_picture)  
                                <img src="{{ asset('uploads/profile_images/'.$items->profile_picture)}}" width="80px" height="80px">
                                @else
                                <img src="{{asset('uploads/profile_images/default_profile.png')}}" height="80px" width="80px">
                                @endif
                            </td>
                            
                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span>
                                 
                                @elseif ($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>
                                @else 
                                   <span class="btn btn-warning">In Active</span>
                                @endif
                            </td>
                        
                            @endforeach
                            </tbody>
                                    </table>









                                    <div class="col-xl-12">
								<div class="card">
									<div class="card-body p-0">
										{{-- <a href="javascript:void(0);" class="bg-primary text-white text-center p-4 fs-20 d-block rounded" data-toggle="modal" data-target="#addMoreTraining">+ Add more training</a> --}}
										<!-- Modal -->
										<div class="modal   fade" id="addMoreTraining">
											<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
                                                    <h2>User Detail</h2>
														<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
														</button>
													</div>
													<div class="modal-body">
														
                                                        <br>
                                                        <div class="row">
															<div class="col-3 first_name">
																<b>First Name: </b>
                                                                <br> 
																<!-- $event_posts.event_post_id -->
                                                                <label id="first_name"></label>
															</div>
                                                            <div class=" col-3 last_name">
                                                             
																<b>Last Name: </b>
                                                                <br> 
																<!-- $event_posts.event_post_id -->
                                                                <label id="last_name"></label>
															</div>
															<div class="col-sm-3 user_name">
                                                           
																<b>UserName: </b>
                                                                <br> 
                                                                <label id="user_name"></label>
															</div>
                                                            </div>
                                                           
                                                            <div class="row">
                                                            <div class="col-sm-3 role_name">
                                                            <br>
																<b>User Role: </b>
                                                                <br> 
																<!-- $event_posts.event_post_id -->
                                                                <label id="role_name"></label>
															</div>
															<div class="col-sm-3 email">
                                                            <br>
																<b>Email:</b>
                                                                <br> 
                                                                <label  id="email"></label>
															</div>
                                                           <div class="col-sm-3 paypal_email">
                                                           <br>
                                                            <b >Paypal Email:</b>
                                                            <br> 
                                                                <label id="paypal_email"></label>
															</div>
                                                            </div>
                                                            <div class="row">
                                                            <div class="col-sm-3 account_type">
                                                            <br>
                                                            <b>Account Type:</b>
                                                            <br> 
                                                                <label id="account_type"></label>
															</div>
                                                            <div class="col-sm-3 total_conncash">
                                                            <br>
                                                            <b>Total Conncash:</b>
                                                            <br> 
                                                                <label id="total_conncash"></label>
															</div>
                                                            <div class="col-sm-3 conncash_dollars">
                                                            <br>
                                                            <b>Conncash Dollars:</b>
                                                            <br> 
                                                                <label id="conncash_dollars"></label>
															</div>
                                                            </div>
                                                           
                                                           <br>
                                                            <div class="row">
                                                            <div class="col-sm-3 social_acc_type">
                                                             <b>Social Account Type:</b>
                                                             <br> 
                                                                <label id="social_acc_type"></label>
															</div>
                                                             <div class="col-sm-3 stripe_customer_id">
                                                             <b>Stripe Customer Id:</b>
                                                             <br> 
                                                            <label id="stripe_customer_id"></label>
															</div>
                                                           
                                                            <div class="col-sm-3 facebook_id">
                                                            
                                                             <b>Facebook Id:</b>
                                                             <br> 
                                                            <label id="facebook_id"></label>
															</div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                            <div class="col-sm-3 subscription_package_id">
                                                             <b>Subscription Package:</b>
                                                             <br> 
                                                            <label id="subscription_package_id"></label>
															</div>
                                                            <div class="col-sm-3 package_updated_at"> 
                                                                <b>Package Updated At</b>
                                                                <br>                                          
                                                            <label id="package_updated_at" ></label>

															</div>
                                                           
                                                            <div class="col-sm-3 one_time_post_count">
                                                             <b>One Time Post Count:</b>
                                                            <br>
                                                            <label id="one_time_post_count"></label>
</div>
</div><br>
                                                                                                      
                                                            <div class="col-sm-3 profile_picture">
                                                            <b>profile_picture:</b>  
                                                      
                                                            <br>
                                                            <br>

                                                                <img src="" style="height: 290px; width: 55%; border:rounded;" class="rounded" id="profile_picture">
															</div> 
                                                            <br>
                                                            <div class="row pb-4">
                                                            <div class="col-sm-3 date_added">
                                                       
                                                             <b>Date Added:</b>
                                                         <br>
                                                            <label id="date_added"></label>
                                                          

															</div>
                                                            <div class="col-sm-4 one_signal_id">
                                                           
                                                           <b>One Signal Id:</b>
                                                           <br>
                                                           
                                                          <label id="one_signal_id"></label>
                                                          </div>   
                                                            
                                                            <br>
                                                            <div class="col-3 status">
                                                             <b>Status:</b>
                                                            <br>
                                                            <label id="status"></label>
															</div>
                                                            </div>
                                                            
													</div>
												</div>
											</div>
										</div>


										
									</div>
								</div>
							</div>















                                </div>
                            </div>
                        </div>
                    </div>
                    				</div>
            </div>
        </div>


        <script>
            function show_users_id(clicked_id){
    $.ajaxSetup({
   headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});
     var a=clicked_id;
   
//  alert(a);

$.ajax({
        url:"{{ route('show_new_manage_users')}}",
        type:'get',
        data:'id='+a,
        success:function(response){
            // console.log(response);
            $('#role_name').html(response.users[0].role_name);
            $('#first_name').html(response.users[0].first_name);
            $('#last_name').html(response.users[0].last_name);
            $('#user_name	').html(response.users[0].user_name	);
            $('#email').html(response.users[0].email);
            $('#account_type').html(response.users[0].account_type);
            $('#total_conncash').html(response.users[0].total_conncash);
            $('#conncash_dollars').html(response.users[0].conncash_dollars);
            if (response.users[0].paypal_email==null || response.users[0].paypal_email=="") {
                $('.paypal_email').hide();
            }else if (response.users[0].paypal_email !==null || response.users[0].paypal_email !=="") {         
            $('#paypal_email').html(response.users[0].paypal_email);
        }
        if (response.users[0].profile_picture=="") {
                $('.profile_picture').hide();
            }else{
     
            $('#profile_picture').attr("src", "https://app.connevents.com/public/uploads/profile_images/"+response.users[0].profile_picture);
        }

        if (response.users[0].social_acc_type=="") {
                $('.social_acc_type').hide();
            }else{
            $('#social_acc_type').html(response.users[0].social_acc_type);
        }
        if (response.users[0].facebook_id ==null || response.users[0].facebook_id =="") {
                $('.facebook_id').hide();
            }else if (response.users[0].facebook_id !==null && response.users[0].facebook_id !=="") {
                
            $('#facebook_id').html(response.users[0].facebook_id);
        }
        if (response.users[0].stripe_customer_id ==null || response.users[0].stripe_customer_id =="") {
                $('.stripe_customer_id').hide();
            }else if (response.users[0].stripe_customer_id !==null && response.users[0].stripe_customer_id !=="") {        
            $('#stripe_customer_id').html(response.users[0].stripe_customer_id);
        }
        $('#subscription_package_id').html(response.users[0].subscription_package);

        $('#package_updated_at').html(response.users[0].package_updated_at);

        $('#one_time_post_count').html(response.users[0].one_time_post_count);

        if (response.users[0].one_signal_id=="") {
                $('.one_signal_id').hide();
            }else{
            $('#one_signal_id').html(response.users[0].one_signal_id);
        }

        $('#date_added').html(response.users[0].date_added);
        $('#status').html(response.users[0].status);



        }
    });

   }
        </script>
@endsection