@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style type="text/css">
    .upload-img {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background-position: center center;
        background-size: cover;
    }
</style>
<div class="panel">
    <div class="panel-body">
        <h3 class="title-hero">FAQs List <a href="{{url('/backoffice/add_faqs_listing')}}" class="btn btn-sm btn-primary" title=""
                style="float: right;margin-bottom: 10px;">Add FAQs</a></h3>
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
                    id="datatable-example">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Date Added</th>
                            <th>Date Modified</th>
                            <th>Status</th>
                            <th style="width: 55px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ @$key + 1 }}</td>
                            <td>{{ @$items->question }}</td>
                            <td>{{ @$items->answer }}</td>
                            <td>{{ @$items->date_added }}</td>
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
                                        <li><a href="{{ url('/backoffice/edit_faqs_listing/' . $items->faqs_id) }}">Edit</a></li>
                                        <li><a href="{{ url('/backoffice/delete_faqs_listings/' . $items->faqs_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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