@extends('commonfront.inner_main')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container">
  
   @foreach($results as $price)
   <h2 class="mt-5">
                  {{$price->name}}
                   <?php $model =DB::table('car_model')->where('car_model_id','=',$price->car_model_id)->first(); if($model){echo $model->name;}?> 
                   <?php $year = DB::Table('car_years')->where('car_years_id','=',$price->car_years_id)->first(); if($year){echo $year->year;} ?>
                    Prices
                  

                                    
                </h2>
   <table class="table table-striped table-bordered orp-event-pricelist nomargin">
                  <thead>
                    <tr>
                      <th>Car Version</th>
                      <th>Ex-Factory Price</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="version-name"><a href="/new-cars/suzuki/alto/vx-14/">{{$price->name}} <?php $model =DB::table('car_model')->where('car_model_id','=',$price->car_model_id)->first(); if($model){echo $model->name;}?>  </a></td>
                      <td class="version-price generic-green">
                        {{ $price->price}}
                        <a href="{{ url('/on_road/'.$price->car_make_id.'/'.$price->car_model_id.'/'.$price->car_variation_id) }}" class="show fs12" event-label="Suzuki Alto VX">Get On Road Price</a>
                      </td>
                      
                    </tr>
                    
                  </tbody>
                </table>
                @endforeach
</div>
@endsection