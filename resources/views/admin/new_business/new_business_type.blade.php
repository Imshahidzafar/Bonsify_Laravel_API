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
                        <span class="ml-2">Business Type</span>
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
                              
                              <div class="col-12">
<button class="btn btn-primary btn-xl float-right" data-toggle="modal" data-target="#addMoreTraining" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
Add Business Type </a></button>      
</div>
</div>
                            
</div>

                                <!-- <div class="col-6">
                               
<h2>Events Categories</h2>
</div> -->




                                                               
                            
                            <div class="card-body bg-white">
                            

<div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                     <tr>
                            <th>#</th>
                             <th>Action</th>
                             
                           
                            <!-- <th>Event Post ID</th> -->
                            <th>Business Type</th>
                            <th>Business Date Added</th>
                            <th>Business Status</th>

                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                           
                                        @foreach ($business_type as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->id  }}" href="{{url('/edit_new_business_type', $items->id)}}">Edit </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->id }}"  href="{{url('/delete_new_business_type', $items->id)}}">Delete </a></button>                                  
</div>
                                </div>
                            </td>
                          

                           
                            <td>{{ $items->type}}</td>
                            <?php
                         $date= date('d-m-Y', strtotime($items->created_at));

                          ?>
                            <td>{{$date}}</td>
                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span>                      
                                @elseif ($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>                           
                                @else 
                                   <span class="btn btn-warning">InActive</span>
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
											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
                          <h2>Add Business Type</h2>
														<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
														</button>
													</div>
													<div class="modal-body">

                                                    <div class="container">
                                                        <div class="row justify-content-center">
                                                            <div class="col">
                                                            <form action="add_business_type" method="POST">
                                                                @csrf
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <b>Business Type:</b>
                                                                    </div>
                                                                    <div class="col-sm-9">

                                                                    <input type="text" class="form-control rounded" name="business_type" placeholder="Enter Business Type" required>
                                                                </div>
                                                                </div>


                                                                    <div class="form-group mt-4 mt-3 row">

                                                                            <div class="col-sm-3">
                                                                            <b for="sel1">Status:</b>

                                                                            </div>

                                                                            <div class="col-sm-9">



                                                                            <select class="form-control" name='status'>          

                                                            <option value="Active">Active</option>

                                                            <option value="Inactive">Inactive</option>
                                         </select> 

                                                                            </div>

                                                                            </div>

                                                               
                                                                <button type="submit" class="btn btn-primary float-right">ADD</button>
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



      

     <script>
        $('#example').dataTable( {
  "scrollX": true
} );
     </script>
       
@endsection