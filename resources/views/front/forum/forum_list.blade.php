@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style>
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
<?php $forums =DB::table('forums')->orderby('date_added','DESC')->get();?> 
<div class="container">
<div class="well" style="margin-top: 30px;">
   <div id="main-outlet" class="wrap" style="padding-bottom: 0px;">
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
      <div class="container  p-3" style="max-width:90%">
         @foreach($forums as $forum)
         <?php $like= DB::Table('forum_likes')->where('forums_id','=',$forum->forums_id)->get();
            $likes_count = sizeof($like,1);?>
         <div class="well">
            <div id="go_{{ $forum->forums_id}}" >
               <div class="container">
                  <div class="title-wrapper">
                     <h2 >
                        <a style="color: #b73439 "  href="{{url('/forum_list?key=go_'.$forum->forums_id)}}">
                        {{ $forum->heading}}
                        </a>
                        <!---->            
                     </h2>
                     <?php $make = DB::table('car_make')->where('car_make_id','=',$forum->forum_make_id)->first(); ?>
                     <?php $model = DB::table('car_model')->where('car_model_id','=',$forum->forum_model_id)->first(); ?>
                     <div id="ember9882" class="ember-view">
                        <a  href="#"><span class="badge-category-bg" style="background-color: #e6262e;"></span>
                        <a  href="#">
                        <span style="color: #b73439 ;" >
                        </span>
                        </a>
                        <div class="list-tags">
                           <!---->  
                        </div>
                        <!---->
                     </div>
                  </div>
                  <!---->
               </div>
            </div>
            <?php $user = DB::table('users')->where('users_id','=',$forum->created_by)->first();?>
            <?php if($user){?>
            <div class="row">
               <div class="container">
                  <div class="topic-avatar">
                     <a class="trigger-user-card main-avatar" href="#" >
                     <img alt="user_image"  width="45" height="45" src="{{asset('uploads/users/'.$user->user_image)}}" title="shanihyder" class="avatar">
                     </a>
                     <div class="poster-avatar-extra"></div>
                  </div>
                  <div class="topic-body clearfix">
                     <div class="container" style="max-width:90%;">
                        <div class="col-md-6">
                           <span class="first username"><a href="/forums/users/shanihyder" data-auto-route="true" data-user-card="shanihyder">{{$user->user_name}}</a></span>
                           <div class="desc"><span class="posts-count"></span>
                            <?php $time=session('date_added');
                              $timestamp = strtotime($time);

                              $day = date('M-d, y',$timestamp); 
                              ?>
                              <span class="user-since">{{$day}}</span></div>
                        </div>
                        <div class="col-md-6"><a href="" data-share-url="" data-post-number="1" class="post-date">
                           <?php $time=$forum->date_added;
                              $timestamp = strtotime($time);

                              $day = date('M-d, y',$timestamp); 
                              ?>

                           <span title="June 12, 2021 1:46pm" data-time="1623487617571" data-format="tiny" class="relative-date">{{$day }}</span></a>
                        </div>
                        <!--   get data  -->
                        <input class="border-0" name="forums_id" id="forums_id" type="text" value="{{ $forum->forums_id }}" hidden="" />
                     </div>
                  </div>
                  <div class="row">
                     <div class="regular contents m-3 p-5">
                        <div class="text-center">
                           <div style="float-left">{!!$forum->description!!}</div>
                        </div>
                        <div class="post-menu-area clearfix">
                           <nav class="post-controls clearfix">
                              <div class="row">
                                 <div class="col-md-2">
                                    <span>
                                    <span id="likes_<?php echo$forum->forums_id?>">{{ $likes_count}}</span> Like</span> 
                                 </div>
                                 <div class="col-md-2">
                                    <button id="like_<?php echo $forum->forums_id?>"  class="btn like"><i class="fa fa-heart " aria-hidden="true"></i></button>
                                 </div>
                                 <div class="col-md-2">
                                 </div>
                                 <!--  <div class="col-md-2">
                                    <button class="btn"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button> 
                                    </div>
                                    <div class="col-md-2">
                                    <button class="btn" ><i class="fa fa-link" aria-hidden="true"></i></button></div> 
                                    </div> -->
                                 @if(session('id'))
                                 <div class="col-md-2">

                                    <button class="btn btn-danger open-AddBookDialog"  id="{{$forum->forums_id}}" data-toggle="modal"  data-target="#forumModal"><i class="fa fa-reply" aria-hidden="true"></i>Reply</button>
                                 </div>
                                 @endif
                           </nav>
                           </div>
                        </div>
                     </div>
                     <div class="container" style="max-width:90%;">
                        <!-- <nav class="buttons">
                           <button"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                           </nav> -->
                        <section class="map map-collapsed">
                           <ul class="clearfix">
                              <li>
                                 <h4>created</h4>
                                 <a class="trigger-user-card main-avatar" href="#" >
                                 <img alt="user_image"  width="45" height="45" src="{{asset('uploads/users/'.$user->user_image)}}" title="shanihyder" class="avatar">
                                 </a>

                                  <?php $time=$forum->date_added;
                                    $timestamp = strtotime($time);

                                    $day = date('M-d, y',$timestamp); 
                                    ?>
                                 <span title="June 12, 2021 1:46pm" data-time="1623487616995" data-format="tiny" class="relative-date">{{$day }}</span>
                              </li>
                              <li>
                                 <?php $reply = DB::table('forum_comments')->where('forums_id','=',$forum->forums_id)->orderBy('date_added', 'DESC')->first();?>
                                 <a href="#">
                                    <span title="June 12, 2021 5:31pm" data-time="1623501061684" data-format="tiny" class="relative-date">
                                       @if($reply)
                                       <?php $time=$reply->date_added;
                                    $timestamp = strtotime($time);

                                    $day = date('M-d, y',$timestamp); 
                                    ?>
                                       <h4>last reply</h4>
                                       {{ $day}}
                                       @endif
                                    </span>
                                 </a>
                              </li>
                              <?php $reply = DB::table('forum_comments')->where('forums_id','=',$forum->forums_id)->get();
                                 $reply_count =sizeof($reply,1);
                                   $users = DB::table('forum_comments')->where('forums_id','=',$forum->forums_id)->distinct('users_id')->get();
                                    $users_count =sizeof($users,1);
                                   
                                 
                                 ?>
                              <li>
                                 <span class="number">{{$reply_count}}</span>
                                 <h4>reply</h4>
                              </li>
                              <li class="secondary">
                                 <span class="number">{{ $forum->views }}</span>
                                 <h4>views</h4>
                              </li>
                              <li class="secondary">
                                 <span class="number">{{ $users_count }}</span>
                                 <h4>Users</h4>
                              </li>
                              <li class="secondary">
                                 <span class="number">{{ $likes_count}}</span>
                                 <h4>likes</h4>
                              </li>
                           </ul>
                        </section>
                        <section>
                           <?php $comments =DB::table('forum_comments')->where('forums_id','=',$forum->forums_id)->get()?>
                           <div class="section-heading sh-t7 sh-s1"><span class="h-text btn-lg btn-danger ">Comments</span></div>
                           @foreach ($comments as $cmnt)
                           <?php $users =DB::table('users')->where('users_id','=',$cmnt->users_id)->first();?>
                           <div class="row mt-5" >
                              @if($users)
                              <a><img  class="rounded-circle" src="{{asset('uploads/users/'.$users->user_image)}}" height="50px" width="50px"> <strong >{{ $users->user_name}}</strong> </a>
                              @else
                              <img src="{{asset('uploads/make/default-image.jpg')}}" height="50px" width="50px">
                              @endif  
                              <div class="text-center">
                                 {{$cmnt->comment}}
                              </div>
                           </div>
                           @endforeach
                        </section>
                     </div>
                  </div>
               </div>
               <?php } ?>
            </div>
            @endforeach
         </div>
      </div>
   </div>
   <!---->
