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
      <h3 class="title-hero">Users Listing
         <a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
            style="float: right;margin-bottom: 10px;">Add Content</a>
      </h3>
      <div class="example-box-wrapper">
         <div class="example-box-wrapper">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
               id="myTable">
               <thead>
                  <tr>
                     <th style="width: 55px;">#</th>
                     <th style="width: 55px;">Action</th>
                     <th>type</th>
                     <th>description</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($static_content as $key => $items)
                  <tr class="odd gradeX">
                     <td>{{ @$key + 1 }}</td>
                     <td>
                        <div class="btn-group">
                           <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                              aria-expanded="false">Action <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu">
                              <li><a data-toggle="modal" class="" data-target="#exampleModal_view_{{ @$items->system_settings_id }}" >View</a></li>
                              <li><a data-toggle="modal" id="system_settings_{{ @$items->system_settings_id  }}" class="system_settings" data-target="#exampleModal_edit" >Edit </a></li>
                              <li><a href="{{ url('/backoffice/delete_content/' . $items->system_settings_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                              </li>
                           </ul>
                        </div>
                     </td>
                     <input type="hidden" id="type_{{ @$items->system_settings_id }}" value="{{ @$items->type }}">
                     <input type="hidden" id="description_{{ @$items->system_settings_id }}" value="{{ @$items->description }}">
                     <td>{{ @$items->type }}</td>
                     <td>{{ @$items->description }}</td>
                     @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<!--ADD  Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/add_content')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Type <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type="text" class="input-mask form-control" placeholder = 'Enter Type' name="type" required >
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Description <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea type="text" class="input-mask form-control ckeditor" placeholder = 'Enter description' name="description" required ></textarea>
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
<!-- View model -->
@foreach ($static_content as $key => $itemsss)
<div class="modal fade" id="exampleModal_view_{{ @$itemsss->system_settings_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelss" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body" style="display: flex;
            flex-direction: column;
            grid-gap: 29px;">
            <div class="row">
               <label class="col-sm-6 control-label">type</label>
               <div class="col-sm-6">
                  {{ @$itemsss->type }}
               </div>
            </div>
            <div class="row">
               <label class="col-sm-6 control-label">Description</label>
               <div class="col-sm-6">
                  {{ @$itemsss->description }}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endforeach
<!-- Edit model -->
<div class="modal fade" id="exampleModal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/backoffice/edit_package/')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Type <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type="text" id="type" class="input-mask form-control" placeholder = 'Enter title' name="type" required >
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Description <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea type="text" id="description" class="input-mask form-control ckeditor" placeholder = 'Enter description' name="description" required ></textarea>
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
<script type="text/javascript">
   function change_my_image(abc) {
           // alert();
           var input = abc;
           if (input.files && input.files[0]) {
               var reader = new FileReader();
               reader.onload = function(e) {
                   // alert(e.target.result);
                   $('#upload_image').css('background', "url('" + e.target.result + "') no-repeat center center");
                   $('#upload_image').css('background-size', 'cover');
               }
               reader.readAsDataURL(input.files[0]);
           }
       }
       $("#upload_image").click(function() {
           $("#my_file").click();
       });
</script>
<script type="text/javascript">
   $(document).ready(function() {
           $('#categoryfetch').change(function() {
               var cat_id = $('#categoryfetch option:selected').val();
               $.ajax({
                   url: "{{ url('/fetchsubcategories') }}",
                   type: 'post',
                   data: {
                       "_token": "{{ csrf_token() }}",
                       'cat_id': cat_id
                   },
                   success: function(response) {
                       var result = jQuery.parseJSON(response);
                       if (result != "") {
                           $('#subcategory').show();
                           $('#sub_category').html(result);
                       } else {
                           $('#subcategory').hide();
                       }
                   }
               });
           });
       });
</script>
<script>
   CKEDITOR.replaceClass = 'ckeditor';
   CKEDITOR.config.width = '75%';
   CKEDITOR.config.allowedContent = true;
</script>
<script type="text/javascript"></script>
@endsection