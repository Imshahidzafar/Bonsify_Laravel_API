@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $likes = DB::table('forum_likes')->where('forums_id','=',$forum->forums_id)->get();
    $likes_count =sizeof($likes,1);?>
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
         <div id="ember2657" class="ember-view">
            <!---->
         </div>
         <div class="container list-container  p-3">
            <div class="row">
               <div class=" p-3 m-3 " >
                  <div id="header-list-area">
                     <!---->
                  </div>
               </div>
            </div>
            <div id="topic-title">
               <div class="container">
                  <div class="title-wrapper">
                     <h2 >
                        
                       
                        <a style="color: #b73439 "  href="#">
                        {{ $forum->heading}}
                        </a>
                        <!---->            
                     </h2>
                     <?php $make = DB::table('car_make')->where('car_make_id','=',$forum->forum_make_id)->first(); ?>
                      <?php $model = DB::table('car_model')->where('car_model_id','=',$forum->forum_model_id)->first(); ?>
                     <div id="ember9882" class="ember-view">
                        <a  href="#"><span class="badge-category-bg" style="background-color: #e6262e;"></span>
                        <a  href="#">
                           <span style="color: #b73439 ;" >{{$make->name}}/{{$model->name}}</span></a>
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
            <div class="row">
               <div class="container">
               <div class="topic-avatar">
                  <a class="trigger-user-card main-avatar" href="/forums/users/shanihyder" data-user-card="shanihyder"><img alt="user_image" width="45" height="45" src="{{asset('uploads/users/'.$user->user_image)}}" title="shanihyder" class="avatar"></a>
                  <div class="poster-avatar-extra"></div>
               </div>
               
               <div class="topic-body clearfix">
                  <div class="topic-meta-data">
                     <div class="names trigger-user-card">
                        <span class="first username"><a href="/forums/users/shanihyder" data-auto-route="true" data-user-card="shanihyder">{{$user->user_name}}</a></span>
                        <div class="desc"><span class="posts-count">Posts: 1</span><span class="user-since">{{$user->date_added}}</span></div>
                     </div>
                     <div class="post-info"><a href="" data-share-url="" data-post-number="1" class="post-date"><span title="June 12, 2021 1:46pm" data-time="1623487617571" data-format="tiny" class="relative-date">{{$forum->date_added }}</span></a></div>
                     <div class="read-state read" title="Post is unread"><i class="fa fa-circle" aria-hidden="true"></i></div>
                  </div>
                  <div class="regular contents">
                     <div class="cooked">
                        <p>{!!$forum->description!!}</p>
                     </div>
                     <section class="post-menu-area clearfix">
                        <nav class="post-controls clearfix">
                           <div class="actions"><button class="widget-button like-count highlight-action" aria-label="1 person liked this post" title="1 person liked this post">{{$likes_count}} Like</button><button class="widget-button toggle-like like no-text" aria-label="like this post" title="like this post"><i class="fa fa-heart" aria-hidden="true"></i></button><button class="widget-button show-more-actions no-text" aria-label="show more" title="show more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button><button class="widget-button share no-text" aria-label="share a link to this post" title="share a link to this post" data-share-url="/forums/t/dunlop-digi-tyre-eco-ec201-175-65r14-are-good-or-not/2249936?u=aimankhanji123" data-post-number="1"><i class="fa fa-link" aria-hidden="true"></i></button></div>
                        </nav>
                     </section>
                  </div>
                  <section class="post-actions"></section>
                  <div class="post-links-container"></div>
                  <div class="topic-map">
                     <nav class="buttons"><button class="widget-button btn no-text" aria-label="toggle topic details" title="toggle topic details"><i class="fa fa-chevron-down" aria-hidden="true"></i></button></nav>
                     <section class="map map-collapsed">
                        <ul class="clearfix">
                           <li>
                              <h4>created</h4>
                              <a class="trigger-user-card " data-user-card="shanihyder"><img alt="" width="20" height="20" src="//dsa1.pakwheels.com/forums/letter_avatar_proxy/v2/letter/s/d6d6ee/20.png" title="shanihyder" class="avatar"></a><span title="June 12, 2021 1:46pm" data-time="1623487616995" data-format="tiny" class="relative-date">{{$forum->date_added }}</span>
                           </li>
                           <li>
                              <?php $reply = DB::table('forum_comments')->where('forums_id','=',$forum->forums_id)->orderBy('date_added', 'DESC')->first();?>
                              <a href="/forums/t/dunlop-digi-tyre-eco-ec201-175-65r14-are-good-or-not/2249936/2">
                                 <h4>last reply</h4>
                              <a class="trigger-user-card " data-user-card="Thinking"><img alt="" width="20" height="20" src="//dsa1.pakwheels.com/forums/letter_avatar_proxy/v2/letter/t/65b543/20.png" title="Thinking" class="avatar"></a><span title="June 12, 2021 5:31pm" data-time="1623501061684" data-format="tiny" class="relative-date">{{ $reply->date_added}}</span></a>
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
                              <span class="number">{{$likes_count}}</span>
                              <h4>likes</h4>
                           </li>
                        </ul>
                     </section>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      <!---->
      <div id="user-card" class="ember-view show-badges">
         <!---->
      </div>
   </div>
</div>
@endsection