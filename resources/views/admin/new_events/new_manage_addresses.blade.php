@extends('layout.list_master')

@section('content')
<style>

.btn-light{
  padding-left:10px;
}
</style>

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Event Address</a></li>
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Event Address</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table dt-responsive nowrap display min-w850">
                                        <thead>
                                           <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>Address ID</th>
                             <th>Event ID</th>
                             <th>Event Title</th>
                            <th>Full Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Status</th>
                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($addresses as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div>
                                    <button id="{{ $items->address_id  }}" onclick="editItem('{{json_encode($items)}}')" type="button" class="btn btn-success">Edit</button>
                              
                                </div>
                            </td>
                            <td>{{ $items->address_id }}</td>
                            <td>{{ $items->event_post_id }}</td>

                            <?php  $items->title = DB::table('event_posts')->where('event_post_id', $items->event_post_id)->get();    ?>
                            <td>{{ $items->title[0]->title }}</td>

                            <td>{{ $items->full_address }}</td>
                            <td>{{ $items->city }}</td>
                            <td>{{ $items->state }}</td>
                            <td>{{ $items->zip }}</td>
                            
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