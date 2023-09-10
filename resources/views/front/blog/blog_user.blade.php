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
<div class="container">
   <div class="row mt-5">
      <div class="">
         <h2>Authors</h2>
      </div>
      <?php $user = DB::table('users')->where('users_id','=',$user_id)->first();?>
      <div>
         @if($user)
         <a>
            <img  class="rounded-circle" src="{{asset('uploads/users/'.$user->user_image)}}" height="50px" width="50px">
            @else
            <img src="{{asset('uploads/make/default-image.jpg')}}" height="50px" width="50px">
            @endif
            {{$user->user_name}}
      </div>
      <div>
      </div>
      <div class="col-md-8">
      <div class="well">
      <section>
      @foreach($blog as $blogs)
      <div class="row">
      <div class="well mr-5">
      <div class="col-md-4">
      @if($blogs->blog_image)
      <a href="{{url('/blog/'.$blogs->blogs_id)}}"><img  src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" height="200px" width="200px"></a>
      @else
      <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
      @endif
      </div>
      <div class="row">
      <div class="col-md-8">
      <strong>{{ $blogs->heading}}</strong>
      </div>
      <?php $users =DB::table('users')->where('users_id','=',$blogs->created_by)->first();?>
      <div class="col-md-12 mt-3" style="color:#9b9b9b">
      <div class="row">
      @if($users)
      <strong ><a href="{{url('/blog_search/'.$users->users_id)}}">{{ $users->user_name}}</a></strong>
      @endif
      <?php $time=$blogs->date_added;
         $timestamp = strtotime($time);
         
         $day = date('M-d, y',$timestamp); 
         ?>
      <span >{{ $day}}</span>
      </div>
      <div class="col-md-12 mt-3">
      {!! $blogs->description !!}
      </div>
      </div>
      </div>
      </div>
      </div>
      @endforeach
      </section>
      </div>
      </div>
      <div class="col-md-4">
         <div class="well">
            <div class="mt-3">
               <h1>Urdu</h1>
            </div>
            @foreach($blog as $blogs)
            <div class="row">
               <div class="well">
                  <div class="col-md-4">
                     @if($blogs->blog_image)
                     <img src="{{asset('uploads/blogs/'.$blogs->blog_image)}}" height="50px" width="50px">
                     @else
                     <img src="{{asset('uploads/make/default-image.jpg')}}" height="200px" width="200px">
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
         </div>
      </div>
   </div>
</div>
@endsection