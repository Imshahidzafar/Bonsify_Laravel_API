@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $make =DB::table('car_make')->get();?>
<style type="text/css">
   .modal.fade .modal-bottom {
   -webkit-transform: translate(0, 25%);
   -o-transform: translate(0, 25%);
   transform: translate(0, 25%);
   }
   .modal .modal-bottom {
   margin:0;
   margin-top:auto;
   padding: 0;
   position:absolute;
   bottom:0;
   top:auto;
   left:auto;
   right:0;
   height:calc(55% + 160px); /* default height:45% its just for demo*/
   }
   .modal .modal-bottom>.modal-content{
   height:100%;
   border-color: #fff;
   border-radius:0;
   margin:0;
   }
   .modal-bottom .modal-body{height: calc(100% - 140px);overflow-y:auto;}
   @media (min-width: 1px){
   .modal-bottom {max-width: 100%;width:100%;}
   }
   /*Custume scroll bar*/
   .modal-bottom ::-webkit-scrollbar {width:8px}
   .modal-bottom ::-webkit-scrollbar-button {width:8px;height:5px}
   .modal-bottom ::-webkit-scrollbar-thumb:hover {background: #ccc}
   .modal-bottom ::-webkit-scrollbar-thumb { background:#f1f1f1;border: thin solid white;border-radius: 10px}
   .modal-bottom ::-webkit-scrollbar-track {background: #fff;border:thin solid white}    
   .selected{
   color: #fff!important;
   background-color: #b63339;
   }
</style>
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
         @if(session('forum_success'))
         <div class="alert alert-success">
            {{ session('forum_success') }}
         </div>
         @endif
          @if(session('comment_error'))
         <div class="alert alert-danger">
            {{ session('comment_error') }}
         </div>
         @endif
         <div class="container">
            <div  class="row">
               <div class="col-md-2">
                  <select name="car_make_id" id="cars">
                     <option value="volvo">All Categories</option>
                     @foreach($make as $makes)
                     <option value="{{ $makes->car_make_id }}">{{$makes->name}}</option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-1">
                  <select name="cars" id="cars">
                     <option value="volvo"> tags</option>
                     <option value="saab">Saab</option>
                     <option value="mercedes">Mercedes</option>
                     <option value="audi">Audi</option>
                  </select>
               </div>
               <div class="col-md-5">
                  <ul class="navbar navbar-expand-lg bg-light">
                     <li class=" "><a class="btn btn-block selected " href="#" id="Categories">Categories </a></li>
                     <li><a href="#"  class="btn btn-block">Latest</a></li>
                     <li><button   class="btn btn-block" id="new" value="new">New</button></li>
                     <li><a href="#" class="btn btn-block">Top</a></li>
                     <li><a href="#" class="btn btn-block">Following</a></li>
                     <li><a href="{{url('/faqs')}}" class="btn btn-block">Faqs</a></li>
                  </ul>
                  <!----><!---->
               </div>
               @if(session('id'))
               <div class="col-md-3">
                  <button class="btn btn-success" data-toggle="modal" data-target="#myMessageBottom"><i class="fa fa-envelope"></i>Message</button>
                  <button class="btn btn-danger" data-toggle="modal" data-target="#myModalBottom">Add Topic</button>
               </div>
               @endif
            </div>
         </div>
         <div class="container list-container  p-3" id="default">
            <div class="row">
               <div class=" p-3 m-3 " >
                  <div id="header-list-area">
                     <!---->
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="full-width" style="width: 90%;!important">
                  <div id="list-area">
                     <div id="ember2658" class="ember-view discovery-list-container-top-outlet discourse-adplugin">
                        <!----><!----><!---->
                     </div>
                     <div id="ember2682" class="ember-view contents">
                        <table class=" table table-bordered">
                           <thead>
                              <tr>
                                 <th class="category">Category</th>
                                 <th class="latest">Latest</th>
                                 <th class="stats topics">Topics</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($make as $makes)
                              <?php  $seven_days = date('Y-m-d', strtotime('-7 days'));
                                 $latest = DB::select("select * from forums where forum_make_id ='".$makes->car_make_id ."'     and date_added BETWEEN '".$seven_days."' and '".date("Y-m-d H:i:s")."' order by date_added DESC LIMIT 3 ");
                                  $total_new= sizeof($latest,1); 
                                 ?>
                              <tr data-category_id="40" class="has-description no-logo">
                                 <td class="category" >
                                    <div>
                                       <h3 id="ember2683" class="ember-view"><a href="{{url('/forum_make/'.$makes->car_make_id)}}"><span class="category-name">{{$makes->name}}</span></a>
                                          @if($total_new)
                                          <a class="badge new-posts badge-notification" href="{{url('/forum_list?key=go_'.$latest[0]->forums_id)}}" title="2 new topics">{{ $total_new}}new </a>
                                          @endif
                                       </h3>
                                       <span id="ember2684" class="ember-view">
                                          <!---->  
                                       </span>
                                       <!---->
                                       <div class="category-description">
                                          All discussion related to {{$makes->name}} cars goes into this section. Find solution to your problems, ask questions and talk about your {{$makes->name}} cars, all in one place.
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>
                                    <?php $model = DB::table('car_model')->where('car_make_id','=',$makes->car_make_id)->get();?>
                                    <?php if(empty($model)){ ?>
                                    <div class="subcategories mt-5 mr-5 ">
                                       <a class="" style="color: #233d7b;" href="">
                                       No Record Found
                                       </a>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="subcategories mt-5 mr-5 ">
                                       @foreach($model as $models)
                                       <a class="badge-wrapper bullet" href="{{url('/forum_model/'.$models->car_model_id)}}"><span   class=" badge-notification clear-badge" style="    color: #233d7b;"  >{{ $models->name}}</span></a>
                                       @endforeach
                                    </div>
                                    <?php } ?>
                                 </td>
                                 <?php if(empty($latest)){?>
                                 <td>No Record Found</td>
                                 <?php } else{ ?>
                                 <td class=" latest">
                                    @foreach($latest as $latests)
                                    <div id="ember2701" class="ember-view featured-topic">
                                       <div id="ember2702" class="ember-view topic-statuses"></div>
                                       <?php if(empty($latests)){?>
                                       <a class="title" href="#">
                                       <label>No record Found</label></a><?php }else {?> 
                                       <a  href="{{url('/forum_list?key=go_'.$latests->forums_id)}}">{{ $latests->heading }}</a>
                                       <?php } ?>
                                       <span id="ember2703" class="ember-view topic-post-badges"></span>
                                       &nbsp;
                                       <?php $topic_date = $latests->date_added ;
                                          $today_date = date("Y-m-d H:i:s");
                                              $count = round(strtotime($today_date) - strtotime($topic_date));
                                             $day = abs(round($count / 86400));
                                          
                                          ?>
                                       @if($day == '0')
                                       <span  >To-day</span>
                                       @else 
                                       {{  $day }}day
                                       @endif
                                    </div>
                                    @endforeach
                                 </td>
                                 <?php }?>
                                 <td >
                                    <?php 
                                       $date=date('Y-m-d H:i:s');
                                       $monday = date('Y-m-d', strtotime( 'last sunday' ) );
                                       $week_days= DB::select("select * from forums where date_added Between '".$date."' AND '".$monday."'");
                                       $day =DB::table('forums')->where('date_added','=',$date)->get();
                                            $day_count = $day->count();
                                            $week=sizeof($week_days,1);  
                                       ?>
                                    <table class="categoryStats">
                                       <tbody>
                                          <tr>
                                             <td class="value"><?php if($day_count=='0'){?> <span>No Topic / Last day</span><?php } else { ?> {{ $day_count }}day<?php } ?></td>
                                             <td class="unit"></td>
                                          </tr>
                                          <tr>
                                             <td class="value">
                                                <?php if($week=='0'){?> <span>No Topic  / Last Week</span><?php } else { ?> {{ $week }}week<?php } ?>
                                             </td>
                                             <td class="unit"> </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              @endforeach
                              <tr data-category_id="41" class="has-description no-logo">
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <footer class="topic-list-bottom"></footer>
                  </div>
               </div>
            </div>
         </div>
         <div class="container" id="newResults">
         </div>
      </div>
      <!---->
      <div id="user-card" class="ember-view show-badges">
         <!---->
      </div>
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
<!-- Modal -->
<div style="max-width: 100%;" class="modal fade" id="forumModal" tabindex="-1" role="dialog" aria-labelledby="forumModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <?php $users= DB::table('car_make')->get();?>
            <?php $model= DB::table('car_model')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('add_forum')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Make<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="forum_make_id"  required="">
                        <option value="">Select Make</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->car_make_id}}">{{ $user->name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">model<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="forum_model_id" required="" >
                        <option value="">Select model</option>
                        @foreach ($model as $models)
                        <option value="{{ $models->car_model_id}}">{{ $models->name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Subject<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" name="title" placeholder="What is the discussion?Brief" required="" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" name="message" required="" ></textarea>
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
<!-- Add topic Model modal -->
<div class="modal fade" id="myModalBottom" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-bottom">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">ADD Topic</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body p-4">
            <?php $users= DB::table('car_make')->get();?>
            <?php $model= DB::table('car_model')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('add_forum')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Make<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="forum_make_id"  required="">
                        <option value="">Select Make</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->car_make_id}}">{{ $user->name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">model<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="forum_model_id" required="" >
                        <option value="">Select model</option>
                        @foreach ($model as $models)
                        <option value="{{ $models->car_model_id}}">{{ $models->name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Subject<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" name="title" placeholder="What is the discussion?Brief" required="" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" name="message" required="" ></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Add Topic</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Message modal -->
<div class="modal fade" id="myMessageBottom" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-bottom">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">ADD Topic</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body p-4">
            <?php $users= DB::table('users')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/send_message/'.session('id').'/reciver')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">Users<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="receiver" required=""  >
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
                     <input type= "text" required=""  name="title" placeholder="What is the discussion?Brief" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" required="" name="message" ></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Send Message</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).on("click", ".open-AddBookDialog", function () {
      var myBookId = $(this).data('id');
      $(".modal-body #bookId").val( myBookId );
     
   });
</script>
<script type="text/javascript">
   $('#new').click(function(){
       
        $("#new").addClass("selected");
         $("#Categories").removeClass("selected");
        
        $("#default").hide();
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