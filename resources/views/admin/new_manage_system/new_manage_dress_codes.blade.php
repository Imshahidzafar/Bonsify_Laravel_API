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
                        <span class="ml-2">Dress Codes</span>
                            @endsection
						
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                    
                </div>
                <!-- row -->


                <div class="row">
                    
                    <div class="col-12">
                        
                                            <div class="card">
                                            <!-- <div class="row">
                              
<div class="col-11">
<button class="btn btn-primary btn-xl float-right" data-toggle="modal" data-target="#addMoreTraining" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
Add Dress Code </a></button>      
</div>
</div> -->
                            <div class="card-header">
                            <div class="row">
                              
                              <div class="col-12">
                              <button class="btn btn-primary btn-xl float-right" data-toggle="modal" data-target="#addMoreTraining" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
                              Add Dress Code </a></button>      
                              </div>
                              </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <br>
                @if(session('success'))
                <div class="alert alert-success">
                   {{ session('success') }}
                </div>
                @endif
                                    <table id="example" class="table dt-responsive nowrap display min-w850 bg-white">
                                        <thead>
                                           <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>ID</th>
                             <th>Dress Code</th>
                             <th>Dress Code Color</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                         @foreach ($dresscodes as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->dress_code_id  }}" href="{{route('new_edit_dresscodes', $items->dress_code_id )}}">Edit </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->dress_code_id  }}" href="{{route('new_delete_dresscode', $items->dress_code_id )}}">Delete </a></button>                                      
                                       
</div>
                                </div>
                            </td>
                            <td>{{ $items->dress_code_id }}</td>
                            <td>{{ $items->dress_code }}</td>
                            <?php
                      $replaced=str_replace("0xff","#","$items->dress_code_color");
?>
                            <td >
                        <label style="background-color: {{ $replaced }} !important; padding: 13px " class="rounded-circle"></label>
                        </td>

                            
                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span>
 
                                @else 
                                   <span class="btn btn-warning">In Active</span>
                                @endif
                            </td>
                        
                            @endforeach
                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    				</div>
            </div>
        </div>








        <!-- // dress code add modal -->
        <div class="col-xl-12">
								<div class="card">
									<div class="card-body p-0">
										{{-- <a href="javascript:void(0);" class="bg-primary text-white text-center p-4 fs-20 d-block rounded" data-toggle="modal" data-target="#addMoreTraining">+ Add more training</a> --}}
										<!-- Modal -->
										<div class="modal   fade" id="addMoreTraining">
											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
                          <h2>Add Dress Code</h2>
														<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
														</button>
													</div>
													<div class="modal-body">

                                                    <div class="container">
                                                        <div class="row justify-content-center">
                                                            <div class="col">
                                                            <form action="new_add_dresscode" method="POST">
                                                                @csrf
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <b>Dress Code:</b>
                                                                    </div>
                                                                    <div class="col-sm-9">

                                                                    <input type="text" class="form-control rounded" name="dress_code" placeholder="Enter Dress Code" required>
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

                                                                            <div class="row mt-4">
                                                                <div class="col-sm-3">
                                                                    <b>Dress Code Color:</b>
                                                                    </div>
                                                                    <div class="col-sm-9">

                                                                    <input type="color" class="" name="dress_code_color" placeholder="Enter Event Type" required>
                                                                </div>
                                                                </div>
                                                               
                                                                <button type="submit" class="btn btn-primary float-right">ADD</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
													</div>


@endsection