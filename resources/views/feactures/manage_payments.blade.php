@extends('common.main')
@section('title', 'Brands')
@section('content')
@inject('helpers', 'App\Classes\Helpers')

<div class="panel">
    <div class="panel-body">
        <div style="display: flex;justify-content: space-between;">
            <div>
             <h3 class="title-hero">Manage Payments</h3>
            </div>
        </div>
       
        <div class="example-box-wrapper">
            <div class="example-box-wrapper">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive-md"
                    id="myTable">
                    <thead>
                        <tr>
                            <th style="width: 55px;">#</th>
                            <th style="width: 55px;">Action</th>
                            <th>User Name</th>         
                            <th>Package Name</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Screen Shot</th>
                            <th>Date Added</th>
                            <th>Admin Response date</th>
                            <th>Status</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $items)
                        <tr class="odd gradeX">
                            <td>{{ @$key + 1 }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" class="" data-target="#exampleModal_view_{{ @$items->payments_id }}" >View</a></li>                                     
                                        <li><a href="{{ url('/backoffice/approve_payment/' . $items->payments_id.'/' . $items->user_id.'/' . $items->user_name) }}" onclick="return confirm('Are you sure you want to approve this payment?');">Approve</a>
                                        <li><a href="{{ url('/backoffice/reject_payment/' . $items->payments_id.'/' . $items->user_id.'/' . $items->user_name) }}"  onclick="return confirm('Are you sure you want to reject this payment?');">Reject</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>                 
                            <td>{{ @$items->user_name }}</td>             
                            <td>{{ @$items->title }}</td>  
                            <td>{{ @$items->amount }}</td>  
                            <td>{{ @$items->name }}</td>  
                            <td> 
                              @if ($items->payment_image!='')
                              <img style="width: 100px;height: 100px;" id="real_payment_image_{{ @$items->payments_id }}" src="{{asset('uploads/payements/'.$items->payment_image)}}">
                              @else
                              <img style="width: 100px;height: 100px;" id="real_payment_image_{{ @$items->payments_id }}" src="{{asset('uploads/make/default-image.jpg')}}">
                              @endif
                             
                            </td>                                     
                            <td>{{ @$items->date_added}}</td>
                            <td>{{ @$items->date_modified}}</td>
                        <!--  <td>{{ @$items->date_modified}}</td> -->
                        <td>
                                @if ($items->status=='Approved')
                                <span class="btn btn-success">Approved</span> 
                                @elseif ($items->status=='Active')
                                <span class="btn btn-success">Active</span> 
                                @elseif($items->status=='Inactive')
                                <span class="btn btn-primary">In Active</span>
                                @elseif($items->status=='Pending')
                                <span class="btn btn-primary">Pending</span>
                                @elseif($items->status=='Inreview')
                                <span class="btn btn-info">In-Review</span>
                                @elseif($items->status=='Expired')
                                <span class="btn btn-warning">Expired</span>
                                @elseif($items->status=='Cancelled')
                                <span class="btn btn-danger">Cancelled</span>
                                
                                @endif
                            </td>
                        
                            @endforeach


                            
                    </tbody>
                </table>
            </div>
                </div>
            </div>
        </div>
        @foreach ($payments as $key => $itemsss)
        <div class="modal fade" id="exampleModal_view_{{ @$itemsss->payments_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelss" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="margin-top: 188px;">
                    
                    <div class="modal-body" style="display: flex;
                    flex-direction: column;
                    grid-gap: 29px;">
                            
                                <div class="row">
                                    <label class="col-sm-6 control-label">User Name</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->user_name }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Package Name</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->title }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Payment Method</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->name }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Amount Paid</label>
                                    <div class="col-sm-6">
                                    @if ($itemsss->amount=='')
                                      {{ @$itemsss->amount }}
                                    @elseif ($itemsss->amount=='Active')
                                     <b>N/A</b>
                                     @endif
                                    </div>
                                </div>
        
                                <div class="row">
                                    <label class="col-sm-6 control-label">Image : </label>
                                    <div class="col-sm-6">
                                    @if ($itemsss->payment_image!='')
                                    <a href="{{asset('uploads/payements/'.$itemsss->payment_image)}}" target="_blank">
                                    <img style="width: 100px;height: 100px;" id="real_payment_image_{{ @$itemsss->packages_id }}" src="{{asset('uploads/payements/'.$itemsss->payment_image)}}">
                                    </a>
                                    @else
                                    <img style="width: 100px;height: 100px;" id="real_payment_image_{{ @$itemsss->packages_id }}" src="{{asset('uploads/make/default-image.jpg')}}">
                                    @endif
                                    
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Status :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->status }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Date Added :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->date_added}}
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-6 control-label">Date Modified :</label>
                                    <div class="col-sm-6">
                                    {{ @$itemsss->date_modified}}
                                    </div>
                                </div>
                                
                        
                    </div>
                    </div>
                </div>
         </div>
         @endforeach
<script type="text/javascript">

</script>
@endsection