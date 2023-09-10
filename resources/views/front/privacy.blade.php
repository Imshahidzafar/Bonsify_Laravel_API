@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<section>
   <div class="container">
      <ul style="margin-top: 50px;" class="nav nav-tabs" role="navigation" itemscope="" itemtype="http://schema.org/SiteNavigationElement">
         <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/about')}}">About</a></li>
         <li class="nav-item btn btn-danger"><a class="active text-white" href="{{url('/faqs')}}">FAQ</a></li>
         <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/terms')}}" class="">Terms of Service</a></li>
         <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/privacy')}}" class="">Privacy</a></li>
      </ul>
      <div class="well p40">
         <div >
            <h1 class="text-center">{{$privacy->heading}}</h1>
            <p> {!! $privacy->description!!}</p>
         </div>
      </div>
   </div>
</section>
@endsection