@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style type="text/css">
   .upload-img {
   width: 300px;
   height: 200px;
   border-radius: 50%;
   background-position: center center;
   background-size: cover;
   }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="container" style="margin-top: 50px;">
   <div class="panel">
      <div class="panel-body">
         <div class="example-box-wrapper">
            <div class="example-box-wrapper">
               <div class="well">
                  <div class="container lst">
                    
                  </div>
                  <form   class="form-horizontal bordered-row" enctype="multipart/form-data" method="post"
                     action="{{url('/profile_update/'.$data[0]->users_id)}}">
                     {{ csrf_field() }}
                      @if (session('profile_errors'))
                     <div class="alert alert-danger">
                        {{ session('profile_errors') }}
                     </div>
                     @endif
                     @if(session('profile_success'))
                     <div class="alert alert-success">
                        {{ session('profile_success') }}
                     </div>
                     @endif
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Image</label>
                        <div class="col-sm-6">
                           <input name="image" type="file" id="my_file" style="display: none;"
                              onchange="change_my_image(this)" accept="image/*" />
                           <div id="upload_image" class="upload-img" style="background-image:url('{{asset('uploads/users/'.$data[0]->user_image)}}');"></div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Gender</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="gender" value="{{ $data[0]->gender }}" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">User Name</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="first_name" value="{{ $data[0]->user_name }}" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="email" value="{{ $data[0]->email }}" required>
                        </div>
                     </div>
                    <!--  <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="password" value="{{ $data[0]->password }}" required>
                        </div>
                     </div> -->
                     <div class="form-group">
                        <label class="col-sm-3 control-label">City</label>
                        <div class="col-sm-6">

                          <select name="city_id" required="">
                           <?php $city =db::table('cities')->get(); ?>
                           @foreach($city as $cities)
                           <option value="{{$cities->id}}">{{$cities->name}}</option>
                           @endforeach
                          </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="address" value="{{ $data[0]->address }}" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Mobile</label>
                        <div class="col-sm-6">
                           <input type="text" class="input-mask form-control" name="mobile" value="{{ $data[0]->mobile }}" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-sm-12">
                           <input type="submit" class="btn btn-primary" value="Update" style="display: table;margin:0 auto"></button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script type="text/javascript">
      function change_my_image(abc){
          // alert();
          var input = abc;
           if (input.files && input.files[0]) {
               var reader = new FileReader();
               reader.onload = function (e) {
                 // alert(e.target.result);
                   $('#upload_image').css('background', "url('"+e.target.result+"') no-repeat center center");
                   $('#upload_image').css('background-size','cover');
               }
                 reader.readAsDataURL(input.files[0]);
          }
      }
      
      $("#upload_image").click(function() {
         $("#my_file").click();
      });
      
      $(document).ready(function() {
          $('#blogs_categories_id').select2();
      });
   </script>
</div>
@endsection