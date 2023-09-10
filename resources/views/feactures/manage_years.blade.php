@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<div class="panel">
    <div class="panel-body">
        <div style="display: flex;justify-content: space-between;">
            <div>
             <h3 class="title-hero">Manage Car Years</h3>
            </div>
           
            <div style="padding: 4px 5px;">
             <span class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Year </span>
            </div>   
        </div>
       
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive-md"
                    id="myTable">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                            <th style="width: 55px;">Action</th>
                            
                            <th>Year</th>                  
                            <th>Date Added</th>
                            <th>Date Modified</th>
                            <th>Status</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($car_years as $key => $items)
                        <tr class="odd gradeX">
                            <input type="hidden" id="real_year_name_{{ @$items->car_years_id }}" value="{{ @$items->year }}">
                            <input type="hidden" id="real_year_status_{{ @$items->car_years_id }}" value="{{ @$items->status }}">
                            <td>{{ @$key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" class="" data-target="#exampleModal_view_{{ @$items->car_years_id  }}" >View Year</a></li>
                                        <li><a data-toggle="modal" id="year_car_{{ @$items->car_years_id  }}" class="make_car_year" data-target="#exampleModal_edit" >Edit year</a></li>                                      
                                        <li><a href="{{ url('/backoffice/delete_year/' . $items->car_years_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>                 
                            <td>{{ @$items->year }}</td>             
                           
                            <td>{{ @$items->date_added}}</td>
                            <td>{{ @$items->date_modified}}</td>
                        <!--  <td>{{ @$items->date_modified}}</td> -->
                        <td>
                                @if ($items->status=='Active')
                                <span class="btn btn-success">Active</span> 
                                @elseif($items->status=='Inactive')
                                <span class="btn btn-primary">In Active</span>
                                @elseif($items->status=='Pending')
                                <span class="btn btn-primary">Pending</span>
                                @elseif($items->status=='Inreview')
                                <span class="btn btn-info">In-Review</span>
                                @elseif($items->status=='Expired')
                                <span class="btn btn-warning">Expired</span>
                                @elseif($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>
                                
                                @endif
                            </td>
                        
                            @endforeach


                            
                    </tbody>
                </table>
            </div>
                </div>
            </div>
        </div>
        @foreach ($car_years as $key => $itemsss)
        <div class="modal fade" id="exampleModal_view_{{ @$itemsss->car_years_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelss" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="margin-top: 188px;">
                    
                    <div class="modal-body" style="display: flex;
                    flex-direction: column;
                    grid-gap: 29px;">
                            
                                <div class="row">
                                    <label class="col-sm-6 control-label">Year :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->year }}
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-sm-6 control-label">Status :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->status }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Date Added :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->date_added}}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Date Modified :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->date_modified}}
                                    </div>
                                </div>
                                
                        
                    </div>
                    </div>
                </div>
         </div>
         @endforeach

                            <div class="modal fade" id="exampleModal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="margin-top: 188px;">
                                    
                                    <div class="modal-body">
                                    <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/edit_year/')}}">
                                                {{ csrf_field() }}                                               
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Year <span class="text-danger">*</span></label>
                                                    <div class="col-sm-6">
                                                        <input type="hidden" name ="id"  id="year_id" required >
                                                        <input type="number" id="year_name"  class="input-mask form-control" placeholder = 'Enter year name' name="year" required >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Status <span class="text-danger">*</span></label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" id="year_status" name="status" required>
                                                            <option value=""  >Please select</option>
                                                            <option value="Active"  > Active</option>
                                                            <option value="Inactive" > Inactive</option>                         
                                                        </select>
                                                    </div>
                                                </div>                                               
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <input type="submit" class="btn btn-primary" value="Update" style="float: right;"></button>
                                                    </div>
                                                </div>
                                            </form>
                                    </div>
                                    </div>
                                </div>
                                </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="margin-top: 188px;">
      
      <div class="modal-body">
      <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/add_year')}}">
                {{ csrf_field() }}
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Year <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="number" class="input-mask form-control" placeholder = 'Enter Year' name="year" required >
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
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary" value="Add" style="float: right;"></button>
                    </div>
                </div>
            </form>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">

</script>
@endsection