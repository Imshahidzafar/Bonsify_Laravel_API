@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles mb-n5">
					<ol class="breadcrumb">
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Mange Event Post</a></li> -->
						
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">

                            <div class="welcome-text">
                        @section('titleBar')
                        <span class="ml-2">Mange Event Post!</span>
                            @endsection
                            {{-- <p class="mb-0">Validation</p> --}}
                        </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w600">
                                        <thead>
                                           <tr>
                            <th>#</th>
                             <th>Action</th>
                             <th>Event Title</th>
                            
                             <th>Event Type</th>
                             <th>User Name</th>
                             <th>Category</th>
                             <th>Event Start Time</th>
                             <th>Sales Start Time</th>

                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                             @foreach ($eventPosts as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                     <!-- <a href="javascript:void(0);"data-toggle="modal" data-target="#addMoreTraining"></a> -->
                                        <button class="dropdown-item" type="button" value="{{$items->event_post_id }}" ><a  id="{{ $items->event_post_id  }}" onClick="event_post_id(this.id)" <a href="javascript:void(0);" data-toggle="modal" data-target="#addMoreTraining">View Detail </a></a></button>
                                        <button class="dropdown-item" type="button"><a id="{{ $items->event_post_id  }}" <a href="javascript:void(0);"data-toggle="modal" data-target="#addMoreTraining">active </a></a></button>
                                        <button class="dropdown-item" type="button"><a id="{{ $items->event_post_id }}" <a href="javascript:void(0);"data-toggle="modal" data-target="#addMoreTraining">inactive </a></a></button>

</div>
                                </div>
                            </td>
                      
                            <td>{{ $items->title }}</td>
                            <?php  $items->event_type = DB::table('event_type')->where('event_type_id', $items->event_type_id)->get();    ?>
                            <td>{{ $items->event_type[0]->event_type }}</td>

                            <?php  $items->user_name = DB::table('users')->where('users_id', $items->users_id)->get();    ?>
                            <td>{{ $items->user_name[0]->user_name }}</td>

                           

                            <?php  $items->category = DB::table('categories')->where('category_id', $items->category_id)->get();    ?>
                            <td>{{ $items->category[0]->category }}</td>

{{--                            <?php  $items->dress_code = DB::table('dress_code')->where('dress_code_id', $items->dress_code_id)->get();    ?>--}}
{{--                            <td>{{ $items->dress_code[0]->dress_code }}</td>--}}

                            <?php
                               $items->event_start_date_var = \Carbon\Carbon::parse($items->event_start_date)->format('d/m/y');
                               $items->event_start_time_var = \Carbon\Carbon::parse($items->event_start_time)->format('h:m a');
                               $items->event_start_time_concat = $items->event_start_date_var. ' ' .$items->event_start_time_var;
                            ?>
                            <td> {{ $items->event_start_time_concat}}</td>


                            <?php
                            $items->sales_start_date_var = \Carbon\Carbon::parse($items->sales_start_date)->format('d/m/y');
                            $items->sales_start_time_var = \Carbon\Carbon::parse($items->sales_start_time)->format('h:m a');
                            $items->sales_start_time_concat = $items->sales_start_date_var. ' ' .$items->sales_start_time_var;
                         ?>
                         <td> {{ $items->sales_start_time_concat}}</td>


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
                          <h2>Event Detail</h2>
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
                                <div class="col-3">
                                                             
																<b>Last Name: </b>
                                  <br> 
																<!-- $event_posts.event_post_id -->
                               <label id="last_name"></label>
															</div>
															<div class="col-3">
                               
																<b>UserName: </b>
                                <br>
                              <label id="user_name"></label>
															</div>
                              </div>
                              <br>
                              <div class="row">
															<div class="col-sm-3 form-group">
                                                          
																<b>Title:</b>
                                                                <br> 
                                                                <label  id="title"></label>
															</div>
                              <div class="col-sm-3 form-group">
                                                             <b>Event Type:</b>
                                                             <br> 
                                                                <label id="event_type"></label>
															</div>
                                   <div class="col-sm-3 form-group">
                                   <b>Event Ticket Type:</b>
                                   <br> 
                                  <label id="event_ticket_type"></label>
															</div>
                              </div>
                              <div class="row">
                                                           
                                                            <div class="col-3 form-group">
                                                             <b>Category:</b>
                                                             <br> 
                                                            <label id="category"></label>
															</div>
                                                           
                                                            <div class="col-3">
                                                             <b>Dress Code:</b>
                                                             <br> 
                                                            <label id="dress_code"></label>
                                                            <br>
                                                            <label id="dress_code_color" class="rounded-circle p-2">   </label>
															</div>
                                                            <div class="col-4"> 
                                                                <br>                                          
                                                          

															</div>
                                                            </div>
                                                            <div class="row">
                                                            <div class="col-sm-4">
                                                             <b>Address:</b>
                                                            <br>
                                                            <label id="full_address"></label>
															</div>
                                        <div class="col-sm-6">
                                                <b>Description:</b>
                                              <br>
                                              <label id="description"></label>
                                        </div>
                                        </div>
                                        <br>
                                                            <div class="row">
                                                            <div class="col-3">
                                                             <b>Event Start Date:</b>
                                                             <br>
                                                            <label id="event_start_date"></label>
															</div>
                                                            <div class="col-3">
                                                               
                                                             <b>Event Start Time:</b>   
                                                             <br>                                                      
                                                            <label id="event_start_time"></label>
															</div>
                                                           
                                                            <div class="col-3">
                                                             <b>Event End Date:</b>
                                                            <br>
                                                            <label id="event_end_date"></label>
                                                          

															</div>
                                                            <div class="col-sm-3">
                                                             <b>Event End Time:</b>
                                                            <br>
                                                          
                                                            <label id="event_end_time"></label>

															</div>
                                                            </div>
                                                            <br>
                                                          <div class="row">
                                                            <div class="col-sm-3 form-group discount_percent">
                                                             <b>Discount Percentage:</b>
                                                            <br>
                                                            <label id="discount_percent"></label>
															</div>
                                                            <div class="col-sm-3 form-group min_tickets_discount">
                                                             <b>Minimum Tickets Discount:</b>
                                                            <br>
                                                            <label id="min_tickets_discount"></label>
															</div>
                                                            <div class="col-3 form-group refundable">
                                                             <b>Refundable:</b>
                                                            <br>
                                                            <label id="refundable"></label>
															</div>
                              </div>
                                                            <div class="row">
                                                            <div class="col-3 sales_start_date">
                                                             <b>Sales Start Date:</b>
                                                             <br>
                                                            <label id="sales_start_date"></label>
                                                          

															</div>
                                                            <div class="col-3 sales_start_time">
                                                             <b>Sales Start Time:</b>
                                                             
                                                          <br>
                                                            <label id="sales_start_time"></label>

															</div>
                                                           
                                                            <div class="col-3 sales_end_date">
                                                             <b>Sales End Date:</b>
                                                            <br>
                                                            <label id="sales_end_date"></label>
                                                          

															</div>
                                                            <div class="col-sm-3 sales_end_time">
                                                             <b>Sales End Time:</b>
                                                            <br>
                                                         
                                                            <label id="sales_end_time"></label>

															</div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                            <b>Events Images/Videos:</b>
                                                          
                                                            <div class="col-sm-2 first_image">
                                                            <br>
                                                            <br>

                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="first_image"></img>
															</div>
                                                            <div class="col-sm-2 second_image" >
                                                            <br>
                                                            <br>
																<img src="" style="height: 140px; width: 96%; border:rounded;" id="second_image"></img>
															</div>
                                                             <div class="col-sm-2 third_image">
                                                             <br>
                                                            <br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="third_image"></img>
                                                                
															</div>
                                                           
                                                            <br><br>
                                                           
                                                              
                                                          
                                                            <div class="col-sm-2 first_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="first_video_thumbnail"></img>
															</div>
                                                            <div class="col-sm-2 second_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="second_video_thumbnail"></img>
															</div>
                                                            <div class="col-sm-2 third_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="third_video_thumbnail"></img>
															</div>
                                                           
                                                            <br><br>
                                                         
                                                                
                                                         
                                                            <div class="col-sm-2 first_video">
                                                         
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="first_video" controls></video>
                                                              
															</div>
                                                            <div class="col-sm-2 second_video">
                                                          
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="second_video" controls></video>
                                                              
															</div>
                                                            <div class="col-sm-2 third_video">
                                                            
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="third_video" controls></video>
                                                              
															</div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                            <div class="col-3 form-group total_likes">
                                                             <b>Total Likes:</b>
                                                            <br>
                                                            <label id="total_likes"></label>
                                                           

															</div>
                                                           
                                                            <div class="col-3 form-group onetime">
                                                            
                                                            <b>One Time:</b>
                                                            <br> 
                                                                <label id="onetime"></label>
															</div>   
                                                            <div class="col-sm-3 form-group hyperlink_not_my_event">
                                                             <b>HyperLink Not My Event:</b>
                                                            <br>
                                                            <label id="hyperlink_not_my_event"></label>
                                                           

															</div>
                              </div>
                              <div class="row">
                                                            <div class="col-sm-3 form-group created_at">
                                                             <b>Date Added:</b>
                                                            <br>
                                                            <label id="created_at"></label>
                                                           

															</div>
                                                            <div class="col-3 form-group status">
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
          
   
 function event_post_id(clicked_id){
    $.ajaxSetup({
   headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});
     var a=clicked_id;
 

$.ajax({
        url:"{{ route('show_new_manage_eventposts')}}",
        type:'get',
        data:'id='+a,
        success:function(response){
       
            // console.log(response);
            $('#first_name').html(response.event_posts[0].first_name);
            $('#last_name').html(response.event_posts[0].last_name);
          

          $('#user_name').html(response.event_posts[0].user_name);
          $('#title').html(response.event_posts[0].title);
        if (response.event_posts[0].first_image=="") {
            $('.first_image').hide();
        }else{
          $('#first_image').attr("src", "/public/uploads/event_images/"+response.event_posts[0].first_image);
        }
        if (response.event_posts[0].second_image=="") {
            $('.second_image').hide();
        }
        else{
          $('#second_image').attr("src", "/public/uploads/event_images/"+response.event_posts[0].second_image);
        }
        if (response.event_posts[0].third_image=="") {
            $('.third_image').hide();
        }else{
          $('#third_image').attr("src", "/public/uploads/event_images/"+response.event_posts[0].third_image);
        }
        if (response.event_posts[0].first_video_thumbnail=="") {
            $('.first_video_thumbnail').hide();
        }else{
          $('#first_video_thumbnail').attr("src", "/public/uploads/event_video_thumbnails/"+response.event_posts[0].first_video_thumbnail);
        }
        if (response.event_posts[0].second_video_thumbnail=="") {
            $('.second_video_thumbnail').hide();
        }else{
          $('#second_video_thumbnail').attr("src", "/public/uploads/event_video_thumbnails/"+response.event_posts[0].second_video_thumbnail);
        }
        if (response.event_posts[0].third_video_thumbnail=="") {
            $('.third_video_thumbnail').hide();
        }else{
          $('#third_video_thumbnail').attr("src", "/public/uploads/event_video_thumbnails/"+response.event_posts[0].third_video_thumbnail);
        }
        if (response.event_posts[0].first_video=="") {
            $('.first_video').hide();
        }else{
          $('#first_video').attr("src", "/public/uploads/event_videos/"+response.event_posts[0].first_video);
        }
        if (response.event_posts[0].second_video=="") {
            $('.second_video').hide();
        }else{
          $('#second_video').attr("src", "/public/uploads/event_videos/"+response.event_posts[0].second_video);
        }
        if (response.event_posts[0].third_video=="") {
            $('.third_video').hide();
        }else{
          $('#third_video').attr("src", "/public/uploads/event_videos/"+response.event_posts[0].third_video);
        }


   
          $('#event_type').html(response.event_posts[0].event_type);
  
          $('#event_ticket_type').html(response.event_posts[0].event_ticket_type);
          $('#category').html(response.event_posts[0].category);
          $('#dress_code').html(response.event_posts[0].dress_code);
          // $dress_code_color=response.event_posts[0].dress_code_color;

          $dress_code_color= response.event_posts[0].dress_code_color;
$res = $dress_code_color.replace("0xff", "#");  
          $('#dress_code_color').css('background-color', $res);
         
          $('#full_address').html(response.event_posts[0].full_address);
          $('#event_start_date').html(response.event_posts[0].event_start_date);
          $('#event_start_time').html(response.event_posts[0].event_start_time);
          $('#event_end_date').html(response.event_posts[0].event_end_date);
          $('#event_end_time').html(response.event_posts[0].event_end_time);
          $('#description').html(response.event_posts[0].description);
          if (response.event_posts[0].discount_percent=="") {
            $('.discount_percent').hide();
          }else{
            $('#discount_percent').html(response.event_posts[0].discount_percent);
          }
          if (response.event_posts[0].min_tickets_discount=="") {
            $('.min_tickets_discount').hide();
          }else{
            $('#min_tickets_discount').html(response.event_posts[0].min_tickets_discount);
          }
          if (response.event_posts[0].refundable=="") {
            $('.refundable').hide();
          }else{
            $('#refundable').html(response.event_posts[0].refundable);
          }

           if (response.event_posts[0].sales_start_date=="") {
            $('.sales_start_date').hide();
        }else{
           $('#sales_start_date').html(response.event_posts[0].sales_start_date);
          }
      if (response.event_posts[0].sales_start_time=="") {
        $('.sales_start_time').hide();
      }else{
        $('#sales_start_time').html(response.event_posts[0].sales_start_time);
      }
      if (response.event_posts[0].sales_end_date=="") {
        $('.sales_end_date').hide();
      }else{
        $('#sales_end_date').html(response.event_posts[0].sales_end_date);
      }
      if (response.event_posts[0].sales_end_time=="") {
        $('.sales_end_time').hide();
      }else{
        $('#sales_end_time').html(response.event_posts[0].sales_end_time);
      }
           if (response.event_posts[0].total_likes=="") {
             $('.total_likes').hide();
           }else{
             $('#total_likes').html(response.event_posts[0].total_likes);
           }
           if (response.event_posts[0].hyperlink_not_my_event=="") {
             $('.hyperlink_not_my_event').hide();
           }else{
             $('#hyperlink_not_my_event').html(response.event_posts[0].hyperlink_not_my_event);
           }
           if (response.event_posts[0].created_at=="") {
             $('.created_at').hide();
           }else{
             $('#created_at').html(response.event_posts[0].created_at);
           }
           if (response.event_posts[0].status=="") {
             $('.status').hide();
           }else{
             $('#status').html(response.event_posts[0].status);
           }
           if (response.event_posts[0].onetime=="") {
             $('.onetime').hide();
           }else{
            $('#onetime').html(response.event_posts[0].onetime);
           }
         


        }
    });

   }
   $('#example').DataTable( {
    responsive: true
} );



        </script>
@endsection