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
        <h3 class="title-hero">Email Listing <a href="{{url('/backoffice/post_template')}}" class="btn btn-sm btn-primary" title=""
                style="float: right;margin-bottom: 10px;">Add EmailTemplate</a></h3>
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
                id="myTable">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                            <th>type</th>
                            <th>subject</th>
                            <th>body</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key+1}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ url('/backoffice/view_templates/' . $items->email_templates_id) }}">View</a></li>
                                        <li><a href="{{ url('/backoffice/edit_template/' . $items->email_templates_id) }}">Edit</a></li>
                                        <li><a href="{{ url('/backoffice/delete_templates/' . $items->email_templates_id) }}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>{{ @$items->type }}</td>
                           
                            <td>{{ @$items->subject }}</td>
                            <td>{!! @$items->body !!}</td>
                           
                           
                            
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function change_my_image(abc) {
            // alert();
            var input = abc;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // alert(e.target.result);
                    $('#upload_image').css('background', "url('" + e.target.result + "') no-repeat center center");
                    $('#upload_image').css('background-size', 'cover');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#upload_image").click(function() {
            $("#my_file").click();
        });
</script>
<script type="text/javascript">
    $(document).ready(function() {
            $('#categoryfetch').change(function() {
                var cat_id = $('#categoryfetch option:selected').val();
                $.ajax({
                    url: "{{ url('/fetchsubcategories') }}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'cat_id': cat_id
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        if (result != "") {
                            $('#subcategory').show();
                            $('#sub_category').html(result);
                        } else {
                            $('#subcategory').hide();
                        }
                    }
                });
            });
        });
</script>
<script type="text/javascript">
</script>
@endsection