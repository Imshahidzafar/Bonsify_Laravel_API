<?php
namespace App\Http\Controllers;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use File;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Arr;
use \stdClass;
use RtcTokenBuilder;
use RtmTokenBuilder;







class LiveStreamingController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;



 /////// CREATE HOST ROOM API //////////
 public function create_host_room(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->eventPostId) && isset($data->liveDate) && isset($data->liveEndDate) && isset($data->liveStartTime)  && isset($data->liveEndTime) && isset($data->channelName) && isset($data->description) && isset($data->userLat) && isset($data->userLong) ){

        $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
        $user_timezone = $response["timeZoneId"];

//Date time Validation
        $event_post = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where([ ['event_posts.event_post_id', $data->eventPostId], ['event_posts.status', 'Active'] ])->first();


        $LiveEndDateTime = $data->liveEndDate . " " . $data->liveEndTime;
        $LiveEndTimeStamp = strtotime($LiveEndDateTime);
        $eventEndDateTime = $event_post->event_end_date . " " . $event_post->event_end_time;
        $eventEndTimeStamp = strtotime($eventEndDateTime);


        if ($eventEndTimeStamp < $LiveEndTimeStamp) {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "You Cannot Create Room After Event End Date Time";


            return response()
                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
        }








            $checkAlreadyCreated = DB::table('host_rooms')->where([ ['users_id', $data->usersId],
                                                                ['event_post_id', $data->eventPostId],
                                                                ['status', 'Active']])
//                                                        ->whereDate('live_date', date('Y-m-d', strtotime($data->liveDate) ))
                                                        ->count();


        if($checkAlreadyCreated != 0) {

            $activeRoom = DB::table('host_rooms')->where([['users_id', $data->usersId],
                ['event_post_id', $data->eventPostId],
                ['status', 'Active']])
                ->first();

            $event_post = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where([ ['event_posts.event_post_id', $data->eventPostId], ['event_posts.status', 'Active'] ])->first();


            $event_timezone = $event_post->timezone;
            date_default_timezone_set($event_timezone);
            $eventDate = date('Y-m-d H:i:s');
            $eventTimeSpam = strtotime($eventDate);

            date_default_timezone_set($user_timezone);
            $userDate = date('Y-m-d H:i:s');
            $userTimeSpam = strtotime($userDate);

            $diff =$eventTimeSpam - $userTimeSpam;
            $eventTimeSpam = $eventTimeSpam - $diff;

            date_default_timezone_set($user_timezone);
            $currentDate = date('Y-m-d H:i:s');
            $currentTimeSpam = strtotime($currentDate);

            date_default_timezone_set($event_timezone);
            $eventSaleStartDateTime = $activeRoom->live_end_date . " " . $activeRoom->live_end_time;
            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
            $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;

//            // add by nabeel
//            $currentDate = date('Y-m-d H:i:s');
//            $currentTimeSpam = strtotime($currentDate);
//            $eventSaleStartDateTime = $activeRoom->live_end_date . " " . $activeRoom->live_end_time;
//            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

            if ($currentTimeSpam < $eventSaleStartDateTimeSpam) {
            } else {
                $activeRoom = DB::table('host_rooms')->where([['users_id', $data->usersId],
                    ['event_post_id', $data->eventPostId],
                    ['status', 'Active']]);

                DB::table('host_rooms')->where([['users_id', $data->usersId],
                    ['event_post_id', $data->eventPostId],
                    ['status', 'Active']])->update(['status' => 'Ended']);
            }

        }


        $checkAlreadyCreated = DB::table('host_rooms')->where([ ['users_id', $data->usersId],
            ['event_post_id', $data->eventPostId],
            ['status', 'Active']])
//                                                        ->whereDate('live_date', date('Y-m-d', strtotime($data->liveDate) ))
            ->count();



        if($checkAlreadyCreated == 0){

            $hostRoomDetails = array(
                    'users_id' => $data->usersId,
                    'event_post_id' => $data->eventPostId,
                    'live_date' => date('Y-m-d', strtotime($data->liveDate)),
                    'live_end_date' => date('Y-m-d', strtotime($data->liveEndDate)),
                    'live_start_time' => date('H:i:s', strtotime($data->liveStartTime)),
                    'live_end_time' =>  date('H:i:s', strtotime($data->liveEndTime)),
                    'description' =>  $data->description,
                    'channel_name' =>  $data->channelName,
                    'role' =>  1,
                    'is_live_streaming_started' =>  'False',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status'=> 'Active'
                );
                $insertQuery = DB::table('host_rooms')->insert($hostRoomDetails);














////                send notification to user followers
//                $followed_users =  DB::table('followers')->where('following_to_user', $data->usersId)->get();
//
//                foreach ($followed_users as $followed_user){
//
//                    $senderDetails=  DB::table('users')->where('users_id',$followed_user->following_to_user)->first();
//                    $receiverDetails= DB::table('users')->where('users_id',$followed_user->followed_by_user)->first();
//
//                    if($senderDetails->users_id != $receiverDetails->users_id){
//                        $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' will go live at '.date('H:i:s', strtotime($data->liveStartTime)), 'RoomCreated');
//
//
//                        if($notificationResponse){
//                            $notificationData = array(
//                                'sender_users_id' => $senderDetails-> users_id,
//                                'receiver_users_id' => $receiverDetails-> users_id,
//                                'notification_type' => 'RoomCreated',
//                                'message' => $senderDetails->user_name.' will go live at '.date('H:i:s', strtotime($data->liveStartTime)),
//                                'event_post_id' => $data->eventPostId,
//                                'datetime' => date('Y-m-d H:i:s'),
//                                'status' => 'Unread'
//                            );
//                            $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
//                        }
//                    }
//                }







//                insert description as comment
                $encodedComment = json_encode($data->description);
//                if($data->commentType == 'comment'){
                    $commentData = array(
                        'event_post_id' => $data->eventPostId,
                        'users_id' => $data->usersId,
                        'comment' => $encodedComment,
                        'comment_type' => "comment",
                        'mentioned_user' => (isset($data->mentionedUserId) ? $data->mentionedUserId : null),
                        'total_likes' => '0',
                        'Live_Status' => '1',
                        'commented_datetime' => date('Y-m-d H:i:s'),
                        'status' => 'Active',
                    );
//                }
                $commentQuery = DB::table('event_comments')->insert($commentData);


                if($insertQuery){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Host room created successfully';
                }
            }  else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "You have already created room with the same details";
            }

        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


  /////// GET RTC TOKENS //////////
  public function get_rtc_token(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->channelName)){

            $tokenDetails = DB::table('host_channel_tokens')->where('channel_name', $data->channelName)->first();

                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $tokenDetails->token;
        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }

 // RTC LIVE STREAM
 public function start_live_stream(){


    $response = array();
    $data = json_decode(file_get_contents("php://input"));



    require_once('vendor/agora_src/RtcTokenBuilder.php');
    require_once('vendor/agora_src/RtmTokenBuilder.php');



    if(isset($data->channelName) && isset($data->hostRoomId) && isset($data->roleId) && isset($data->uId)  && isset($data->userName) && isset($data->expireTimeInSeconds)){
        try{

//            $appID = env('AGORA_APP_ID');
//            $appCertificate = env('AGORA_APP_CERTIFICATE');

//            Now we are directly passing Agora credentials
            $appID = "ff6f3b43c26441df8457f3645afd465a";
            $appCertificate = "67a9669fc0054f0299d74fef5573cde0";

            $channelName = $data->channelName;
            $uid = $data->uId;
            $role = $data->roleId;

            $expireTimeInSeconds = 3600;
            $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
            $privilegeExpiredTsRtc = $currentTimestamp + $expireTimeInSeconds;

                if($data->roleId == 1){
                    DB::table('host_rooms')->where('host_room_id', $data->hostRoomId)->update(['is_live_streaming_started' => 'True']);


                    $rtcToken = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTsRtc);

                    //RTM CASE
                    $role = RtmTokenBuilder::RoleRtmUser;
                    $expireTimeInSeconds = $data->expireTimeInSeconds;
                    $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
                    $privilegeExpiredTsRtm= $currentTimestamp + $expireTimeInSeconds;


                    $rtmToken = RtmTokenBuilder::buildToken($appID, $appCertificate, $data->userName, "1", $privilegeExpiredTsRtm);


                    $tokenData = array(
                        'channel_name' => $data->channelName,
                        'rtc_token' => $rtcToken,
                        'rtm_token' => $rtmToken,
                        'u_id' => $uid,
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    DB::table('host_channel_tokens')->insert($tokenData);





                    //                send notification to user followers
                    $event_id = DB::table('host_rooms')->where('host_room_id', $data->hostRoomId)->pluck("event_post_id");

                    $followed_users =  DB::table('followers')->where('following_to_user', $data->uId)->get();

                    foreach ($followed_users as $followed_user){

                        $senderDetails=  DB::table('users')->where('users_id',$followed_user->following_to_user)->first();
                        $receiverDetails= DB::table('users')->where('users_id',$followed_user->followed_by_user)->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->first_name.' is live now', 'LiveStreamStarted');


                            if($notificationResponse){
                                $notificationData = array(
                                    'sender_users_id' => $senderDetails-> users_id,
                                    'receiver_users_id' => $receiverDetails-> users_id,
                                    'notification_type' => 'LiveStreamStarted',
                                    'message' => 'is live now',
                                    'event_post_id' => $event_id[0],
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'status' => 'Unread'
                                );
                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                            }
                        }
                    }


                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Live streaming started successfully';
                    $response["rtc_token"] = $rtcToken;
                    $response["rtm_token"] = $rtmToken;

                }else{
                    $hostRoomDetails = DB::table('host_rooms')->where('host_room_id', $data->hostRoomId)->first();
                    //check already started flag
                    if($hostRoomDetails->is_live_streaming_started == 'True'){
                            $rtcToken = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTsRtc);

                            //RTM CASE
                            $role = RtmTokenBuilder::RoleRtmUser;
                            $expireTimeInSeconds = $data->expireTimeInSeconds;
                            $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
                            $privilegeExpiredTsRtm= $currentTimestamp + $expireTimeInSeconds;


                            $rtmToken = RtmTokenBuilder::buildToken($appID, $appCertificate, $data->userName, "1", $privilegeExpiredTsRtm);


                            $tokenData = array(
                                'channel_name' => $data->channelName,
                                'rtc_token' => $rtcToken,
                                'rtm_token' => $rtmToken,
                                'u_id' => $uid,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            DB::table('host_channel_tokens')->insert($tokenData);


                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = 'Live streaming started successfully';
                            $response["rtc_token"] = $rtcToken;
                            $response["rtm_token"] = $rtmToken;
                    }else{
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["data"] = 'The host meeting is not started yet';
                        $response["rtc_token"] = null;
                        $response["rtm_token"] = null;
                    }

                }


        }catch (\Exception $ex) {
        return $ex->getMessage().' -- exception error occured';
             }

    }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
        $response["rtc_token"] = "";
        $response["rtm_token"] = "";    }


    return response()
            ->json(array('status' => $response["status"], 'rtc_token' => $response["rtc_token"],'rtm_token' => $response["rtm_token"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


 // END LIVE STREAM EXPLICITLY
 public function end_live_stream_explicitly(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->hostRoomId)){
            DB::table('host_rooms')->where('host_room_id', $data->hostRoomId)->update(['status' => 'Ended']);


//            Delete Description Comment Of The Room
        $room = DB::table('host_rooms')->where('host_room_id', $data->hostRoomId)->first();
        $commentQuery = DB::table('event_comments')->where([ ["Live_Status" , 1], ["event_post_id" , $room->event_post_id] ]);
        $commentQuery->delete();

        $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = 'Live streaming ended successfully';

    }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
       }


    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


  // END LIVE STREAM CRON JOB
  public function end_live_stream_cron(){
            DB::table('test_cron')->insert(array('value' => 'Testing cron', 'created_at' => date('Y-m-d H:i:s') ));

       /*   $allRooms =  DB::table('host_rooms')->where('status', 'Active')->get();
         foreach($allRooms as $k){
             $currentTime = date('H:i:s');
             $currentDate = date('Y-m-d');
                if($currentDate == date('Y-m-d', strtotime($k->live_date)) && strtotime($currentTime)  > strtotime($k->live_end_time)){
                        DB::table('host_rooms')->where('host_room_id', $k->host_room_id)->update(['status' => 'Ended']);
                }
         } */
 }



 /*
 // RTM LIVE STREAM
 public function start_rtm_stream(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));
    require_once('vendor/agora_src/RtmTokenBuilder.php');

    if(isset($data->user)){
        try{
            $appID = env('AGORA_APP_ID');
            $appCertificate = env('AGORA_APP_CERTIFICATE');
            $user = $data->user;
            $role = RtmTokenBuilder::RoleRtmUser;
            $expireTimeInSeconds = 3600;
            $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
            $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;


            $token = RtmTokenBuilder::buildToken($appID, $appCertificate, $user, $role, $privilegeExpiredTs,);

            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = 'Live streaming started successfully';
            $response["token"] = $token;


        }catch (\Exception $ex) {
        return $ex->getMessage().' -- exception error occured';
          }

    }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
        $response["token"] = null;
    }


    return response()
            ->json(array('status' => $response["status"], 'token' => $response["token"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

        }
 */



}


