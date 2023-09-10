@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
<!-- // verified modal starts /// /// /// ///  -->
<div class="modal modal_close fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Status Verification Confirmation</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div class="modal-body">
       
      <p id="para"></p>
        
      </div>
      <div class="modal-footer">
        <input type="hidden" id="business_id">
        <input type="hidden" id="business_status">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" id="{{ $business[0]->business_id}}" onClick="verified(this.id)" class="btn btn-primary">Yes</button>
      </div>
     
    </div>
  </div>
</div>
<!-- // verified modal ends /// /// /// ///  -->



        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles  mb-n5">
					<ol class="breadcrumb">
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Mange Event Post</a></li> -->
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header" style="margin-bottom: 120px">

                            <div class="welcome-text">
                        @section('titleBar')
                        <span class="ml-2">Manage Business!</span>
                            @endsection
                            {{-- <p class="mb-0">Validation</p> --}}
                        </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w850 ">
                                        <thead>
                                           <tr>
                            <th style="width: 30px;">#</th>
                             <th style="width: 30px;">Action</th>
                             <!-- <th>Event ID</th> -->
                             <th>Business Title</th>
                             <th>UsersName</th>
                             <th>Business Type</th>
                             <th>Business Added Date</th>
                            <th>Status</th>
                            <th>Active Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                             @foreach ($business as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                     <!-- <a href="javascript:void(0);"data-toggle="modal" data-target="#addMoreTraining"></a> -->
                                        <button class="dropdown-item" type="button" value="{{$items->business_id}}" ><a  id="{{ $items->business_id}}" onClick="business_type(this.id)" <a href="javascript:void(0);" data-toggle="modal" data-target="#addMoreTraining">View Detail </a></a></button>
                                        <button class="dropdown-item" type="button"><a id="{{$items->business_id}}" onClick="acive(this.id)"<a href="javascript:void(0);" >active </a></a></button>
                                        <button class="dropdown-item" type="button"><a id="{{$items->business_id}}" <a href="javascript:void(0);" onClick="inacive(this.id)">inactive </a></a></button>

</div>
                                </div>
                            </td>
                          
                            <td>{{ $items->title }}</td>
                            <td>{{ $items->user_name }}</td>

                         
                            <td>{{ $items->business_type}}</td>

                          <?php
                         $date= date('d-m-Y', strtotime($items->created_at));

                          ?>

                <td>{{ $date}}</td>

              


                          


                            <td>
                              <button type="button" onClick="setModalInput({{ $items->business_id }},'{{$items->status}}')" class="btn btn-small"  data-toggle="modal" data-target="#exampleModal"  >

                              @if ($items->status=='Active')
                                 <span class="btn btn-secondary">Active</span>

                                @elseif ($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>
                                @elseif($items->status=='RequestedVerification')
                                   <span class="btn btn-warning">RequestedVerification</span>
                                   @elseif ($items->status=='CanceledVerification')
                                   <span class="btn btn-info">CanceledVerification</span>
                                   @else
                                 <span class="btn btn-success">Verified</span>

                                  
                                @endif
                              </button>
                              
                            </td>


                            <td>
                            

                              @if ($items->active_status=='Active')
                                 <span class="btn btn-primary">Active</span>

                                @else 
                                <span class="btn btn-secondary">Inactive</span>        
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
                          <h2>Business Detail</h2>
														<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
														</button>
													</div>
													<div class="modal-body">
												
                                                        <br>
                            <div class="row">
															<div class="col-4 first_name">
																<b>First Name: </b>
                                  <br> 
																<!-- $event_posts.event_post_id -->
                                 <label id="first_name"></label>
															</div>
                                <div class="col-4">
                                                             
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
                              <br>

                              <div class="row">
															<div class="col-sm-4 form-group">
                                                          
																<b>Business Title:</b>
                                                                <br> 
                                                                <label  id="title"></label>
															</div>
                              <div class="col-sm-4 form-group">
                                                             <b>Business Type:</b>
                                                             <br> 
                                                                <label id="business_type"></label>
															</div>
                              <div class="col-sm-2 ">
                              <b>Business Logo:</b>
                                                            <br>

                                                                <img src="" style="height: 100px; width: 96%; border:rounded;" id="business_logo"></img>
															</div>
                           
                                  
                              
                              </div>
                              <div class="row">
                                                            <b>Business Images/Videos:</b>
                                                          
                                                            <div class="col-sm-2 mt-3 first_image">
                                                           
                                                            

                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="first_image"></img>
															</div>
                                                            <div class="col-sm-2 mt-3 second_image" >
                                                         
                                                           
																<img src="" style="height: 140px; width: 96%; border:rounded;" id="second_image"></img>
															</div>
                                                             <div class="col-sm-2 mt-3 third_image">
                                                            
                                                      
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="third_image"></img>
                                                                
															</div>
                              <div class="col-sm-2 mt-3 fourth_image">
                                                            
                                                       
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="fourth_image"></img>
                                                                
															</div>
                              <div class="col-sm-2 mt-3 fifth_image">
                                                           
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="fifth_image"></img>
                                                                
															</div>      
                              <div class="col-sm-2 mt-3 sixth_image">
                                                            
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="sixth_image"></img>
                                                                
															</div>              
                                                           
                                                           
                                                              
                                                          
                                                            <div class="col-sm-2 mt-3 first_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="first_video_thumbnail"></img>
															</div>
                                                            <div class="col-sm-2 mt-3 second_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="second_video_thumbnail"></img>
															</div>
                                                            <div class="col-sm-2 mt-3 third_video_thumbnail">
                                                            <br><br>
                                                                <img src="" style="height: 140px; width: 96%; border:rounded;" id="third_video_thumbnail"></img>
															</div>
                                                           
                                                         
                                                         
                                                                
                                                         
                                                            <div class="col-sm-2 mt-3 first_video">
                                                         
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="first_video" controls></video>
                                                              
															</div>
                                                            <div class="col-sm-2 mt-3 second_video">
                                                          
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="second_video" controls></video>
                                                              
															</div>
                                                            <div class="col-sm-2 mt-3 third_video">
                                                            
                                                            
                                                             <video src="" style="height: 200px; width: 96%; border:rounded;" id="third_video" controls></video>
                                                              
															</div>
                                                            </div>
                                                            <br>
                                                            <br>
                            
                              <div class="row">      
                                                            <div class="col-sm-6 description">
                                                             <b>Description:</b>
                                                             <br> 
                                                            <label id="description"></label>
                                                           
															</div>
                             
                                                            <div class="col-sm-5 address"> 
                                                              
                                                                <b>Address</b>
                                                                <label id="address"></label>                                        
                                                          

															</div>
                                                            </div>
                                                            <br>
                                                            <div class="row ">
                                                            <div class="col-4 city">
                                                             <b>city:</b>
                                                            <br>
                                                            <label id="city"></label>
															</div>
                                        <div class="col-4 state">
                                                <b>State:</b>
                                              <br>
                                              <label id="state"></label>
                                        </div>
                                        <div class="col-3 form-group verified">
                                                             <b>Verified:</b>
                                                            <br>
                                                            <label id="verified"></label>
															</div>
                                        </div>
                                        <br>
                                                            <div class="row">
                                                            <div class="col-4 zip">
                                                             <b>Zip:</b>
                                                             <br>
                                                            <label id="zip"></label>
															</div>
                              <div class="col-4 form-group hyperlink">
                                   <b>Hyperlink:</b>
                                   <br> 
                                  <label id="hyperlink"></label>
															</div>
                                                           
                              <div class="col-3 form-group total_likes">
                                                             <b>Total Likes:</b>
                                                            <br>
                                                            <label id="total_likes"></label>
															</div>
                                                            
                                                            </div>
                                                            <br>                                          
                              <div class="row">
                              <div class="col-sm-4 form-group discount">
                                                             <b>Business Discount:</b>
                                                             <br> 
                                                            <label id="discount"></label>
															</div>
                                                            <div class="col-sm-4 form-group created_at">
                                                             <b>Business Date Added:</b>
                                                            <br>
                                                            <label id="created_at"></label>
                                                           

															</div>
                                                            <div class="col-sm-3 form-group status">
                                                             <b>Business Status:</b>
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
        <script>
          
   
 function business_type(clicked_id){
    $.ajaxSetup({
   headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});
     var a=clicked_id;
     
     

$.ajax({
        url:"{{ route('show_new_manage_business')}}",
        type:'get',
        data:'id='+a,
        success:function(response){
       
         console.log(response.business[0].address);

         $('#first_name').html(response.business[0].first_name);
            $('#last_name').html(response.business[0].last_name);
          $('#user_name').html(response.business[0].user_name);
          $('#title').html(response.business[0].title);
          $('#business_type').html(response.business[0].business_type);
          if (response.business[0].hyperlink=="") {
            $('.hyperlink').hide();
          }else{
            $('#hyperlink').html(response.business[0].hyperlink);
          }
          if (response.business[0].discount=="") {
            $('.discount').hide();
          }else{
            $('#discount').html(response.business[0].discount);
          }
          if (response.business[0].description=="") {
            $('.description').hide();
          }else{
            $('#description').html(response.business[0].description);
          }
          if (response.business[0].address=="") {
            $('.address').hide();
          }else{
            $('#address').html(response.business[0].address);
          }
          if (response.business[0].city=="") {
            $('.city').hide();
          }else{
            $('#city').html(response.business[0].city);
          }
          if (response.business[0].state=="") {
            $('.state').hide();
          }else{
            $('#state').html(response.business[0].state);
          }
          if (response.business[0].zip=="") {
            $('.zip').hide();
          }else{
            $('#zip').html(response.business[0].zip);
          }
          if (response.business[0].business_lat=="") {
            $('.business_lat').hide();
          }else{
            $('#business_lat').html(response.business[0].business_lat);
          }

          if (response.business[0].business_long=="") {
            $('.business_long').hide();
          }else{
            $('#business_long').html(response.business[0].business_long);
          }
         
          if (response.business[0].verified=="") {
            $('.verified').hide();
          }else{
            $('#verified').html(response.business[0].verified);
          }
          if (response.business[0].total_likes=="") {
            $('.total_likes').hide();
          }else{
            $('#total_likes').html(response.business[0].total_likes);
          }



          if (response.business[0].first_image=="") {
            $('.first_image').hide();
        }else{
          $('#first_image').attr("src", "/public/uploads/business_images/"+response.business[0].first_image);
        }
        if (response.business[0].second_image=="") {
            $('.second_image').hide();
        }
        else{
          $('#second_image').attr("src", "/public/uploads/business_images/"+response.business[0].second_image);
        }
        if (response.business[0].third_image=="") {
            $('.third_image').hide();
        }else{
          $('#third_image').attr("src", "/public/uploads/business_images/"+response.business[0].third_image);
        }
        if (response.business[0].fourth_image=="") {
            $('.fourth_image').hide();
        }else{
          $('#fourth_image').attr("src", "/public/uploads/business_images/"+response.business[0].fourth_image);
        }
        if (response.business[0].fifth_image=="") {
            $('.fifth_image').hide();
        }else{
          $('#fifth_image').attr("src", "/public/uploads/business_images/"+response.business[0].fifth_image);
        }
        if (response.business[0].sixth_image=="") {
            $('.sixth_image').hide();
        }else{
          $('#sixth_image').attr("src", "/public/uploads/business_images/"+response.business[0].sixth_image);
        }
        if (response.business[0].first_video_thumbnail=="") {
            $('.first_video_thumbnail').hide();
        }else{
          $('#first_video_thumbnail').attr("src", "/public/uploads/business_video_thumbnails/"+response.business[0].first_video_thumbnail);
        }
        if (response.business[0].second_video_thumbnail=="") {
            $('.second_video_thumbnail').hide();
        }else{
          $('#second_video_thumbnail').attr("src", "/public/uploads/business_video_thumbnails/"+response.business[0].second_video_thumbnail);
        }
        if (response.business[0].third_video_thumbnail=="") {
            $('.third_video_thumbnail').hide();
        }else{
          $('#third_video_thumbnail').attr("src", "/public/uploads/business_video_thumbnails/"+response.business[0].third_video_thumbnail);
        }
       
        if (response.business[0].first_video=="") {
            $('.first_video').hide();
        }else{
          $('#first_video').attr("src", "/public/uploads/business_videos/"+response.business[0].first_video);
        }
        if (response.business[0].second_video=="") {
            $('.second_video').hide();
        }else{
          $('#second_video').attr("src", "/public/uploads/business_videos/"+response.business[0].second_video);
        }
        if (response.business[0].third_video=="") {
            $('.third_video').hide();
        }else{
          $('#third_video').attr("src", "/public/uploads/business_videos/"+response.business[0].third_video);
        }
        if (response.business[0].business_logo=="") {
            $('.business_logo').hide();
        }else{
          $('#business_logo').attr("src", "/public/uploads/business_logos/"+response.business[0].business_logo);
        }
        if (response.business[0].created_at=="") {
             $('.created_at').hide();
           }else{
             $('#created_at').html(response.business[0].created_at);
           }
           if (response.business[0].status=="") {
             $('.status').hide();
           }else{
             $('#status').html(response.business[0].status);
           }



          

        }
    });

   }

   function setModalInput(clicked_id, clicked_status){
    var id=clicked_id;
    var status=clicked_status;
    // alert(status);

    $('#business_id').val(id);
    $('#business_status').val(status);
    if (status=="RequestedVerification") {
 
      $('#para').text("Are You Sure He is A Verified User!");
    }
      else if(status=="Verified"){
       
        $('#para').text("Are You Sure He Is An Unverified User!");
      }

   }
  
   function verified(clicked_id){
    var business_id= $('#business_id').val();
    var status =$('#business_status').val();
    if (status=="RequestedVerification") {
      var data ='Verified';
      $('#para').text("Are You Sure He is A Verified User!");
    }
      else if(status=="Verified"){
        var data='RequestedVerification';
        $('#para').text("Are You Sure He Is An Unverified User!");
      }
    

   
    // alert(a);{{ route('show_new_manage_business')}}
    $.ajax({
      url: "{{url('verifyModalAjax')}}",
      type: 'post',
      data:{id:business_id, data:data },
      success:function(response){
       
       $('#exampleModal').modal('hide');
       location.reload(true);
      }

     

    });
   
   }
   function acive(clicked_id)
   {
    var business_id=clicked_id;
    $.ajax({
      url: "{{url('activeAjax')}}",
      type: 'post',
      data:{id:business_id},
      success:function(response){
       console.log(response);
      //  $('#exampleModal').modal('hide');
            location.reload(true);

      }

     

    });
   }

   function inacive(clicked_id)
   {
    var business_id=clicked_id;
    $.ajax({
      url: "{{url('inactiveAjax')}}",
      type: 'post',
      data:{id:business_id},
      success:function(response){
       console.log(response);
      //  $('#exampleModal').modal('hide');
      location.reload(true);
      }

     

    });
   }
   

        </script>
@endsection