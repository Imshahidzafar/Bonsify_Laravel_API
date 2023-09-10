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
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Events Tags</a></li> -->
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                         <div class="card">
                            <div class="card-header">
                                @section('titleBar')
                                    <span class="ml-2">Events Tags</span>
                                @endsection
                                <!-- <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-xl float-right" data-toggle="modal" data-target="#addMoreTraining" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
                                            Add Event Tag
                                        </button>      
                                    </div>
                                </div> -->
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 55px;">Action</th>
                                                <th>Tag ID</th>
                                                <th>Tag Name</th>
                                                <!-- <th>Event Post ID</th> -->
                                                <th>Event Title</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($event_tags as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                                <button class="dropdown-item" type="button"><a id="{{ $items->event_tag_id  }}"  href="{{url('/edit_event_tags',$items->event_tag_id)}}" onClick="update_tags(this.id)" >Edit </a></button>                                      
                                                                <button class="dropdown-item" type="button"><a id="{{ $items->event_tag_id }}" href="{{url('/delete_tags',$items->event_tag_id)}}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></button>                                  
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $items->event_tag_id }}</td>

                                                    <?php  $items->tag_name = DB::table('tags')->where('tag_id', $items->tag_id)->get();    ?>
                                                    <td>{{ $items->tag_name[0]->tag_name }}</td>

                                                    <!-- <td>{{ $items->event_post_id }}</td> -->
                                                    
                                                    <?php  $items->title = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->get();    ?> 
                                                    <td>{{ $items->title[0]->title }}</td>

                                                    <td>
                                                        @if ($items->status=='Active')
                                                        <span class="btn btn-success">Active</span>
                                                        @elseif ($items->status=='Deleted')
                                                        <span class="btn btn-danger">Deleted</span>
                                                        @elseif ($items->status=='Canceled')
                                                        <span class="btn btn-warning">Canceled</span>
                                                        @else 
                                                        <span class="btn btn-info">In Active</span>
                                                        @endif
                                                    </td>
                                                </tr>
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

        <!-- MODAL START -->
        <!-- <div class="modal fade" id="addMoreTraining">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h2>Add Event Tag</h2>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <form action="add_event_tag" method="POST">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <b>Tag Name:</b>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control rounded" name="tag_name" placeholder="Enter Tag Name" required>
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
                                                    <option value="Deleted">Deleted</option>
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
        </div> -->
        <!-- MODAL END -->
      
@endsection