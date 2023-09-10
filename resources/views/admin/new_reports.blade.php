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
                                        <span class="ml-2">Reports</span>
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
                                                <th>Action</th>
                                                <th>Event Organizer</th>
                                                <th>Event Title</th>
                                                <th>Event Type</th>
                                                <th>Category</th>
                                                <th>Reported By</th>
                                                <th>Reporting Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eventReports as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                                <button class="dropdown-item" type="button" value="{{ $items->event_post_id }}">
                                                                    <a  id="{{ $items ->event_post_id  }}_MODEL" data-type="{{json_encode($items)}}" onclick="respondQuery(this.id)" href="javascript:;" data-toggle="modal" data-target="#Modal_respond">
                                                                        <span class="ml-20">Respond</span>
                                                                    </a>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <?php  
                                                      $event_post = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->first();
                                                      $organizer = DB::table('users')->where('users_id', $event_post->users_id)->first();
                                                    ?>
                                                    <td>{{ $organizer->user_name }}</td>
                        
                                                    <td>{{ $event_post->title }}</td>

                                                    <?php  $event_type = DB::table('event_type')->where('event_type_id', $event_post->event_type_id)->first(); ?>
                                                    <td>{{ $event_type->event_type }}</td>

                                                    <?php  $event_category = DB::table('categories')->where('event_type_id', $event_post->event_type_id)->first(); ?>
                                                    <td>{{ $event_category->category }}</td>

                                                    <?php  $reporter = DB::table('users')->where('users_id', $items->users_id)->first(); ?>
                                                    <td>{{ $reporter->user_name }}</td>
                                
                                                    <?php 
                                                        $temp = explode(' ', $items->date_time);
                                                        $date = \Carbon\Carbon::parse($temp[0])->format('d/m/y');
                                                        $time = \Carbon\Carbon::parse($temp[1])->format('h:m a');
                                                        $datetime =  $date. ' ' .$time;
                                                    ?>
                                                    <td>{{ $datetime }}</td>

                                                    <td>
                                                        @if ($items->status=='Requested')
                                                        <span class="btn btn-info">Requested</span>
                                                        @elseif ($items->status=='Reported')
                                                        <span class="btn btn-success">Reported</span>
                                                        @elseif ($items->status=='ReportCanceled')
                                                        <span class="btn btn-warning">ReportCanceled</span>
                                                        @else
                                                        <span class="btn btn-warning">PostInactive</span>
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

        <!-- MODAL RESPOND START -->
        <div class="modal fade" id="Modal_respond" tabindex="-1" role="dialog" aria-labelledby="Modal_respond" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="margin-top: 140px;">
                    <div class="modal-body">
                        <form class="form-horizontal bordered-row" id="itemForm" enctype="multipart/form-data" method="post" action="{{ url('/respond_report') }}">
                            @csrf
                            <input type="hidden" id="event_post_id" name="event_post_id">

                            <div class="p-5 text-center">                
                                <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>

                                <div style="font-size: 20px;">Type email for Report</div><br><br>
                                    <label for="email_subject">Email subject</label>
                                    <input type="text" class="form-control" id="email_subject" name="email_subject" required><br>

                                    <label for="email_message">Email message</label>
                                    <textarea class="form-control" rows="10" id="email_message" name="email_message" required></textarea>
                                </div>

                                <div class="form-group" > 
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-dismiss="modal" class="btn btn-secondary mr-1">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>      
            </div>
        </div> 
        <!-- MODAL RESPOND END -->
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function respondQuery(item){
                var data = $("#"+item).attr("data-type");
                itemJson = JSON.parse(data);
                $("#itemForm input[name='event_post_id']").val(itemJson.event_post_id);    
            }
        </script>
@endsection