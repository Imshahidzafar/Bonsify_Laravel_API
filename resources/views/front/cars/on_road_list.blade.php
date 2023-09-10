@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container mt-5">
  
   @foreach($price as $result)
    <table class="table table-striped table-bordered on-road-price nomargin mt20">
        <thead>
          <tr>
            <th colspan="2"><strong>Vehicle Details</strong></th>
          </tr>
        </thead>
        <tbody>
              <tr>
                <td>Manufacturer</td>
                <td><?php  $make = DB::Table('car_make')->where('car_make_id','=',$result->car_make_id)->first(); ?> {{$make->name}} </td>
              </tr>
              <tr>
                <td>Model</td>
                <td><?php  $model = DB::Table('car_model')->where('car_model_id','=',$result->car_model_id)->first(); ?> {{$model->name}} </td>
              </tr>
              <tr>
                <td>Version</td>
                <td><?php  $variation = DB::Table('car_variation')->where('car_variation_id','=',$result->car_variation_id)->first(); ?> {{$variation->name}}</td>
              </tr>
              <tr>
                <td>Ex-Factory Price</td>
               <!--  -->
              </tr>
        </tbody>
          <tr>
            <th colspan="2"><strong>Price</strong></th>
          </tr>
        </thead>
        <tbody>
              <tr>
                <td class="tl"><strong class="generic-green">Total Price Filer</strong></td>
                <td class="generic-green tl"><strong>PKR 4,033,175</strong>
                </td>
              </tr>            
              <tr>
                <td class="tl"><strong class="generic-green">Total Price Non Filer</strong></td>
                <td class="generic-green tl"><strong>PKR 4,116,050</strong>
                </td>
              </tr>            
        </tbody>
      </table>
      @endforeach
</div>
@endsection