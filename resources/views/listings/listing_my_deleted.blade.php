@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $cities=DB::table('cities')->get(); ?>
<?php $car_make=DB::table('car_make')->get(); ?>
<?php $car_model=DB::table('car_model')->get(); ?>
<?php $car_variation=DB::table('car_variation')->get(); ?>
<?php $result=DB::table('countries')->get(); ?>
<?php $states=DB::table('states')->get(); ?>
<?php $colors=DB::table('car_colors')->get(); ?>
<?php $years=DB::table('car_years')->get(); ?>
<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">Deleted Listings</h3>
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
                    id="datatable-example">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                            <td>City</td>
                            
                            <td>Make</td>
                            <td>Model</td>
                            <td>Variation</td>
                            <td>Registered</td>
                            <td>Exterior Color</td>
                            <td>Mileage (km)</td>
                            <td>Price</td>
                            <td>Description</td>
                            <td>Mobile</td>
                            <td>Date Added</td>
                           <!--  <td>Date Modified</td> -->
                            <th>Status</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listings as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ @$key + 1 }}</td>
                            
                           
                            <td>
                                @foreach ($cities as $category)
                                    @if ($category->id == @$items->city_id)
                                      {{ @$category->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                
                                 @foreach ($car_make as $category)
                                    @if ($category->car_make_id == @$items->car_make_id)
                                      {{ @$category->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                 @foreach ($car_model as $category)
                                    @if ($category->car_model_id == @$items->car_model_id)
                                      {{ @$category->name }}
                                    @endif
                                @endforeach


                            </td>
                            <td>
                                @foreach ($car_variation as $category)
                                    @if ($category->car_variation_id == @$items->car_variation_id)
                                      {{ @$category->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ @$items->register }}</td>
                            <td>
                                @foreach ($colors as $category)
                                    @if ($category->colors_id == @$items->color_id)
                                      {{ @$category->name }}
                                    @endif
                                @endforeach
                                


                            </td>
                            <td>{{ @$items->km }}</td>
                            <td>{{ @$items->price }}</td>
                            <td>{{ @$items->description }}</td>
                            <td>{{ @$items->mobile }}</td>
                            <td>{{ @$items->date_added}}</td>
                           <!--  <td>{{ @$items->date_modified}}</td> -->
                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span> 
                                @elseif($items->status=='Inactive')
                                <span class="btn btn-primary">In Active</span>
                                 @elseif($items->status=='Pending')
                                <span class="btn btn-primary">Pending</span>
                                 @elseif($items->status=='Inreview')
                                <span class="btn btn-info">In-Review</span>
                                @elseif($items->status=='Expired')
                                <span class="btn btn-warning">Expired</span>
                                @elseif($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>
                                
                                @endif
                            </td>
                           
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>
@endsection