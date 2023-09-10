
<?php  $image = session('user_image')?> 
<style>
.active{
 border: 1px solid #EAEDEF;
    display: table-cell;  
}

</style>
<div class="container mb-5">
   <div class="user-ads ">
      <div class="well mb40" style="margin-top: 10px;">
         <div class="dashboard-profile clearfix">
            <div class="profile-photo pull-left" style="background-image: url({{asset('uploads/users/'.$image)}});"></div>
            <div class="row">
               <h2>{{ Session::get('user_name')}}</h2>
               <p><?php $time=session('date_added');
               $timestamp = strtotime($time);

               $day = date('M-d, y',$timestamp);   
               ?> Member Since : {{$day}}</p>
               <p class="fs12"><a href="{{ url('/myprofile')}}">Edit Profile</a> | <a href="{{ url('/resetpassword')}}">Change Password</a></p>
            </div>
         </div>
         <ul class="dashboard-nav">
            <li <?php if($page =="my_ads"){?> class="active"<?php } ?>><a href="{{ url('/my_ads')}}"><i class="fa fa-bullhorn"></i> My Ads</a></li>
            <li <?php if($page =="my_save_ads"){?> class="active"<?php } ?>><a href="{{url('/my_save_add')}}"><i class="fa fa-heart"></i> My Saved Ads</a></li>
            
            <li <?php if($page == "notifications"){?> class="active"<?php } ?>><a href="{{ url('/notifications')}}"><i class="fa fa-bell"></i> My Notifications</a></li>
            <li <?php if($page =="my_messages"){?> class="active"<?php } ?>><a href="{{ url('/my_messages')}}"><i class="fa fa-envelope"></i> My Messages</a></li>
            <li <?php if($page =="packages"){?> class="active"<?php } ?>><a href="{{ url('/packages')}}"><i class="fa fa-credit-card"></i> Payment</a></li>
         </ul>
      </div>
   </div>
</div>