@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">Expired Listings</h3>
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
                    id="datatable-example">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                            <td>User</td>
                            <td>Make</td>
                            <td>Model</td>
                            <td>Variation</td>
                            <td>Date Added</td>
                            <td>Date Modified</td>
                            <th>Status</th>
                            <th style="width: 55px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listings as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ @$key + 1 }}</td>
                            <td>{{ @$items->user_id }}</td>
                            <td>{{ @$items->car_make_id }}</td>
                            <td>{{ @$items->car_model_id}}</td>
                            <td>{{ @$items->car_variation_id}}</td>
                            <td>{{ @$items->date_added}}</td>
                            <td>{{ @$items->date_modified}}</td>
                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span> 
                                @else
                                <span class="btn btn-danger">In Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ url('/backoffice/edit_listing/' . $items->listings_abundant_id) }}">Edit Listing</a></li>
                                        <li><a href="{{ url('/backoffice/delete_listings/' . $items->listings_abundant_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                        </li>
                                    </ul>
                                </div>
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