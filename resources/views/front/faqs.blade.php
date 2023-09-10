@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container">
   <ul style="margin-top: 50px;" class="nav nav-tabs" role="navigation" itemscope="" itemtype="http://schema.org/SiteNavigationElement">
      <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/about')}}">About</a></li>
      <li class="nav-item btn btn-danger"><a class="active text-white" href="{{url('/faqs')}}">FAQ</a></li>
      <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/terms')}}" class="">Terms of Service</a></li>
      <li class="nav-item btn btn-danger"><a class=" text-white" href="{{url('/privacy')}}" class="">Privacy</a></li>
   </ul>
   <div class="well" >
      <div class="contents clearfix body-page">
         <!---->
         @foreach($faqs as $faq)
         <div itemscope="" itemtype="http://schema.org/WebPage">
            <meta itemprop="lastReviewed" content="2016-11-25">
            <div itemprop="mainContentOfPage">
               <p><a name="civilized"></a></p>
               <h1 class="text-center">Faqs</h1>
               <h2><a href="#civilized">{{ $faq->question}}</a></h2>
               <p>{!! $faq->answer !!}</p>
            </div>
         </div>
         @endforeach
         <!---->
         <!---->  
      </div>
   </div>
</div>
@endsection