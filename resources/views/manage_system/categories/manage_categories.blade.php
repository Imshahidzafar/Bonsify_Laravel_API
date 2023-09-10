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
<div class="panel">
    <div class="panel-body">

        <h3 class="title-hero">Categories Management <a data-toggle="modal" data-target="#Modal_add" class="btn btn-sm btn-primary" title=""
            style="float: right;margin-bottom: 10px;">Add Category</a></h3>
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
                             <th>ID</th>
                             <th>Category</th>
                            <th>Category Type</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a id="{{ $items->category_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></li>                                      
                                        <li><a id="{{ $items->category_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></li>                                      
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $items->category_id }}</td>
                            <td>{{ $items->category }}</td>
                            <td>{{ $items->category_type }}</td>
                            
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

           {{--  ADD MODAL --}}
           <div class="modal fade" id="Modal_add" tabindex="-1" role="dialog" aria-labelledby="Modal_add" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_category')}}">
                {{ csrf_field() }}
                <input type="hidden" id="category_id" name="category_id">
             
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Category <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="category" placeholder="Please enter category" class="input-mask form-control" name="category" required >
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Category Type <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="category_type" name="category_type" required>
                                <option value="CorporateEvents"> Corporate Events</option>
                                <option value="SocialEvents"> Social Events</option> 
                                <option value="CharityFundraising">Charity Fundraising</option>                 
                            </select>
                        </div>
                    </div>                       
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="status" name="status" required>
                                <option value="Active"> Active</option>
                                <option value="Inactive"> Inactive</option>         
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
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/category_update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="category_id" name="category_id">
             
                    <div class="form-group">
                        <label class="col-sm-3 control-label">category <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="category"  class="input-mask form-control" name="category" required >
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Category Type <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="category_type" name="category_type" required>
                                <option value="CorporateEvents"> Corporate Events</option>
                                <option value="SocialEvents"> Social Events</option> 
                                <option value="CharityFundraising">Charity Fundraising</option>                 
                            </select>
                        </div>
                    </div>                       
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="status" name="status" required>
                                <option value="Active"> Active</option>
                                <option value="Inactive"> Inactive</option>         
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
        <div class="modal fade" id="Modal_delete" tabindex="-1" role="dialog" aria-labelledby="Modal_delete" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/delete_user')}}">
                {{ csrf_field() }}
                <input type="hidden" id="users_id" name="users_id">
             
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
            </div>
        
        
            <script>
                function editItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='category_id']").val(itemJson.category_id);
                    $("#itemForm input[name='category']").val(itemJson.category);
                    $("#itemForm #category_type").val(itemJson.category_type);
                    $("#itemForm #status").val(itemJson.status);
            
                    $("#Modal_edit").modal('show');
                }
                
                
                function deleteItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='users_id']").val(itemJson.users_id);    
                    $("#Modal_delete").modal('show');
              
                }
            </script>
  
<script type="text/javascript">
</script>
@endsection