@extends('commonfront.inner_main')
@section('title', 'Dashboard')
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
<div class="remove-section">
   <section>
      <div class="container">
         <div class="linked-fields pos-rel sell-form-main">
            <div class="well">
               <h1>Pending/In Review Ads</h1>
               <!-- USeR LISTING TABLE HERE -->
               <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive-md" id="myTable">
                  <thead>
                     <tr>
                        <th style="width: 55px;">#</th>
                        <th style="width: 55px;">Action</th>
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
                     @foreach ($listings_review as $key => $items)
                     <tr class="odd gradeX">
                        <td>{{ @$key + 1 }}</td>
                        <td>
                           <li class="dropdown">
                              <a href="#" class="btn btn-success" >
                              Action <i class="fa fa-caret-down"></i>
                              </a>
                              <ul class="dropdown-menu mega-navbar columns-1">
                                 <li class="clearfix">
                                    <div class="col-md-12">
                                       <ul class="list-unstyled">
                                          <li class="mb-2">
                                             <a  href="{{ url('/view_listings/' . $items->listings_id) }}">View Post</a>
                                          </li>
                                          </li>
                                          <li class="mb-2">
                                             <a  href="{{ url('/edit_listing/' . $items->listings_id) }}">Edit Post</a>
                                          </li>
                                          </li>
                                          <li><a  href="{{ url('/delete_listings/' . $items->listings_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                          </li>
                                       </ul>
                                    </div>
                                 </li>
                              </ul>
                           </li>
                        </td>
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
               <!-- USeR LISTING TABLE HERE -->
            </div>
            <div class="well">
               <!-- USeR LISTING TABLE HERE -->
               <h1>Active Ads</h1>
               <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive-md" id="example">
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
                        <th style="width: 55px;">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($listings_active as $key => $items)
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
                        <td>
                           <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                 aria-expanded="false">Action <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                 <li><a href="{{ url('/backoffice/view_listings/' . $items->listings_id) }}">View Listing</a></li>
                                 <li><a href="{{ url('/backoffice/editlisting/' . $items->listings_id.'/'.$items->status) }}">Edit Listing</a></li>
                                 <li><a href="{{ url('/backoffice/delete_listings/' . $items->listings_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                 </li>
                              </ul>
                           </div>
                        </td>
                        @endforeach
                  </tbody>
               </table>
               <!-- USeR LISTING TABLE HERE -->
            </div>
         </div>
      </div>
   </section>
</div>
@endsection