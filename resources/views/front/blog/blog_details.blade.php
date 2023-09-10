@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
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
<?php $make =DB::table('car_make')->get();?>
<?php ?>
<div class="container">
   <div class="row">
      <div class="col-md-8 mt-5  mb-2">
         <section>
            @foreach($blog as $blogs)
            <div class="row">
               <div class="well mr-5">
                  @if($blogs->blog_image)
                  <a href="{{url('/blog/'.$blogs->blogs_id)}}"><img  src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" ></a>
                  @else
                  <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
                  @endif
                  <div class="col-md-4 ">
                  </div>
                  <!--  <div class="row">
                     <div class="col-md-8">
                       
                     </div>
                     <?php $users =DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
                     <div class="col-md-12 mt-3" style="color:#9b9b9b">
                        <div class="row">
                           @if($users)
                           <strong >{{ $users->user_name}}</strong>
                           @endif
                           <span >{{ $blogs->date_added}}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                           
                        </div>
                     </div>
                     </div> -->
               </div>
            </div>
            <div class="row">
               <?php $users =DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
               <div class="col-md-12 mt-3" style="color:#9b9b9b">
                  <div class="row" style="display: inline-block;">
                     @if($users)
                     <a><img  class="rounded-circle" src="{{asset('uploads/users/'.$users->user_image)}}" height="50px" width="50px"></a>
                     <strong >{{ $users->user_name}}</strong>
                     <span >
                     <?php $time=$blogs->date_added;
                        $timestamp = strtotime($time);
                        
                        $day = date('M-d, y',$timestamp); 
                        ?>
                     {{ $day}}</span>
                     <span> <a href="#reply" class="btn btn-danger btn-sm">Reply</a></span>
                     @endif
                  </div>
               </div>
            </div>
         </section>
         <section>
            <div>
               <strong>{{ $blogs->heading}}</strong>
            </div>
            <div>
               {!!$blogs->description!!}
            </div>
         </section>
         <section>
            <div class=""><span class=" btn btn-danger ">Comments</span></div>
            <?php $comments =DB::table('blogs_comments')->where('blogs_id','=',$blogs->blogs_id)->get()?>
            @foreach ($comments as $cmnt)
            <?php $users =DB::table('users')->where('users_id','=',$cmnt->users_id)->first();?>
            <div class="row mt-5 text m-5" style="border: 1px solid; max-width: 90%;">
               @if($users)
               <a><img  class="rounded-circle" src="{{asset('uploads/users/'.$users->user_image)}}" height="50px" width="50px"> <strong >{{ $users->user_name}}</strong> </a>
               @endif  
               <div class="text-center">
                  {{$cmnt->comment}}
               </div>
            </div>
            @endforeach
         </section>
         @endforeach
      </div>
      <div class="col-md-4">
         <div class="m-3 ">
            <h1>Urdu</h1>
         </div>
         <?php $popblogs = DB::table('blogs')->get();?>
         @foreach($popblogs as $blogs)
         <div class="row">
            <div class="well m-3">
               <div class="col-md-4">
                  @if($blogs->blog_image)
                  <img src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" height="50px" width="50px">
                  @else
                  <img src="{{asset('uploads/make/default-image.jpg')}}" height="50px" width="50px">
                  @endif
               </div>
               <div class="col-md-8">
                  <div class="row">
                     <div class="col-md-12">
                        {{$blogs->heading}}
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endforeach
         <div class="mt-5" >
            <h1>Search Cars</h1>
            <span>Under development..</span>
         </div>
         <div class="row">
            <div class="col-md-12 mt-2">
               <select name="cars" id="cars" disabled="">
                  <option value="">Make</option>
                  <option value="saab">Saab</option>
                  <option value="opel">Opel</option>
                  <option value="audi">Audi</option>
               </select>
            </div>
            <div class="col-md-12 mt-2 ">
               <select name="cars" id="cars" disabled="">
                  <option value="volvo">Model</option>
                  <option value="saab">Saab</option>
                  <option value="opel">Opel</option>
                  <option value="audi">Audi</option>
               </select>
            </div>
            <div class="col-md-12 mt-2 ">
               <select name="cars" id="cars" disabled="">
                  <option value="volvo">Volvo</option>
                  <option value="saab">Saab</option>
                  <option value="opel">Opel</option>
                  <option value="audi">Audi</option>
               </select>
            </div>
         </div>
         <div class="col-md-12 text-center mt-3 mb-5">
            <a class="btn btn-block btn-success disabled">Search</a>
         </div>
         <div  class="m-5">
            <div class="">
               <h1 class="mt-5">Popular Tags</h1>
               <span>(under developement)</span>
            </div>
            <div class="tagcloud">
               <?php $make = db::table('car_make')->get();?>
               @foreach($make as$makes)
               <a class="tag"  >{{ $makes->name}}</a>
               @endforeach
            </div>
         </div>
      </div>
   </div>
   <section>
      <div class="row">
         <h1>Related </h1>
      </div>
      <div class="row mt-3">
         <?php $popblogs = DB::table('blogs')->where('blogs_categories_id','=',6)->get();?>
         @foreach($popblogs as $blogs)
         <div class="col-md-3 ">
            <div class="card border-0" style="width:250px">
               @if($blogs->blog_image)
               <img class="card-img-top" src="{{asset('uploads/blogs/'.$blogs->blog_image)}}"  style="width:100%">
               @else
               <img class="card-img-top" src="{{asset('uploads/make/default-image.jpg')}}"  style="width:100%">
               @endif
               <?php $users= DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
               <div class="card-body">
                  <h4 class="card-title">{{$users->user_name}}</h4>
                  <p class="card-text">{{$blogs->heading}}</p>
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </section>
</div>
<!-- Modal -->
<div style="max-width: 100%;" class="modal fade" id="forumModal" tabindex="-1" role="dialog" aria-labelledby="forumModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 188px;">
         <div class="modal-body">
            <?php $categories= DB::table('blogs_categories')->get();?>
            <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('add_blog')}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <label class="col-sm-3 control-label">category<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <select name="blogs_categories_id" >
                        <option value="">Select category</option>
                        @foreach ($categories as $cate)
                        <option value="{{ $cate->blogs_categories_id}}">{{ $cate->name}}</option>
                        @endforeach    
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Title<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type= "text" name="heading" placeholder="What is the discussion?Brief" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Description <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <textarea  class="ckeditor" name="description" ></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Select image <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <input type="file"  class="form-control" name="image" class="myfrm form-control">
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
<div class="container">
   <div id="reply" class="comment-respond">
      <p id="reply-title" class="comment-reply-title"></p>
      <div class="section-heading sh-t7 sh-s1">
         <h1 class="h-text">Leave A Reply</h1>
      </div>
      <form action="{{url('/blog_comment/'.$blog[0]->blogs_id)}}" method="post" id="commentform" class="comment-form" novalidate="">
         {{ csrf_field() }}
         <div class="note-before">
            <p>Your email address will not be published.</p>
         </div>
         <p class="comment-wrap"><textarea name="comment" class="comment" id="comment" cols="45" rows="10" aria-required="true" placeholder="Your Comment"></textarea></p>
         <input name="author" class="author" id="author" type="text" value="" size="45" placeholder="Your Name"></p>
         <p class="email-wrap">
            <input name="email" class="email" id="email" type="text" value="" size="45" placeholder="Your Email">
         </p>
         <a class="btn btn-danger text-center mb-5"  name="submit" type="submit">Submit</a>
      </form>
   </div>
</div>
@endsection