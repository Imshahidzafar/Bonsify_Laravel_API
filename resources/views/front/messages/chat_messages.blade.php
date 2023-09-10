@extends('commonfront.inner_page')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<?php $msg = DB::table('messages')->where('messages_id','=',$message_id)->first();
 $sender = $msg->sender_id;
 $receiver =$msg->receiver_id;
if($sender == session('id')){
   $msg_reciver =$receiver; 
}
else{
   $msg_reciver =$sender;
}
?>
<div class="container" >
   <div class="registration-page registration-panel">
      <div class="well mb40" >
         <div class="">
            <div class="row">
               <div class="col-md-12">
                 <!--  <div class="panel-heading">
                     <div class="pull-right text-error">
                        <a href="/conversations/7871530/block" class="btn btn-link generic-red" data-confirm="Are you sure you want to report this conversation?" title="Report Converstaion">
                           <img alt="Report Conversation" class="fa fa-ban" src="https://wsa3.pakwheels.com/assets/report-311ec4960b81807c72ab368682e24999.png"></a>
                     </div>
                     <h4 class="nomargin"><a href="/used-cars/toyota-aqua-2018-for-sale-in-islamabad-5126236">Toyota Aqua G LED Soft Leather Selection </a></h4>
                     <div class="time-stamp fs10">Aug 25, 2021 10:32 AM</div>
                  </div> -->
                  <div class="panel-body conversation">
                     <?php $messages = DB::select( DB::raw("SELECT * FROM messages WHERE (sender_id = '$sender' and receiver_id='$receiver') or (sender_id='$receiver' and receiver_id='$sender')  order by  date_added ASC") );?>
                     @foreach($messages as $message)
                     <div id="scroll-able-messages" class="messages" style="width: auto; height:100px; overflow-y: hidden; outline: none;" tabindex="5000">
                        <div>
                         <?php if($message->receiver_id == session('id') ){?>
                           <div class="bubble" style="float:left; background-color:#ffc107 ">
                           
                                 <span > {{ $message->content}}</span>
                             
                              
                              <br>
                              <p><small>{{$message->date_added}}</small></p>
                           </div>
                           <?php }else{?>
                              <div class="bubble"style="float:right" >
                           
                                 <span > {{ $message->content}}</span>
                             
                              
                              <br>
                              <p><small>{{$message->date_added}}</small></p>
                           </div>
                        <?php }?>

                           <div class="clearfix"></div>
                        </div>
                        <div>
                          
                           <div class="clearfix"></div>
                        </div>
                     </div>
                     @endforeach
                     @if (session('errors'))
                       <div class="alert alert-danger">
                         {{ session('errors') }}
                       </div> 
                       @endif
                     <form accept-charset="UTF-8" action="{{ url('/send_message/'.session('id').'/'.$msg_reciver)}}" data-parsley-validate="" method="post" style="margin-top:10px;margin-bottom: 0;" novalidate="">
                        {{ csrf_field() }}
                        
                         

                        <textarea class="sellerTextArea" placeholder="Your Message" style="width: 100%;"  name="message" rows="4" maxlength="250"></textarea>
                       
                        
                        <button type="submit" class="btn btn-primary" >Submit</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection