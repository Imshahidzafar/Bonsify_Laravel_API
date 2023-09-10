@extends('commonfront.inner_page')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<style>

.scrollit {
    overflow:scroll;
    height:100px;
}
</style>
<div class="container" >
  <div class="registration-page registration-panel">
     <div class="well mb40" >
      <div class=""><div class="row">
  <div class="col-md-12">

      <table class="table table-striped table-bordered table-responsive scrollit" id="myTable overflow-hidden" style=" height:100px; ">
        <thead>
          <tr>
            
            <th>Notifiction</th>
            <th>Date</th>
          </tr>
        </thead>
         @foreach($notifications as $notifi)
        <tbody>
        
            <tr>
              
              <td>
                <strong>{!! $notifi->content !!}</strong>
              </td>
              <td>
                {{ $notifi->date_added }}
              </td>
            </tr>

        </tbody>
         @endforeach
      </table>
      
  </div>
</div>
</div> 
      
     </div>
    </div>  
</div>
<script>
  $(document).ready( function () {
    $('#myTable').DataTable();
} );
  </script>
          @endsection