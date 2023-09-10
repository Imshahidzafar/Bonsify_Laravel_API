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

<div class="panel">
    <div class="panel-body">

        <h3 class="title-hero">Cards Management {{-- <a data-toggle="modal" data-target="#Modal_add" class="btn btn-sm btn-primary" title=""
            style="float: right;margin-bottom: 10px;">Add Card</a> --}}</h3>
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
                             <th>Card ID</th>
                            <th>User ID</th>
                            <th>Card Holder Name</th>
                            <th>Card Number</th>
                            <th>CVV</th>
                            <th>Expiry Months</th>
                            <th>Expiry Years</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($cards as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a id="{{ $items->card_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></li>                                      
                                        <li><a id="{{ $items->card_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></li>                                      
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $items->card_id }}</td>
                            <td>{{ $items->users_id }}</td>
                            <td>{{ $items->card_holder_name }}</td>
                            <td>{{ $items->card_number }}</td>
                            <td>{{ $items->cvv }}</td>
                            <td>{{ $items->expiry_months }}</td>
                            <td>{{ $items->expiry_years }}</td>


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
   {{--         <div class="modal fade" id="Modal_add" tabindex="-1" role="dialog" aria-labelledby="Modal_add" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_card')}}">
                {{ csrf_field() }}
                <input type="hidden" id="user_category_id" name="user_category_id">
             
                    <div class="form-group">
                        <label class="col-sm-3 control-label">UserCategory <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="user_category" placeholder="Please enter User category" class="input-mask form-control" name="user_category" required >
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
            </div> --}}
        
        
                      {{--  EDIT MODAL --}}
        <div class="modal fade" id="Modal_edit" tabindex="-1" role="dialog" aria-labelledby="Modal_edit" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 100px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/card_update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="card_id" name="card_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">User Id <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="users_id"  class="input-mask form-control" name="users_id" required readonly>
                    </div>
                </div>   

                <div class="form-group" style="padding-left: 10px">
                    <label class="col-sm-3 control-label">Card Holder Name <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="card_holder_name"  class="input-mask form-control" name="card_holder_name" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label class="col-sm-3 control-label"> Card Number <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" id="card_number"  class="input-mask form-control" name="card_number" minlength="16" maxlength="16" required>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-2 control-label"> CVV <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" id="cvv"  class="input-mask form-control" name="cvv" minlength="3" maxlength="3" required>
                        </div>
                    </div> 
                </div> 

                
                <div class="form-group">
                    <div>
                        <label class="col-sm-3 control-label"> Expiry months <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" id="expiry_months"  class="input-mask form-control" name="expiry_months" minlength="2" maxlength="2" required>
                        </div>
                    </div>

                    <div>
                        <label class="col-sm-3 control-label"> Expiry Years <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                            <input type="text" id="expiry_years"  class="input-mask form-control" name="expiry_years" minlength="2" maxlength="2" required>
                        </div>
                    </div> 
                </div>          

                                     
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status<span class="text-danger">*</span></label>
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
            </div>
        
        
            <script>
                function editItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='card_id']").val(itemJson.card_id);
                    $("#itemForm input[name='users_id']").val(itemJson.users_id);
                    $("#itemForm input[name='card_holder_name']").val(itemJson.card_holder_name);
                    $("#itemForm input[name='card_number']").val(itemJson.card_number);
                    $("#itemForm input[name='cvv']").val(itemJson.cvv);
                    $("#itemForm input[name='expiry_months']").val(itemJson.expiry_months);
                    $("#itemForm input[name='expiry_years']").val(itemJson.expiry_years);

                    $("#itemForm #status").val(itemJson.status);
            
                    $("#Modal_edit").modal('show');
                }
                
                
                function deleteItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='card_id']").val(itemJson.card_id);    
                    $("#Modal_delete").modal('show');
              
                }
            </script>
  
<script type="text/javascript">
</script>
@endsection