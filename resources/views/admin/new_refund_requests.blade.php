@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles mb-n5">
					<ol class="breadcrumb">
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Mange Event Post</a></li> -->	
					</ol>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <div class="welcome-text">
                                    @section('titleBar')
                                        <span class="ml-2">Refund Requests</span>
                                    @endsection
                                    {{-- <p class="mb-0">Validation</p> --}}
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w600">
                                        <thead>
                                           <tr>
                                                <th>#</th>
                                                <th>Event Organizer</th>
                                                <th>Event Title</th>
                                                <th>Event Type</th>
                                                <th>Category</th>
                                                <th>Buyer Name</th>
                                                <th>Total Amount</th>
                                                <th>Refund Request Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($refundRequests as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>

                                                    <?php  $organizer = DB::table('users')->where('users_id', $items->event_organizer_id)->first(); ?>
                                                    <td>{{ $organizer->user_name }}</td>
                        
                                                    <?php  $event_post = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->first(); ?>
                                                    <td>{{ $event_post->title }}</td>

                                                    <?php  $event_type = DB::table('event_type')->where('event_type_id', $event_post->event_type_id)->first(); ?>
                                                    <td>{{ $event_type->event_type }}</td>

                                                    <?php  $event_category = DB::table('categories')->where('event_type_id', $event_post->event_type_id)->first(); ?>
                                                    <td>{{ $event_category->category }}</td>

                                                    <?php  $buyer = DB::table('users')->where('users_id', $items->ticket_buyer_id)->first(); ?>
                                                    <td>{{ $buyer->user_name }}</td>

                                                    <td>{{ $items->total_amount }}</td>
                                
                                                    <?php 
                                                        $temp = explode(' ', $items->datetime);
                                                        $date = \Carbon\Carbon::parse($temp[0])->format('d/m/y');
                                                        $time = \Carbon\Carbon::parse($temp[1])->format('h:m a');
                                                        $datetime =  $date. ' ' .$time;
                                                    ?>
                                                    <td>{{ $datetime }}</td>

                                                    <td>
                                                        @if ($items->status=='Refunded')
                                                        <span class="btn btn-success">Refunded</span>
                                                        @else
                                                        <span class="btn btn-info">Requested</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
							    </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
@endsection