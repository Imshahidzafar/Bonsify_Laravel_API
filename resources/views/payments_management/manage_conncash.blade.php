@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style type="text/css">
    .upload-img {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background-position: center center;
        background-size: cover;
    }
</style>

<?php  $categoriesModel = DB::table('categories')->where('status', 'Active')->get();    ?>
<?php  $usersModel = DB::table('users')->where('status', 'Active')->get();    ?>



<div class="panel">
    <div class="panel-body">

        <h3 class="title-hero">Conncash Management <a data-toggle="modal" data-target="#Modal_add" class="btn btn-sm btn-primary" title=""
            style="float: right;margin-bottom: 10px;">Add Conncash Details</a></h3>
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <br>
                @if(session('success'))
                <div class="alert alert-success">
                   {{ session('success') }}
                </div>
                @endif

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
                id="myTable">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>Conncash ID</th>
                            <th>User ID</th>
                            <th>Conncash</th>
                            <th>Conncash Source</th>
                            <th>Total Conncash</th>

                        </tr>
                    </thead>
                    <tbody>                        
                        @foreach ($allconncash as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div>
                                    <button id="{{ $items->conncash_id  }}" onclick="editItem('{{json_encode($items)}}')" type="button" class="btn btn-success">Edit</button>
                              
                                </div>
                            </td>
                            <td>{{ $items->conncash_id }}</td>
                            <td>{{ $items->users_id }}</td>
                            <td>{{ $items->conncash }}</td>
                            <td>{{ $items->conncash_source }}</td>
                            <?php  $items->userTotalConncash = DB::table('users')->where('users_id', $items->users_id)->where('status', 'Active')->get();    ?>

                            <td>{{ $items->userTotalConncash[0]->total_conncash }}</td>

                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

           {{--  ADD MODAL --}}
           <div class="modal fade" id="Modal_add" tabindex="-1" role="dialog" aria-labelledby="Modal_add" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_conncash')}}">
                {{ csrf_field() }}
                <input type="hidden" id="conncash_id" name="conncash_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">User ID <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="users_id" name="users_id" required>
                            @foreach($usersModel as $user)
                            @if ($user->email != "")
                            <option value="{{ $user->users_id }}">{{ $user->users_id.' -- '.$user->email}}</option>
                            @else
                            <option value="{{ $user->users_id }}">{{ $user->users_id.' -- '.$user->first_name. ' '.$user->last_name}}</option>
                            @endif
                          @endforeach                 
                        </select>
                    </div>
                </div>  
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Conncash<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="conncash"  class="input-mask form-control" name="conncash" required>
                    </div>
                </div> 

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Conncash Source<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="conncash_source" name="conncash_source" required>
                                <option value="SelfCredited"> Self Credited</option>
                                <option value="AdminGifted"> Admin Gifted</option> 
                            </select>
                        </div>
                    </div>                       
            
                    <div class="form-group" >
                        <div  style="float: right;">
                           <div class="col-sm-12">
                               <input type="button"  class="btn btn-danger"  data-dismiss="modal" aria-label="Close" value="Cancel"></button>
                               <input type="submit" class="btn btn-primary"  style="float: right; margin-left:8px;"></button>
                            </div>
                        </div>
                       </div>
                        </form>
                </div>
                </div>
            </div>
            </div>
        
        
                      {{--  EDIT MODAL --}}
        <div class="modal fade" id="Modal_edit" tabindex="-1" role="dialog" aria-labelledby="Modal_edit" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 100px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/conncash_update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="conncash_id" name="conncash_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">User ID <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="users_id"  class="input-mask form-control" name="users_id" required readonly>
                    </div>
                </div>  

                <div class="form-group">
                    <label class="col-sm-3 control-label">Conncash <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="conncash"  class="input-mask form-control" name="conncash" required readonly>
                    </div>
                </div> 

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Conncash Source<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="conncash_source" name="conncash_source" required>
                                <option value="SelfCredited"> Self Credited</option>
                                <option value="AdminGifted"> Admin Gifted</option> 
                            </select>
                        </div>
                    </div>         
                    

                    <div class="form-group" >
                        <div  style="float: right;">
                           <div class="col-sm-12">
                               <input type="button"  class="btn btn-danger"  data-dismiss="modal" aria-label="Close" value="Cancel"></button>
                               <input type="submit" class="btn btn-primary"  style="float: right;  margin-left:8px;"></button>
                            </div>
                        </div>
                       </div>
        
                        </form>
                </div>
                </div>
            </div>
            </div>       

            
                      {{--  DELETE MODAL --}}
     {{--    <div class="modal fade" id="Modal_delete" tabindex="-1" role="dialog" aria-labelledby="Modal_delete" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/delete_card')}}">
                {{ csrf_field() }}
                <input type="hidden" id="card_id" name="card_id">
             
                    <div class="form-group" style="padding-left: 20px;">
                        <label class="control-label" style="font-size: 14px;">Are you sure to delete this item ?</label>
                    </div>  
                                                                     
                         <div class="form-group" >
                             <div  style="float: right;">
                                <div class="col-sm-12">
                                    <input type="button"  class="btn btn-primary"  data-dismiss="modal" aria-label="Close" value="No"></button>
                                    <input type="submit" class="btn btn-danger" style="margin-left: 5px;" value="Yes"></button>
                                </div>
                             </div>
                            </div>
                        </form>
                </div>
                </div>
            </div>
            </div> --}}
        
        
            <script>
                function editItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='conncash_id']").val(itemJson.conncash_id);
                    $("#itemForm  #users_id").val(itemJson.users_id);
                    $("#itemForm input[name='conncash']").val(itemJson.conncash);
                    $("#itemForm  #conncash_source").val(itemJson.conncash_source);

                    $("#Modal_edit").modal('show');
                }
                
                
          /*       function deleteItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='conncash_id']").val(itemJson.conncash_id);    
                    $("#Modal_delete").modal('show');
              
                } */

            </script>
  
<script type="text/javascript">
</script>
@endsection