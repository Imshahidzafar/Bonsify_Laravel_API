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
                        <span class="ml-2">Update Dress Code</span>
                            @endsection
                            </div>
                            <div class="card-body">
                              
                            <div class="row justify-content-around align-items-center  mb-10 pt-5">
    <div class="col">

        
    <form action="{{route('new_update_dresscode', $dress_code[0]->dress_code_id)}}" method="POST">
                                                                @csrf
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <b>Dress Code:</b>
                                                                    </div>
                                                                    <div class="col-sm-9">

                                                                    <input type="text" class="form-control rounded" name="dress_code" value="{{$dress_code[0]->dress_code}}" placeholder="Enter Dress Code" required>
                                                                </div>
                                                                </div>

                                                               

                                                                    <div class="form-group mt-4 mt-3 row">

                                                                            <div class="col-sm-3">
                                                                            <b for="sel1">Status:</b>

                                                                            </div>

                                                                            <div class="col-sm-9">



                                                                            <select class="form-control" name='status'>          

                                                            <option value="Active" {{$dress_code[0]->status == "Active" ? "selected" : ""}}>Active</option>

                                                            <option value="Inactive" {{$dress_code[0]->status == "Inactive" ? "selected" : ""}}>Inactive</option>
                                                          


                                                            </select> 

                                                                            </div>

                                                                            </div>

                                                                            <div class="row mt-2">
                                                                        <div class="col-sm-3">
                                                                        <b for="sel1">Dress Code Color:</b>
                                                                        </div>
                                                                        <div class="col-sm-9">
                                                                  <?php
                                                                  $a=$dress_code[0]->dress_code_color;
                                                                   $replaced=str_replace("0xff","#",$a);
                                                                  
                                                                  ?>

<input type="color" class="" name="dress_code_color" value="{{$replaced}}" placeholder="Enter Dress Code Color" required>
</div>
                                                                    </div>

                                                               
                                                                <button type="submit" class="btn btn-primary float-right">Update</button>
                                                                </form>

</div>
        </div>
      
@endsection









































