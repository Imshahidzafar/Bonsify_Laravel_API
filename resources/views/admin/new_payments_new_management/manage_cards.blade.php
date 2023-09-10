@extends('layout.list_master')

@section('content')

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
                    @section('titleBar')
                        <span class="ml-2">Card</span>
                            @endsection
						
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title">Card</h4> -->
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <br>
                @if(session('success'))
                <div class="alert alert-success">
                   {{ session('success') }}
                </div>
                @endif
                                    <table id="example" class="display min-w850">
                                        <thead>
                                           <tr>
                            <th style="width: 55px;">#</th>
                             <th style="width: 55px;">Action</th>
                             <th>Card ID</th>
                            <th>User ID</th>
                            <th>Card Holder Name</th>
                            <th>Card Number</th>
                            <th>CVV</th>
                            <th>Expiry Months</th>
                            <th>Expiry Years</th>
                            <th>Status</th>

                        </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($cards as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button"><a id="{{ $items->card_id  }}" onclick="editItem('{{json_encode($items)}}')" >Edit </a></button>                                      
                                        <button class="dropdown-item" type="button"><a id="{{ $items->card_id  }}" onclick="deleteItem('{{json_encode($items)}}')" >Delete </a></button>                                      
</div>
                                </div>
                            </td>
                            <td>{{ $items->card_id }}</td>
                            <td>{{ $items->users_id }}</td>
                            <td>{{ $items->card_holder_name }}</td>
                            <td>{{ $items->card_number }}</td>
                            <td>{{ $items->cvv }}</td>
                            <td>{{ $items->expiry_months }}</td>
                            <td>{{ $items->expiry_years }}</td>


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