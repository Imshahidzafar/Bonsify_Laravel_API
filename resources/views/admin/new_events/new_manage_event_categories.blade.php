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
                        <span class="ml-2">Events Categories</span>
                            @endsection
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Events Tags</a></li> -->
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                         <div class="card">
                            <div class="card-header">
                                
                            <div class="row">
                              
    <div class="col-5">
    <b for="sel1">Event Type:</b>
    </div>
    <div class="col-7">
    <select class="form-select"  name='event_post_id' name="event_type_id" id="event_type">
<option value="">Select Event Type</option>

 @foreach ($event_type as $item)

  <option value="{{$item->event_type_id}}">{{$item->event_type}}</option>

  @endforeach 

</select>
    </div>
</div>


<div class="row">
    <div class="col">
<button class="btn btn-primary text-white" data-toggle="modal" data-target="#addMoreTraining" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
Add Category </a></button>      
</div>
</div>              
                            
</div>

                            <div class="card-body bg-white">
                            

<div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                     <tr>
                            <th>#</th>
                             <th>Action</th>
                             <th>Category Id</th>
                             <th>Category</th>
                            <!-- <th>Event Post ID</th> -->
                            <th>Event Type</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                        @foreach ($event as $items)
                                        <tr>
                                      
                                        <td>{{ $no++ }}</td>
         <td><a href="{{route('edit_category', $items->category_id)}}" class="btn btn-warning" id='+items.category_id+' type="button">Edit</a></td>
          <td>{{$items->category_id}}</td>
          <td>{{$items->category}}</td>
          <td>{{$items->event_type}}</td>
          <td id="status">
            
         @if($items->status=='Active')
    <button type="button " class="btn btn-primary">Active</button>

@elseif($items->status=='Inactive')
  
    <button type="button" class="btn btn-warning">Inactive</button>

@else
    <button type="button" class="btn btn-danger">Deleted</button>

@endif

</td>
        

         </tr>          
         @endforeach         
                            </tbody>
                                    </table>
                                    <div class="col-xl-12">
								<div class="card">
									<div class="card-body p-0">
										{{-- <a href="javascript:void(0);" class="bg-primary text-white text-center p-4 fs-20 d-block rounded" data-toggle="modal" data-target="#addMoreTraining">+ Add more training</a> --}}
										<!-- Modal -->
										<div class="modal   fade" id="addMoreTraining">
											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
                          <h2>Add Category</h2>
														<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
														</button>
													</div>
													<div class="modal-body">

                                                    <div class="container">
                                                        <div class="row justify-content-center">
                                                            <div class="col">
                                                            <form action="add_category" method="POST">
                                                                @csrf
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <b>Category:</b>
                                                                    </div>
                                                                    <div class="col-9">

                                                                    <input type="text" class="form-control rounded" name="category" placeholder="Enter Category" required>
                                                                </div>
                                                                </div>

                                                                <div class="row mt-5">
                                                                        <div class="col-3">
                                                                        <b for="sel1">Event Type:</b>
                                                                        </div>
                                                                        <div class="col-9">
                                                                        <select class="form-control" name="event_type_id" id="event_type">
                                                                 

                                                                    @foreach ($event_type as $item)

                                                                    <option value="{{$item->event_type_id}}">{{$item->event_type}}</option>

                                                                    @endforeach 

                                                                    </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mt-4 mt-3 row">

                                                                            <div class="col-3">
                                                                            <b for="sel1">Status:</b>

                                                                            </div>

                                                                            <div class="col-9">



                                                                            <select class="form-control" name='status'>          

                                                            <option value="Active">Active</option>

                                                            <option value="Inactive">Inactive</option>
                                                            <option value="Deleted">Deleted</option>


                                                            </select> 

                                                                            </div>

                                                                            </div>

                                                               
                                                                <button type="submit" class="btn btn-primary float-right">Submit</button>
                                                                </form>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <script>
 



    $('#event_type').change(function(){

       
    var event_type =$(this).val();
   
 
    $.ajax({
        url:"{{ route('new_event_categories')}}",
        type:'post',
        data:{
        "_token": "{{ csrf_token() }}",
        "event_type": event_type
        },
        success:function(result){
            $('#tbody').html("");
          
          $.each(result.query, function(key, items){

var status=items.status;

$("#class").addClass("btn btn-primary");
if(items.status=='Active'){
    $status='btn btn-primary';
}
if(items.status=='Inactive'){
    $status='btn btn-warning';
}
if(items.status=='Deleted'){
    $status='btn btn-danger';
}
$a = '<tr>\
         <td>'+(key + 1)+'</td>\
         <td><button class="btn btn-warning" id='+items.category_id+' onClick="urlCall(this.id)" type="button">Edit</button></td>\
          <td>'+items.category_id+'</td>\
          <td>'+items.category+'</td>\
          <td>'+items.event_type+'</td>\
          <td id="status"><button type="button" class="'+$status+'">'+items.status+'</button></td>\
         </tr>';
$('#tbody').append($a);


       
          });
        }
    });
  
    });
    function urlCall(clicked){
        var a=clicked;
        var myURL="https://bonsify.com/edit_category/"+a;
        window.location=myURL;

    }
        </script>   
@endsection