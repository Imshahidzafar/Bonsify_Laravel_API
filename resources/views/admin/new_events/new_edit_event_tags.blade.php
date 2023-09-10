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

        
        <form id="edit_order" action="{{route('update_event_tags',$event_tags[0]->event_tag_id)}}" method="POST">

            @csrf

            <div class="form-group mt-4 row">

<div class="col-sm-3">



<b for="sel1">Tag Name:</b>

</div>

<div class="col-sm-9">

<select class="form-control" name='tag_id' id="sell_platform">
<option value="">Select Tag Name</option>

    @foreach ($tag as $item)

<option value="{{$item->tag_id}}" {{$item->tag_id == $event_tags[0]->tag_id ? "selected" : ''}}>{{$item->tag_name}}</option>

@endforeach

</select>

</div>

</div>










                          
<div class="form-group mt-4 row">

<div class="col-sm-3">



<b for="sel1">Tag Title:</b>

</div>

<div class="col-sm-9">

<select class="form-control" name='event_post_id' id="sell_platform">
<option value="">Select Tag Title</option>

 @foreach ($event_posts as $item)

  <option value="{{$item->event_post_id}}" {{$item->event_post_id == $event_tags[0]->event_post_id ? "selected" : ''}}>{{$item->title}}</option>

  @endforeach 

</select>

</div>

</div>

<div class="form-group mt-4 mt-3 row">

                  <div class="col-sm-3">



                  <b for="sel1">Status:</b>

                  </div>

                <div class="col-sm-9">



                <select class="form-control" name='status' id="sel1">          

<option value="Active" {{$event_tags[0]->status == "Active" ? "selected" : ""}}>Active</option>

<option value="Inactive" {{$event_tags[0]->status == "Inactive" ? "selected" : ""}}>Inactive</option>

<option value="Canceled" {{$event_tags[0]->status == "Canceled" ? "selected" : ""}}>Canceled</option>
<option value="Deleted" {{$event_tags[0]->status == "Deleted" ? "selected" : ""}}>Deleted</option>


</select> 

                </div>

                </div>
                <button type="submit" class="btn btn-primary float-right">Update</button>

</form>

</div>
        </div>
      
@endsection









































