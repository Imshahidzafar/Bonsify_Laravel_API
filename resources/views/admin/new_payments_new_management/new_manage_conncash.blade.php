@extends('layout.list_master')

@section('content')

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
                    @section('titleBar')
                        <span class="ml-2">Conncash</span>
                            @endsection
						
						{{-- <li class="breadcrumb-item active"><a href="javascript:void(0)">Datatable</a></li> --}}
					</ol>
                </div>
                <!-- row -->
<?php  $categoriesModel = DB::table('categories')->where('status', 'Active')->get();    ?>
<?php  $usersModel = DB::table('users')->where('status', 'Active')->get();    ?>

                <div class="row">
                    <div class="col-12">
                                            <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title">Conncash</h4> -->
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
                             <th>Conncash ID</th>
                            <th>User ID</th>
                            <th>Conncash</th>
                            <th>Conncash Source</th>
                            <th>Total Conncash</th>

                        </tr>

                        </tr>
                                        </thead>
                                        <tbody>
                                         @foreach ($allconncash as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div>
                                    <button id="{{ $items->conncash_id  }}" onclick="editItem('{{json_encode($items)}}')" type="button" class="btn btn-success">Edit</button>
                              
                                </div>
                            </td>
                            <td>{{ $items->conncash_id }}</td>
                            <td>{{ $items->users_id }}</td>
                            <td>{{ $items->conncash }}</td>
                            <td>{{ $items->conncash_source }}</td>
                            <?php  $items->userTotalConncash = DB::table('users')->where('users_id', $items->users_id)->where('status', 'Active')->get();    ?>

                            <td>{{ $items->userTotalConncash[0]->total_conncash }}</td>

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