</div>
<!-- Modal -->
<!-- Modal -->
<div style="max-width: 100%;" class="modal fade" id="forumModal" tabindex="-1" role="dialog" aria-labelledby="forumModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-bottom" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_comment')}}">
               {{ csrf_field() }}

               <h1>Reply </h1>
               <div class="form-group hidden">
                  <label class="col-sm-3 control-label">ID <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input   id="bookId" name="forum_id" value="" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Message <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" name="comment" required="" ></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-12 text-center">
                     <input type="submit" class="btn btn-primary " value="Add" ></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   var  clicks = 0;
   $(".like").click(function(){
   
     var id_value = $(this).attr('id');
     var res = id_value.split("_");
     
    
     var id=res[1];
      
     var span = $('#likes_'+id).text();
     if(clicks=='0'){
        $(".like").addClass("btn-danger");
        span++;
        clicks += 1;
     }else{
        $(".like").removeClass("btn-danger");
        span--;
        clicks = 0;
     }
     
   
   
      var type=res[0];
       
      $('#likes_'+id).text(span);
     
       var url = 'https://dev.eigix.com/sayarat/index.php/add_like/' + id;
         
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
<script>
   $(document).on("click", ".open-AddBookDialog", function () {
      var myBookId = document.getElementById("forums_id").value;  
      //alert(myBookId);
      $(".modal-body #bookId").val( myBookId );
     
   });
</script>
<script type="text/javascript">
   <?php
      $valueID = $_GET['key'];
      
      ?>
    var value = '<?php echo  $valueID; ?>';  
    $('html, body').animate({
          scrollTop: $("#"+value).offset().top-50
       }, 100);
   
   
</script>
<script>
   $("#submit").click(function () {
      alert('');
     
      var forums_id = $("#forums_id").val();
     
     
      $(".modal-body #forums_id").val(forums_id);
      
           
        });
</script>
@endsection