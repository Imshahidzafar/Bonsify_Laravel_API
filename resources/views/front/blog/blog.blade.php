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
   .tag{
   display: inline-block;
   line-height: 16px;
   color: #444;
   background-color: #fff;
   border: 1px solid #dedede;
   margin: 0 6px 6px 0;
   padding: 5px 7px;
   font-size: 12px!important;
   font-weight: 400;
   }
</style>
<?php $make =DB::table('car_make')->get();?>
<?php ?>
<div class="container">
<section>
   <section>
      <div class="row">
      <div class="col-md-8 mt-2">
         <div class="">
            <h1 class="" >Recent Posts</h1>
         </div>
                 <section class="m-5">
            @foreach($blog as $blogs)
            <div class="row ">
               <div class="well mr-5 mb-5">
                  <div class="col-md-4">
                     @if($blogs->blog_image)
                     <a href="{{url('/blog/'.$blogs->blogs_id)}}"><img  src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" height="400px" width="300px"></a>
                     @else
                     <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
                     @endif
                  </div>
                  <div class="row">
                     <div class="col-md-8">
                        <strong>{{ $blogs->heading}}</strong>
                     </div>
                     <?php $users =DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
                     <div class="col-md-10 mt-3" style="color:#9b9b9b">
                        <div class="row " style="display: inline-block;" >
                           @if($users)
                           <strong ><a href="{{url('/blog_search/'.$users->users_id)}}">{{ $users->user_name}}</a></strong>
                           @endif
                           <?php $time=$blogs->date_added;
                              $timestamp = strtotime($time);
                              
                              $day = date('M-d, y',$timestamp); 
                              ?>
                           <span >{{ $day}}</span>
                           <span> <?php $reply = db::table('blogs_comments')->where('blogs_id','=',$blogs->blogs_id)->get();
                              $reply_count= sizeof($reply,1);  ?>
                           </span>
                           <span> <a  href="{{url('/blog/7?#reply')}}"><i class="fas fa-envelope"></i> {{ $reply_count}}</a></span>
                        </div>
                        <div class="col-md-12 mt-3">
                           {{$blogs->sub_title}}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            @endforeach
         </section>
         <section>
            <div class="row">
               <div class="col-md-5">
                  <div class="btn-lg btn-success m-3 text-center">
                     <span class="">News </span>
                  </div>
                  <?php $popblogs = DB::table('blogs')->where('blogs_categories_id','=',3)->get();?>
                  @foreach($popblogs as $blogs)
                  <div class="well m-3">
                     @if($blogs->blog_image)
                     <img src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" class="img-responsie" style="max-width: 100%; " >
                     @else
                     <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
                     @endif
                     <div class="mt-2">
                        <strong>{{$blogs->heading}}</strong>
                     </div>
                     <?php $users= DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
                     <div class="row" style="display:inline-block;" >
                        <span >@if($users) {{$users->user_name}} @endif</span>
                        <span>{{$day}}</span>
                     </div>
                  </div>
                  @endforeach
               </div>
               <div class="col-md-5 ">
                  <div class="btn-lg btn-success m-3 text-center">
                     <span class=""> Famous</span>
                  </div>
                  <?php $popblogs = DB::table('blogs')->where('blogs_categories_id','=',7)->get();?>
                  @foreach($popblogs as $blogs)
                  <div class="well m-3">
                     @if($blogs->blog_image)
                     <img src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" class="img-responsie" style="max-width: 100%; " >
                     @else
                     <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
                     @endif
                     <div class="mt-2">
                        <strong>{{$blogs->heading}}</strong>
                     </div>
                     <?php $users= DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
                     <div class="row" style="display:inline-block">
                        <span > {{$users->user_name}}</span>
                        <span>{{$day}}</span>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </section>
      </div>
      <div class="col-md-4">
         <div class="mt-5">
            <h1>Urdu</h1>
         </div>
         <?php $popblogs = DB::table('blogs')->get();?>
         @foreach($popblogs as $blogs)
         <div class="row">
            <div class="well mt-3">
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
   </section>
   </div>
   </div>
   <div class="container">
      <div class="row">
         <h1>Tips and Advices</h1>
         <div class="row mt-3">
            <?php $popblogs = DB::table('blogs')->where('blogs_categories_id','=',6)->get();?>
            @foreach($popblogs as $blogs)
            <div class="col-md-3 m-5">
               <div class="card border-0" style="width:200px">
                  @if($blogs->blog_image)
                  <img class="card-img-top" src="{{asset('uploads/blogs/'.$blogs->blog_image)}}"  style="width:100%">
                  @else
                  <img class="card-img-top" src="{{asset('uploads/make/default-image.jpg')}}"  style="width:100%">
                  @endif
                  <?php $users= DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
                  <div class="card-body">
                     <h4 class="card-title">@if($users)  {{$users->user_name}}@endif</h4>
                     <p class="card-text">{{$blogs->heading}}</p>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
      </div>
   </div>
</section>
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
@endsection