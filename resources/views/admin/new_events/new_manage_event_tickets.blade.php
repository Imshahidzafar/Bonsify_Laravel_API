@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles mb-n5">
					<ol class="breadcrumb">
						<!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Events Ticket</a></li> -->
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">
                            @section('titleBar')
                        <span class="ml-2">Events Ticket</span>
                            @endsection
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                 <br>
                @if(session('success'))
                <div class="alert alert-success">
                   {{ session('success') }}
                </div>
                @endif
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                     <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>Ticket ID</th>
                             <th>Ticket</th>
                            <th>Event Post ID</th>
                            <th>Event Title</th>
                            <th>Ticket Quantity</th>
                            <th>Total Price</th>
                            <th>Closing Date</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($event_tickets as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->ticket_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->ticket_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></button>                                      
</div>
                                </div>
                            </td>
                            <td>{{ $items->ticket_id }}</td>
                            <td>{{ $items->ticket }}</td>

                            <td>{{ $items->event_post_id }}</td>
                            <?php  $items->title = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->get();    ?>
                            <td>{{ $items->title[0]->title }}</td>

                            <td>{{ $items->total_quantity }}</td>
                            <td>{{ $items->total_quantity * $items->price }}</td>
                            <td>
                                @if ($items->closing_date != '0')
                                  {{ \Carbon\Carbon::parse($items->closing_date)->format('d/m/y h:m a')}}
                                @else
                                {{ ''}}
                                @endif
                            </td>

                            <td>
                                @if ($items->status=='Active')
                                 <span class="btn btn-success">Active</span>
                                 
                                @elseif ($items->status=='Deleted')
                                <span class="btn btn-danger">Deleted</span>
                                @else 
                                   <span class="btn btn-warning">In Active</span>
                                @endif
                            </td>
                        
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