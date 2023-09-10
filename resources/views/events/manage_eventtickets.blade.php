
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

<?php  $eventsModel = DB::table('event_posts')->where('status', 'Active')->get();    ?>

<div class="panel">
    <div class="panel-body">

        <h3 class="title-hero">Event Tickets Management <a data-toggle="modal" data-target="#Modal_add" class="btn btn-sm btn-primary" title=""
            style="float: right;margin-bottom: 10px;">Add Event Ticket</a></h3>
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
                             <th>Ticket ID</th>
                             <th>Ticket</th>
                            <th>Event Post ID</th>
                            <th>Event Title</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Closing Date</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($event_tickets as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a id="{{ $items->ticket_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></li>                                      
                                        <li><a id="{{ $items->ticket_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></li>                                      
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $items->ticket_id }}</td>
                            <td>{{ $items->ticket }}</td>

                            <td>{{ $items->event_post_id }}</td>
                            <?php  $items->title = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->get();    ?>
                            <td>{{ $items->title[0]->title }}</td>

                            <td>{{ $items->quantity }}</td>
                            <td>{{ $items->price }}</td>
                            <td>
                                @if ($items->closing_date != '0')
                                  {{ \Carbon\Carbon::parse($items->closing_date)->format('d/m/y h:m a')}}
                                @else
                                {{ ''}}
                                @endif
                            </td>

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
                <div class="modal-content" style="margin-top: 160px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_eventticket')}}">
                {{ csrf_field() }}
                <input type="hidden" id="ticket_id" name="ticket_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ticket Name <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="ticket" name="ticket" required>
                            <option value="Early bird"> Early Bird</option>
                            <option value="Regular"> Regular</option>       
                            <option value="VIP"> VIP</option>         
  
                        </select>
                    </div>
                </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Event<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" id="event_post_id" name="event_post_id" required>
                                @foreach($eventsModel as $event)
                                <option value="{{ $event->event_post_id }}">{{ $event->event_post_id.' -- '.$event->title}}</option>
                              @endforeach                 
                            </select>
                        </div>
                    </div> 

                    <div class="form-group" style="margin-left: 20px">
                        <div>
                            <label class="col-sm-2 control-label"> Quantity <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="text" id="quantity"  class="input-mask form-control" name="quantity" placeholder="Enter Quantity" required>
                            </div>
                        </div>
    
                        <div>
                            <label class="col-sm-2 control-label"> Price <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="text" id="price"  class="input-mask form-control" name="price" placeholder="Enter Price" required>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label style="display: none;" id="closing_date_info" class="col-sm-9 control-label">Closing Date can only be selected for Early bird type ticket !</label>
                        <label id="closing_date_label" class="col-sm-3 control-label">Closing Date</label>
                        <div class="col-sm-6">
                            <input  type="datetime-local" id="closing_date" class="input-mask form-control" name="closing_date"x    >
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
                <div class="modal-content" style="margin-top: 100px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/eventticket_update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="ticket_id" name="ticket_id">

                <div class="form-group">
                    <label class="col-sm-3 control-label">Event <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="event_post_id" class="input-mask form-control" name="event_post_id" required readonly>
                    </div>
                </div> 
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ticket Name <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="ticket_edit" name="ticket_edit" required>
                            <option value="Early bird"> Early Bird</option>
                            <option value="Regular"> Regular</option>       
                            <option value="VIP"> VIP</option>         
  
                        </select>
                    </div>
                </div>

                    <div class="form-group" style="margin-left: 20px">
                        <div>
                            <label class="col-sm-2 control-label"> Quantity <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="text" id="quantity"  class="input-mask form-control" name="quantity" placeholder="Enter Quantity" required>
                            </div>
                        </div>
    
                        <div>
                            <label class="col-sm-2 control-label"> Price <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="text" id="price"  class="input-mask form-control" name="price" placeholder="Enter Price" required>
                            </div>
                        </div>
                    </div> 
                    
                    
                    <div class="form-group">
                        <label style="display: none;" id="closing_date_info_edit" class="col-sm-9 control-label">Closing Date can only be selected for Early bird type ticket !</label>
                        <label id="closing_date_label_edit" class="col-sm-3 control-label">Closing Date</label>
                        <div class="col-sm-6">
                            <input  type="datetime-local" id="closing_date_edit" class="input-mask form-control" name="closing_date_edit" placeholder="Enter Closing date for early bird ticket only">
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

            
                      {{--  DELETE MODAL --}}
        <div class="modal fade" id="Modal_delete" tabindex="-1" role="dialog" aria-labelledby="Modal_delete" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="margin-top: 188px;">
                
                <div class="modal-body">
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/delete_eventticket')}}">
                {{ csrf_field() }}
                <input type="hidden" id="ticket_id" name="ticket_id">
             
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
                    $("#itemForm input[name='ticket_id']").val(itemJson.ticket_id);
                    $("#itemForm input[name='event_post_id']").val(itemJson.event_post_id);
                    $("#itemForm #ticket_edit").val(itemJson.ticket);
                    $("#itemForm input[name='quantity']").val(itemJson.quantity);
                    $("#itemForm input[name='price']").val(itemJson.price);
                    
                 /*   // var date  = new Date();
                   let date  = " <?php echo  \Carbon\Carbon::parse($items->closing_date)->format('d/m/y h:m a');  ?>"
                   alert(php);               

                    dateFormat(, "d/m/y h:m a");
                    $("#itemForm input[name='closing_date']").val(date); */
                    $("#itemForm #status").val(itemJson.status);            
                    $("#Modal_edit").modal('show');
                }
                                
                function deleteItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='ticket_id']").val(itemJson.ticket_id);    
                    $("#Modal_delete").modal('show');
              
                }
            </script>

            <script>
                //For add modal check
                     $("#ticket").on('change', function () {
                        var selected = $(this);
                        if (selected.val() != "Early bird") {
                            document.getElementById("closing_date_label").style.display = "none";
                            document.getElementById("closing_date").style.display = "none";
                            document.getElementById("closing_date_info").style.display = "block";

                        } else{
                            document.getElementById("closing_date_label").style.display = "block";
                            document.getElementById("closing_date").style.display = "block";
                            document.getElementById("closing_date_info").style.display = "none";
                        }
                    });
                </script>

                <script>
                   $( document ).ready(function() {  
                        //For Edit modal check
                     $("#ticket_edit").on('change', function () {
                        var selected = $(this);
                            if (selected.val() != "Early bird") {
                                document.getElementById("closing_date_label_edit").style.display = "none";
                                document.getElementById("closing_date_edit").style.display = "none";
                                document.getElementById("closing_date_info_edit").style.display = "block";

                            } else{
                                document.getElementById("closing_date_label_edit").style.display = "block";
                                document.getElementById("closing_date_edit").style.display = "block";
                                document.getElementById("closing_date_info_edit").style.display = "none";
                            }                       
                         });
                    });
            </script>
  
<script type="text/javascript">
</script>
@endsection