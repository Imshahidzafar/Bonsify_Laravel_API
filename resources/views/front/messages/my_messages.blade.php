@extends('commonfront.inner_page')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container" >
   <div class="registration-page registration-panel">

      <div class="well mb40">
         <div class="">
            <div class="row">
               <div class="col-md-12">
                  <table class="table table-striped table-bordered nomargin" id="conversation-listing">
                     <thead>
                        <tr>
                           <th>From</th>
                           <th>Message</th>
                           <th>Date</th>
                        </tr>
                     </thead>
                     @foreach($my_messages as $message)
                     <tbody>
                        <?php $user = DB::table('users')->where('users_id','=',$message->sender_id)->first(); ?>
                        <tr>
                           <td> {{ $user->user_name }}  </td>
                           <td>
                              
                              <a href="{{ url('/chat_messages/'.$message->messages_id)}}"><strong>{!! $message->content !!}</strong></a>
                           </td>
                           <td>
                              {{ $message->date_added }}
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
@endsection