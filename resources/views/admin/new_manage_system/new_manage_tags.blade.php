@extends('layout.list_master')

@section('content')

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
                    @section('titleBar')
                        <span class="ml-2">Tags</span>
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
                                    <table id="example" class="display min-w850">

                                        <thead>
                                           <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>ID</th>
                             <th>Tag</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($tags as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->tag_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->tag_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></button>                                      
                                       
</div>
                                </div>
                            </td>
                            <td>{{ $items->tag_id }}</td>
                            <td>{{ $items->tag_name }}</td>
                            
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
                                </div>
                            </div>
                        </div>
                    </div>
                    				</div>
            </div>
        </div>
@endsection