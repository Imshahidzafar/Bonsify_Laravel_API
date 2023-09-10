
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

<?php  $tagsModel = DB::table('tags')->where('status', 'Active')->get();    ?>
<?php  $eventsModel = DB::table('event_posts')->where('status', 'Active')->get();    ?>

<div class="panel">
    <div class="panel-body">

        <h3 class="title-hero">Event Tags Management <a data-toggle="modal" data-target="#Modal_add" class="btn btn-sm btn-primary" title=""
            style="float: right;margin-bottom: 10px;">Add Event Tag</a></h3>
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
                             <th>Tag ID</th>
                             <th>Tag Name</th>
                            <th>Event Post ID</th>
                            <th>Event Title</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($event_tags as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a id="{{ $items->event_tag_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></li>                                      
                                        <li><a id="{{ $items->event_tag_id }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></li>                                      
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $items->event_tag_id }}</td>

                            <?php  $items->tag_name = DB::table('tags')->where('tag_id', $items->tag_id)->get();    ?>
                            <td>{{ $items->tag_name[0]->tag_name }}</td>

                            <td>{{ $items->event_post_id }}</td>
                            
                            <?php  $items->title = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->get();    ?>
                            <td>{{ $items->title[0]->title }}</td>
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
                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_eventtag')}}">
                {{ csrf_field() }}
                <input type="hidden" id="event_tag_id" name="event_tag_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tag<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="tag_id" name="tag_id" required>
                            @foreach($tagsModel as $tag)
                            <option value="{{ $tag->tag_id }}">{{ $tag->tag_id.' -- '.$tag->tag_name}}</option>
                          @endforeach                 
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
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/eventtag_update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="event_tag_id" name="event_tag_id">
             
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tag<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" id="tag_id" name="tag_id" required>
                            @foreach($tagsModel as $tag)
                            <option value="{{ $tag->tag_id }}">{{ $tag->tag_id.' -- '.$tag->tag_name}}</option>
                          @endforeach                 
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
                <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{url('/delete_eventtag')}}">
                {{ csrf_field() }}
                <input type="hidden" id="event_tag_id" name="event_tag_id">
             
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
                    $("#itemForm input[name='event_tag_id']").val(itemJson.event_tag_id);
                    $("#itemForm #event_post_id").val(itemJson.event_post_id);
                    $("#itemForm #tag_name").val(itemJson.tag_name);
                    $("#itemForm #status").val(itemJson.status);
            
                    $("#Modal_edit").modal('show');
                }
                
                
                function deleteItem(item)
                {
                    var itemJson = JSON.parse(item);
                    $("#itemForm input[name='event_tag_id']").val(itemJson.event_tag_id);    
                    $("#Modal_delete").modal('show');
              
                }
            </script>
  
<script type="text/javascript">
</script>
@endsection