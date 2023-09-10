@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $model =DB::table('car_model')->get();?>
<style type="text/css">
   .modal-dialog {
   position:fixed;
   max-width: 100% !important;
   bottom:-30px;
   }  
   .selected{
    color: #fff!important;
    background-color: #b63339;
   }
</style>
<?php ?>
<div class="container">
   <div class="well" style="margin-top: 30px;">
      <div id="main-outlet" class="wrap" style="padding-bottom: 0px;">
         <div class="container">
            <div id="ember791" class="ember-view"></div>
            <div id="ember802" class="ember-view hidden create-topics-notice">
               <!---->
            </div>
         </div>
         <div class="container ">
            <div id="ember2655" class="ember-view m-5 p-3">
               <div class="row">
                  <div id="banner">
                     <div id="banner-content">
                        <p>"<strong>Respect others to earn respect</strong>", please stay civilized.<br>(You are requested, to use the search button to find answers to your queries / questions before posting one. It might be possible that your issue has already been discussed and resolved multiple times here)</p>
                        <!---->    
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container" >
            <div  class="row">

               <div class="col-md-2">
                  <select name="car_make_id" id="cars">

                       <option value="volvo">All Categories</option>
                       @foreach($model as $makes)
                       <option value="{{ $makes->car_make_id }}">{{$makes->name}}</option>
                       @endforeach
                  </select>
               
               </div>
               <div class="col-md-2">
                 <select name="cars" id="cars">
                       <option value="volvo">All tags</option>
                       <option value="saab">Saab</option>
                       <option value="mercedes">Mercedes</option>
                       <option value="audi">Audi</option>
                  </select>
             </div>
             <div class="col-md-6">
              <ul class="navbar navbar-expand-lg bg-light">
              <li class=" "><a class="btn btn-block selected" href="#">Categories </a></li>
              <li><a href="#"  class="btn btn-block">Latest</a></li>
              <li><button   class="btn btn-block" id="new" value="new">New</button></li>
              <li><a href="#" class="btn btn-block">Top</a></li>
              <li><a href="#" class="btn btn-block">Following</a></li>
               <li><a href="{{url('/faqs')}}" class="btn btn-block">Faqs</a></li>
            </ul>
               <!----><!---->
            </div>
            @if(session('id'))
            <div class="col-md-2">
               <button class="btn btn-success" id="submit"  data-toggle="modal"  data-target="#exampleModal"><i class="fa fa-envelope"></i> Message </button>
            </div>
            @endif
            </div>
         </div>
        <div class="container" id="newResults">
         </div>
         <div class="container list-container  p-3" id="default" >
            <div class="row">
               <div  style="width: 90%;!important">
                  <table id="ember4241" class="table table-bordered">
                     <thead>
                        <tr>
                           <th >Topic</th>
                           <th >Users</th>
                           <th >Replies</th>
                           <th >Views</th>
                           <th >Activity</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($forum as $forums)
                        <tr>
                           <td colspan="1">
                              <a href="{{url('/forum_list?key=go_'.$forums->forums_id)}}" >{{$forums->heading}}</a>
                           </td>
                         
                           <td >
                               @if(!empty($forums->user_image))
                              <img alt="" width="25" height="25" src="{{asset('uploads/users/'.$forums->user_image)}}"  title="system - Original Poster, Most Recent Poster">
                            @else
                            <img height="25" width="25" src="https://dev.eigix.com/sayarat/public/uploads/make/default-image.jpg">
                            @endif
                           </td>
                          
                           <?php  $reply = DB::table('forum_comments')->where('forums_id','=',$forums->forums_id)->get();
                               $reply_count = count($reply);  
                              ?>
                           <td>
                              @if(!empty($reply_count))
                              <span>{{ $reply_count}}</span>
                              @else
                              <span >No Comments Yet</span>
                              @endif
                           </td>
                           <td >
                              <?php if(empty($forums->views)){?>
                             <span >No views Yet</span>
                              <?php }else{?>
                              <span >{{ $forums->views}}</span>
                              <?php } ?>
                           </td>
                           <td >
                            @if($forums->date_modified)
                           {{ $forums->date_modified}}</span>
                            @else
                            <span >No activity Yet</span>
                            @endif
                            </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <!---->
      
      
   </div>
</div>

<!-- Modal -->
<div style="max-width: 100%;" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <?php $users= DB::table('users')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/send_message/'.session('id').'/reciver')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Users<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="receiver" >
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->users_id}}">{{ $user->user_name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Subject<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" name="title" placeholder="What is the discussion?Brief" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" name="message" ></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-12">
                     <input type="submit" class="btn btn-primary" value="Add" ></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   
   $('#new').click(function(){
       $("#default").hide();
        $("#new").addClass("selected");

        
        var url = "{{URL('/get_forum/new')}}";
        var id_value= $(this).attr('id');
         $.ajax({
         type:'get',
         url:url,
         dataType:'html',
         data:'_token = <?php echo csrf_token() ?>',
         success: function(data) {
            $("#newResults").html(data);
         }
       });
   });
</script>
@endsection