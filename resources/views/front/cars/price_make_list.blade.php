@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $make = DB::table('car_make')->get();?>
<div class="container mt-5">
<ul class="logo-listing logo-listing-price row clearfix text-center">
   @foreach($make as $makes)

          <li class="col-md-2">
            <a href="{{url('/pricelist/'.$makes->car_make_id)}}" class="show" title="{{$makes->name}} Car Prices">
               @if($makes->image)
              <img alt="{{$makes->name}} Car Prices in Pakistan" src="{{asset('uploads/make/'.$makes->image)}}" title="{{$makes->name}} Car Prices in Pakistan">
              @else
              <img alt="{{$makes->name}}Car Prices in Pakistan" src="{{asset('uploads/make/default-image.jpg')}}" title="{{$makes->name}} Car Prices in Pakistan">
              @endif
            </a>
           </li>
   @endforeach        
     
 </ul>
 </div>      
@endsection