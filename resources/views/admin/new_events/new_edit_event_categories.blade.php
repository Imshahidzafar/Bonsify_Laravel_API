@extends('layout.list_master')

@section('content')

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
                        <span class="ml-2">Update Event Tags</span>
                            @endsection
                            </div>
                            <div class="card-body">
                              
                            <div class="row justify-content-around align-items-center  mb-10 pt-5">
    <div class="col">

        
    <form action="{{route('update_category', $category[0]->category_id)}}" method="POST">
                                                                @csrf
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <b>Category:</b>
                                                                    </div>
                                                                    <div class="col-sm-9">

                                                                    <input type="text" class="form-control rounded" name="category" value="{{$category[0]->category}}" placeholder="Enter Category" required>
                                                                </div>
                                                                </div>

                                                                <div class="row mt-5">
                                                                        <div class="col-sm-3">
                                                                        <b for="sel1">Event Type:</b>
                                                                        </div>
                                                                        <div class="col-sm-9">
                                                                        <select class="form-control" name="event_type_id" id="event_type">
                                                                 

                                                                    @foreach ($event_type as $item)

                                                                    <option value="{{$item->event_type_id}}" {{$item->event_type_id==$category[0]->event_type_id ? "selected" : ''}}>{{$item->event_type}}</option>

                                                                    @endforeach 

                                                                    </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mt-4 mt-3 row">

                                                                            <div class="col-sm-3">
                                                                            <b for="sel1">Status:</b>

                                                                            </div>

                                                                            <div class="col-sm-9">



                                                                            <select class="form-control" name='status'>          

                                                            <option value="Active" {{$category[0]->status == "Active" ? "selected" : ""}}>Active</option>

                                                            <option value="Inactive" {{$category[0]->status == "Inactive" ? "selected" : ""}}>Inactive</option>
                                                            <option value="Deleted" {{$category[0]->status == "Deleted" ? "selected" : ""}}>Deleted</option>


                                                            </select> 

                                                                            </div>

                                                                            </div>

                                                               
                                                                <button type="submit" class="btn btn-primary float-right">Update</button>
                                                                </form>

</div>
        </div>
      
@endsection









































