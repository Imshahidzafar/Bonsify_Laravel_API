<?php
namespace App\Http\Controllers;
use App\Helpers\Helper;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
//use File;
use Illuminate\Support\Facades\File;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\StripeClient;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Arr;
use \stdClass;

class EventApiController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;
    //================ EVENT POSTS ===================//

    /*** DISPLAY TAGS ***/
    public function tags(){
        $count =   DB::table('tags')->get()->count();
        $tags =   DB::table('tags')->get();
        if ($count > 0) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] =  $tags;
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Tags does not exists.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /*** DISPLAY TAGS ***/

    /*** USER CATEGORIES ***/
    public  function user_categories(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->userId)) {

            if(sizeof($data->categoryIds) > 0){
                $existing_user_category = DB::table('user_categories')->where('category_id', $data->categoryIds)->where('users_id',$data->userId)->get()->count();
                if($existing_user_category == 0 ){
                    //saving Tags data on success
                    $deselectPrevious = DB::table('user_categories')->where('users_id', $data->userId)->delete();

                    foreach($data->categoryIds as $k){
                        $categoryIds = array(
                          'users_id' => $data->userId,
                          'category_id' => $k,
                          'status' => 'Active',
                        );

                        $query = DB::table('user_categories')->insert($categoryIds);
                    }

                    if($query){
                        $user_categories =  DB::table('user_categories')
                                        ->select('user_categories.*','categories.*')
                                        ->join('categories','categories.category_id','=','user_categories.category_id')
                                        ->where('user_categories.users_id',$data->userId)
                                        ->where('user_categories.status','Active')->get();


                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = array("user_categories" => $user_categories);
                    } else {
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Something went wrong.";
                    }
                } else {
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "This category is already selected by the user.";
                }
            } else {
                $deselectAll = DB::table('user_categories')->where('users_id', $data->userId)->delete();

                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = "All categories are deselected by user";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /*** USER CATEGORIES ***/

    /*** DISPLAY SPECIFIC USER CATEGORIES ***/
    public  function specific_user_categories(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->userId)) {

            $event_types =   DB::table('event_type')->where('status', 'Active')->get();
                    if (sizeof($event_types) > 0) {
                        foreach($event_types as $k){
                            $eventCategories = DB::table('categories')->where([ ['event_type_id', $k->event_type_id], ['status', 'Active'] ])->get();

                            //checking specific user categories and appending bool flag in response
                              foreach($eventCategories as $i){
                                $checkUserCategory = DB::table('user_categories')->where([ ['category_id', $i->category_id], ['users_id', $data->userId], ['status', 'Active'] ])->count();
                                if($checkUserCategory == 0){
                                    $i->is_category_selected = false;
                                } else{
                                    $i->is_category_selected = true;
                                }
                            }

                            $k->categories = $eventCategories;
                        }
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] =  $event_types;
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "Event Types does not exists.";
                    }

        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }

        return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** DISPLAY SPECIFIC USER CATEGORIES ***/

    /*** DISPLAY EVENT TYPES ***/
    public function event_types(){

        $event_types =   DB::table('event_type')->where('status', 'Active')->get();
            if (sizeof($event_types) > 0) {
                foreach($event_types as $k){
                    $eventCategories = DB::table('categories')->where([ ['event_type_id', $k->event_type_id], ['status', 'Active'] ])->get();
                    $k->categories = $eventCategories;
                }
                $response["code"] = 200;
                $response["status"] = "success";
                $response["event_types"] =  $event_types;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Event Types does not exists.";
            }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'event_types' => isset($response["message"]) ? $response["message"] : $response["event_types"]))
    ->header('Content-Type', 'application/json');
}
    /*** DISPLAY EVENT TYPES ***/


    /*** DISPLAY DRESS CODES***/
    public function dress_codes(){

        $dress_codes =   DB::table('dress_code')->get();
        if ($dress_codes) {
            if (!empty($dress_codes)) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] =  $dress_codes;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Dress Codes does not exists.";
            }
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
    /*** DISPLAY DRESS CODES***/

    /////// Add custom tags //////////
 public function add_custom_tag(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->tagName)){

                    $customTagData = array(
                        'tag_name' => $data->tagName,
                        'tag_type' => 'Custom',
                        'custom_tag_users_id' => $data->usersId,
                        'status' => 'Active'
                    );
                    $lastInsertedId = DB::table('tags')->insertGetId($customTagData);
                    if($lastInsertedId) {
                         $addedTag = DB::table('tags')->where('tag_id', $lastInsertedId)->first();
                         if($addedTag){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $addedTag;

                         }

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
//start sheraz practice work

public function add_tag_practice(Request $req)
{
    $response=array();
    if(isset($req->tag_type) && isset($req->tag_name) && isset($req->custom_tag_users_id) && isset($req->status))
    {
        $tagData=array(

            'tag_name'=>$req->tag_name,
            'tag_type'=>$req->tag_type,
            'custom_tag_users_id'=>$req->custom_tag_users_id,
            'status'=>$req->status,
        );
        $lastInsetId=DB::table('tags')->insertGetId($tagData);
        if ($lastInsetId) {
           $addedData=DB::table('tags')->where('tag_id', $lastInsetId)->first();
           if ($addedData) {
            $response['code']=200;
            $response['status']='success';
            $response['data']=$addedData;
           }
        }

    }else{
        $response['code']=404;
        $response['status']='Error';
        $response['message']='All Fields are needed';
    }
 
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}

public function update_tag_practice(Request $req, $id)
{
    $response=array();
    if(isset($req->tag_type) && isset($req->tag_name) && isset($req->custom_tag_users_id) && isset($req->status))
    {
        $tagData=array(

            'tag_name'=>$req->tag_name,
            'tag_type'=>$req->tag_type,
            'custom_tag_users_id'=>$req->custom_tag_users_id,
            'status'=>$req->status,
        );
    $updatedData=DB::table('tags')->where('tag_id', $id)->update($tagData);
           if ($updatedData) {
            $response['code']=200;
            $response['status']='success';
            $response['data']=$updatedData;
           }
       

    }else{
        $response['code']=404;
        $response['status']='Error';
        $response['message']='All Fields are needed';
    }
 
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}

public function show_tag_practice()
{
    $response=array();
   
    $Data=DB::table('tags')->get();
           if ($Data) {
            $response['code']=200;
            $response['status']='success';
            $response['data']=$Data;
           }
       

    else{
        $response['code']=404;
        $response['status']='Error';
        $response['message']='All Fields are needed';
    }
 
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
public function delete_tag_practice($id)
{
    $response=array();
   
    $Data=DB::table('tags')->where('tag_id', $id)->delete();
           if ($Data) {
            $response['code']=200;
            $response['status']='success';
            $response['data']=$Data;
           }
       

    else{
        $response['code']=404;
        $response['status']='Error';
        $response['message']='All Fields are needed';
    }
 
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
//end sheraz practice work

   /////// Remove custom tag //////////
   public function remove_custom_tag(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->customTagId)){
            $tagData = DB::table('tags')->where([ ['tag_id',$data->customTagId], ['users_id', $data->usersId]])->first();
            if($tagData){
                $query = DB::table('tags')->where([ ['tag_id',$data->customTagId], ['users_id', $data->usersId]])->delete();
                if($query){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Removed successfully';
                }
            }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Custom tag of this user not found";
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


   /////// Get all tags //////////
   public function get_all_tags(){
            $response = array();

                $tags = DB::table('tags')->where('tag_type', 'Default')->get();
                    if(sizeof($tags) > 0){
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $tags;
                    }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "No tags found";
                    }

    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


  /////// Get all tags with custom tags for edit event //////////
  public function get_all_tags_with_custom(){

    $data = json_decode(file_get_contents("php://input"));
    $response = array();

//      if(isset($data->usersId)){
//            $tags = DB::table('tags')->where('tag_type', 'Default')->orWhere([ ['tag_type', 'Custom'],['custom_tag_users_id', $data->usersId] ])->get();



//    latest code uncomment it after code is uploaded on play store
    if(isset($data->usersId) && isset($data->eventPostId)){
//            $tags = DB::table('tags')->where('tag_type', 'Default')->orWhere([ ['tag_type', 'Custom'],['custom_tag_users_id', $data->usersId] ])->get();
            $default_tags = DB::table('tags')->where('tag_type', 'Default');
            $tags = DB::table('event_tags')->join("tags","tags.tag_id","event_tags.tag_id")->select("tags.*")->where('event_tags.event_post_id', $data->eventPostId)->where('tags.tag_type', 'Default')
                ->orWhere([ ['tags.tag_type', 'Custom'],['tags.custom_tag_users_id', $data->usersId] ])
                ->union($default_tags)
                ->get();

            if(sizeof($tags) > 0){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $tags;
            }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "No tags found";
            }

    }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All fields are needed";
    }


return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');

}


    public  function add_log()
    {
        $response = array();
//            $data = json_decode(file_get_contents("php://input"));
        $data = json_decode(file_get_contents("php://input"));


        $logs_data = array(
            'name' => "create_event_post",
            'error' => "Check Support ID",
            'json' => file_get_contents("php://input"),
        );
        $ticketsInsertQuery = DB::table('logs')->insertGetId($logs_data);


        return response()
            ->json(array('status' => 200, "message" => "Contact Admin for feedback. Your support id is:".$ticketsInsertQuery))
            ->header('Content-Type', 'application/json');

    }





        /*** CREATE EVENT POST ***/
        public  function create_event_post(){
            $response = array();
//            $data = json_decode(file_get_contents("php://input"));
            $data = json_decode(file_get_contents("php://input"));



            if (isset($data->title) && isset($data->userId)  && isset($data->eventTypeId) && isset($data->eventTicketType)  && isset($data->eventCategoryId) && isset($data->eventStartDate) && isset($data->eventStartTime)  && isset($data->eventEndDate) && isset($data->eventEndTime) && isset($data->eventDescription) && isset($data->locationLong) && isset($data->locationLat))
            {
                $user_package_details = DB::table('users')->where('users_id', $data->userId)->where('status', 'Active')->first();

                        //check for the availability of one time event post if status is not premium
                    /*     if($user_package_details->subscription_package_id == 1 && $user_package_details->one_time_post_count == 0){
                                $response["code"] = 404;
                                $response["status"] = "error";
                                $response["message"] = "upgrade your package to post more event";

                            return response()
                                        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                                        ->header('Content-Type', 'application/json');

                                    exit;
                        }
                        */
                        $onetime= 0;
                            //updating value of one time post count if status is one time post subscribed and used for event posting
                        if($user_package_details->subscription_package_id == 4 && $user_package_details->one_time_post_count > 0){
                            $updatedOneTimePostCount = $user_package_details->one_time_post_count - 1;
                            DB::table('users')->where('users_id', $data->userId)->update(array('one_time_post_count' => $updatedOneTimePostCount));
                            $onetime = 1;
                        }

// inkkkkkk
                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->locationLat.','.$data->locationLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                
                // return response(array($response));

                
                
                $event_timezone = $response["timeZoneId"];
                date_default_timezone_set($event_timezone);


                        $event_data = array(
                            'users_id' => $data->userId,
                              'title' => $data->title,
                              'socialLink' => (isset($data->socialLink) ? $data->socialLink : ''),
                          'first_image' => '',
                          'second_image' => '',
                          'third_image' => '',
                          'first_video_thumbnail' => '',
                          'second_video_thumbnail' => '',
                          'third_video_thumbnail' => '',
                          'first_video' => (isset($data->firstVideo) ? $data->firstVideo : ''),
                          'second_video' => (isset($data->secondVideo) ? $data->secondVideo: ''),
                          'third_video' => (isset($data->thirdVideo) ? $data->thirdVideo : ''),
                              'event_type_id' => $data->eventTypeId,
                              'event_ticket_type' => $data->eventTicketType,
                              'category_id' => $data->eventCategoryId,
                              'event_start_date' => $data->eventStartDate,
                              'event_start_time' =>  $data->eventStartTime,
                              'event_end_date' =>  $data->eventEndDate,
                              'event_end_time' =>  $data->eventEndTime,
                              'description' => $data->eventDescription,
                              'discount_percent' => (isset($data->discountPercent) ? $data->discountPercent : ''),
                              'min_tickets_discount' => (isset($data->minTicketsDiscount) ? $data->minTicketsDiscount : ''),
                              'refundable' => (isset($data->refundable) ? $data->refundable : null),
//                              'table_service' => (isset( $data->tableService) ?  $data->tableService : null),
//                              'tbl_four_people_cost' => (isset($data->tblFourPeopleCost) ? $data->tblFourPeopleCost : ''),
//                              'tbl_six_people_cost' => (isset($data->tblSixPeopleCost) ? $data->tblSixPeopleCost : ''),
//                              'tbl_eight_people_cost' => (isset($data->tblEightPeopleCost) ? $data->tblEightPeopleCost : ''),
//                              'tbl_ten_people_cost' => (isset($data->tblTenPeopleCost) ? $data->tblTenPeopleCost : ''),
                              'sales_start_date' => (isset($data->salesStartDate) ? $data->salesStartDate : null),
                              'sales_start_time' => (isset($data->salesStartTime) ? $data->salesStartTime : null),
                              'sales_end_date' => (isset($data->salesEndDate) ? $data->salesEndDate : null),
                              'sales_end_time' => (isset($data->salesEndTime) ? $data->salesEndTime : null),
                              'dress_code_id' => (isset($data->dressCodeId) ? $data->dressCodeId : '0'),
                              'total_likes' => '0',
                              'hyperlink_not_my_event' => (isset($data->hyperlink) ? $data->hyperlink : ''),
                              'created_at' => date('Y-m-d H:i:s'),
                              'status' => 'Active',
                              'onetime' => $onetime

                          );

                          $lastInsertedId = DB::table('event_posts')->insertGetId($event_data);


                if ($lastInsertedId) {
                    $event_post_data = DB::table('event_posts')->where('event_post_id',$lastInsertedId)->first();



                    if($event_post_data->event_ticket_type == "Paid") {
                        $checkinData = array(
                            'event_post_id' => $event_post_data->event_post_id,
                            'users_id' => $event_post_data->users_id,
                            'ticket_unique_number' => Helper::generateRandomString(10),
                            'date_time' => date('Y-m-d H:i:s')
                        );
                        DB::table('event_checkins')->insert($checkinData);
                    }


                    //  Notification of event post is send to all users which follow the user which create the post
                    $followed_users =  DB::table('followers')->where('following_to_user', $event_post_data->users_id)->get();

                    foreach ($followed_users as $followed_user){

                        $senderDetails=  DB::table('users')->where('users_id',$followed_user->following_to_user)->first();
                        $receiverDetails= DB::table('users')->where('users_id',$followed_user->followed_by_user)->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' posted a new event', 'EventPost');


                            if($notificationResponse){
                                $notificationData = array(
                                    'sender_users_id' => $senderDetails-> users_id,
                                    'receiver_users_id' => $receiverDetails-> users_id,
                                    'notification_type' => 'EventPost',
                                    'message' => $senderDetails->user_name.' posted a new event',
                                    'event_post_id' => $lastInsertedId,
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'status' => 'Unread'
                                );
                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                            }
                        }
                    }

                           $upload_images = array(
                               'first_image' => (isset($data->firstImageBasecode) ? $data->firstImageBasecode: ''),
                               'second_image' => (isset($data->secondImageBasecode) ? $data->secondImageBasecode: ''),
                               'third_image' => (isset($data->thirdImageBasecode) ? $data->thirdImageBasecode: ''),
                           );
                          $imagesCount = 0;
                              if(isset($data->firstImageBasecode)){  $imagesCount++; }
                              if(isset($data->secondImageBasecode)){  $imagesCount++; }
                              if(isset($data->thirdImageBasecode)){  $imagesCount++; }

                           // Loop for images
                          for($i=0;$i<$imagesCount;$i++){
                                      $postfix = 'conn_events';
                                      $upd_prefix = date('d-m-y');
                                      $random = rand(1,1000000);
                                      $image_path_url= config('base_urls.relative_path_event_images');

                                      if($i == 0){
                                          $uploaded_image = Helper::decode_image($upload_images['first_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                          $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('first_image' => $uploaded_image));
                                      }
                                       if($i == 1){
                                          $uploaded_image = Helper::decode_image($upload_images['second_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                          $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('second_image' => $uploaded_image));
                                       }
                                      if ($i == 2){
                                          $uploaded_image = Helper::decode_image($upload_images['third_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                          $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('third_image' => $uploaded_image));
                                      }
                                  }

                                  $upload_thumbnails = array(
                                      'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail: ''),
                                      'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail: ''),
                                      'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail: ''),
                                  );

                                  $thumbnailCount = 0;
                                  if(isset($data->firstVideoThumbnail)){  $thumbnailCount++; }
                                  if(isset($data->secondVideoThumbnail)){  $thumbnailCount++; }
                                  if(isset($data->thirdVideoThumbnail)){  $thumbnailCount++; }
                                      // Loop for images
                                 for($i=0;$i<$thumbnailCount;$i++){
                                             $postfix = 'conn_events';
                                             $upd_prefix = date('d-m-y');
                                             $random = rand(1,1000000);
                                             $image_path_url= config('base_urls.relative_path_event_video_thumbnails');

                                             if($i == 0){
                                                 $uploaded_image = Helper::decode_image($upload_thumbnails['first_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                 $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('first_video_thumbnail' => $uploaded_image));
                                             }
                                              if($i == 1){
                                                 $uploaded_image = Helper::decode_image($upload_thumbnails['second_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                 $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('second_video_thumbnail' => $uploaded_image));
                                              }
                                             if ($i == 2){
                                                 $uploaded_image = Helper::decode_image($upload_thumbnails['third_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                 $query = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->update(array('third_video_thumbnail' => $uploaded_image));
                                             }
                                         }

                           //Main response query
                              $event_details = DB::table('event_posts')->where('event_post_id', $lastInsertedId)->get();
                              if (!empty($event_details)) {


                                        //updating images urls fields in response
                                        $image_base_url = config('base_urls.event_image_base_url');

                                      foreach($event_details as $k){
                                          if(!empty( $k->first_image)){
                                              $k->first_image= $image_base_url.$k->first_image;
                                          }
                                           if(!empty( $k->second_image)){
                                              $k->second_image = $image_base_url.$k->second_image;
                                          }
                                           if(!empty( $k->third_image)){
                                              $k->third_image = $image_base_url.$k->third_image;
                                          }
                                         }

                                          //updating Videos thumbnail urls fields in response
                                    $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');

                                    foreach($event_details as $k){

                                          if(!empty( $k->first_video_thumbnail)){
                                             $k->first_video_thumbnail= $video_thumbnail_base_url.$k->first_video_thumbnail;
                                          }
                                          if(!empty( $k->second_video_thumbnail)){
                                               $k->second_video_thumbnail = $video_thumbnail_base_url.$k->second_video_thumbnail;
                                           }
                                           if(!empty( $k->third_video_thumbnail)){
                                             $k->third_video_thumbnail = $video_thumbnail_base_url.$k->third_video_thumbnail;
                                         }
                                       }

                                    //updating Videos urls fields in response
                                    $video_base_url = config('base_urls.event_video_base_url');

                                 foreach($event_details as $k){

                                       if(!empty( $k->first_video)){
                                          $k->first_video= $video_base_url.$k->first_video;
                                       }
                                       if(!empty( $k->second_video)){
                                            $k->second_video = $video_base_url.$k->second_video;
                                        }
                                        if(!empty( $k->third_video)){
                                          $k->third_video = $video_base_url.$k->third_video;
                                      }
                                    }

                                    //appending Category data from categories in response array after success
                                      $category =   DB::table('categories')->where('category_id',$data->eventCategoryId)->first();
                                          if($category){
                                              $k->category = $category;
                                          }

                                       //appending Event Type data from Event_type in response array after success
                                       $event_type =   DB::table('event_type')->where('event_type_id',$data->eventTypeId)->first();
                                           if($event_type){
                                               $k->event_type = $event_type;
                                           }

                                    if(isset($data->dressCodeId)){
                                         //appending Dress Code data from dress_code in response array after success
                                        $dress_code =   DB::table('dress_code')->where('dress_code_id',$data->dressCodeId)->first();
                                        if($dress_code){
                                            $k->dress_code = $dress_code;
                                        }
                                    } else{
                                        $k->dress_code = null;
                                    }

                                  //saving Tags data on success
                                  if(isset($data->tags)){
                                      foreach($data->tags as $k){

//                                          Existing Tag
                                          if(is_numeric($k)){
                                              $tags_data = array(
                                                  'event_post_id' => $lastInsertedId,
                                                  'tag_id' => $k,
                                                  'status' => 'Active',
                                              );
                                              $tagsInsertedQuery = DB::table('event_tags')->insert($tags_data);
                                          }else{
//                                              New Tags
                                              $customTagData = array(
                                                  'tag_name' => $k,
                                                  'tag_type' => 'Custom',
                                                  'custom_tag_users_id' => $data->userId,
                                                  'status' => 'Active'
                                              );
                                              $lastTagId = DB::table('tags')->insertGetId($customTagData);

                                              $tags_data = array(
                                                  'event_post_id' => $lastInsertedId,
                                                  'tag_id' => $lastTagId,
                                                  'status' => 'Active',
                                              );
                                              $tagsInsertedQuery = DB::table('event_tags')->insert($tags_data);
                                          }
                                  }

//                                      //saving New Tags data on success
//                                      if(isset($data->newtags)){
//                                          foreach($data->newtags as $k){
//
//
//                                          }
//                                      }





                                            //appending tags in response array after success
                                            if($tagsInsertedQuery){
                                            $tags =   DB::table('event_tags')
                                                                ->select('event_tags.*','tags.*')
                                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                                ->where('event_tags.event_post_id',$lastInsertedId)
                                                                ->where('event_tags.status','Active')->get();

                                              foreach($event_details as $event_del){
                                                  if($tags){
                                                      $event_del->event_tags = $tags;
                                                  }
                                              }
                                         }
                                  }

                               if(isset($data->tickets)){
                                    //saving Tickets data on success
                                    if(!empty($data->tickets)){
                                        foreach($data->tickets as $k){
                                        $k->closing_date = (isset($k->closing_date) ? $k->closing_date : '0');
                                            $tickets_data = array(
                                            'event_post_id' => $lastInsertedId,
                                            'ticket' => $k->ticket,
                                            'quantity' => $k->quantity,
                                            'total_quantity' => $k->quantity,
                                            'price' => $k->price,
                                            'closing_date' => $k->closing_date,
                                            'status' => 'Active'

                                        );
                                    $ticketsInsertQuery = DB::table('tickets')->insert($tickets_data);
                                }
                                     //appending tickets in response array after success
                                       if($ticketsInsertQuery){
                                          $tickets =   DB::table('tickets')->where('event_post_id',$lastInsertedId)->where('status',"Active")->get();
                                          foreach($event_details as $event_del){
                                            if($tickets){
                                                $event_del->event_tickets = $tickets;
                                            }
                                        }
                                         }
                                    }
                               }


//inkkkkk                        
                                  $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->locationLat.','.$data->locationLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();

                                  //saving Addresses data on success
                                  $address_data = array(
                                      'full_address' => $data->fullAddress,
                                      'city' => (isset($data->city) ? $data->city : ''),
                                      'state' => (isset($data->state) ? $data->state : ''),
                                      'zip' => (isset($data->zip) ? $data->zip : ''),
                                      'event_post_id' => $lastInsertedId,
                                      'status' => 'Active',
                                      'location_long'=>$data->locationLong,
                                      'location_lat'=>$data->locationLat,
                                      'timezone'=>$response["timeZoneId"]

                                  );

                                  if(!empty($address_data)){
                                      $addressInsertQuery=  DB::table('addresses')->insert($address_data);

                                      //appending addresses in response array after success
                                          if($addressInsertQuery){
                                          $address =   DB::table('addresses')->where('event_post_id',$lastInsertedId)->where('status',"Active")->get()->first();
                                              foreach($event_details as $event_del){
                                                  if($address){
                                                      $event_del->event_address = $address;
                                                  }
                                              }
                                          }
                                          }        
                                  //---------------------------------------------------------------------------------------//
                               // Date conversion in 12 hrs format in response
//                                  jaga
                                  foreach($event_details as $k){
                                      $k->event_start_date = date("M d,Y", strtotime($k->event_start_date));
                                      $k->event_end_date = date("M d,Y", strtotime($k->event_end_date));
                                      $k->sales_start_date = date("M d,Y", strtotime($k->sales_start_date));
                                      $k->sales_end_date = date("M d,Y", strtotime($k->sales_end_date));
                                  }

                                  // time conversion in 12 hrs format in response
                                  foreach($event_details as $k){
                                      $k->event_start_time = date("g:i a", strtotime($k->event_start_time));
                                      $k->event_end_time = date("g:i a", strtotime($k->event_end_time));
                                      $k->sales_start_time = date("g:i a", strtotime($k->sales_start_time));
                                      $k->sales_end_time = date("g:i a", strtotime($k->sales_end_time));
                                  }

                                  $oneTimePostCountQuery = DB::table('users')->where('users_id', $data->userId)->first();

                                  ////RESPONSE
                                  $response["code"] = 200;
                                  $response["status"] = "success";
                                  $response["one_time_post_count"] = $oneTimePostCountQuery->one_time_post_count;
                                  $response["data"] =  $event_details;

                              } else {
                                  $response["code"] = 404;
                                  $response["one_time_post_count"] = '0';
                                  $response["status"] = "error";
                                  $response["message"] = "Event does not exists.";
                              }

                          } else {
                              $response["code"] = 500;
                              $response["one_time_post_count"] = '0';
                              $response["status"] = "error";
                              $response["message"] = "Something went wrong.";
                          }


                }
             else {
                $response["code"] = 404;
                $response["one_time_post_count"] = '0';
                $response["status"] = "error";
                $response["message"] = "All fields are needed.";
            }

            return response()
                        ->json(array('one_time_post_count' => $response["one_time_post_count"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                        ->header('Content-Type', 'application/json');
        }
        /*** CREATE EVENT POST ***/



      /*** DISPLAY EVENT POSTS***/
      public function get_event_posts(){
        $data = json_decode(file_get_contents("php://input"));

       if($data->offset != ''){
        $total_posts = DB::table('event_posts')->where('status','Active')->count();

        $event_posts =   DB::table('event_posts')->skip($data->offset)->take(5)->where('status',"Active")->get();
        if (!empty($event_posts)) {

            foreach($event_posts as $key => $event_post){

                    //appending images urls fields in response
                    $image_base_url = config('base_urls.event_image_base_url');

                        if(!empty( $event_post->first_image)){
                            $event_post->first_image= $image_base_url.$event_post->first_image;
                        }
                         if(!empty( $event_post->second_image)){
                            $event_post->second_image = $image_base_url.$event_post->second_image;
                        }
                         if(!empty( $event_post->third_image)){
                            $event_post->third_image = $image_base_url.$event_post->third_image;
                        }

                //updating Videos thumbnail urls fields in response
                $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');

                foreach($event_posts as $k){

                    if(!empty( $k->first_video_thumbnail)){
                        $k->first_video_thumbnail= $video_thumbnail_base_url.$k->first_video_thumbnail;
                    }
                    if(!empty( $k->second_video_thumbnail)){
                        $k->second_video_thumbnail = $video_thumbnail_base_url.$k->second_video_thumbnail;
                    }
                    if(!empty( $k->third_video_thumbnail)){
                        $k->third_video_thumbnail = $video_thumbnail_base_url.$k->third_video_thumbnail;
                    }
                }

                  //appending Videos urls fields in response
                  $video_base_url = config('event_video_base_url');

                            if(!empty( $event_post->first_video)){
                                $event_post->first_video= $video_base_url.$event_post->first_video;
                            }
                            if(!empty( $event_post->second_video)){
                                $event_post->second_video = $video_base_url.$event_post->second_video;
                            }
                            if(!empty( $event_post->third_video)){
                                $event_post->third_video = $video_base_url.$event_post->third_video;
                            }


                       //appending Category data from categories in response array after success
                       $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->get();
                       foreach($event_posts as $event_del){
                           if($category){
                               $event_del->category = $category;
                           }
                       }

                        //appending Event Type data from Event_type in response array after success
                        $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->get();
                        foreach($event_posts as $event_del){
                            if($event_type){
                                $event_del->event_type = $event_type;
                            }
                        }

                      //appending Dress Code data from dress_code in response array after success
                      $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->get();
                      foreach($event_posts as $event_del){
                          if($dress_code){
                              $event_del->dress_code = $dress_code;
                          }
                      }

                //fetching tags from tags table of each event post
                $tags =   DB::table('event_tags')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get();
                 if($tags){
                    $event_post->event_tags = $tags;
                 }

                //fetching tickets from tickets table of each event post
                $tickets =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get();
                if($tickets){
                    $event_post->event_tickets = $tickets;
                }

                 //fetching address from address table of each event post
                 $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                 if($address){
                    $event_post->event_address = $address;
                 }
            }

             // Date conversion in 12 hrs format in response
//            jaga
             foreach($event_posts as $k){
                $k->event_start_date = date("M d,Y", strtotime($k->event_start_date));
                $k->event_end_date = date("M d,Y", strtotime($k->event_end_date));
                $k->sales_start_date = date("M d,Y", strtotime($k->sales_start_date));
                $k->sales_end_date = date("M d,Y", strtotime($k->sales_end_date));
            }

              // time conversion in 12 hrs format in response
              foreach($event_posts as $k){
                $k->event_start_time = date("g:i a", strtotime($k->event_start_time));
                $k->event_end_time = date("g:i a", strtotime($k->event_end_time));
                $k->sales_start_time = date("g:i a", strtotime($k->sales_start_time));
                $k->sales_end_time = date("g:i a", strtotime($k->sales_end_time));
            }

            $response["code"] = 200;
            $response["status"] = "success";
            $response["total_posts"] = $total_posts;
            $response["data"] =  array("event" => $event_posts);


        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["total_posts"] = "0";
            $response["message"] = "Event Posts does not exists.";
        }
     } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["total_posts"] = "0";
        $response["message"] = "Offset not passed.";
     }



    return response()
    ->json(array('status' => $response["status"], 'total_posts'=>$response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
       /*** DISPLAY EVENT POSTS***/


  /*** DELETE EVENT POST ***/
  public  function delete_event_post(){
    $data = json_decode(file_get_contents("php://input"));
    $response = array();
    if (isset($data->eventPostId)) {
        //---- COndition to be defined for restricting delete event if any tickets are purchased -- //
        $checkPurchasedTickets = DB::table('user_tickets')->where('event_post_id', $data->eventPostId)->where('status','Active')->get();

            if (sizeof($checkPurchasedTickets) > 0) {
              //event tickets that are active still exist,  That event cannot be canceled or deleted
              $response["code"] = 500;
              $response["status"] = "error";
              $response["message"] = "Purchased Tickets of this event exists.";
        } else{
            $eventDetail = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
            if (!empty($eventDetail)) {
                   //Deleting tags, addresses, tickets first to avoid foreign key constraints.
                   DB::table('event_tags')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Deleted'));
                   DB::table('tickets')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Deleted'));
                   DB::table('addresses')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Deleted'));


                  //Deleting Event Post
                  $deleteEventQuery =   DB::table('event_posts')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Deleted'));
                  if($deleteEventQuery){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] =   "Event data of ID: " . $data->eventPostId. " is deleted successfully"  ;
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Event does not exists.";
            }
        }


    } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All fields are needed.";
    }

    return response()
                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
}
  /*** DELETE EVENT POST ***/

    /*** CANCEL EVENT POST ***/
    public  function cancel_event_post(){

        $data = json_decode(file_get_contents("php://input"));
        $response = array();

        if (isset($data->eventPostId)) {
            //---- COndition to be defined for restricting delete event if any tickets are purchased -- //
            $checkPurchasedTickets = DB::table('user_tickets')->where('event_post_id', $data->eventPostId)->where('status','Active')->get();

                if (sizeof($checkPurchasedTickets) > 0) {
                  //event tickets that are active still exist,  That event cannot be canceled or deleted
                  $response["code"] = 500;
                  $response["status"] = "error";
                  $response["data"] = "Purchased Tickets of this event exists.";
            } else{
                $eventDetail = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                if (!empty($eventDetail)) {
                    if($eventDetail->status != 'Canceled'){
                       //Canceling tags, addresses, tickets first to avoid foreign key constraints.
                       DB::table('event_tags')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Canceled'));
                       DB::table('tickets')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Canceled'));

                        //Canceling Event Post
                        $deleteEventQuery =   DB::table('event_posts')->where('event_post_id','=', $data->eventPostId)->update(array('status' => 'Canceled'));
                        if($deleteEventQuery){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] =   "Event is Canceled successfully"  ;
                        }
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Event is already canceled.";
                    }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Event does not exists.";
                }
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }
        return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
      /*** CANCEL EVENT POST ***/

         /////// GET SELECTED CATEGORY FOR EDIT //////////
    public function get_selected_event_category(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventPostId)){
                $eventDetails = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                $selectedCategory = DB::table('categories')->where('category_id', $eventDetails->category_id)->first();

                if($selectedCategory){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] =  $selectedCategory;
                }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "category not found";
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

              /////// GET CATEGORIES OF EVENT TYPE FOR EDIT //////////
    public function get_event_type_categories(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventTypeId)){
                $categories = DB::table('categories')->where('event_type_id', $data->eventTypeId)->get();
                if(sizeof($categories) > 0){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] =  $categories;
                } else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "No categories found of this event type";
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




    /*** EDIT/UPDATE EVENT POST ***/
    public  function edit_event_post(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->title) && isset($data->eventPostId) &&  isset($data->eventTypeId) &&  isset($data->eventTicketType) && isset($data->eventCategoryId) && isset($data->eventStartDate) && isset($data->eventStartTime)  && isset($data->eventEndDate) && isset($data->eventEndTime) && isset($data->eventDescription)  && isset($data->fullAddress) && isset($data->city) && isset($data->state) && isset($data->zip) && isset($data->locationLong) && isset($data->locationLat))
        {
            $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->locationLat.','.$data->locationLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
            $event_timezone = $response["timeZoneId"];
            date_default_timezone_set($event_timezone);



              $old_data = DB::table('event_posts')->Where('event_post_id', $data->eventPostId)->where('status','Active')->first();

              $eventDetailsForNotifications = DB::table('event_posts')
                                                                ->select('event_posts.*','addresses.*')
                                                                ->join('addresses', 'addresses.event_post_id', '=', 'event_posts.event_post_id')
                                                                ->where('event_posts.event_post_id',$data->eventPostId)
                                                                ->first();

                    $event_data = array(

                        'title' => $data->title,
                        'socialLink' => (isset($data->socialLink) ? $data->socialLink : ''),
                        'first_image' => (isset($data->firstImage) ? $data->firstImage : $old_data->first_image),
                        'second_image' => (isset($data->secondImage) ? $data->secondImage : $old_data->second_image),
                        'third_image' => (isset($data->thirdImage) ? $data->thirdImage : $old_data->third_image),
                        'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail : $old_data->first_video_thumbnail),
                        'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail : $old_data->second_video_thumbnail),
                        'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail : $old_data->third_video_thumbnail),
                        'first_video' => (isset($data->firstVideo) ? $data->firstVideo : $old_data->first_video),
                        'second_video' => (isset($data->secondVideo) ? $data->secondVideo: $old_data->second_video),
                        'third_video' => (isset($data->thirdVideo) ? $data->thirdVideo : $old_data->third_video),

                            'event_type_id' => $data->eventTypeId,
                            'category_id' => $data->eventCategoryId,
                            'event_ticket_type' => $data->eventTicketType,
                            'event_start_date' =>  $data->eventStartDate,
                            'event_start_time' =>   $data->eventStartTime,
                            'event_end_date' =>  $data->eventEndDate,
                            'event_end_time' =>  $data->eventEndTime,
                            'description' => $data->eventDescription,
                            'discount_percent' => (isset($data->discountPercent) ? $data->discountPercent : ''),
                            'min_tickets_discount' => (isset($data->minTicketsDiscount) ? $data->minTicketsDiscount : ''),
                            'refundable' => (isset($data->refundable) ? $data->refundable : null),
                            'table_service' => (isset( $data->tableService) ?  $data->tableService : null),
                            'tbl_four_people_cost' => (isset($data->tblFourPeopleCost) ? $data->tblFourPeopleCost : ''),
                            'tbl_six_people_cost' => (isset($data->tblSixPeopleCost) ? $data->tblSixPeopleCost : ''),
                            'tbl_eight_people_cost' => (isset($data->tblEightPeopleCost) ? $data->tblEightPeopleCost : ''),
                            'tbl_ten_people_cost' => (isset($data->tblTenPeopleCost) ? $data->tblTenPeopleCost : ''),
                            'sales_start_date' => (isset($data->salesStartDate) ? $data->salesStartDate : null),
                            'sales_start_time' => (isset($data->salesStartTime) ? $data->salesStartTime : null),
                            'sales_end_date' => (isset($data->salesEndDate) ? $data->salesEndDate : null),
                            'sales_end_time' => (isset($data->salesEndTime) ? $data->salesEndTime : null),
                            'dress_code_id' => (isset($data->dressCodeId) ? $data->dressCodeId : '0'),
                            'hyperlink_not_my_event' => (isset($data->hyperlink) ? $data->hyperlink : ''),
                            'status' => 'Active'
                        );
                        $checkData =  DB::table('event_posts')->where('event_post_id', $data->eventPostId)->count();


                        if($checkData){

                            $updatingRecord =   DB::table('event_posts')->where('event_post_id','=', $data->eventPostId)->update($event_data);
                               $updatedData =  DB::table('event_posts')->where('event_post_id', $data->eventPostId)->get();
                                if(!empty($updatedData)){
                                    //SUCCESS CASE

                                     //getting last updated record id
                                     $lastUpdatedId = $data->eventPostId;

                                        //fetching and updating images and videos
                                        $upload_images = array(
                                            'first_image' => (isset($data->firstImageBasecode) ? $data->firstImageBasecode: $old_data->first_image),
                                            'second_image' => (isset($data->secondImageBasecode) ? $data->secondImageBasecode: $old_data->second_image),
                                            'third_image' => (isset($data->thirdImageBasecode) ? $data->thirdImageBasecode: $old_data->third_image),
                                        );
                                       $imagesCount = 0;
                                           if(isset($data->firstImageBasecode)){  $imagesCount++; }
                                           if(isset($data->secondImageBasecode)){  $imagesCount++; }
                                           if(isset($data->thirdImageBasecode)){  $imagesCount++; }

                                        // Loop for images
                                     for($i=0;$i<$imagesCount;$i++){
                                                 $postfix = 'conn_events';
                                                 $upd_prefix = date('d-m-y');
                                                 $random = rand(1,10000);
                                                 $image_path_url= config('base_urls.relative_path_event_images');;

                                                 if($i == 0){
                                                     $uploaded_image = Helper::decode_image($upload_images['first_image'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                     $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('first_image' => $uploaded_image));
                                                 }
                                                  if($i == 1){
                                                     $uploaded_image = Helper::decode_image($upload_images['second_image'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                     $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('second_image' => $uploaded_image));
                                                  }
                                                 if ($i == 2){
                                                     $uploaded_image = Helper::decode_image($upload_images['third_image'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                     $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('third_image' => $uploaded_image));
                                                 }
                                             }

                                             $upload_thumbnails = array(
                                                'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail: $old_data->first_video_thumbnail),
                                                'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail: $old_data->second_video_thumbnail),
                                                'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail: $old_data->third_video_thumbnail),
                                            );

                                            $thumbnailCount = 0;
                                            if(isset($data->firstVideoThumbnail)){  $thumbnailCount++; }
                                            if(isset($data->secondVideoThumbnail)){  $thumbnailCount++; }
                                            if(isset($data->thirdVideoThumbnail)){  $thumbnailCount++; }
                                              // Loop for images
                                           for($i=0;$i<$thumbnailCount;$i++){
                                                       $postfix = 'conn_events';
                                                       $upd_prefix = date('d-m-y');
                                                       $random = rand(1,1000000);
                                                       $image_path_url= config('base_urls.relative_path_video_thumbnails');

                                                       if($i == 0){
                                                           $uploaded_image = Helper::decode_image($upload_thumbnails['first_video_thumbnail'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                           $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('first_video_thumbnail' => $uploaded_image));
                                                       }
                                                        if($i == 1){
                                                           $uploaded_image = Helper::decode_image($upload_thumbnails['second_video_thumbnail'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                           $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('second_video_thumbnail' => $uploaded_image));
                                                        }
                                                       if ($i == 2){
                                                           $uploaded_image = Helper::decode_image($upload_thumbnails['third_video_thumbnail'], $lastUpdatedId, $image_path_url, $upd_prefix, $random, $postfix);
                                                           $query = DB::table('event_posts')->where('event_post_id', $lastUpdatedId)->update(array('third_video_thumbnail' => $uploaded_image));
                                                       }
                                                   }


                                     $updatedData =  DB::table('event_posts')->where('event_post_id', $data->eventPostId)->get();

                                              //updating images urls fields in response
                                              $image_base_url = config('base_urls.event_image_base_url');

                                              foreach($updatedData as $k){
                                                  if(!empty( $k->first_image)){
                                                      $k->first_image= $image_base_url.$k->first_image;
                                                  }
                                                   if(!empty( $k->second_image)){
                                                      $k->second_image = $image_base_url.$k->second_image;
                                                  }
                                                   if(!empty( $k->third_image)){
                                                      $k->third_image = $image_base_url.$k->third_image;
                                                  }

                                                 }
                                             //updating Videos thumbnail urls fields in response
                                            $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');

                                            foreach($updatedData as $k){
                                                    if(!empty( $k->first_video_thumbnail)){
                                                        $k->first_video_thumbnail= $video_thumbnail_base_url.$k->first_video_thumbnail;
                                                    }
                                                    if(!empty( $k->second_video_thumbnail)){
                                                        $k->second_video_thumbnail = $video_thumbnail_base_url.$k->second_video_thumbnail;
                                                    }
                                                    if(!empty( $k->third_video_thumbnail)){
                                                        $k->third_video_thumbnail = $video_thumbnail_base_url.$k->third_video_thumbnail;
                                                    }
                                                }

                                            //updating Videos urls fields in response
                                            $video_base_url = config('event_video_base_url');

                                         foreach($updatedData as $k){

                                               if(!empty( $k->first_video)){
                                                  $k->first_video= $video_base_url.$k->first_video;
                                               }
                                               if(!empty( $k->second_video)){
                                                    $k->second_video = $video_base_url.$k->second_video;
                                                }
                                                if(!empty( $k->third_video)){
                                                  $k->third_video = $video_base_url.$k->third_video;
                                              }
                                            }



                                //appending Category data from categories in response array after success
                                $category =   DB::table('categories')->where('category_id',$data->eventCategoryId)->get();
                                foreach($updatedData as $event_del){
                                    if($category){
                                        $event_del->category = $category;
                                    }
                                }

                                 //appending Event Type data from Event_type in response array after success
                                 $event_type =   DB::table('event_type')->where('event_type_id',$data->eventTypeId)->get();
                                 foreach($updatedData as $event_del){
                                     if($event_type){
                                         $event_del->event_type = $event_type;
                                     }
                                 }

                               if(isset($data->dressCodeId)){
                                   //appending Dress Code data from dress_code in response array after success
                                    $dress_code =   DB::table('dress_code')->where('dress_code_id',$data->dressCodeId)->get();
                                    foreach($updatedData as $event_del){
                                        if($dress_code){
                                            $event_del->dress_code = $dress_code;
                                        }
                                      }
                               } else{
                                   $event_del->dress_code = null;
                               }


                            //Updating Tags data on success
                            if(isset($data->tags)){
                                $deletePreviousTags = DB::table('event_tags')->where('event_post_id', $data->eventPostId)->delete();

                                foreach($data->tags as $k){

//                                          Existing Tag
                                    if(is_numeric($k)){
                                        $tags_data = array(
                                            'event_post_id' => $data->eventPostId,
                                            'tag_id' => $k,
                                            'status' => 'Active',
                                        );
                                        $tagsInsertedQuery = DB::table('event_tags')->insert($tags_data);
                                    }else{
//                                              New Tags
                                        $customTagData = array(
                                            'tag_name' => $k,
                                            'tag_type' => 'Custom',
                                            'custom_tag_users_id' => $data->userId,
                                            'status' => 'Active'
                                        );
                                        $lastTagId = DB::table('tags')->insertGetId($customTagData);

                                        $tags_data = array(
                                            'event_post_id' => $data->eventPostId,
                                            'tag_id' => $lastTagId,
                                            'status' => 'Active',
                                        );
                                        $tagsInsertedQuery = DB::table('event_tags')->insert($tags_data);
                                    }
                                }



                                      //appending tags in response array after success
                                        $tags =   DB::table('event_tags')
                                                                    ->select('event_tags.*','tags.*')
                                                                    ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                                    ->where('event_tags.event_post_id',$lastUpdatedId)
                                                                    ->where('event_tags.status','Active')->get();
                                        foreach($updatedData as $event_del){
                                            if($tags){
                                                $event_del->event_tags = $tags;
                                            }
                                        }
                            }

                            //deleting tickets in case event ticket type is edted to free
                            if($data->eventTicketType != 'Paid'){
                                DB::table('tickets')->where('event_post_id',$data->eventPostId)->delete();
                            }

                            if(isset($data->tickets) && $data->eventTypeId != 'Paid'){
                                //Updating Tickets data on success
                                    if(!empty($data->tickets)){
                                        foreach($data->tickets as $k){
                                            $k->closing_date = (isset($k->closing_date) ? $k->closing_date : '0');

                                    if(isset($k->ticket_id)){
                                        $checkPreviousTickets = DB::table('tickets')->where('ticket_id', $k->ticket_id)->where('status','Active')->first();
                                        if($checkPreviousTickets){
                                                if($k->quantity == 0){
                                                    $updatedTotalQuantity = $checkPreviousTickets->total_quantity;
                                                }else{
                                                    $difference = $k->quantity - $checkPreviousTickets->quantity;
                                                    $updatedTotalQuantity = $checkPreviousTickets->total_quantity + $difference;
                                                }

                                                    $ticket_updated_data = array(
                                                        'ticket' => $k->ticket,
                                                        'total_quantity' => $updatedTotalQuantity,
                                                        'quantity' => $k->quantity,
                                                        'price' => $k->price,
                                                        'event_post_id' => $data->eventPostId,
                                                        'closing_date' =>   $k->closing_date,
                                                        'status' => 'Active'
                                                        );
                                            $ticketsUpdatedQuery =  DB::table('tickets')->where('ticket_id', $k->ticket_id)->update($ticket_updated_data);
                                            }
                                        } else{
                                            $checkPreviouslyDeleted = DB::table('tickets')->where('event_post_id', $data->eventPostId)->where('ticket',$k->ticket)->first();
                                                    if($checkPreviouslyDeleted){
                                                        if($k->quantity == 0){
                                                            $updatedTotalQuantity = $checkPreviouslyDeleted->total_quantity;
                                                        }else{
                                                            $difference = $k->quantity - $checkPreviouslyDeleted->quantity;
                                                            $updatedTotalQuantity = $checkPreviouslyDeleted->total_quantity + $difference;
                                                        }

                                                                    $ticket_updated_data = array(
                                                                        'ticket' => $k->ticket,
                                                                        'total_quantity' => $updatedTotalQuantity,
                                                                        'quantity' => $k->quantity,
                                                                        'price' => $k->price,
                                                                        'event_post_id' => $data->eventPostId,
                                                                        'closing_date' =>   $k->closing_date,
                                                                        'status' => 'Active'
                                                                        );

                                                        $ticketUpdateQuery =DB::table('tickets')->where('event_post_id', $data->eventPostId)->where('ticket',$k->ticket)->update($ticket_updated_data);
                                                    } else{
                                                            $new_ticket_data = array(
                                                                'ticket' => $k->ticket,
                                                                'total_quantity' => $k->quantity,
                                                                'quantity' => $k->quantity,
                                                                'price' => $k->price,
                                                                'event_post_id' => $data->eventPostId,
                                                                'closing_date' =>   $k->closing_date,
                                                                'status' => 'Active'
                                                        );

                                                        $ticketsInsertQuery =  DB::table('tickets')->insert($new_ticket_data);
                                                    }
                                        }
                                    }
                                                //deleting tickets that are unselected in the edit after the event created
                                        if(isset($data->removedTickets)){
                                                    foreach($data->removedTickets as $t){
                                                        $checkUnselected = DB::table('tickets')->where('ticket_id',$t->ticket_id)
                                                                                            ->where('event_post_id',$data->eventPostId)
                                                                                            ->where('status','Active')->count();
                                                        if($checkUnselected > 0){
                                                                $deleteUnselected = DB::table('tickets')->where('ticket_id',$t->ticket_id)
                                                                                                ->where('event_post_id',$data->eventPostId)
                                                                                                ->update(array('status'=>'Deleted'));

                                                            }
                                                }
                                        }
                                            //appending tickets in response array after success
                                                $tickets =   DB::table('tickets')->where('event_post_id',$data->eventPostId)->get();
                                                foreach($updatedData as $event_del){
                                                    if($tickets){
                                                        $event_del->event_tickets = $tickets;
                                                    }
                                                }
                                        }
                                    }


                            //saving Addresses data on success
                                    $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->locationLat.','.$data->locationLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();


                            $address_data = array(
                                'full_address' => $data->fullAddress,
                                'city' => $data->city,
                                'state' => $data->state,
                                'zip' => $data->zip,
                                'location_long'=>$data->locationLong,
                                'location_lat'=>$data->locationLat,
                                'timezone'=>$response["timeZoneId"]
                            );

                            $checkPreviousAddress = DB::table('addresses')->where('event_post_id', $lastUpdatedId)->count();
                            if($checkPreviousAddress >0){
                                $AddressUpdatedQuery =  DB::table('addresses')->where('event_post_id', $lastUpdatedId)->update($address_data);
                            }
                                      //appending tags in response array after success
                                      if($AddressUpdatedQuery){
                                        $address =   DB::table('addresses')->where('event_post_id',$lastUpdatedId)->get();
                                        foreach($updatedData as $event_del){
                                            if($address){
                                                $event_del->event_address = $address;
                                            }
                                        }

                                   }

                                    // Date conversion in 12 hrs format in response
//                                    jaga
                        foreach($updatedData as $k){
                            $k->event_start_date = date("M d,Y", strtotime($k->event_start_date));
                            $k->event_end_date = date("M d,Y", strtotime($k->event_end_date));
                            $k->sales_start_date = date("M d,Y", strtotime($k->sales_start_date));
                            $k->sales_end_date = date("M d,Y", strtotime($k->sales_end_date));
                        }

                        // time conversion in 12 hrs format in response
                        foreach($updatedData as $k){
                            $k->event_start_time = date("g:ia", strtotime($k->event_start_time));
                            $k->event_end_time = date("g:ia", strtotime($k->event_end_time));
                            $k->sales_start_time = date("g:ia", strtotime($k->sales_start_time));
                            $k->sales_end_time = date("g:ia", strtotime($k->sales_end_time));
                        }

                        // Notification to users wo purchased tickets of this event
                        $purchasedUsersData = DB::table('user_tickets')->select('event_post_id', 'users_id', 'status')->where([ ['event_post_id', $data->eventPostId], ['status', 'Active'] ])->distinct()->get();
                            if(sizeof($purchasedUsersData) > 0){
                                foreach($purchasedUsersData as $k){
                                    //case when location is updated
                                    if($eventDetailsForNotifications->location_long != $data->locationLong || $eventDetailsForNotifications->location_lat != $data->locationLat){
                                            $senderDetails=  DB::table('users')->where('users_id',$eventDetailsForNotifications->users_id)->first();
                                            $receiverDetails= DB::table('users')->where('users_id',$k->users_id)->first();
                                                if($senderDetails->users_id != $receiverDetails->users_id){
                                                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, 'Location of event '.$eventDetailsForNotifications->title.' is updated just now, Check it out in the app', 'eventLocationUpdate');
                                                        if($notificationResponse){
                                                            $notificationData = array(
                                                                'sender_users_id' => $senderDetails-> users_id,
                                                                'receiver_users_id' => $receiverDetails-> users_id,
                                                                'notification_type' => 'EventLocationUpdate',
                                                                'message' => ' Location of event '.$eventDetailsForNotifications->title.' is updated just now, Check it out in the app',
                                                                'event_post_id' => $data->eventPostId,
                                                                'datetime' => date('Y-m-d H:i:s'),
                                                                'status' => 'Unread'
                                                            );
                                                            $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                                        }
                                                }

                                         }

                                    //case when event schedule timings are being updated
                                    if((string)$eventDetailsForNotifications->event_start_date != (string)$data->eventStartDate || (string)$eventDetailsForNotifications->event_start_time != (string)$data->eventStartTime){
                                        $senderDetails=  DB::table('users')->where('users_id',$eventDetailsForNotifications->users_id)->first();
                                        $receiverDetails= DB::table('users')->where('users_id', $k->users_id)->first();
                                        if($senderDetails->users_id != $receiverDetails->users_id){
                                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, 'Timings Schedule of event '.$eventDetailsForNotifications->title.' is updated just now, Check it out in the app', 'eventTimingsUpdate');
                                            if($notificationResponse){
                                                $notificationData = array(
                                                    'sender_users_id' => $senderDetails-> users_id,
                                                    'receiver_users_id' => $receiverDetails-> users_id,
                                                    'notification_type' => 'EventTimingsUpdate',
                                                    'message' => ' Timings schedule of event '.$eventDetailsForNotifications->title.' is updated just now, Check it out in the app',
                                                    'event_post_id' => $data->eventPostId,
                                                    'datetime' => date('Y-m-d H:i:s'),
                                                    'status' => 'Unread'
                                                );
                                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                            }
                                        }
                                     }
                                }
                            }


                            //deducting onetimepostcount if user is not premium and changed free type event to other types
                            $userDetails = DB::table('users')->where('users_id', $data->userId)->first();
                            if($userDetails->subscription_package_id != '2' && $userDetails->subscription_package_id == '4' && $data->eventTypeId != '2'){
                                $userUpdatedPostCount = $userDetails->one_time_post_count - 1;
                                DB::table('users')->where('users_id',$data->userId)->update(['one_time_post_count' => $userUpdatedPostCount]);
                            }


                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = array("event" => $updatedData);

                         } else {
                            $response["code"] = 500;
                            $response["status"] = "error";
                            $response["message"] = "Updated data not found.";
                        }


                }
                    else {
                             $response["code"] = 404;
                             $response["status"] = "error";
                             $response["message"] = "Event Post does not exists.";
                         }


            }
         else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }

        return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** EDIT/UPDATE EVENT POST ***/


    /*** GET EVENT CITIES FOR SEARCH FILTER ***/
    public function get_event_cities(){

        $event_cities =   DB::table('addresses')
                                        ->select('city')
                                        ->where('status', 'Active')
                                        ->distinct()->get();

       if (sizeof($event_cities) > 0) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = array('cities'=>$event_cities) ;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Events does not exists.";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');

}
    /*** GET EVENT CITIES FOR SEARCH FILTER ***/
/***   GET  EVENT  ***/
    public function get_event(){
        $data = json_decode(file_get_contents("php://input"));
        $response = array();
        $filtered_miles_response = array();
        $filtered_miles_count = array();

        if( isset($data->userId) && isset($data->userLat) && isset($data->userLong) && isset($data->eventId)){

            $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
            $user_timezone = $response["timeZoneId"];

            $givenUserDetails = DB::table('users')->where('users_id', $data->userId)->first();
            if($givenUserDetails->subscription_package_id == 1)
                $basicFlag = true;
            else
                $basicFlag = false;

            //total unread messages count
            $totalUnreadMessagesCount = DB::table('chat_messages')->where([ ['receiver_id', $data->userId], ['status', 'Unread'] ])->count();


            $this->city_filter = $city_filter = (isset($data->cityFilter)? $data->cityFilter: '');
            $this->date_filter = $date_filter = (isset($data->dateFilter)? $data->dateFilter: '');
            $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');
            $this->category_filter = $category_filter = (isset($data->categoryFilter)? $data->categoryFilter: '');
            $this->title_filter = $title_filter = (isset($data->titleFilter)? $data->titleFilter: '');
            // $this->tags_filter = $tags_filter = (isset($data->tagsFilter) ? $data->tagsFilter: '');
            if(isset($data->tagsFilter)){
                $dbQuery = DB::table('event_tags')->select('event_post_id')->whereIn('tag_id',$data->tagsFilter)->distinct()->get();
                $filteredEventTagList = Arr::pluck($dbQuery, 'event_post_id');
                // echo json_encode($filteredEventTagList); exit;
                $this->tags_filter = $tags_filter =  $filteredEventTagList;
            } else{
                $this->tags_filter = $tags_filter = '';
            }


//jaga
            //Query to get total posts with filters for pagination
            $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                ->where('event_posts.event_post_id','=',$data->eventId)
                ->where(function($query) {
                    if($this->city_filter != '')
                    {
                        $query->where('addresses.city',trim($this->city_filter));
                    }
                })->where(function($query) {
                    if( $this->date_filter != '')
                    {
                        $query->where('event_posts.event_start_time','like', '%' . trim($this->date_filter) . '%');
                    }
                })->where(function($query) {
                    if($this->event_type_filter != '')
                    {
                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                    }
                })->where(function($query) {
                    if($this->category_filter != '')
                    {
                        $query->where('event_posts.category_id',$this->category_filter);
                    }
                })->where(function($query) {
                    if($this->title_filter != '')
                    {
                        $query->orWhere('event_posts.title','like', '%' . trim($this->title_filter) . '%')
                            ->orWhere('event_posts.description','like', '%' . trim($this->title_filter) . '%');
                    }
                }) ->where(function($query) {
                    if($this->tags_filter != '')
                    {
                        $query->whereIn('event_posts.event_post_id',$this->tags_filter);
                    }
                })
                ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                ->where('event_posts.status', 'Active')->get();





//jaga
            //Main filtering query with limit
            $filtered_posts =    DB::table('event_posts')
                ->select('event_posts.*','addresses.*')
                ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                ->where('event_posts.event_post_id','=',$data->eventId)
                ->where(function($query) {
                    if($this->city_filter != '')
                    {
                        $query->where('addresses.city',trim($this->city_filter));
                    }
                })
                ->where(function($query) {
                    if( $this->date_filter != '')
                    {
                        $query->where('event_posts.event_start_date','like', '%' . trim($this->date_filter) . '%');
                    }
                })
                ->where(function($query) {
                    if($this->event_type_filter != '')
                    {
                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                    }
                })
                ->where(function($query) {
                    if($this->category_filter != '')
                    {
                        $query->where('event_posts.category_id',$this->category_filter);
                    }
                })
                ->where(function($query) {
                    if($this->title_filter != '')
                    {
                        $query->orWhere('event_posts.title','like', '%' . trim($this->title_filter) . '%')
                            ->orWhere('event_posts.description','like', '%' . trim($this->title_filter) . '%');
                    }
                })
                ->where(function($query) {
                    if($this->tags_filter != '')
                    {
                        $query->whereIn('event_posts.event_post_id',$this->tags_filter);
                    }
                })
                ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                // ->skip($data->offset)->take(5)
                ->orderBy('event_posts.event_post_id', 'DESC')
                ->where('event_posts.status', 'Active')
                ->get();





            //Result if any filter is set then filtered , otherwise all posts will be displayed
            if (sizeof($filtered_posts)>0) {
                //count of total posts after checking  miles check for premium
                if(sizeof($total_posts) == 0){
                    $total_posts_count_after_miles = 0;
                } else{
                    foreach($total_posts as $k){

                        $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                        $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                        $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                        if($organizerDetails->subscription_package_id == '1' || $organizerDetails->subscription_package_id == '4'){
                            //other case, 1000 miles
                            // if($eventDistanceFromUser <= 1000){
                            if($k->event_ticket_type == 'NotMyEvent'){
                                //Remove the event created week ago in this scenario
                                $daysAgo = Helper::get_days_difference($k->created_at);
                                if($daysAgo <= '7'){
                                    array_push($filtered_miles_count, $k);
                                }
                            }else{
                                array_push($filtered_miles_count, $k);
                            }
                            // }

                        } else{
                            //premium organizer and business , both 1000 miles
                            // if($eventDistanceFromUser <= 1000){
                            if($k->event_ticket_type == 'NotMyEvent'){
                                //Remove the event created week ago in this scenario
                                $daysAgo = Helper::get_days_difference($k->created_at);
                                if($daysAgo <= '7'){
                                    array_push($filtered_miles_count, $k);
                                }
                            }else{
                                array_push($filtered_miles_count, $k);
                            }
                            //  }
                        }
                    }

                    $total_posts_count_after_miles = sizeof($filtered_miles_count);
                }


                foreach($filtered_posts as $key => $event_post){


                    // add by nabeel
//                    gp
//            Find differenec between event and user timestamp
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
                    $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                    $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;


//                    $currentDate = date('Y-m-d H:i:s');
//                    $currentTimeSpam = strtotime($currentDate);
//                    $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
//                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
//                        return response()->json(['d1' => $currentDate, 'd2' => $eventSaleStartDateTime]);


                    if($event_post->event_ticket_type == "NotMyEvent"){
                        $event_post->is_editable_event = false;
                    }else{
                        if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                            $event_post->is_editable_event = true;
                        }
                        else{
                            $event_post->is_editable_event = false;
                        }
                    }


                    //appending images urls fields in response
                    $image_base_url = config('base_urls.event_image_base_url');
                    if(!empty( $event_post->first_image)){
                        $event_post->first_image= $image_base_url.$event_post->first_image;
                    }
                    if(!empty( $event_post->second_image)){
                        $event_post->second_image = $image_base_url.$event_post->second_image;
                    }
                    if(!empty( $event_post->third_image)){
                        $event_post->third_image = $image_base_url.$event_post->third_image;
                    }

                    //updating Videos thumbnail urls fields in response
                    $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                    if(!empty( $event_post->first_video_thumbnail)){
                        $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                    }
                    if(!empty( $event_post->second_video_thumbnail)){
                        $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                    }
                    if(!empty( $event_post->third_video_thumbnail)){
                        $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                    }

                    //appending Videos urls fields in response
                    $video_base_url = config('base_urls.event_video_base_url');

                    if(!empty( $event_post->first_video)){
                        $event_post->first_video= $video_base_url.$event_post->first_video;
                    }
                    if(!empty( $event_post->second_video)){
                        $event_post->second_video = $video_base_url.$event_post->second_video;
                    }
                    if(!empty( $event_post->third_video)){
                        $event_post->third_video = $video_base_url.$event_post->third_video;
                    }

                    //time conversion for minutes/hours/days ago
                    foreach($filtered_posts as $k){
//                        $k->time_ago = Helper::time_elapsed_string($k->created_at);
                        $event_timezone = $k->timezone;
                        $k->time_ago = Helper::user_time_elapsed_string($k->created_at,$event_timezone);
                    }

                    // appending tickets quantity info
                    foreach($filtered_posts as $k){
                        $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                        if($early_bird_query){
                            $k->early_bird_available = $early_bird_query->quantity;
                        }

                        $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                        if($regular_query){
                            $k->regular_available = $regular_query->quantity;
                        }

                        $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                        if($vip_query){
                            $k->vip_available = $vip_query->quantity;
                        }

                        $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                        if($skip_query){
                            $k->skipping_line_available = $skip_query->quantity;
                        }


                        $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                        $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                        $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                        $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                        $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
                    }

                    //appending Category data from categories in response array after success
                    $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                    if($category){
                        $k->category = $category;
                    }

                    //appending Event Type data from Event_type in response array after success
                    $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                    if($event_type){
                        $k->event_type = $event_type;
                    }

                    if($event_post->dress_code_id != 0){
                        //appending Dress Code data from dress_code in response array after success
                        $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                        if($dress_code){
                            $k->dress_code = $dress_code;
                        }
                    }else{
                        $k->dress_code = null;
                    }

                    //fetching tags from tags table of each event post
                    $tags =   DB::table('event_tags')
                        ->select('event_tags.*','tags.*')
                        ->join('tags','tags.tag_id','=','event_tags.tag_id')
                        ->where('event_tags.event_post_id',$event_post->event_post_id)
                        ->where('event_tags.status','Active')->get();
                    if($tags){
                        $event_post->event_tags = $tags;
                    }

                    //fetching tickets from tickets table of each event post
                    $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                    $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                    $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                    $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                    if($ticket1){
                        $event_post->Early_bird = $ticket1;
                    }
                    if($ticket2){
                        $event_post->Regular = $ticket2;
                    }
                    if($ticket3){
                        $event_post->VIP = $ticket3;
                    } if($ticket4){
                        $event_post->skipping_line = $ticket4;
                    }


                    //fetching address from address table of each event post
                    $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                    if($address){
                        $event_post->event_address = $address;
                    }

                    //appending liked event post hint
                    $checkLike = DB::table('event_likes')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                    if($checkLike > 0){
                        $event_post->liked = 'true';
                    } else{
                        $event_post->liked = 'false';
                    }

                    //appending favourite post hint
                    $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                    if($isFavouriteQuery > 0){
                        $event_post->isFavourite = 'true';
                    } else{
                        $event_post->isFavourite = 'false';
                    }

                    //appending total likes on event
                    $total_likes =  DB::table('event_posts')->where('event_post_id', $event_post->event_post_id)->first();
                    if($total_likes){
                        $event_post->total_likes = $total_likes->total_likes;
                    }

                    //appending event distance in miles from user current location
                    if(isset($data->userLat) && isset($data->userLong)){
                        $eventCordinates = DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->first();
                        if($eventCordinates){
                            $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                            $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                            $event_post->distance_miles = $distance_miles_calc;
                            $event_post->miles = $miles_calc;
                        }
                    }

                }

                foreach($filtered_posts as $k){
                    $k->sales_end_datetime = $k->sales_end_date.' '.$k->sales_end_time;
                    // Date conversion in 12 hrs format in response
                    $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                    $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));
                    $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                    $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));

                    // time conversion in 12 hrs format in response
                    $k->event_start_time = date("g:iA", strtotime($k->event_start_time));
                    $k->event_end_time = date("g:iA", strtotime($k->event_end_time));
                    $k->sales_start_time = date("g:iA", strtotime($k->sales_start_time));
                    $k->sales_end_time = date("g:iA", strtotime($k->sales_end_time));

                    //Total comments count on Event Post
                    $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $k->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                    $k->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                    if($k->dress_code_id != 0){
                        // Appending dresscode color code for thumbnail bar color ease in event details
                        $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $k->dress_code_id)->first();
                        $k->dress_code_color_code = $dressCodeColor->dress_code_color;
                    }else{
                        $k->dress_code_color_code = null;
                    }


                    //Appeding organizer details
                    $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                    $k->organizer_users_id = $organizerDetails->users_id;
                    $k->organizer_user_name = $organizerDetails->user_name;

                    if($organizerDetails->profile_picture != ""){
                        $k->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                    }else{
                        $k->organizer_profile_picture =  "";
                    }


                    if($k->users_id == $data->userId){
                        $k->is_admin = true;
                    }else{
                        $k->is_admin = false;
                    }

                    //following flag
                    $checkFollowing = DB::table('followers')->where([ ['following_to_user',$k->users_id] ,['followed_by_user' ,$data->userId] ])->first();
                    if($checkFollowing){
                        $k->is_following = true;
                    } else{
                        $k->is_following = false;
                    }

                    //totalFollowers
                    $totalFollowers = DB::table('followers')->where('following_to_user', $k->users_id)->count();
                    $k->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                    //CHECK event peeks available
                    $eventPeeks = DB::table('event_peeks')
                        ->select('event_peeks.*', 'event_posts.*')
                        ->join('event_posts', 'event_posts.event_post_id','=','event_peeks.event_post_id')
                        ->where('event_peeks.event_post_id', $k->event_post_id)
                        ->whereDate('event_posts.event_end_date', '>=', date('Y-m-d'))
                        ->where('event_peeks.created_at', '>=', Carbon::now()->subDay())
                        ->count();

                    if($eventPeeks > 0)
                        $k->is_peeks_available = true;
                    else
                        $k->is_peeks_available = false;

                }

                // display only post in 100 miles for premuium and 50 for other
                foreach($filtered_posts as $k){
                    $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                    $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                    $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                    if($organizerDetails->subscription_package_id == '2'){
                        //premium case, 1000 miles
                        // if($eventDistanceFromUser <= 1000){
                        if($k->event_ticket_type == 'NotMyEvent'){
                            //Remove the event created week ago in this scenario
                            $daysAgo = Helper::get_days_difference($k->created_at);
                            if($daysAgo <= '7'){
                                array_push($filtered_miles_response, $k);
                            }
                        }else{
                            array_push($filtered_miles_response, $k);
                        }
                        // }
                    } else{
                        //other case non premium, 1000 miles
                        // if($eventDistanceFromUser <= 1000){
                        if($k->event_ticket_type == 'NotMyEvent'){
                            //Remove the event created week ago in this scenario
                            $daysAgo = Helper::get_days_difference($k->created_at);
                            if($daysAgo <= '7'){
                                array_push($filtered_miles_response, $k);
                            }
                        }else{
                            array_push($filtered_miles_response, $k);
                        }
                        //   }
                    }
                }

                if(sizeof($filtered_miles_response) > 0){
                    //live stream host room data append
                    foreach($filtered_miles_response as $k){
                        $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->whereDate('live_date','=', date('Y-m-d'))->whereTime('live_end_time','>', date('H:i:s'))->latest()->first();
                        if($liveStreamRoom){
                            $k->is_room_created = true;
                            unset($liveStreamRoom->updated_at);
                            unset($liveStreamRoom->created_at);
                            $liveStreamRoom->converted_start_time = date("g:i A", strtotime($liveStreamRoom->live_start_time));
                            $liveStreamRoom->converted_end_time = date("g:i A", strtotime($liveStreamRoom->live_end_time));

                            $k->room_details = $liveStreamRoom;
                        }else{
                            $k->is_room_created = false;
                        }

                    }

                    $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('miles')->take(5)->toArray());
                    // $filtered_miles_response = array_values(collect($filtered_miles_response)->toArray());



                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["total_posts"] = $total_posts_count_after_miles;
                    $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                    $response["is_basic"] = $basicFlag;
                    $response["data"] = $filtered_miles_response[0];
                }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = 0;
                    $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                    $response["message"] = "Event Post does not exists.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = 0;
                $response["is_basic"] = $basicFlag;
                $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                $response["message"] = "Event Post does not exists.";
            }

        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["total_posts"] = 0;
            $response["is_basic"] = false;
//                $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
            $response["message"] = "All fields are needed";
        }

        return response()
            ->json(array('status' => $response["status"],'is_basic' => $response["is_basic"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
//        ->json(array('status' => $response["status"],'is_basic' => $response["is_basic"],'total_posts' => $response["total_posts"],'total_unread_messages_count' => $response["total_unread_messages_count"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /***   GET  EVENT  ***/

/*** EVENT SEARCH FILTER ***/
public function event_search_filter(){
            $data = json_decode(file_get_contents("php://input"));
            $response = array();
            $filtered_miles_response = array();
            $filtered_miles_count = array();


            if(isset($data->offset) && isset($data->userId) && isset($data->userLat) && isset($data->userLong)){

                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                $user_timezone = $response["timeZoneId"];


                $givenUserDetails = DB::table('users')->where('users_id', $data->userId)->first();
                    if($givenUserDetails->subscription_package_id == 1)
                        $basicFlag = true;
                    else
                        $basicFlag = false;

                    //total unread messages count
                    $totalUnreadMessagesCount = DB::table('chat_messages')->where([ ['receiver_id', $data->userId], ['status', 'Unread'] ])->count();


                $this->city_filter = $city_filter = (isset($data->cityFilter)? $data->cityFilter: '');
                $this->date_filter = $date_filter = (isset($data->dateFilter)? $data->dateFilter: '');
                $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');
                $this->category_filter = $category_filter = (isset($data->categoryFilter)? $data->categoryFilter: '');
                $this->title_filter = $title_filter = (isset($data->titleFilter)? $data->titleFilter: '');
               // $this->tags_filter = $tags_filter = (isset($data->tagsFilter) ? $data->tagsFilter: '');
                    if(isset($data->tagsFilter)){
                        $dbQuery = DB::table('event_tags')->select('event_post_id')->whereIn('tag_id',$data->tagsFilter)->distinct()->get();
                        $filteredEventTagList = Arr::pluck($dbQuery, 'event_post_id');
                       // echo json_encode($filteredEventTagList); exit;
                        $this->tags_filter = $tags_filter =  $filteredEventTagList;
                    } else{
                        $this->tags_filter = $tags_filter = '';
                    }



         //Query to get total posts with filters for pagination
            $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                ->where(function($query) {
                    if($this->city_filter != '')
                    {
                        $query->where('addresses.city',trim($this->city_filter));
                    }
                })->where(function($query) {
                    if( $this->date_filter != '')
                    {
                        $query->where('event_posts.event_start_time','like', '%' . trim($this->date_filter) . '%');
                    }
                })->where(function($query) {
                    if($this->event_type_filter != '')
                    {
                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                    }
                })->where(function($query) {
                    if($this->category_filter != '')
                    {
                        $query->where('event_posts.category_id',$this->category_filter);
                    }
                })->where(function($query) {
                    if($this->title_filter != '')
                    {
                        $query->orWhere('event_posts.title','like', '%' . trim($this->title_filter) . '%')
                        ->orWhere('event_posts.description','like', '%' . trim($this->title_filter) . '%');
                    }
                }) ->where(function($query) {
                    if($this->tags_filter != '')
                    {
                        $query->whereIn('event_posts.event_post_id',$this->tags_filter);
                    }
                })
               ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
              //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                ->where('event_posts.status', 'Active')->get();






        //Main filtering query with limit
        $filtered_postis =    DB::table('event_posts')
                ->select('event_posts.*','addresses.*')
                ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                    ->where(function($query) {
                        if($this->city_filter != '')
                        {
                            $query->where('addresses.city',trim($this->city_filter));
                        }
                    })
                    ->where(function($query) {
                        if( $this->date_filter != '')
                        {
                            $query->where('event_posts.event_start_date','like', '%' . trim($this->date_filter) . '%');
                        }
                    })
                    ->where(function($query) {
                        if($this->event_type_filter != '')
                        {
                            $query->where('event_posts.event_type_id',$this->event_type_filter);
                        }
                    })
                    ->where(function($query) {
                        if($this->category_filter != '')
                        {
                            $query->where('event_posts.category_id',$this->category_filter);
                        }
                    })
                    ->where(function($query) {
                        if($this->title_filter != '')
                        {
                            $query->orWhere('event_posts.title','like', '%' . trim($this->title_filter) . '%')
                            ->orWhere('event_posts.description','like', '%' . trim($this->title_filter) . '%');
                        }
                    })
                    ->where(function($query) {
                        if($this->tags_filter != '')
                        {
                            $query->whereIn('event_posts.event_post_id',$this->tags_filter);
                        }
                    })
                    ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                  //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                    // ->skip($data->offset)->take(5)
                    ->orderBy('event_posts.event_post_id', 'DESC')
                    ->where('event_posts.status', 'Active')
                    ->get();



        $filtered_posts = array();
        
        

        foreach ($filtered_postis as $eventi){

            // cp
//            $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$eventi->location_lat.','.$eventi->location_long.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
            $event_timezone = $eventi->timezone;

//            Find differenec between event and user timestamp
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
            $eventSaleStartDateTime = $eventi->event_end_date." ".$eventi->event_end_time;
            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
            $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;
//            $date = date('d-m-Y H:i:s', $eventSaleStartDateTimeSpam);
//            return response($currentDate."   ".$date);
            if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                array_push($filtered_posts, $eventi);
            }else{

            }
        }


               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {

                            //count of total posts after checking  miles check for premium
                            if(sizeof($total_posts) == 0){
                                $total_posts_count_after_miles = 0;
                            } else{
                                foreach($total_posts as $k){


                                    $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                                    $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );


                                    $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                                    if($organizerDetails->subscription_package_id == '1' || $organizerDetails->subscription_package_id == '4'){
                                        //other case, 1000 miles
                                       // if($eventDistanceFromUser <= 1000){
                                            if($k->event_ticket_type == 'NotMyEvent'){
                                                //Remove the event created week ago in this scenario
                                                $daysAgo = Helper::get_days_difference($k->created_at);
                                                if($daysAgo <= '7'){
                                                    array_push($filtered_miles_count, $k);
                                                }
                                            }else{
                                                array_push($filtered_miles_count, $k);
                                            }
                                       // }

                                    } else{
                                            //premium organizer and business , both 1000 miles
                                               // if($eventDistanceFromUser <= 1000){
                                                    if($k->event_ticket_type == 'NotMyEvent'){
                                                        //Remove the event created week ago in this scenario
                                                        $daysAgo = Helper::get_days_difference($k->created_at);
                                                        if($daysAgo <= '7'){
                                                            array_push($filtered_miles_count, $k);
                                                        }
                                                    }else{
                                                        array_push($filtered_miles_count, $k);
                                                    }
                                              //  }
                                    }
                                }

                                $total_posts_count_after_miles = sizeof($filtered_miles_count);
                            }





                    foreach($filtered_posts as $key => $event_post){


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
                        $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
                        $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                        $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;



//                        // add by nabeel
//                        $currentDate = date('Y-m-d H:i:s');
//                        $currentTimeSpam = strtotime($currentDate);
//                        $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
//                        $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
////                        return response()->json(['d1' => $currentDate, 'd2' => $eventSaleStartDateTime]);


                        if($event_post->event_ticket_type == "NotMyEvent"){
                            $event_post->is_editable_event = false;
                        }else{
                            if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                $event_post->is_editable_event = true;
                            }
                            else{
                                $event_post->is_editable_event = false;
                            }
                        }


                         //appending images urls fields in response
                         $image_base_url = config('base_urls.event_image_base_url');
                         
                        //  return response($image_base_url);
                         
                         
                         if(!empty( $event_post->first_image)){
                             $event_post->first_image= $image_base_url.$event_post->first_image;
                         }
                          if(!empty( $event_post->second_image)){
                             $event_post->second_image = $image_base_url.$event_post->second_image;
                         }
                          if(!empty( $event_post->third_image)){
                             $event_post->third_image = $image_base_url.$event_post->third_image;
                         }

                   //updating Videos thumbnail urls fields in response
                   $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                         if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                         }
                         if(!empty( $event_post->second_video_thumbnail)){
                              $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                          }
                          if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }

                   //appending Videos urls fields in response
                   $video_base_url = config('base_urls.event_video_base_url');

                             if(!empty( $event_post->first_video)){
                                 $event_post->first_video= $video_base_url.$event_post->first_video;
                             }
                             if(!empty( $event_post->second_video)){
                                 $event_post->second_video = $video_base_url.$event_post->second_video;
                             }
                             if(!empty( $event_post->third_video)){
                                 $event_post->third_video = $video_base_url.$event_post->third_video;
                             }

                             //time conversion for minutes/hours/days ago
                             foreach($filtered_posts as $k){
//                                 date_default_timezone_set($user_timezone);

//                                 $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$k->location_lat.','.$k->location_long.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
//                                 $event_timezone = $response["timeZoneId"];
                                 $event_timezone = $k->timezone;
                                 $k->time_ago = Helper::user_time_elapsed_string($k->created_at,$event_timezone);

                             }

                              // appending tickets quantity info
                              foreach($filtered_posts as $k){
                                     $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                                        if($early_bird_query){
                                            $k->early_bird_available = $early_bird_query->quantity;
                                        }

                                     $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                                        if($regular_query){
                                            $k->regular_available = $regular_query->quantity;
                                        }

                                     $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                                        if($vip_query){
                                            $k->vip_available = $vip_query->quantity;
                                        }

                                  $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                                  if($skip_query){
                                      $k->skipping_line_available = $skip_query->quantity;
                                  }


                                            $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                                            $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                                            $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                                            $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                                    $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
                            }

                               //appending Category data from categories in response array after success
                               $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                                   if($category){
                                       $k->category = $category;
                                   }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                                    if($event_type){
                                        $k->event_type = $event_type;
                                    }

                             if($event_post->dress_code_id != 0){
                                  //appending Dress Code data from dress_code in response array after success
                                    $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                                    if($dress_code){
                                        $k->dress_code = $dress_code;
                                    }
                             }else{
                                 $k->dress_code = null;
                             }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                             ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $event_post->event_tags = $tags;
                                }

                                  //fetching tickets from tickets table of each event post
                            $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                            $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                            $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                            $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                            if($ticket1){
                                $event_post->Early_bird = $ticket1;
                            }
                            if($ticket2){
                                $event_post->Regular = $ticket2;
                            }
                            if($ticket3){
                                $event_post->VIP = $ticket3;
                            } if($ticket4){
                                $event_post->skipping_line = $ticket4;
                            }


                         //fetching address from address table of each event post
                         $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $event_post->event_address = $address;
                            }

                            //appending liked event post hint
                        $checkLike = DB::table('event_likes')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                            if($checkLike > 0){
                                $event_post->liked = 'true';
                            } else{
                                $event_post->liked = 'false';
                            }

                            //appending favourite post hint
                        $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                        if($isFavouriteQuery > 0){
                            $event_post->isFavourite = 'true';
                        } else{
                            $event_post->isFavourite = 'false';
                        }

                            //appending total likes on event
                            $total_likes =  DB::table('event_posts')->where('event_post_id', $event_post->event_post_id)->first();
                                if($total_likes){
                                    $event_post->total_likes = $total_likes->total_likes;
                                }

                                  //appending event distance in miles from user current location
                                if(isset($data->userLat) && isset($data->userLong)){
                                        $eventCordinates = DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->first();
                                        if($eventCordinates){
                                            $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                            $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");

//                                            if((float)$distance_miles_calc < 1){
//                                                $event_post->distance_miles = $distance_miles_calc * 5280;
//                                            }
                                            $event_post->distance_miles = $distance_miles_calc;
                                            $event_post->miles = $miles_calc;

                                        }
                                }

                      }

                            foreach($filtered_posts as $k){

//                                yaha
                                // add by nabeel
//                                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$eventi->location_lat.','.$eventi->location_long.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
//                                $event_timezone = $response["timeZoneId"];
                                $event_timezone = $k->timezone;

//            Find differenec between event and user timestamp
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
                                $eventSaleStartDateTime = $k->event_end_date." ".$k->event_end_time;
                                $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                                $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;
//                                nabeel
//                                Convert event start time
                                date_default_timezone_set($event_timezone);
                                $event_stater = $k->event_start_date.' '.$k->event_start_time;
                                $event_staterTimeSpam = strtotime($event_stater);
                                $event_staterTimeSpam = $event_staterTimeSpam - $diff;
                                $eventstart_dateTime = date('d-m-Y H:i:s', $event_staterTimeSpam);


//                                Convert event end time
                                $event_ender = $k->event_end_date.' '.$k->event_end_time;
                                $event_enderTimeSpam = strtotime($event_ender);
                                $event_enderTimeSpam = $event_enderTimeSpam - $diff;
                                $eventend_dateTime = date('d-m-Y H:i:s', $event_enderTimeSpam);


//                                Convert event sales time
                                $event_sales_start = $k->sales_start_date.' '.$k->sales_start_time;
                                $event_sales_startTimeSpam = strtotime($event_sales_start);
                                $event_sales_startTimeSpam = $event_sales_startTimeSpam - $diff;
                                $event_sales_start_dateTime = date('d-m-Y H:i:s', $event_sales_startTimeSpam);

//                                Convert event sales end time
                                $event_sales_end = $k->sales_end_date.' '.$k->sales_end_time;
                                $event_sales_endTimeSpam = strtotime($event_sales_end);
                                $event_sales_endTimeSpam = $event_sales_endTimeSpam - $diff;
                                $event_sales_end_dateTime = date('Y-m-d H:i:s', $event_sales_endTimeSpam);


//return response($event_sales_end."   ".$event_sales_end_dateTime);

                                $k->sales_end_datetime = $event_sales_end_dateTime;
//                                $k->sales_end_datetime = "2022-07-30 13:27:00";
                                ;
//                                return response($event_sales_end_dateTime);

                                // Date conversion in 12 hrs format in response
                                $k->event_start_date = date("M d, Y", strtotime($eventstart_dateTime));
                                $k->event_end_date = date("M d, Y", strtotime($eventend_dateTime));
                                $k->sales_start_date = date("M d, Y", strtotime($event_sales_start_dateTime));
                                $k->sales_end_date = date("M d, Y", strtotime($event_sales_end_dateTime));

                            // time conversion in 12 hrs format in response
                                $k->event_start_time = date("g:iA", strtotime($eventstart_dateTime));
                                $k->event_end_time = date("g:iA", strtotime($eventend_dateTime));
                                $k->sales_start_time = date("g:iA", strtotime($event_sales_start_dateTime));
                                $k->sales_end_time = date("g:iA", strtotime($event_sales_end_dateTime));

                                //Total comments count on Event Post cmm
                            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $k->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                                $k->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                                if($k->dress_code_id != 0){
                                    // Appending dresscode color code for thumbnail bar color ease in event details
                                        $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $k->dress_code_id)->first();
                                        $k->dress_code_color_code = $dressCodeColor->dress_code_color;
                                }else{
                                    $k->dress_code_color_code = null;
                                }


                                //Appeding organizer details
                                $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                                $k->organizer_users_id = $organizerDetails->users_id;
                                $k->organizer_user_name = $organizerDetails->user_name;

                                if($organizerDetails->profile_picture != ""){
                                    $k->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                                }else{
                                    $k->organizer_profile_picture =  "";
                                }


                                if($k->users_id == $data->userId){
                                    $k->is_admin = true;
                                }else{
                                    $k->is_admin = false;
                                }

                                //following flag
                                $checkFollowing = DB::table('followers')->where([ ['following_to_user',$k->users_id] ,['followed_by_user' ,$data->userId] ])->first();
                                if($checkFollowing){
                                    $k->is_following = true;
                                } else{
                                    $k->is_following = false;
                                }

                                //totalFollowers
                                $totalFollowers = DB::table('followers')->where('following_to_user', $k->users_id)->count();
                                $k->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                                //CHECK event peeks available
                                $eventPeeks = DB::table('event_peeks')
                                                            ->select('event_peeks.*', 'event_posts.*')
                                                            ->join('event_posts', 'event_posts.event_post_id','=','event_peeks.event_post_id')
                                                            ->where('event_peeks.event_post_id', $k->event_post_id)
                                                            ->whereDate('event_posts.event_end_date', '>=', date('Y-m-d'))
                                                            ->where('event_peeks.created_at', '>=', Carbon::now()->subDay())
                                                            ->count();

                                if($eventPeeks > 0)
                                    $k->is_peeks_available = true;
                                else
                                    $k->is_peeks_available = false;

                            }

                            // display only post in 100 miles for premuium and 50 for other
                            foreach($filtered_posts as $k){
                                $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                                $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                                $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                                if($organizerDetails->subscription_package_id == '2'){
                                    //premium case, 1000 miles
                                   // if($eventDistanceFromUser <= 1000){
                                        if($k->event_ticket_type == 'NotMyEvent'){
                                            //Remove the event created week ago in this scenario
                                            $daysAgo = Helper::get_days_difference($k->created_at);
                                            if($daysAgo <= '7'){
                                                array_push($filtered_miles_response, $k);
                                              }
                                          }else{
                                                array_push($filtered_miles_response, $k);
                                            }
                                   // }
                                } else{
                                    //other case non premium, 1000 miles
                                   // if($eventDistanceFromUser <= 1000){
                                        if($k->event_ticket_type == 'NotMyEvent'){
                                            //Remove the event created week ago in this scenario
                                            $daysAgo = Helper::get_days_difference($k->created_at);
                                            if($daysAgo <= '7'){
                                                array_push($filtered_miles_response, $k);
                                              }
                                          }else{
                                                array_push($filtered_miles_response, $k);
                                            }
                                 //   }
                                }
                            }






                        if(sizeof($filtered_miles_response) > 0){
                            //live stream host room data append nabeel
                            foreach($filtered_miles_response as $k){
                                $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->latest()->first();
//                                $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->whereDate('live_date','=', date('Y-m-d'))->whereTime('live_end_time','>', date('H:i:s'))->latest()->first();
                                if($liveStreamRoom){


//                                    yaha
                                    // add by nabeel
//                                    $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$k->location_lat.','.$k->location_long.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
//                                    $event_timezone = $response["timeZoneId"];
                                    $event_timezone = $k->timezone;

//            Find differenec between event and user timestamp
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


//                                    Convert live stream Room Time
                                    date_default_timezone_set($event_timezone);
                                    $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
                                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                                    $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;
//            $date = date('d-m-Y H:i:s', $eventSaleStartDateTimeSpam);
//            return response($currentDate."   ".$date);
//                                    return response($liveStreamRoom->host_room_id."   ".$currentDate."   ".$date);


//                                    $currentDate = date('Y-m-d H:i:s');
//                                    $currentTimeSpam = strtotime($currentDate);
//                                    $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
//                                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                                    $data = json_decode(file_get_contents("php://input"));


                                    $logs_data = array(
                                        'name' => $k->event_post_id,
                                        'error' => $currentDate,
                                        'json' => $eventSaleStartDateTime,
                                    );
                                    $ticketsInsertQuery = DB::table('logs')->insertGetId($logs_data);

//                                    return response(["date1" => $currentDate,"date2" => $eventSaleStartDateTime]);

                                        if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                            $k->is_room_created = true;
                                            unset($liveStreamRoom->updated_at);
                                            unset($liveStreamRoom->created_at);
                                            $liveStreamRoom->converted_start_time = date("g:i A", strtotime($liveStreamRoom->live_start_time));
                                            $liveStreamRoom->converted_end_time = date("g:i A", strtotime($liveStreamRoom->live_end_time));
                                            $k->room_details = $liveStreamRoom;                                        }
                                        else{
                                            $k->is_room_created = false;
                                        }
                                }else{
                                    $k->is_room_created = false;
                                }

                            }

                            $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('miles')->skip($data->offset)->take(5)->toArray());
                            // $filtered_miles_response = array_values(collect($filtered_miles_response)->toArray());




                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["total_posts"] = $total_posts_count_after_miles;
                            $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                            $response["is_basic"] = $basicFlag;
                            $response["data"] = $filtered_miles_response;
                        }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["total_posts"] = 0;
                            $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                            $response["message"] = "Event Post does not exists.";
                        }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = 0;
                    $response["is_basic"] = $basicFlag;
                    $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                    $response["message"] = "Event Post does not exists.";
                }

            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = 0;
                $response["is_basic"] = false;
//                $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                $response["message"] = "All fields are needed";
            }

        return response()
        ->json(array('status' => $response["status"],'is_basic' => $response["is_basic"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
//        ->json(array('status' => $response["status"],'is_basic' => $response["is_basic"],'total_posts' => $response["total_posts"],'total_unread_messages_count' => $response["total_unread_messages_count"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }
         /*** EVENT SEARCH FILTER ***/


           /*** EVENT SEARCH BAR FILTER ***/
           public function event_search_bar_filter(){
            $data = json_decode(file_get_contents("php://input"));
            $response = array();

            if(isset($data->offset) && isset($data->userId) && isset($data->userLat) && isset($data->userLong) && isset($data->searchFilter)){
                    //total unread messages count

                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                $user_timezone = $response["timeZoneId"];


                    $totalUnreadMessagesCount = DB::table('chat_messages')->where([ ['receiver_id', $data->userId], ['status', 'Unread'] ])->count();


              $this->search_filter =  $search_filter = $data->searchFilter;
                $titleDescriptionBasedEventIds = DB::table('event_posts')->where(function($query) {
                                                                            $query->where('title','like', '%' . trim($this->search_filter) . '%');
                                                                            $query->orWhere('description','like', '%' . trim($this->search_filter) . '%');
                                                                    })->pluck('event_post_id');

                        $CityBasedEventIds = DB::table('addresses')->where(function($query) {
                                                                            $query->where('city','like', '%' . trim($this->search_filter) . '%');
                                                                    })->pluck('event_post_id');

                        $tagIds = DB::table('tags')->where(function($query) {
                                                                         $query->where('tag_name','like', '%' . trim($this->search_filter) . '%');
                                                                    })->pluck('tag_id');


                       $tagsBasedEventIds = DB::table('event_tags')->whereIn('tag_id', $tagIds)->pluck('event_post_id');

                       $mergedArrays = array_unique(array_merge($titleDescriptionBasedEventIds->toArray(),$CityBasedEventIds->toArray(),$tagsBasedEventIds->toArray()));
                       $finalEventPostIds = array_values(collect($mergedArrays)->sort()->toArray());

                       $filtered_posts =  DB::table('event_posts')
                                                        ->select('event_posts.*','addresses.*')
                                                        ->leftJoin('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                                            ->whereIn('event_posts.event_post_id', $finalEventPostIds)
                                                            ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                                        //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                                                            ->skip($data->offset)->take(5)
                                                            ->orderBy('event_posts.event_post_id', 'DESC')
                                                            ->where('event_posts.status', 'Active')
                                                        ->get();
                           //total posts count
                            $total_posts  = sizeof($filtered_posts);

               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {
                    foreach($filtered_posts as $key => $event_post){
                         //appending images urls fields in response
                         $image_base_url = config('base_urls.event_image_base_url');
                         if(!empty( $event_post->first_image)){
                             $event_post->first_image= $image_base_url.$event_post->first_image;
                         }
                          if(!empty( $event_post->second_image)){
                             $event_post->second_image = $image_base_url.$event_post->second_image;
                         }
                          if(!empty( $event_post->third_image)){
                             $event_post->third_image = $image_base_url.$event_post->third_image;
                         }

                   //updating Videos thumbnail urls fields in response
                   $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                         if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                         }
                         if(!empty( $event_post->second_video_thumbnail)){
                              $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                          }
                          if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }

                   //appending Videos urls fields in response
                   $video_base_url = config('base_urls.event_video_base_url');

                             if(!empty( $event_post->first_video)){
                                 $event_post->first_video= $video_base_url.$event_post->first_video;
                             }
                             if(!empty( $event_post->second_video)){
                                 $event_post->second_video = $video_base_url.$event_post->second_video;
                             }
                             if(!empty( $event_post->third_video)){
                                 $event_post->third_video = $video_base_url.$event_post->third_video;
                             }


                              // appending tickets quantity info
                              foreach($filtered_posts as $k){
                                   //time conversion for minutes/hours/days ago
//                                  $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$k->location_lat.','.$k->location_long.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
//                                  $event_timezone = $response["timeZoneId"];
//                                  date_default_timezone_set($event_timezone);


//                                  ya wala
                                  $event_timezone = $k->timezone;
                                  $k->time_ago = Helper::user_time_elapsed_string($k->created_at,$event_timezone);
//                                  $k->time_ago = Helper::time_elapsed_string($k->created_at);

                                     $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                                        if($early_bird_query){
                                            $k->early_bird_available = $early_bird_query->quantity;
                                        }

                                     $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                                        if($regular_query){
                                            $k->regular_available = $regular_query->quantity;
                                        }

                                     $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                                        if($vip_query){
                                            $k->vip_available = $vip_query->quantity;
                                        }

                                  $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                                  if($skip_query){
                                      $k->skipping_line_available = $skip_query->quantity;
                                  }


                                  $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                                  $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                                  $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                                  $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                                  $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
                            }

                               //appending Category data from categories in response array after success
                               $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                                   if($category){
                                       $k->category = $category;
                                   }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                                    if($event_type){
                                        $k->event_type = $event_type;
                                    }

                             if($event_post->dress_code_id != 0){
                                  //appending Dress Code data from dress_code in response array after success
                                    $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                                    if($dress_code){
                                        $k->dress_code = $dress_code;
                                    }
                             }else{
                                 $k->dress_code = null;
                             }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                                ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $event_post->event_tags = $tags;
                                }

                        //fetching tickets from tickets table of each event post
                        $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                        $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                        $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                        $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                        if($ticket1){
                            $event_post->Early_bird = $ticket1;
                        }
                        if($ticket2){
                            $event_post->Regular = $ticket2;
                        }
                        if($ticket3){
                            $event_post->VIP = $ticket3;
                        } if($ticket4){
                            $event_post->skipping_line = $ticket4;
                        }


                         //fetching address from address table of each event post
                         $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $event_post->event_address = $address;
                            }

                            //appending liked event post hint
                        $checkLike = DB::table('event_likes')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                            if($checkLike > 0){
                                $event_post->liked = 'true';
                            } else{
                                $event_post->liked = 'false';
                            }

                            //appending favourite post hint
                        $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                        if($isFavouriteQuery > 0){
                            $event_post->isFavourite = 'true';
                        } else{
                            $event_post->isFavourite = 'false';
                        }

                            //appending total likes on event
                            $total_likes =  DB::table('event_posts')->where('event_post_id', $event_post->event_post_id)->first();
                                if($total_likes){
                                    $event_post->total_likes = $total_likes->total_likes;
                                }

                                  //appending event distance in miles from user current location
                                if(isset($data->userLat) && isset($data->userLong)){
                                        $eventCordinates = DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->first();
                                        if($eventCordinates){
                                            $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                                $event_post->distance_miles = $distance_miles_calc;
                                                $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                                $event_post->miles = $miles_calc;
                                        }
                                }

                      }

                            foreach($filtered_posts as $k){
//                                ya wala 2

                                $k->sales_end_datetime = $k->sales_end_date.' '.$k->sales_end_time;

                                  // Date conversion in 12 hrs format in response
                                $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                                $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));
                                $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                                $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));

                            // time conversion in 12 hrs format in response
                                $k->event_start_time = date("g:iA", strtotime($k->event_start_time));
                                $k->event_end_time = date("g:iA", strtotime($k->event_end_time));
                                $k->sales_start_time = date("g:iA", strtotime($k->sales_start_time));
                                $k->sales_end_time = date("g:iA", strtotime($k->sales_end_time));

                                //Total comments count on Event Post
                            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $k->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                                $k->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                                if($k->dress_code_id != 0){
                                    // Appending dresscode color code for thumbnail bar color ease in event details
                                        $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $k->dress_code_id)->first();
                                        $k->dress_code_color_code = $dressCodeColor->dress_code_color;
                                }else{
                                    $k->dress_code_color_code = null;
                                }


                                //Appeding organizer details
                                $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                                $k->organizer_users_id = $organizerDetails->users_id;
                                $k->organizer_user_name = $organizerDetails->user_name;

                                if($organizerDetails->profile_picture != ""){
                                    $k->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                                }else{
                                    $k->organizer_profile_picture =  "";
                                }

                                //following flag
                                $checkFollowing = DB::table('followers')->where([ ['following_to_user',$k->users_id] ,['followed_by_user' ,$data->userId] ])->first();
                                if($checkFollowing){
                                    $k->is_following = true;
                                } else{
                                    $k->is_following = false;
                                }

                                //totalFollowers
                                $totalFollowers = DB::table('followers')->where('following_to_user', $k->users_id)->count();
                                $k->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                                //live stream host room data append
                                $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->whereDate('live_date','=', date('Y-m-d'))->whereTime('live_end_time','>', date('H:i:s'))->latest()->first();
                                if($liveStreamRoom){
                                    $k->is_room_created = true;
                                    unset($liveStreamRoom->updated_at);
                                    unset($liveStreamRoom->created_at);
                                    $liveStreamRoom->converted_start_time = date("g:i A", strtotime($liveStreamRoom->live_start_time));
                                    $liveStreamRoom->converted_end_time = date("g:i A", strtotime($liveStreamRoom->live_end_time));

                                    $k->room_details = $liveStreamRoom;
                                }else{
                                    $k->is_room_created = false;
                                }

                            }


                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["total_posts"] = $total_posts;
                            $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                            $response["data"] = $filtered_posts;

                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = 0;
                    $response["total_unread_messages_count"] = $totalUnreadMessagesCount;
                    $response["message"] = "Event Post does not exists.";
                }

            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = 0;
                $response["total_unread_messages_count"] = 0;
                $response["message"] = "All fields are needed";
            }

        return response()
        ->json(array('status' => $response["status"],'total_posts' => $response["total_posts"],'total_unread_messages_count' => $response["total_unread_messages_count"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }




      /*** HOST ROOM EVENT POSTS ***/
         public function host_room_event_posts(){
            $data = json_decode(file_get_contents("php://input"));
            $response = array();

            if(isset($data->userId)){

            $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');

        //Query to get total posts with filters for pagination
         $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                ->where(function($query) {
                    if($this->event_type_filter != '')
                    {
                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                    }
                })
                 ->where('event_posts.users_id',$data->userId)
                 ->where('event_posts.event_ticket_type','Paid')
                 ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                //->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                ->where('event_posts.status', 'Active')->count();

    //Main filtering query with limit
        $filtered_posts =    DB::table('event_posts')
                                        ->select('event_posts.*','addresses.*')
                                        ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                            ->where(function($query) {
                                                if($this->event_type_filter != '')
                                                {
                                                    $query->where('event_posts.event_type_id',$this->event_type_filter);
                                                }
                                            })
                                            ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                        //   ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                                            ->where('event_posts.users_id',$data->userId)
                                        ->where('event_posts.event_ticket_type', '!=', 'NotMyEvent')
                                            ->where('event_posts.status', 'Active')
                                        ->get();

               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {

                    foreach($filtered_posts as $key => $event_post){

                         //appending images urls fields in response
                         $image_base_url = config('base_urls.event_image_base_url');

                         if(!empty( $event_post->first_image)){
                             $event_post->first_image= $image_base_url.$event_post->first_image;
                         }
                          if(!empty( $event_post->second_image)){
                             $event_post->second_image = $image_base_url.$event_post->second_image;
                         }
                          if(!empty( $event_post->third_image)){
                             $event_post->third_image = $image_base_url.$event_post->third_image;
                         }

                   //updating Videos thumbnail urls fields in response
                   $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');

                         if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                         }
                         if(!empty( $event_post->second_video_thumbnail)){
                              $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                          }
                          if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }


                   //appending Videos urls fields in response
                   $video_base_url = config('event_video_base_url');

                             if(!empty( $event_post->first_video)){
                                 $event_post->first_video= $video_base_url.$event_post->first_video;
                             }
                             if(!empty( $event_post->second_video)){
                                 $event_post->second_video = $video_base_url.$event_post->second_video;
                             }
                             if(!empty( $event_post->third_video)){
                                 $event_post->third_video = $video_base_url.$event_post->third_video;
                             }

                   // appending tickets quantity info
                   foreach($filtered_posts as $k){
                    $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                       if($early_bird_query){
                           $k->early_bird_quantity = $early_bird_query->quantity;
                       }

                    $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                       if($regular_query){
                           $k->regular_quantity = $regular_query->quantity;
                       }

                    $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                       if($vip_query){
                           $k->vip_quantity = $vip_query->quantity;
                       }

                       $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                       if($skip_query){
                           $k->skipping_line_available = $skip_query->quantity;
                       }


                       $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                       $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                       $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                       $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                       $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
            }
                               //appending Category data from categories in response array after success
                               $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                                   if($category){
                                       $event_post->category = $category;
                                   }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                                    if($event_type){
                                        $event_post->event_type = $event_type;
                                    }

                             if($event_post->dress_code_id != 0){
                                  //appending Dress Code data from dress_code in response array after success
                                $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                                if($dress_code){
                                    $event_post->dress_code = $dress_code;

                                }
                             }else{
                                 $event_post->dress_code = null;
                             }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                             ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $event_post->event_tags = $tags;
                                }


                        //fetching tickets from tickets table of each event post
                        $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                        $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                        $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                        $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                        if($ticket1){
                            $event_post->Early_bird = $ticket1;
                        }
                        if($ticket2){
                            $event_post->Regular = $ticket2;
                        }
                        if($ticket3){
                            $event_post->VIP = $ticket3;
                        } if($ticket4){
                            $event_post->skipping_line = $ticket4;
                        }


                         //fetching address from address table of each event post
                         $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $event_post->event_address = $address;
                            }

                    }

                // Date conversion in 12 hrs format in response
                foreach($filtered_posts as $k){
                    $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                    $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));

                        $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                        $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));
                }

                // time conversion in 12 hrs format in response
                foreach($filtered_posts as $k){
                    $k->event_start_time = date("g:i A", strtotime($k->event_start_time));
                    $k->event_end_time = date("g:i A", strtotime($k->event_end_time));

                        $k->sales_start_time = date("g:i A", strtotime($k->sales_start_time));
                       $k->sales_end_time = date("g:i A", strtotime($k->sales_end_time));
                }
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["total_posts"] = $total_posts;
                    $response["data"] =  $filtered_posts;


                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = "0";
                    $response["message"] = "Event Post does not exists.";
                }

            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = "0";
                $response["message"] = "All details are missing";
            }

        return response()
        ->json(array('status' => $response["status"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }
         /*** USER EVENT POST WITH EVENT TYPE FILTER ***/



           /*** USER EVENT POST WITH EVENT TYPE FILTER ***/
           public function user_event_type_posts(){
            $data = json_decode(file_get_contents("php://input"));
            $response = array();

//            gp
            if(isset($data->userId) && isset($data->userLat) && isset($data->userLong)  ){

                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                $user_timezone = $response["timeZoneId"];

            $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');

        //Query to get total posts with filters for pagination
         $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                ->where(function($query) {
                    if($this->event_type_filter != '')
                    {
                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                    }
                })
                 ->where('event_posts.users_id',$data->userId)
                //  ->where('event_posts.event_ticket_type','Paid')
                 ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                //->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                ->where('event_posts.status', 'Active')->count();

    //Main filtering query with limit
        $filtered_posts =    DB::table('event_posts')
                ->select('event_posts.*','addresses.*')
                ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                     ->where(function($query) {
                        if($this->event_type_filter != '')
                        {
                            $query->where('event_posts.event_type_id',$this->event_type_filter);
                        }
                    })
                    ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                 //   ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                      ->where('event_posts.users_id',$data->userId)
                //  ->where('event_posts.event_ticket_type','Paid')
                    ->where('event_posts.status', 'Active')
                 ->get();



               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {

                    foreach($filtered_posts as $key => $event_post){

//                        // add by nabeel
//                         $currentDate = date('Y-m-d H:i:s');
//                         $currentTimeSpam = strtotime($currentDate);
//                         $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
//                         $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                        $event_timezone = $event_post->timezone;

//            Find differenec between event and user timestamp
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
                        $eventSaleStartDateTime = $event_post->sales_start_date." ".$event_post->sales_start_time;
                        $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                        $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;


                         if($event_post->event_ticket_type == "NotMyEvent"){
                             $event_post->is_editable_event = false;
                         }else if($event_post->event_ticket_type == "MyFreeEvent"){




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
                             $eventSaleStartDateTime = $event_post->event_start_date." ".$event_post->event_start_time;
                             $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                             $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;



//                             // add by nabeel
//                             $currentDate = date('Y-m-d H:i:s');
//                             $currentTimeSpam = strtotime($currentDate);
//                             $eventSaleStartDateTime = $event_post->event_start_date." ".$event_post->event_start_time;
//                             $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                             if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                 $event_post->is_editable_event = true;
                             }
                             else{
                                 $event_post->is_editable_event = true;
                             }

                                }else{
                                    if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                        $event_post->is_editable_event = true;
                                    }
                                    else{
                                        $event_post->is_editable_event = true;
                                    }
                                }



                         //  $event_post->is_editable_event = $event_post->sales_start_date." ".$event_post->sales_start_time;


                         //appending images urls fields in response
                         $image_base_url = config('base_urls.event_image_base_url');

                         if(!empty( $event_post->first_image)){
                             $event_post->first_image= $image_base_url.$event_post->first_image;
                         }
                          if(!empty( $event_post->second_image)){
                             $event_post->second_image = $image_base_url.$event_post->second_image;
                         }
                          if(!empty( $event_post->third_image)){
                             $event_post->third_image = $image_base_url.$event_post->third_image;
                         }
                        //  sales_start_date sales_start_time
                   //updating Videos thumbnail urls fields in response
                   $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');

                         if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                         }
                         if(!empty( $event_post->second_video_thumbnail)){
                              $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                          }
                          if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }


                   //appending Videos urls fields in response
                   $video_base_url = config('event_video_base_url');

                             if(!empty( $event_post->first_video)){
                                 $event_post->first_video= $video_base_url.$event_post->first_video;
                             }
                             if(!empty( $event_post->second_video)){
                                 $event_post->second_video = $video_base_url.$event_post->second_video;
                             }
                             if(!empty( $event_post->third_video)){
                                 $event_post->third_video = $video_base_url.$event_post->third_video;
                             }

                   // appending tickets quantity info
                   foreach($filtered_posts as $k){
                    $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                       if($early_bird_query){
                           $k->early_bird_quantity = $early_bird_query->quantity;
                       }

                    $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                       if($regular_query){
                           $k->regular_quantity = $regular_query->quantity;
                       }

                    $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                       if($vip_query){
                           $k->vip_quantity = $vip_query->quantity;
                       }


                       $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                       if($skip_query){
                           $k->skipping_line_available = $skip_query->quantity;
                       }


                       $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                       $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                       $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                       $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                       $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
            }
                               //appending Category data from categories in response array after success
                               $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                                   if($category){
                                       $event_post->category = $category;
                                   }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                                    if($event_type){
                                        $event_post->event_type = $event_type;
                                    }

                             if($event_post->dress_code_id != 0){
                                  //appending Dress Code data from dress_code in response array after success
                                $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                                if($dress_code){
                                    $event_post->dress_code = $dress_code;

                                }
                             }else{
                                 $event_post->dress_code = null;
                             }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                             ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $event_post->event_tags = $tags;
                                }


                            $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                            $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                            $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                            $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                            if($ticket1){
                                $event_post->Early_bird = $ticket1;
                            }
                            if($ticket2){
                                $event_post->Regular = $ticket2;
                            }
                            if($ticket3){
                                $event_post->VIP = $ticket3;
                            }
                            if($ticket4){
                                $event_post->skipping_line = $ticket4;
                            }


                         //fetching address from address table of each event post
                         $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $event_post->event_address = $address;
                            }




                    }

                // Date conversion in 12 hrs format in response
                foreach($filtered_posts as $k){
                    $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                    $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));

                        $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                        $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));
                }

                // time conversion in 12 hrs format in response
                foreach($filtered_posts as $k){
                    $k->event_start_time = date("g:i A", strtotime($k->event_start_time));
                    $k->event_end_time = date("g:i A", strtotime($k->event_end_time));

                        $k->sales_start_time = date("g:i A", strtotime($k->sales_start_time));
                       $k->sales_end_time = date("g:i A", strtotime($k->sales_end_time));
                }
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["total_posts"] = $total_posts;
                    $response["data"] =  $filtered_posts;


                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = "0";
                    $response["message"] = "Event Post does not exists.";
                }

            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = "0";
                $response["message"] = "All details are missing";
            }

        return response()
        ->json(array('status' => $response["status"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }



        //  ======================== CARDS =============== //

     /*** CARD DETAILS STORE ***/
     public  function store_card_details(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->userId) && isset($data->cardNumber) && isset($data->cvv)  && isset($data->expiryMonths) && isset($data->expiryYears) && isset($data->zipCode) && isset($data->token)) {

            $existing_card = DB::table('card_details')->where('card_number', $data->cardNumber)->get();

            if(count($existing_card)>0){
                //card already exist
                if($existing_card[0]->status == 'Inactive'){
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Your this card is Inactive in our record.";
                }
                else if($existing_card[0]->status == 'Deleted'){
                    $card_data = array(
                        'users_id'=> $data->userId,
                        'card_number' => (isset($data->cardNumber)? $data->cardNumber : ''),
                        'cvv' => $data->cvv,
                        'card_holder_name' =>  $data->cardHolderName,
                        'expiry_months' => $data->expiryMonths,
                        'expiry_years' => $data->expiryYears,
                        'zip_code' => $data->zipCode,
                        'token'=>$data->token,
                        'status'=>'Active'
                    );
                    $update = DB::table('card_details')->where('card_number',$data->cardNumber)->update($card_data);
                    if($update){
                        $card_details = DB::table('card_details')->where('card_number', $data->cardNumber)->get();
                        if(sizeof($card_details)>0){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = array("card_details" => $card_details);
                        } else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "Updated card not found.";
                        }
                    }else {
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Something went wrong.";
                    }

               } else {
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Card number already exists.";
                }


            } else{
                //add new card
                $card_data = array(
                    'users_id'=> $data->userId,
                    'card_number' => $data->cardNumber,
                    'cvv' => $data->cvv,
                    'card_holder_name' =>  $data->cardHolderName,
                    'expiry_months' => $data->expiryMonths,
                    'expiry_years' => $data->expiryYears,
                    'zip_code' => $data->zipCode,
                    'token'=>$data->token
                );

                $lastInsertedId = DB::table('card_details')->insertGetId($card_data);
                if ($lastInsertedId) {
                    $card_details = DB::table('card_details')->where('card_id', $lastInsertedId)->where('status','Active')->get();
                    if (!empty($card_details)) {
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = array("card_details" => $card_details);
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "Card does not exists.";
                    }
                } else {
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Something went wrong.";
                }
            }

        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }

        return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** CARD DETAILS STORE ***/


    /*** GET CARD DETAILS ***/
    public function get_card_details(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->userId)){

        $count =   DB::table('card_details')->where('users_id', $data->userId)->where('status','Active')->get()->count();
        $card_details =   DB::table('card_details')->where('users_id', $data->userId)->where('status', 'Active')->get();

        if ($count > 0) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] =  $card_details;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "cards does not exists.";
    }

} else{
    $response["code"] = 404;
    $response["status"] = "error";
    $response["message"] = "User Id not passed.";
}
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');

}
    /*** GET CARD DETAILS ***/

        /*** DELETE CARD DETAILS ***/
        public function delete_card_details(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->cardId) && isset($data->userId)){

            $count =   DB::table('card_details')->where('card_id',$data->cardId)->where('users_id', $data->userId)->where('status', 'Active')->get()->count();
            if ($count > 0) {
                $result =   DB::table('card_details')->where('card_id', $data->cardId)->where('status', 'Active')->delete();
                    if($result){
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = 'Card Deleted successfully';
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Error removing card";
                    }

                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "card does not exists.";
        }

    } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Some fields missing";
    }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');

    }
        /*** GET CARD DETAILS ***/

    /*** GET CONNCASH DETAILS ***/
    public function get_conncash_details(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->userId)){
        $conncash_details =   DB::table('users')
                              ->select('users_id','email','first_name','conncash_dollars')
                              ->where('users_id', $data->userId)
                              ->where('status','Active')
                              ->first();

        if ($conncash_details) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] =  $conncash_details;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Conncash record does not exists.";
    }

} else{
    $response["code"] = 404;
    $response["status"] = "error";
    $response["message"] = "User Id not passed.";
}
    return response()

    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');

}
    /*** GET CONNCASH DETAILS ***/

    //  ======================== PURCHASING TICKETS =============== //\

    /*** PURCHASE TICKETS WITH CARDS ***/
    public  function user_purchase_tickets_card(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        $purchasedTicketsArray = array();
        $purchased_tickets = $data->purchasedTickets;
        $payment_type = $data->paymentType;


        if(sizeof($purchased_tickets) > 0){
                 // case of card where stripe payment is to be made first
                if(isset($data->cardId)  && isset($data->usersId) && isset($data->totalAmount) && isset($data->stripeToken) && isset($data->conneventFees) && isset($data->stripeFees) ){
//                if(isset($data->cardId) && isset($data->totalReservedTables) && isset($data->usersId) && isset($data->totalAmount) && isset($data->stripeToken) && isset($data->conneventFees) && isset($data->stripeFees) ){
                    $card_id = $data->cardId;
                    $user_id = $data->usersId;
                    $total_amount = $data->totalAmount;
                    $stripe_token = $data->stripeToken;
                   // stripe payment

                  try {
                    require_once('vendor/stripe-php/init.php');

//                    Stripe::setApiKey(env('STRIPE_SECRET'));
                    Stripe::setApiKey("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");

                    $checkExistingCustomer = DB::table('users')->where('users_id', $user_id)->first();

                    if($checkExistingCustomer->stripe_customer_id == null){
                        $customer = Customer::create([
                            'id' => $user_id,
                            'source'  => $stripe_token
                        ]);
                        $customer_id = $customer->id;
                       $updateCustomerId = DB::table('users')->where('users_id',$user_id)->update(array('stripe_customer_id' => $customer_id));
                     } else{
                         // existing customer is being used
                        $customer_id = $checkExistingCustomer->stripe_customer_id;
                    }

                    $pkg_selected_id   = "1";
                    $currency     = 'USD';

                    $charge = Charge::create(array(
                        'customer' => $customer_id,
                        'amount'   => $total_amount * 100,
                        'currency' => $currency,
                        'description' => 'Event Ticket purchase',
                        'metadata' => array(
                            'ticket_id_temp' => $pkg_selected_id
                        )
                    ));

                    if($charge->status == 'succeeded'){
                          //Transaction result being saved in user tickets total after success
                          $transaction_data = array(
                            'event_post_id' => $data->eventPostId,
                            'users_id' => $data->usersId,
                            'total_amount' => $data->totalAmount,
                            'discount' => (isset($data->discount) ? $data->discount : 0),
                            'total_ppl_tbl_services' => (isset($data->totalPeopleTableServices) ? $data->totalPeopleTableServices : ''),
                            'total_reserved_tables' => (isset($data->totalReservedTables) ? $data->totalReservedTables : 0),
                            'connevent_fees' => $data->conneventFees,
                            'stripe_fees' => $data->stripeFees,
                            'payment_method'=>'Card',
                            'charge_id' => $charge->id,
                            'event_invite_id' => (isset($data->eventInviteId) ? $data->eventInviteId : null),
                            'created_at' => date('Y-m-d H:i:s'),
                            'status' => 'Paid'
                        );
                        $lastTransactionId = DB::table('transactions')->insertGetId($transaction_data);
                            if($lastTransactionId){

                            // Insertion of data in Database
                            foreach ($purchased_tickets as $k) {

                                if (isset($k->eventPostId) && isset($k->ticketId) && isset($k->usersId) && isset($k->quantity) && isset($k->amount) ) {
                                   //updating tickets Quantity
                                     $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticketId)->where('status','Active')->first();
                                      $updatedQuantity = $previousQuantity->quantity - $k->quantity;
                                    $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticketId)->update(array( 'quantity' => $updatedQuantity));


                                    $conneventPerTicketUnrounded = $k->quantity * 0.5;
                                    $conneventPerTicket = round($conneventPerTicketUnrounded, 2);

                                     $stripePerTicketUnrounded = $k->quantity * 0.3;
                                     $stripePerTicket = round($stripePerTicketUnrounded, 2);

                                           //check if ticket is early bird or regular, then dont save table service
                                           if(isset($data->totalPeopleTableServices)){
                                            $checkTicket = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                                if($checkTicket->ticket == 'Early Bird' || $checkTicket->ticket == 'Regular'){
                                                    $totalPeopleTblServicesVar = '';
                                                    $tblFourPplServiceVar = 'no';
                                                    $tblSixPplServiceVar = 'no';
                                                    $tblEightPplServiceVar = 'no';
                                                    $tblTenPplServiceVar = 'no';
                                                }else{
                                                    $totalPeopleTblServicesVar = $data->totalPeopleTableServices;
                                                    $tblFourPplServiceVar = $data->tblFourPplService;
                                                    $tblSixPplServiceVar = $data->tblsixPplService;
                                                    $tblEightPplServiceVar = $data->tblEightPplService;
                                                    $tblTenPplServiceVar = $data->tblTenPplService;
                                                }
                                         }else{
                                                     $totalPeopleTblServicesVar = '';
                                                     $tblFourPplServiceVar = 'no';
                                                    $tblSixPplServiceVar = 'no';
                                                    $tblEightPplServiceVar = 'no';
                                                    $tblTenPplServiceVar = 'no';
                                         }


                                    $purchase_data = array(
                                        'ticket_unique_number' => Helper::generateRandomString(10),
                                        'transaction_id' => $lastTransactionId,
                                        'event_post_id'=> $k->eventPostId,
                                        'users_id' => $k->usersId,
                                        'ticket_id' => $k->ticketId,
                                        'quantity' =>  $k->quantity,
                                        'total_ppl_tbl_services' => $totalPeopleTblServicesVar,
//                                        'tbl_four_ppl_service' => $tblFourPplServiceVar,
//                                        'tbl_six_ppl_service' => $tblSixPplServiceVar,
//                                        'tbl_eight_ppl_service' => $tblEightPplServiceVar,
//                                        'tbl_ten_ppl_service' => $tblTenPplServiceVar,
                                        'amount' => $k->amount,
                                        'connevent_per_ticket' => $conneventPerTicket,
                                        'stripe_per_ticket' => $stripePerTicket,
                                        'transaction_date' => date('Y-m-d H:i:s'),
                                        'payment_type' => $payment_type,
                                        'status' => 'Active'
                                    );
                                    $k->lastInsertedId = DB::table('user_tickets')->insertGetId($purchase_data);
                                    $qty = $k->quantity;

                                    if ($k->lastInsertedId) {


//                                        panga new ticket
                                        $user_token_id = $k->lastInsertedId;
                                        for($i=0; $i<$qty; $i++){
                                            $ticket_data = array(
                                                'tt_token' => Helper::generateRandomString(10),
                                                'tt_user_ticket_id' => $user_token_id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'status' => 'Active'
                                            );
                                            $insert = DB::table('ticket_token')->insertGetId($ticket_data);
                                        }
//                                        panga new ticket


                                        $k->user_tickets = DB::table('user_tickets')->where('user_ticket_id', $k->lastInsertedId)->first();
                                        if ($k->user_tickets) {
                                            $amount = $k->amount;
                                            switch($payment_type){
                                                case 'Card':
                                                    array_push($purchasedTicketsArray, $k->user_tickets);
                                                    break;

                                                default:
                                                $response["code"] = 500;
                                                $response["status"] = "error";
                                                $response["message"] = "Invalid payment type";
                                                break;

                                            }

                                        } else {
                                            $response["code"] = 404;
                                            $response["status"] = "error";
                                            $response["message"] = "Purchased tickets of this user doesnot exists.";
                                        }
                                    } else {
                                        $response["code"] = 500;
                                        $response["status"] = "error";
                                        $response["message"] = "Something went wrong.";
                                    }

                                } else {
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["message"] = "All fields are needed.";
                                }
                    }


                                 //rewarding conncash points
                                 $user_data = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
                                 if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                        $updatedConncash = $user_data->total_conncash + 5;
                                        DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                                        DB::table('conncash_details')->insert(array(
                                                                            'users_id' => $data->usersId,
                                                                            'conncash_source' => 'PurchaseReward',
                                                                            'conncash' => '5',
                                                                            'method' => 'Card',
                                                                            'event_post_id' => $data->eventPostId,
                                                                            'added_at'=> date('Y-m-d H:i:s'),
                                                                        ));

                                 } else if($data->totalAmount >= '20'){
                                        $updatedConncash = $user_data->total_conncash + 10;
                                        DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                                        DB::table('conncash_details')->insert(array(
                                                                            'users_id' => $data->usersId,
                                                                            'conncash_source' => 'PurchaseReward',
                                                                            'conncash' => '10',
                                                                            'method' => 'Card',
                                                                            'event_post_id' => $data->eventPostId,
                                                                            'added_at'=> date('Y-m-d H:i:s'),
                                                                        ));
                                 }

                                 $transactionDetails = DB::table('transactions')->where('transaction_id', $lastTransactionId)->first();

                                    //sending push notification
                                     $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                                    $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                                    $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                                    if($senderDetails->users_id != $receiverDetails->users_id){
                                        $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' purchased a ticket of your event ', 'ticketPurchase');
                                        if($notificationResponse){
                                            $notificationData = array(
                                                'sender_users_id' => $senderDetails-> users_id,
                                                'receiver_users_id' => $receiverDetails-> users_id,
                                                'notification_type' => 'TicketPurchase',
                                                'message' => ' purchased a ticket of your event ',
                                                'event_post_id' => $data->eventPostId,
                                                'datetime' => date('Y-m-d H:i:s'),
                                                'status' => 'Unread'
                                            );
                                            $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                        }
                                    }


                                    //Appending ticket name in each purchase ticket
                                    foreach($purchasedTicketsArray as $k){
                                        $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticket_id)->first();
                                        $k->ticket_name = $ticketDetails->ticket;
                                    }

                                    //rewarding award points if purchased via Invite
                                    if(isset($data->eventInviteId)){
                                        $eventInviteDetails = DB::table('event_invites')->where([ ['event_invite_id', $data->eventInviteId], ['status', 'Invited'] ])->first();
                                        if($eventInviteDetails){
                                            $user_data = DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->where('status','Active')->first();
                                            if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                                   $updatedConncash = $user_data->total_conncash + 5;
                                                   DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                                   DB::table('conncash_details')->insert(array(
                                                                                       'users_id' => $eventInviteDetails->sender_id,
                                                                                       'conncash_source' => 'InviteReward',
                                                                                       'conncash' => '5',
                                                                                       'method' => 'Card',
                                                                                       'event_post_id' => $data->eventPostId,
                                                                                       'added_at'=> date('Y-m-d H:i:s'),
                                                                                   ));

                                            } else if($data->totalAmount >= '20'){
                                                   $updatedConncash = $user_data->total_conncash + 10;
                                                   DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                                   DB::table('conncash_details')->insert(array(
                                                                                       'users_id' => $eventInviteDetails->sender_id,
                                                                                       'conncash_source' => 'InviteReward',
                                                                                       'conncash' => '10',
                                                                                       'method' => 'Card',
                                                                                       'event_post_id' => $data->eventPostId,
                                                                                       'added_at'=> date('Y-m-d H:i:s'),
                                                                                   ));
                                            }
                                             //Update invite status in event invites
                                              DB::table('event_invites')->where('event_invite_id', $data->eventInviteId)->update([ 'status', 'PurchasedOnInvite']);
                                        }
                                    }


                                    //saving earning of event organizer
                                      $organizerId = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                                    $earning_data = array(
                                        'transaction_id' => $lastTransactionId,
                                        'users_id' => $organizerId->users_id,
                                         'status' => 'Earned',
                                         'date_time' => date('Y-m-d H:i:s')
                                    );
                                        DB::table('earnings')->insert($earning_data);



                                    //Email to user for ticket details and payment confirmation
                                    $userDetails = DB::table('users')->where('users_id', $data->usersId)->first();
                                    $purchasedTicketsEventDetails = DB::table('event_posts')->where('event_post_id', $transactionDetails->event_post_id)->first();

                                    $toEmail = $userDetails->email;
                                    $ticketDetailsQuantities = "";

                                    $message = "";
                                    $subject = "Transaction details from connevents";
                                    $baseDetails = "Greetings ".$userDetails->user_name.",<br>
                                                Thankyou for purchasing tickets from connevents.<br>
                                                Here's the detail of your purchase: <br><br><br>

                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Transaction ID: </strong></td>
                                                            <td>".$transactionDetails->transaction_id."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Event title: </strong></td>
                                                            <td>".$purchasedTicketsEventDetails->title."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Payment Method: </strong></td>
                                                            <td>Card (using stripe payment)</td>
                                                        </tr>
                                                         <tr>
                                                            <td><strong>Amount: </strong></td>
                                                            <td>".$transactionDetails->total_amount."$</td>
                                                        </tr>";

                                            foreach($data->purchasedTickets as $k){
                                                  $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                                $ticketDetailsQuantities .= "<tr>
                                                                        <td><strong>".$ticketDetails->ticket." tickets: </strong></td>
                                                                        <td>".$k->quantity."</td>
                                                                    </tr>";
                                                                    }

                                $totalPplTblServicesDetails =" <tr>
                                                            <td><strong>Total People table services: </strong></td>
                                                            <td>".$transactionDetails->total_ppl_tbl_services."</td>
                                                        </tr>";

                                            $footerDetails = "<tr>
                                                                <td><strong>Transaction on: </strong></td>
                                                                <td>".date('d/m/Y -- H:i A', strtotime($transactionDetails->created_at))."</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                        <br><br>
                                                        This transaction has been posted to your account and the receipt can also be found in your ticket library.<br>
                                                         If you have any questions, please call 1-800-555-555 or send us a note at <strong>mailto: </strong>customersupport@connevents.com. <br>
                                                          Our customer service representatives are available Monday through Friday; 9 a.m. to 4 p.m ET.
                                                        <br>
                                                          Best Regards, <br>
                                                          ConnEvents Customer Service Team";

                                          if($transactionDetails->total_ppl_tbl_services != ""){
                                              $message = $baseDetails.$ticketDetailsQuantities.$totalPplTblServicesDetails.$footerDetails;
                                          }else{
                                            $message = $baseDetails.$ticketDetailsQuantities.$footerDetails;
                                          }


                                        //emailData
                                        $emailDetails = array(
                                            'to_email' => $toEmail,
                                            'subject' => $subject,
                                            //'from_email' => $fromEmail,

                                        );
                                        $message_content = $message;

                                        Mail::send(array(), array(), function ($message) use ($emailDetails, $message_content) {
                                                                        $message->to($emailDetails['to_email'])
                                                                        ->subject($emailDetails['subject'])
                                                                        //->from($emailDetails['from_email'])
                                                                        ->setBody($message_content, 'text/html');
                                                                    });


                                    //response
                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = $purchasedTicketsArray;

                            } else{
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "Something happend in database transaction";
                            }
                  } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Error in stripe transaction.";
                    }

                    } catch (\Exception $ex) {
                        return $ex->getMessage().' error occured';
                    }

             } else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "All fields are needed.";
                   }
       } else{
           //case where tickets are not being passed in array
           $response["code"] = 404;
           $response["status"] = "error";
           $response["message"] = "Tickets does not exists in array.";
          }

        return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
     /*** PURCHASE TICKETS ***/

    /*** PURCHASE TICKETS WITH Google Pay ***/
    public  function user_purchase_tickets_google_pay(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->userId) && isset($data->totalAmount) && isset($data->token) && isset($data->payment_status) && isset($data->paymentType) && isset($data->purchasedTickets)){
            $purchasedTicketsArray = array();
            $purchased_tickets = $data->purchasedTickets;
            $payment_type = $data->paymentType;
          
            $user_id = $data->userId;
            $total_amount = $data->totalAmount;
            $stripe_token = $data->token;
            
            if($data->payment_status == 'succeeded'){
                //Transaction result being saved in user tickets total after success
                $transaction_data = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->userId,
                    'total_amount' => $data->totalAmount,
                    'discount' => (isset($data->discount) ? $data->discount : 0),
                    'total_ppl_tbl_services' => (isset($data->totalPeopleTableServices) ? $data->totalPeopleTableServices : ''),
                    'total_reserved_tables' => (isset($data->totalReservedTables) ? $data->totalReservedTables : 0),
                    'connevent_fees' => (isset($data->connevent_fees) ? $data->connevent_fees : 0),
                    'stripe_fees' => (isset($data->stripe_fees) ? $data->stripe_fees : 0),
                    'payment_method'=>'Google',
                    'charge_id' => $data->token,
                    'event_invite_id' => (isset($data->eventInviteId) ? $data->eventInviteId : null),
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'Paid'
                );
                    
                $lastTransactionId = DB::table('transactions')->insertGetId($transaction_data);
                if($lastTransactionId){
                    // Insertion of data in Database
                    foreach ($purchased_tickets as $k) {
                        if (isset($k->eventPostId) && isset($k->ticketId) && isset($k->usersId) && isset($k->quantity) && isset($k->amount) ) {
                            //updating tickets Quantity
                            $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticketId)->where('status','Active')->first();
                            $updatedQuantity = $previousQuantity->quantity - $k->quantity;
                            $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticketId)->update(array( 'quantity' => $updatedQuantity));
                            $conneventPerTicketUnrounded = $k->quantity * 0.5;
                            $conneventPerTicket = round($conneventPerTicketUnrounded, 2);
                            $stripePerTicketUnrounded = $k->quantity * 0.3;
                            $stripePerTicket = round($stripePerTicketUnrounded, 2);
                           
                            //check if ticket is early bird or regular, then dont save table service
                            if(isset($data->totalPeopleTableServices)){
                                $checkTicket = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                if($checkTicket->ticket == 'Early Bird' || $checkTicket->ticket == 'Regular'){
                                    $totalPeopleTblServicesVar = '';
                                    $tblFourPplServiceVar = 'no';
                                    $tblSixPplServiceVar = 'no';
                                    $tblEightPplServiceVar = 'no';
                                    $tblTenPplServiceVar = 'no';
                                } else {
                                    $totalPeopleTblServicesVar = $data->totalPeopleTableServices;
                                    $tblFourPplServiceVar = $data->tblFourPplService;
                                    $tblSixPplServiceVar = $data->tblsixPplService;
                                    $tblEightPplServiceVar = $data->tblEightPplService;
                                    $tblTenPplServiceVar = $data->tblTenPplService;
                                }
                            } else {
                                $totalPeopleTblServicesVar = '';
                                $tblFourPplServiceVar = 'no';
                                $tblSixPplServiceVar = 'no';
                                $tblEightPplServiceVar = 'no';
                                $tblTenPplServiceVar = 'no';
                            }


                            $purchase_data = array(
                                'ticket_unique_number' => Helper::generateRandomString(10),
                                'transaction_id' => $lastTransactionId,
                                'event_post_id'=> $k->eventPostId,
                                'users_id' => $k->usersId,
                                'ticket_id' => $k->ticketId,
                                'quantity' =>  $k->quantity,
                                'total_ppl_tbl_services' => $totalPeopleTblServicesVar,
                                // 'tbl_four_ppl_service' => $tblFourPplServiceVar,
                                // 'tbl_six_ppl_service' => $tblSixPplServiceVar,
                                // 'tbl_eight_ppl_service' => $tblEightPplServiceVar,
                                // 'tbl_ten_ppl_service' => $tblTenPplServiceVar,
                                'amount' => $k->amount,
                                'connevent_per_ticket' => $conneventPerTicket,
                                'stripe_per_ticket' => $stripePerTicket,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'payment_type' => $payment_type,
                                'status' => 'Active'
                            );
                                
                            $k->lastInsertedId = DB::table('user_tickets')->insertGetId($purchase_data);
                            $qty = $k->quantity;
                            
                            if ($k->lastInsertedId) {
                                //panga new ticket
                                $user_token_id = $k->lastInsertedId;
                                for($i=0; $i<$qty; $i++){
                                    $ticket_data = array(
                                        'tt_token' => Helper::generateRandomString(10),
                                        'tt_user_ticket_id' => $user_token_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'status' => 'Active'
                                    );
                                    $insert = DB::table('ticket_token')->insertGetId($ticket_data);
                                }
                                //panga new ticket

                                $k->user_tickets = DB::table('user_tickets')->where('user_ticket_id', $k->lastInsertedId)->first();
                                if ($k->user_tickets) {
                                    $amount = $k->amount;
                                    switch($payment_type){
                                        case 'Google':
                                            array_push($purchasedTicketsArray, $k->user_tickets);
                                        break;

                                        default:
                                            $response["code"] = 500;
                                            $response["status"] = "error";
                                            $response["message"] = "Invalid payment type";
                                        break;
                                    }
                                } else {
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["message"] = "Purchased tickets of this user doesnot exists.";
                                }
                            } else {
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "Something went wrong.";
                            }
                        } else {
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "All fields for ticket are needed.";
                        }
                    }
                }

                //rewarding conncash points
                $user_data = DB::table('users')->where('users_id',$data->userId)->where('status','Active')->first();
                if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                    $updatedConncash = $user_data->total_conncash + 5;
                    DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                    DB::table('conncash_details')->insert(array(
                                                        'users_id' => $data->userId,
                                                        'conncash_source' => 'PurchaseReward',
                                                        'conncash' => '5',
                                                        'method' => 'Google',
                                                        'event_post_id' => $data->eventPostId,
                                                        'added_at'=> date('Y-m-d H:i:s'),
                                                    ));
                
                } else if($data->totalAmount >= '20'){
                    $updatedConncash = $user_data->total_conncash + 10;
                    DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                    DB::table('conncash_details')->insert(array(
                                                        'users_id' => $data->userId,
                                                        'conncash_source' => 'PurchaseReward',
                                                        'conncash' => '10',
                                                        'method' => 'Google',
                                                        'event_post_id' => $data->eventPostId,
                                                        'added_at'=> date('Y-m-d H:i:s'),
                                                    ));
                }

                $transactionDetails = DB::table('transactions')->where('transaction_id', $lastTransactionId)->first();
                
                //sending push notification
                $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                $senderDetails= DB::table('users')->where([ ['users_id', $data->userId], ['status', 'Active'] ])->first();
                $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                if($senderDetails->users_id != $receiverDetails->users_id){
                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' purchased a ticket of your event ', 'ticketPurchase');
                    if($notificationResponse){
                        $notificationData = array(
                            'sender_users_id' => $senderDetails-> users_id,
                            'receiver_users_id' => $receiverDetails-> users_id,
                            'notification_type' => 'TicketPurchase',
                            'message' => ' purchased a ticket of your event ',
                            'event_post_id' => $data->eventPostId,
                            'datetime' => date('Y-m-d H:i:s'),
                            'status' => 'Unread'
                        );
                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                    }
                }

                //Appending ticket name in each purchase ticket
                foreach($purchasedTicketsArray as $k){
                    $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticket_id)->first();
                    $k->ticket_name = $ticketDetails->ticket;
                }

                //rewarding award points if purchased via Invite
                if(isset($data->eventInviteId)){
                    $eventInviteDetails = DB::table('event_invites')->where([ ['event_invite_id', $data->eventInviteId], ['status', 'Invited'] ])->first();
                    if($eventInviteDetails){
                        $user_data = DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->where('status','Active')->first();
                        if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                               $updatedConncash = $user_data->total_conncash + 5;
                               DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                               DB::table('conncash_details')->insert(array(
                                                                   'users_id' => $eventInviteDetails->sender_id,
                                                                   'conncash_source' => 'InviteReward',
                                                                   'conncash' => '5',
                                                                   'method' => 'Google',
                                                                   'event_post_id' => $data->eventPostId,
                                                                   'added_at'=> date('Y-m-d H:i:s'),
                                                               ));

                        } else if($data->totalAmount >= '20'){
                               $updatedConncash = $user_data->total_conncash + 10;
                               DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                               DB::table('conncash_details')->insert(array(
                                                                   'users_id' => $eventInviteDetails->sender_id,
                                                                   'conncash_source' => 'InviteReward',
                                                                   'conncash' => '10',
                                                                   'method' => 'Google',
                                                                   'event_post_id' => $data->eventPostId,
                                                                   'added_at'=> date('Y-m-d H:i:s'),
                                                               ));
                        }
                         //Update invite status in event invites
                          DB::table('event_invites')->where('event_invite_id', $data->eventInviteId)->update([ 'status', 'PurchasedOnInvite']);
                    }
                }

                //saving earning of event organizer
                $organizerId = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                $earning_data = array(
                    'transaction_id' => $lastTransactionId,
                    'users_id' => $organizerId->users_id,
                    'status' => 'Earned',
                    'date_time' => date('Y-m-d H:i:s')
                );
                DB::table('earnings')->insert($earning_data);

                //Email to user for ticket details and payment confirmation
                $userDetails = DB::table('users')->where('users_id', $data->userId)->first();
                $purchasedTicketsEventDetails = DB::table('event_posts')->where('event_post_id', $transactionDetails->event_post_id)->first();

                $toEmail = $userDetails->email;
                $ticketDetailsQuantities = "";

                $message = "";
                $subject = "Transaction details from connevents";
                $baseDetails = "Greetings ".$userDetails->user_name.",<br>
                                Thankyou for purchasing tickets from connevents.<br>
                                Here's the detail of your purchase: <br><br><br>

                                <table>
                                    <tbody>
                                        <tr>
                                            <td><strong>Transaction ID: </strong></td>
                                            <td>".$transactionDetails->transaction_id."</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Event title: </strong></td>
                                            <td>".$purchasedTicketsEventDetails->title."</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Method: </strong></td>
                                            <td>Google (using stripe payment)</td>
                                        </tr>
                                         <tr>
                                            <td><strong>Amount: </strong></td>
                                            <td>".$transactionDetails->total_amount."$</td>
                                        </tr>";

                foreach($data->purchasedTickets as $k){
                    $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                    $ticketDetailsQuantities .= "<tr>
                                                    <td><strong>".$ticketDetails->ticket." tickets: </strong></td>
                                                    <td>".$k->quantity."</td>
                                                </tr>";
                }

                $totalPplTblServicesDetails =" <tr>
                                                    <td><strong>Total People table services: </strong></td>
                                                    <td>".$transactionDetails->total_ppl_tbl_services."</td>
                                                </tr>";

                $footerDetails = "<tr>
                                                    <td><strong>Transaction on: </strong></td>
                                                    <td>".date('d/m/Y -- H:i A', strtotime($transactionDetails->created_at))."</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br><br>
                                        This transaction has been posted to your account and the receipt can also be found in your ticket library.<br>
                                         If you have any questions, please call 1-800-555-555 or send us a note at <strong>mailto: </strong>customersupport@connevents.com. <br>
                                          Our customer service representatives are available Monday through Friday; 9 a.m. to 4 p.m ET.
                                        <br>
                                          Best Regards, <br>
                                          ConnEvents Customer Service Team";

                if($transactionDetails->total_ppl_tbl_services != ""){
                      $message = $baseDetails.$ticketDetailsQuantities.$totalPplTblServicesDetails.$footerDetails;
                } else {
                    $message = $baseDetails.$ticketDetailsQuantities.$footerDetails;
                }

                //emailData
                $emailDetails = array(
                    'to_email' => $toEmail,
                    'subject' => $subject,
                    //'from_email' => $fromEmail,

                );
                $message_content = $message;

                Mail::send(array(), array(), function ($message) use ($emailDetails, $message_content) {
                    $message->to($emailDetails['to_email'])
                    ->subject($emailDetails['subject'])
                    //->from($emailDetails['from_email'])
                    ->setBody($message_content, 'text/html');
                });

                //response
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $purchasedTicketsArray;
                
            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "All fields are neededh 2.";
            }
        } else {
           //case where tickets are not being passed in array
           $response["code"] = 404;
           $response["status"] = "error";
           $response["message"] = "All fields are required.";
        }
    
        return response()->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
     /*** PURCHASE TICKETS  WITH Google Pay***/


     /*** PURCHASE TICKETS WITH CONNCASH ***/
    public  function user_purchase_tickets_conncash(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));


        $purchasedTicketsArray = array();
        $purchased_tickets = $data->purchasedTickets;
        $payment_type = $data->paymentType;


        if(sizeof($purchased_tickets) > 0){
            $previous_conncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
           // $converted_conncash_dollars = Helper::convert_conncash($previous_conncash->total_conncash);

              if($previous_conncash->conncash_dollars > $data->totalAmount){
                   //Transaction result being saved in user tickets total after success
                   $transaction_data = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->usersId,
                    'total_amount' => $data->totalAmount,
                    'discount' => (isset($data->discount) ? $data->discount : 0),
                    'total_ppl_tbl_services' => (isset($data->totalPeopleTableServices) ? $data->totalPeopleTableServices : ''),
                    'total_reserved_tables' => (isset($data->totalReservedTables) ? $data->totalReservedTables : 0),
                    'connevent_fees' => $data->conneventFees,
                    'stripe_fees' => '0',
                    'payment_method'=>'Conncash',
                    'event_invite_id' => (isset($data->eventInviteId) ? $data->eventInviteId : null),
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'Paid'
                );
            $lastTransactionId = DB::table('transactions')->insertGetId($transaction_data);
            if($lastTransactionId){
                        // Insertion of data in Database
                        foreach ($purchased_tickets as $k) {
                            if (isset($k->eventPostId) && isset($k->ticketId) && isset($k->usersId) && isset($k->quantity) && isset($k->amount) ) {

                                $conneventPerTicketUnrounded = $k->quantity * 0.5;
                                $conneventPerTicket = round($conneventPerTicketUnrounded, 2);

                                     //check if ticket is early bird or regular, then dont save table service
                                     if(isset($data->totalPeopleTableServices)){
                                        $checkTicket = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                            if($checkTicket->ticket == 'Early Bird' || $checkTicket->ticket == 'Regular'){
                                                $totalPeopleTblServicesVar = '';
                                                $tblFourPplServiceVar = 'no';
                                                $tblSixPplServiceVar = 'no';
                                                $tblEightPplServiceVar = 'no';
                                                $tblTenPplServiceVar = 'no';
                                            }else{
                                                $totalPeopleTblServicesVar = $data->totalPeopleTableServices;
                                                $tblFourPplServiceVar = $data->tblFourPplService;
                                                $tblSixPplServiceVar = $data->tblsixPplService;
                                                $tblEightPplServiceVar = $data->tblEightPplService;
                                                $tblTenPplServiceVar = $data->tblTenPplService;
                                            }
                                     }else{
                                                 $totalPeopleTblServicesVar = '';
                                                 $tblFourPplServiceVar = 'no';
                                                $tblSixPplServiceVar = 'no';
                                                $tblEightPplServiceVar = 'no';
                                                $tblTenPplServiceVar = 'no';
                                     }

                                $purchase_data = array(
                                    'ticket_unique_number' => Helper::generateRandomString(10),
                                    'transaction_id' => $lastTransactionId,
                                    'event_post_id'=> $k->eventPostId,
                                    'users_id' => $k->usersId,
                                    'ticket_id' => $k->ticketId,
                                    'quantity' =>  $k->quantity,
                                    'total_ppl_tbl_services' => $totalPeopleTblServicesVar,
//                                    'tbl_four_ppl_service' => $tblFourPplServiceVar,
//                                    'tbl_six_ppl_service' => $tblSixPplServiceVar,
//                                    'tbl_eight_ppl_service' => $tblEightPplServiceVar,
//                                    'tbl_ten_ppl_service' => $tblTenPplServiceVar,
                                    'amount' => $k->amount,
                                    'connevent_per_ticket' => $conneventPerTicket,
                                    'stripe_per_ticket' => '0',
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'payment_type' => $payment_type,
                                    'status' => 'Active'
                                );

                                $qty = $k->quantity;
                                $k->lastInsertedId = DB::table('user_tickets')->insertGetId($purchase_data);
                                if ($k->lastInsertedId) {

//                                        panga new ticket
                                    $user_token_id = $k->lastInsertedId;
                                    for($i=0; $i<$qty; $i++){
                                        $ticket_data = array(
                                            'tt_token' => Helper::generateRandomString(10),
                                            'tt_user_ticket_id' => $user_token_id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'status' => 'Active'
                                        );
                                        $insert = DB::table('ticket_token')->insertGetId($ticket_data);
                                    }
//                                        panga new ticket



                                    //updating tickets Quantity
                                    $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticketId)->where('status','Active')->first();
                                    $updatedQuantity = $previousQuantity->quantity - $k->quantity;
                                    $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticketId)->update(array( 'quantity' => $updatedQuantity));

                                    $k->user_tickets = DB::table('user_tickets')->where('user_ticket_id', $k->lastInsertedId)->first();
                                    if ($k->user_tickets) {
                                        $amount = $k->amount;
                                        switch($payment_type){
                                            case 'Conncash':
                                                    $convertedConncash = Helper::convert_dollars($k->amount);
                                                //query to insert in conncash details
                                                        $conncash_update = array(
                                                            'users_id' => $data->usersId,
                                                            'conncash_source' => 'PurchaseDeduct',
                                                            'conncash' => $convertedConncash,
                                                            'event_post_id' => $data->eventPostId,
                                                            'ticket_id' => $k->ticketId,
                                                            'method' => 'Conncash',
                                                            'added_at' => date('Y-m-d H:i:s')
                                                        );
                                                $k->insertConncashDetails = DB::table('conncash_details')->where('users_id',$k->usersId)->insert($conncash_update);

                                                array_push($purchasedTicketsArray, $k->user_tickets);
                                                break;
                                            default:
                                            $response["code"] = 500;
                                            $response["status"] = "error";
                                            $response["message"] = "Invalid payment type";
                                            break;
                                        }
                                    } else {
                                        $response["code"] = 404;
                                        $response["status"] = "error";
                                        $response["message"] = "Purchased tickets of this user doesnot exists.";
                                    }
                                } else {
                                    $response["code"] = 500;
                                    $response["status"] = "error";
                                    $response["message"] = "Something went wrong.";
                                }
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "All fields are needed.";
                    }
            }
                 // Update user table conncash
                    $previousUserConncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
                      $updatedConncashDollars = $previousUserConncash->conncash_dollars - $data->totalAmount;
                 $updateUserConncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->update(array('conncash_dollars' => $updatedConncashDollars));

                      //rewarding conncash points
                      $user_data = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
                      if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                             $updatedConncash = $user_data->total_conncash + 5;
                             DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                             DB::table('conncash_details')->insert(array(
                                                                 'users_id' => $data->usersId,
                                                                 'conncash_source' => 'PurchaseReward',
                                                                 'conncash' => '5',
                                                                 'method' => 'Conncash',
                                                                 'event_post_id' => $data->eventPostId,
                                                                 'added_at'=> date('Y-m-d H:i:s'),
                                                             ));
                      } else if($data->totalAmount >= '20'){
                             $updatedConncash = $user_data->total_conncash + 10;
                             DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                            $conncash_det_upd =  DB::table('conncash_details')->insert(array(
                                                                 'users_id' => $data->usersId,
                                                                 'conncash_source' => 'PurchaseReward',
                                                                 'conncash' => '10',
                                                                 'method' => 'Conncash',
                                                                 'event_post_id' => $data->eventPostId,
                                                                 'added_at'=> date('Y-m-d H:i:s'),

                                                             ));
                      }

                      $transactionDetails = DB::table('transactions')->where('transaction_id', $lastTransactionId)->first();

                       //sending push notification
                       $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                       $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                       $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                       if($senderDetails->users_id != $receiverDetails->users_id){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' purchased a ticket of your event ', 'ticketPurchase');
                            if($notificationResponse){
                                $notificationData = array(
                                    'sender_users_id' => $senderDetails-> users_id,
                                    'receiver_users_id' => $receiverDetails-> users_id,
                                    'notification_type' => 'TicketPurchase',
                                    'message' => ' purchased a ticket of your event ',
                                    'event_post_id' => $data->eventPostId,
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'status' => 'Unread'
                                );
                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                            }
                       }


                            //Appending ticket name in each purchase ticket
                            foreach($purchasedTicketsArray as $k){
                                $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticket_id)->first();
                                $k->ticket_name = $ticketDetails->ticket;
                            }


                             //rewarding award points if purchased via Invite
                             if(isset($data->eventInviteId)){
                                $eventInviteDetails = DB::table('event_invites')->where([ ['event_invite_id', $data->eventInviteId], ['status', 'Invited'] ])->first();
                                if($eventInviteDetails){
                                    $user_data = DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->where('status','Active')->first();
                                    if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                           $updatedConncash = $user_data->total_conncash + 5;
                                           DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                           DB::table('conncash_details')->insert(array(
                                                                               'users_id' => $eventInviteDetails->sender_id,
                                                                               'conncash_source' => 'InviteReward',
                                                                               'conncash' => '5',
                                                                               'method' => 'Conncash',
                                                                               'event_post_id' => $data->eventPostId,
                                                                               'added_at'=> date('Y-m-d H:i:s'),
                                                                           ));

                                    } else if($data->totalAmount >= '20'){
                                           $updatedConncash = $user_data->total_conncash + 10;
                                           DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                           DB::table('conncash_details')->insert(array(
                                                                               'users_id' => $eventInviteDetails->sender_id,
                                                                               'conncash_source' => 'InviteReward',
                                                                               'conncash' => '10',
                                                                               'method' => 'Conncash',
                                                                               'event_post_id' => $data->eventPostId,
                                                                               'added_at'=> date('Y-m-d H:i:s'),
                                                                           ));
                                    }
                                    //Update invite status in event invites
                                    DB::table('event_invites')->where('event_invite_id', $data->eventInviteId)->update([ 'status', 'PurchasedOnInvite']);
                                }
                            }

                              //saving earning of event organizer
                              $organizerId = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                              $earning_data = array(
                                  'transaction_id' => $lastTransactionId,
                                  'users_id' => $organizerId->users_id,
                                   'status' => 'Earned',
                                   'date_time' => date('Y-m-d H:i:s')
                              );
                                  DB::table('earnings')->insert($earning_data);


                                 //Email to user for ticket details and payment confirmation
                                    $userDetails = DB::table('users')->where('users_id', $data->usersId)->first();
                                    $purchasedTicketsEventDetails = DB::table('event_posts')->where('event_post_id', $transactionDetails->event_post_id)->first();

                                    $toEmail = $userDetails->email;
                                    $ticketDetailsQuantities = "";

                                    $message = "";
                                    $subject = "Transaction details from connevents";
                                    $baseDetails = "Greetings ".$userDetails->user_name.",<br>
                                                Thankyou for purchasing tickets from connevents.<br>
                                                Here's the detail of your purchase: <br><br><br>

                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Transaction ID: </strong></td>
                                                            <td>".$transactionDetails->transaction_id."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Event title: </strong></td>
                                                            <td>".$purchasedTicketsEventDetails->title."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Payment Method: </strong></td>
                                                            <td>Conncash</td>
                                                        </tr>
                                                         <tr>
                                                            <td><strong>Amount: </strong></td>
                                                            <td>".$transactionDetails->total_amount."$</td>
                                                        </tr>";

                                            foreach($data->purchasedTickets as $k){
                                                  $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                                $ticketDetailsQuantities .= "<tr>
                                                                        <td><strong>".$ticketDetails->ticket." tickets: </strong></td>
                                                                        <td>".$k->quantity."</td>
                                                                    </tr>";
                                                                    }

                                $totalPplTblServicesDetails =" <tr>
                                                            <td><strong>Total People table services: </strong></td>
                                                            <td>".$transactionDetails->total_ppl_tbl_services."</td>
                                                        </tr>";

                                            $footerDetails = "<tr>
                                                                <td><strong>Transaction on: </strong></td>
                                                                <td>".date('d/m/Y -- H:i A', strtotime($transactionDetails->created_at))."</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                        <br><br>
                                                        This transaction has been posted to your account and the receipt can also be found in your ticket library.<br>
                                                         If you have any questions, please call 1-800-555-555 or send us a note at <strong>mailto: </strong>customersupport@connevents.com. <br>
                                                          Our customer service representatives are available Monday through Friday; 9 a.m. to 4 p.m ET.
                                                          <br>

                                                          Best Regards, <br>
                                                          ConnEvents Customer Service Team";

                                                  if($transactionDetails->total_ppl_tbl_services != ""){
                                                    $message = $baseDetails.$ticketDetailsQuantities.$totalPplTblServicesDetails.$footerDetails;
                                                }else{
                                                  $message = $baseDetails.$ticketDetailsQuantities.$footerDetails;
                                                }


                                       //emailData
                                       $emailDetails = array(
                                        'to_email' => $toEmail,
                                        'subject' => $subject,
                                        //'from_email' => $fromEmail,

                                    );
                                    $message_content = $message;

                                    Mail::send(array(), array(), function ($message) use ($emailDetails, $message_content) {
                                                                    $message->to($emailDetails['to_email'])
                                                                    ->subject($emailDetails['subject'])
                                                                    //->from($emailDetails['from_email'])
                                                                    ->setBody($message_content, 'text/html');
                                                                });


                      //response
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $purchasedTicketsArray;
                }
                else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Something happed in database transaction";
                }
           }
              else{
                  $response["code"] = 500;
                  $response['status']="error";
                  $response['message'] = "Conncash is not enough for transaction";
               }
       } else{
           //case where tickets are not being passed in array
           $response["code"] = 404;
           $response["status"] = "error";
           $response["message"] = "Tickets does not exists in array.";
          }

        return response()
                    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
     /*** PURCHASE TICKETS WITH CONNCASH ***/



     /*** PURCHASE TICKETS WITH GPAY ***/
   /*  public  function user_purchase_tickets_gpay(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));


        $purchasedTicketsArray = array();
        $purchased_tickets = $data->purchasedTickets;
        $payment_type = $data->paymentType;


        if(sizeof($purchased_tickets) > 0){
            $previous_conncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
           // $converted_conncash_dollars = Helper::convert_conncash($previous_conncash->total_conncash);

              if($previous_conncash->conncash_dollars > $data->totalAmount){
                   //Transaction result being saved in user tickets total after success
                   $transaction_data = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->usersId,
                    'total_amount' => $data->totalAmount,
                    'discount' => (isset($data->discount) ? $data->discount : 0),
                    'total_ppl_tbl_services' => (isset($data->totalPeopleTableServices) ? $data->totalPeopleTableServices : ''),
                    'total_reserved_tables' => $data->totalReservedTables,
                    'connevent_fees' => $data->conneventFees,
                    'stripe_fees' => '0',
                    'payment_method'=>'Conncash',
                    'event_invite_id' => (isset($data->eventInviteId) ? $data->eventInviteId : null),
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'Paid'
                );
            $lastTransactionId = DB::table('transactions')->insertGetId($transaction_data);
            if($lastTransactionId){
                        // Insertion of data in Database
                        foreach ($purchased_tickets as $k) {
                            if (isset($k->eventPostId) && isset($k->ticketId) && isset($k->usersId) && isset($k->quantity) && isset($k->amount) ) {

                                $conneventPerTicketUnrounded = $k->quantity * 0.5;
                                $conneventPerTicket = round($conneventPerTicketUnrounded, 2);

                                $purchase_data = array(
                                    'ticket_unique_number' => Helper::generateRandomString(10),
                                    'transaction_id' => $lastTransactionId,
                                    'event_post_id'=> $k->eventPostId,
                                    'users_id' => $k->usersId,
                                    'ticket_id' => $k->ticketId,
                                    'quantity' =>  $k->quantity,
                                    'total_ppl_tbl_services' => (isset($data->totalPeopleTableServices) ? $data->totalPeopleTableServices : ''),
                                    'amount' => $k->amount,
                                    'connevent_per_ticket' => $conneventPerTicket,
                                    'stripe_per_ticket' => '0',
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'payment_type' => $payment_type,
                                    'status' => 'Active'
                                );

                                $k->lastInsertedId = DB::table('user_tickets')->insertGetId($purchase_data);
                                if ($k->lastInsertedId) {
                                    //updating tickets Quantity
                                    $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticketId)->where('status','Active')->first();
                                    $updatedQuantity = $previousQuantity->quantity - $k->quantity;
                                    $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticketId)->update(array( 'quantity' => $updatedQuantity));

                                    $k->user_tickets = DB::table('user_tickets')->where('user_ticket_id', $k->lastInsertedId)->first();
                                    if ($k->user_tickets) {
                                        $amount = $k->amount;
                                        switch($payment_type){
                                            case 'Conncash':
                                                    $convertedConncash = Helper::convert_dollars($k->amount);
                                                //query to insert in conncash details
                                                        $conncash_update = array(
                                                            'users_id' => $data->usersId,
                                                            'conncash_source' => 'PurchaseDeduct',
                                                            'conncash' => $convertedConncash,
                                                            'event_post_id' => $data->eventPostId,
                                                            'ticket_id' => $k->ticketId,
                                                            'method' => 'Conncash',
                                                            'added_at' => date('Y-m-d H:i:s')
                                                        );
                                                $k->insertConncashDetails = DB::table('conncash_details')->where('users_id',$k->usersId)->insert($conncash_update);

                                                array_push($purchasedTicketsArray, $k->user_tickets);
                                                break;
                                            default:
                                            $response["code"] = 500;
                                            $response["status"] = "error";
                                            $response["message"] = "Invalid payment type";
                                            break;
                                        }
                                    } else {
                                        $response["code"] = 404;
                                        $response["status"] = "error";
                                        $response["message"] = "Purchased tickets of this user doesnot exists.";
                                    }
                                } else {
                                    $response["code"] = 500;
                                    $response["status"] = "error";
                                    $response["message"] = "Something went wrong.";
                                }
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "All fields are needed.";
                    }
            }
                 // Update user table conncash
                    $previousUserConncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
                      $updatedConncashDollars = $previousUserConncash->conncash_dollars - $data->totalAmount;
                 $updateUserConncash = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->update(array('conncash_dollars' => $updatedConncashDollars));

                      //rewarding conncash points
                      $user_data = DB::table('users')->where('users_id',$data->usersId)->where('status','Active')->first();
                      if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                             $updatedConncash = $user_data->total_conncash + 5;
                             DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                             DB::table('conncash_details')->insert(array(
                                                                 'users_id' => $data->usersId,
                                                                 'conncash_source' => 'PurchaseReward',
                                                                 'conncash' => '5',
                                                                 'method' => 'Conncash',
                                                                 'event_post_id' => $data->eventPostId,
                                                                 'added_at'=> date('Y-m-d H:i:s'),
                                                             ));
                      } else if($data->totalAmount >= '20'){
                             $updatedConncash = $user_data->total_conncash + 10;
                             DB::table('users')->where('users_id',$data->usersId)->update(array('total_conncash' => $updatedConncash));
                            $conncash_det_upd =  DB::table('conncash_details')->insert(array(
                                                                 'users_id' => $data->usersId,
                                                                 'conncash_source' => 'PurchaseReward',
                                                                 'conncash' => '10',
                                                                 'method' => 'Conncash',
                                                                 'event_post_id' => $data->eventPostId,
                                                                 'added_at'=> date('Y-m-d H:i:s'),

                                                             ));
                      }

                      $transactionDetails = DB::table('transactions')->where('transaction_id', $lastTransactionId)->first();

                       //sending push notification
                       $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                       $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                       $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                       if($senderDetails->users_id != $receiverDetails->users_id){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' purchased a ticket of your event ', 'ticketPurchase');
                            if($notificationResponse){
                                $notificationData = array(
                                    'sender_users_id' => $senderDetails-> users_id,
                                    'receiver_users_id' => $receiverDetails-> users_id,
                                    'notification_type' => 'TicketPurchase',
                                    'message' => ' purchased a ticket of your event ',
                                    'event_post_id' => $data->eventPostId,
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'status' => 'Unread'
                                );
                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                            }
                       }


                            //Appending ticket name in each purchase ticket
                            foreach($purchasedTicketsArray as $k){
                                $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticket_id)->first();
                                $k->ticket_name = $ticketDetails->ticket;
                            }


                             //rewarding award points if purchased via Invite
                             if(isset($data->eventInviteId)){
                                $eventInviteDetails = DB::table('event_invites')->where([ ['event_invite_id', $data->eventInviteId], ['status', 'Invited'] ])->first();
                                if($eventInviteDetails){
                                    $user_data = DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->where('status','Active')->first();
                                    if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                           $updatedConncash = $user_data->total_conncash + 5;
                                           DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                           DB::table('conncash_details')->insert(array(
                                                                               'users_id' => $eventInviteDetails->sender_id,
                                                                               'conncash_source' => 'InviteReward',
                                                                               'conncash' => '5',
                                                                               'method' => 'Conncash',
                                                                               'event_post_id' => $data->eventPostId,
                                                                               'added_at'=> date('Y-m-d H:i:s'),
                                                                           ));

                                    } else if($data->totalAmount >= '20'){
                                           $updatedConncash = $user_data->total_conncash + 10;
                                           DB::table('users')->where('users_id',$eventInviteDetails->sender_id)->update(array('total_conncash' => $updatedConncash));
                                           DB::table('conncash_details')->insert(array(
                                                                               'users_id' => $eventInviteDetails->sender_id,
                                                                               'conncash_source' => 'InviteReward',
                                                                               'conncash' => '10',
                                                                               'method' => 'Conncash',
                                                                               'event_post_id' => $data->eventPostId,
                                                                               'added_at'=> date('Y-m-d H:i:s'),
                                                                           ));
                                    }
                                    //Update invite status in event invites
                                    DB::table('event_invites')->where('event_invite_id', $data->eventInviteId)->update([ 'status', 'PurchasedOnInvite']);
                                }
                            }

                              //saving earning of event organizer
                              $organizerId = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                              $earning_data = array(
                                  'transaction_id' => $lastTransactionId,
                                  'users_id' => $organizerId->users_id,
                                   'status' => 'Earned',
                                   'date_time' => date('Y-m-d H:i:s')
                              );
                                  DB::table('earnings')->insert($earning_data);


                                 //Email to user for ticket details and payment confirmation
                                    $userDetails = DB::table('users')->where('users_id', $data->usersId)->first();
                                    $purchasedTicketsEventDetails = DB::table('event_posts')->where('event_post_id', $transactionDetails->event_post_id)->first();

                                    $toEmail = $userDetails->email;
                                    $ticketDetailsQuantities = "";

                                    $message = "";
                                    $subject = "Transaction details from connevents";
                                    $baseDetails = "Greetings ".$userDetails->user_name.",<br>
                                                Thankyou for purchasing tickets from connevents.<br>
                                                Here's the detail of your purchase: <br><br><br>

                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Transaction ID: </strong></td>
                                                            <td>".$transactionDetails->transaction_id."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Event title: </strong></td>
                                                            <td>".$purchasedTicketsEventDetails->title."</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Payment Method: </strong></td>
                                                            <td>Conncash</td>
                                                        </tr>
                                                         <tr>
                                                            <td><strong>Amount: </strong></td>
                                                            <td>".$transactionDetails->total_amount."$</td>
                                                        </tr>";

                                            foreach($data->purchasedTickets as $k){
                                                  $ticketDetails = DB::table('tickets')->where('ticket_id', $k->ticketId)->first();
                                                $ticketDetailsQuantities .= "<tr>
                                                                        <td><strong>".$ticketDetails->ticket." tickets: </strong></td>
                                                                        <td>".$k->quantity."</td>
                                                                    </tr>";
                                                                    }

                                $totalPplTblServicesDetails =" <tr>
                                                            <td><strong>Total People table services: </strong></td>
                                                            <td>".$transactionDetails->total_ppl_tbl_services."</td>
                                                        </tr>";

                                            $footerDetails = "<tr>
                                                                <td><strong>Transaction on: </strong></td>
                                                                <td>".date('d/m/Y -- H:i A', strtotime($transactionDetails->created_at))."</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                        <br><br>
                                                        This transaction has been posted to your account and the receipt can also be found in your ticket library.<br>
                                                         If you have any questions, please call 1-800-555-555 or send us a note at <strong>mailto: </strong>customersupport@connevents.com. <br>
                                                          Our customer service representatives are available Monday through Friday; 9 a.m. to 4 p.m ET.
                                                          <br>

                                                          Best Regards, <br>
                                                          ConnEvents Customer Service Team";

                                                  if($transactionDetails->total_ppl_tbl_services != ""){
                                                    $message = $baseDetails.$ticketDetailsQuantities.$totalPplTblServicesDetails.$footerDetails;
                                                }else{
                                                  $message = $baseDetails.$ticketDetailsQuantities.$footerDetails;
                                                }


                                       //emailData
                                       $emailDetails = array(
                                        'to_email' => $toEmail,
                                        'subject' => $subject,
                                        //'from_email' => $fromEmail,

                                    );
                                    $message_content = $message;

                                    Mail::send(array(), array(), function ($message) use ($emailDetails, $message_content) {
                                                                    $message->to($emailDetails['to_email'])
                                                                    ->subject($emailDetails['subject'])
                                                                    //->from($emailDetails['from_email'])
                                                                    ->setBody($message_content, 'text/html');
                                                                });


                      //response
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $purchasedTicketsArray;
                }
                else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Something happed in database transaction";
                }
           }
              else{
                  $response["code"] = 500;
                  $response['status']="error";
                  $response['message'] = "Conncash is not enough for transaction";
               }
       } else{
           //case where tickets are not being passed in array
           $response["code"] = 404;
           $response["status"] = "error";
           $response["message"] = "Tickets does not exists in array.";
          }

        return response()
                    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    } */
     /*** PURCHASE TICKETS WITH CONNCASH ***/




    /*** REQUEST REFUND ***/
    public function request_refund(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventPostId) && isset($data->transactionId) && isset($data->totalAmount)  && isset($data->ticketBuyerId) && isset($data->userTickets)){
            $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->first();

            if($eventDetails->refundable == 1){
                    $date_now = new DateTime();
                    $eventStartDate  = new DateTime($eventDetails->event_start_date);
                    $diff = $date_now->diff($eventStartDate);

                    if($diff->days > 0){
                            $transaction_details = DB::table('transactions')
                                                    ->where([ ['transaction_id', $data->transactionId],
                                                            ['event_post_id', $data->eventPostId],
                                                            ['users_id', $data->ticketBuyerId],
                                                            ['status', 'Paid']
                                                            ])  ->first();

                                        if($transaction_details){
                                            $checkAlreadyRefundRequest = DB::table('refund_requests') ->where([
                                                                                                                ['transaction_id', $data->transactionId],
                                                                                                                ['event_post_id', $data->eventPostId],
                                                                                                                ['event_organizer_id', $eventDetails->users_id],
                                                                                                                ['ticket_buyer_id', $data->ticketBuyerId],
                                                                                                                ['status', 'Requested'],
                                                                                                            ])->count();
                                                    if($checkAlreadyRefundRequest == 0){
                                                                $refundData = array(
                                                                    'transaction_id'=> $data->transactionId,
                                                                    'event_post_id'=> $data->eventPostId,
                                                                    'total_amount'=> $data->totalAmount,
                                                                    'event_organizer_id'=> $eventDetails->users_id,
                                                                    'ticket_buyer_id'=> $data->ticketBuyerId,
                                                                    'datetime' => date('Y-m-d H:i:s'),
                                                                    'status'=> 'Requested'
                                                            );
                                                        $refundRequestQuery = DB::table('refund_requests')->insert($refundData);
                                                            if($refundRequestQuery){
                                                                            //sending push notification
                                                                            $senderDetails= DB::table('users')->where([ ['users_id', $data->ticketBuyerId], ['status', 'Active'] ])->first();
                                                                            $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                                                                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Requested a refund of your event ticket  ', 'requestRefund');
                                                                            if($notificationResponse){
                                                                                $notificationData = array(
                                                                                    'sender_users_id' => $senderDetails-> users_id,
                                                                                    'receiver_users_id' => $receiverDetails-> users_id,
                                                                                    'notification_type' => 'RequestRefund',
                                                                                    'message' => ' Requested refund',
                                                                                    'event_post_id' => $data->eventPostId,
                                                                                    'refund_amount' => $data->totalAmount,
                                                                                    'datetime' => date('Y-m-d H:i:s'),
                                                                                    'status' => 'Unread'
                                                                                );
                                                                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                                                            }

                                                                DB::table('user_tickets')->whereIn('user_ticket_id', $data->userTickets)->update(['status' => 'Refund Requested']);
                                                                DB::table('transactions')->where('transaction_id', $data->transactionId)->update(['status' => 'Refund Requested']);

                                                                $response["code"] = 200;
                                                                $response["status"] = "success";
                                                                $response["data"] = "Refund request for your tickets is submitted successfully";
                                                            }
                                                    } else{
                                                        $response["code"] = 404;
                                                        $response["status"] = "error";
                                                        $response["message"] = "Refund Already Requested";
                                                    }

                                            } else{
                                                $response["code"] = 404;
                                                $response["status"] = "error";
                                                $response["message"] = "Refund Already Requested";
                                            }
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Ticket can only be refunded before a day event starts";
                    }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Ticket of this event is non-refundable";
              }
        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
    /*** REQUEST REFUND ***/


    /*** GET ALL REFUND REQUESTS ***/
    public function get_refund_requests(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId)){
            $refundRequestedTickets = DB::table('refund_requests')->where('event_organizer_id',$data->usersId)->where('status','Requested')->get();

               if(sizeof($refundRequestedTickets) > 0){
                   foreach($refundRequestedTickets as $k){
                            //Appending username of ticket buyer
                            $buyerDetail = DB::table('users')->where('users_id', $k->ticket_buyer_id)->first();
                            $k->buyer_username = $buyerDetail->user_name;

                            //Appending Event Details of each post
                            $eventDetails = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();
                            $k->event_name = $eventDetails->title;

                            $k->starting_date = date('d/m/Y', strtotime($eventDetails->event_start_date));
                            $k->starting_time = date('H:i A', strtotime($eventDetails->event_start_time));

                            //Appending user tickets array of transaction
                            $transactionDetails = DB::table('transactions')->where([ ['transaction_id', $k->transaction_id], ['status', 'Refund Requested']])->first();

                            //DB::enableQueryLog();

                            $userPurchasedTicketsData = DB::table('user_tickets')->select('user_ticket_id')
                                                                                    ->where([
                                                                                            ['users_id', $k->ticket_buyer_id],
                                                                                            ['event_post_id', $k->event_post_id],
                                                                                            ['status', 'Refund Requested'],
                                                                                          ])
                                                                                     ->whereDate('transaction_date', date('Y-m-d', strtotime($transactionDetails->created_at)) )
                                                                                     ->whereTime('transaction_date', date('H:i:s', strtotime($transactionDetails->created_at)) )
                                                                                    ->get();   
                            //dd(DB::getQueryLog()); 
                            //print_r($userPurchasedTicketsData); exit;

                            $userTickets = Arr::pluck($userPurchasedTicketsData, 'user_ticket_id');
                            $k->user_tickets = $userTickets;

                   }
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $refundRequestedTickets;
               } else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "No Refund requests found";
               }

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
    /*** GET ALL REFUND REQUESTS ***/



     /*** REFUND TICKETS ***/
     public function accept_refund_request(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->eventPostId) && isset($data->transactionId) && isset($data->totalAmount) && isset($data->userTickets)){
            $checkRefundable = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->first();
            if($checkRefundable->refundable == 1){
                $transaction_details = DB::table('transactions')
                                            ->where([ ['transaction_id', $data->transactionId],
                                                    ['event_post_id', $data->eventPostId],
                                                    ['users_id', $data->userId],
                                                    ['status', 'Refund Requested']
                                                    ])  ->first();

                if($transaction_details){
                    switch($transaction_details->payment_method){
                        case 'Card':
                            $charge_id = $transaction_details->charge_id;
                            if($charge_id != null){
                                try {
                                    require_once('vendor/stripe-php/init.php');
                                    $stripe = new StripeClient("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");

                                    $refund = $stripe->refunds->create([
                                        'charge' => $charge_id,
                                        'amount' => $data->totalAmount * 100,
                                        'reason' => 'requested_by_customer'
                                    ]);

                                    if($refund->status == 'succeeded'){

                                        $refundQuery =  DB::table('transactions')
                                                            ->where([ ['transaction_id', $data->transactionId],
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['users_id', $data->userId],
                                                                                ['status', 'Refund Requested']
                                                                                ])
                                                                        ->update(array('status' => 'Refunded', 'updated_at' => date('Y-m-d H:i:s')));

                                        $userTicketsUpdateQuery = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->update(array( 'status' => 'Refunded'));
                                        if($refundQuery && $userTicketsUpdateQuery){
                                            $refundRequestTableUpdate = DB::table('refund_requests')->where([ ['transaction_id',$data->transactionId], ['status', 'Requested'] ])->update(array( 'status' => 'Refunded'));
                                            // Incrementing quantity of tickets after refund
                                            $userTicketsDetails = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->where('status', 'Refunded')->get();
                                            if(sizeof($userTicketsDetails) > 0){
                                                    foreach($userTicketsDetails  as $k){
                                                        $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticket_id)->where('status','Active')->first();
                                                        $updatedQuantity = $previousQuantity->quantity + $k->quantity;
                                                        $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticket_id)->update(array( 'quantity' => $updatedQuantity));
                                                    }
                                            }

                                            //Deducting reward conncash points
                                            $user_data = DB::table('users')->where('users_id',$data->userId)->where('status','Active')->first();
                                            if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                                $updatedConncash = $user_data->total_conncash - 5;
                                                DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                                DB::table('conncash_details')->insert(array(
                                                        'users_id' => $data->userId,
                                                        'conncash_source' => 'RefundDeductReward',
                                                                            'conncash' => '5',
                                                                            'method' => 'Card',
                                                                            'event_post_id' => $data->eventPostId,
                                                                            'added_at'=> date('Y-m-d H:i:s'),
                                                                        ));
                                            } else if($data->totalAmount >= '20'){
                                                $updatedConncash = $user_data->total_conncash - 10;
                                                DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                                $conncash_det_upd =  DB::table('conncash_details')->insert(array(
                                                    'users_id' => $data->userId,
                                                    'conncash_source' => 'RefundDeductReward',
                                                    'conncash' => '10',
                                                    'method' => 'Card',
                                                    'event_post_id' => $data->eventPostId,
                                                    'added_at'=> date('Y-m-d H:i:s'),

                                                ));
                                            }

                                            $response["code"] = 200;
                                            $response["status"] = "success";
                                            $response["data"] = "Ticket of this event is refunded successfully";
                                        }
                                    }
                                } catch (\Exception $ex) {
                                    return $ex->getMessage().' error occured';
                                }
                            } else{
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "No charge id found against this transaction";
                            }
                        break;

                        case 'Conncash':
                            $previousData = DB::table('users')->where('users_id',$data->userId)->where('status','Active')->first();
                            $updatedConncash = $previousData->conncash_dollars + $data->totalAmount;
                            $userConncashRefundQuery = DB::table('users')->where('users_id',$data->userId)->update(array('conncash_dollars' => $updatedConncash));

                            $refundQuery =  DB::table('transactions')
                                        ->where('event_post_id', $data->eventPostId)
                                        ->where('users_id',$data->userId)
                                        ->where('total_amount',$data->totalAmount)
                                        ->update(array('status' => 'Refunded'));

                            $userTicketsUpdateQuery = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->update(array( 'status' => 'Refunded'));
                            $convertedConncash = Helper::convert_dollars($data->totalAmount);
                            $conncashDet = array(
                                'users_id' => $data->userId,
                                'conncash' =>  $convertedConncash,
                                'conncash_source' => 'PurchaseRefund',
                                'method' => 'Conncash',
                                'event_post_id'=> $data->eventPostId,
                                'added_at' => date('Y-m-d H:i:s')
                            );
                            $conncashDetailsInsert = DB::table('conncash_details')->insert($conncashDet);

                            if($userConncashRefundQuery && $userTicketsUpdateQuery && $conncashDetailsInsert){
                                $refundRequestTableUpdate = DB::table('refund_requests')->where([ ['transaction_id',$data->transactionId], ['status', 'Requested'] ])->update(array( 'status' => 'Refunded'));

                                // Incrementing quantity of tickets after refund
                                $userTicketsDetails = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->where('status', 'Refunded')->get();
                                if(sizeof($userTicketsDetails) > 0){
                                        foreach($userTicketsDetails  as $k){
                                            $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticket_id)->where('status','Active')->first();
                                            $updatedQuantity = $previousQuantity->quantity + $k->quantity;
                                            $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticket_id)->update(array( 'quantity' => $updatedQuantity));
                                        }
                                }

                                //Deducting reward conncash points
                                $user_data = DB::table('users')->where('users_id',$data->userId)->where('status','Active')->first();
                                if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                        $updatedConncash = $user_data->total_conncash - 5;
                                        DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                        DB::table('conncash_details')->insert(array(
                                                                            'users_id' => $data->userId,
                                                                            'conncash_source' => 'RefundDeductReward',
                                                                            'conncash' => '5',
                                                                            'method' => 'Card',
                                                                            'event_post_id' => $data->eventPostId,
                                                                            'added_at'=> date('Y-m-d H:i:s'),
                                                                        ));
                                } else if($data->totalAmount >= '20'){
                                        $updatedConncash = $user_data->total_conncash - 10;
                                        DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                        $conncash_det_upd =  DB::table('conncash_details')->insert(array(
                                                                            'users_id' => $data->userId,
                                                                            'conncash_source' => 'RefundDeductReward',
                                                                            'conncash' => '10',
                                                                            'method' => 'Card',
                                                                            'event_post_id' => $data->eventPostId,
                                                                            'added_at'=> date('Y-m-d H:i:s'),

                                                                        ));
                                }

                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = "Ticket of this event is refunded successfully";
                            } else {
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "Something went wrong";
                            }
                        break;

                        case 'Google':
                            $refundQuery =  DB::table('transactions')
                                                ->where([ ['transaction_id', $data->transactionId],
                                                        ['event_post_id', $data->eventPostId],
                                                        ['users_id', $data->userId],
                                                        ['status', 'Refund Requested']
                                                        ])
                                                ->update(array('status' => 'Refunded', 'updated_at' => date('Y-m-d H:i:s')));

                            $userTicketsUpdateQuery = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->update(array( 'status' => 'Refunded'));
                            if($refundQuery && $userTicketsUpdateQuery){
                                $refundRequestTableUpdate = DB::table('refund_requests')->where([ ['transaction_id',$data->transactionId], ['status', 'Requested'] ])->update(array( 'status' => 'Refunded'));
                                // Incrementing quantity of tickets after refund
                                $userTicketsDetails = DB::table('user_tickets')->whereIn('user_ticket_id',$data->userTickets)->where('status', 'Refunded')->get();
                                if(sizeof($userTicketsDetails) > 0){
                                        foreach($userTicketsDetails  as $k){
                                            $previousQuantity = DB::table('tickets')->where('ticket_id',$k->ticket_id)->where('status','Active')->first();
                                            $updatedQuantity = $previousQuantity->quantity + $k->quantity;
                                            $ticketQuantityUpdateQuery = DB::table('tickets')->where('ticket_id',$k->ticket_id)->update(array( 'quantity' => $updatedQuantity));
                                        }
                                }

                                //Deducting reward conncash points
                                $user_data = DB::table('users')->where('users_id',$data->userId)->where('status','Active')->first();
                                if($data->totalAmount >= '10' && $data->totalAmount < '20'){
                                    $updatedConncash = $user_data->total_conncash - 5;
                                    DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                    DB::table('conncash_details')->insert(array(
                                            'users_id' => $data->userId,
                                            'conncash_source' => 'RefundDeductReward',
                                                                'conncash' => '5',
                                                                'method' => 'Card',
                                                                'event_post_id' => $data->eventPostId,
                                                                'added_at'=> date('Y-m-d H:i:s'),
                                                                    ));
                                } else if($data->totalAmount >= '20'){
                                    $updatedConncash = $user_data->total_conncash - 10;
                                    DB::table('users')->where('users_id',$data->userId)->update(array('total_conncash' => $updatedConncash));
                                    $conncash_det_upd =  DB::table('conncash_details')->insert(array(
                                        'users_id' => $data->userId,
                                        'conncash_source' => 'RefundDeductReward',
                                        'conncash' => '10',
                                        'method' => 'Card',
                                        'event_post_id' => $data->eventPostId,
                                        'added_at'=> date('Y-m-d H:i:s'),

                                    ));
                                }

                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = "Ticket of this event is refunded successfully";
                            }
                        break;

                        default:
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "Payment gateway not found";
                        break;
                    }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Transaction not found";
                }
            } else {
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Ticket of this event is non-refundable";
            }
        } else {
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }

        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
    /*** ACCEPT REFUND REQUEST ***/



    // ========= SUBSCRIPTION API's ===================/

         /*** PREMIUM SUBSCRIPTION ***/
  /*   public function subscribe_to_premium(Request $req){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId)){
            $user_details = DB::table('users')->where('users_id', $data->userId)->first();
            $checkPremium = $user_details->subscription_package_id;

            $date1 = Carbon::now();
            $date2 = Carbon::createFromFormat('Y-m-d H:s:i', $user_details->package_updated_at);

            //check months
            $diff_in_months  = $date1->diffInMonths($date2);

            if($checkPremium == '2' && $diff_in_months == '0'){
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Your package is already subscribed to premium";
            } else{
                 $premium_package_details = DB::table('subscription_packages')
                                                            ->where('subscription_package_id','2')
                                                            ->first();

                    $premium_amount = $premium_package_details->subscription_price;

                        $card_id = $data->cardId;
                        $user_id = $data->userId;
                        $stripe_token = $data->stripeToken;
                    // stripe payment

                    try {
                        require_once('vendor/stripe-php/init.php');

                        Stripe::setApiKey(env('STRIPE_SECRET'));

                        $checkExistingCustomer = DB::table('users')->where('users_id', $user_id)->first();

                    if($checkExistingCustomer->stripe_customer_id == null){
                        $customer = Customer::create([
                            'id' => $user_id,
                            'source'  => $stripe_token
                        ]);
                        $customer_id = $customer->id;
                      $updateCustomerId = DB::table('users')->where('users_id',$user_id)->update(array('stripe_customer_id' => $customer_id));
                    } else{
                        // existing customer is being used
                        $customer_id = $checkExistingCustomer->stripe_customer_id;
                    }

                    $pkg_selected_id   = "1";
                    $currency     = 'USD';

                    $charge = Charge::create(array(
                        'customer' => $customer_id,
                        'amount'   => $premium_amount * 100,
                        'currency' => $currency,
                        'description' => 'Premium package upgrade',
                        'metadata' => array(
                            'premium_id_temp' => $pkg_selected_id
                        )
                    ));

                        if($charge->status == 'succeeded'){
                                DB::table('users')->where('users_id', $data->userId)->update(array(
                                                                                                'subscription_package_id' => '2',
                                                                                                'package_updated_at' => date('Y-m-d H:i:s')
                                                                                                ));
                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = 'Package upgraded to premium successfully';

                        } else{
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "Error in transaction.";
                            }

                    } catch (\Exception $ex) {
                        return $ex->getMessage().' error occured';
                    }

            }

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }

        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
 */


   // ========= SUBSCRIPTION API's ===================/

         /*** PREMIUM SUBSCRIPTION ***/
         public function subscribe_to_premium(Request $req){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->userId) && isset($data->stripeToken) && isset($data->paymentType)){

                $user_details = DB::table('users')->where('users_id', $data->userId)->first();
                $checkPremium = $user_details->subscription_package_id;

                $date1 = Carbon::now();
                $date2 = Carbon::createFromFormat('Y-m-d H:s:i', $user_details->package_updated_at);

                //check months
                $diff_in_months  = $date1->diffInMonths($date2);

                $one_time_package_details = DB::table('subscription_packages')
                    ->where('subscription_package_id','2')
                    ->first();

                $one_time_post_amount = $one_time_package_details->subscription_price;

                $user_id = $data->userId;
                $stripe_token = $data->stripeToken;


                             if($checkPremium == '2' && $diff_in_months == '0'){
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["message"] = "Your package is already subscribed to premium";
                            }else{

                                 // stripe payment
                                 try {
                                     require_once('vendor/stripe-php/init.php');

                                     Stripe::setApiKey("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");

                                     $checkExistingCustomer = DB::table('users')->where('users_id', $user_id)->first();

                                     if($checkExistingCustomer->stripe_customer_id == null){
                                         $customer = Customer::create([
                                             'id' => $user_id,
                                             'source'  => $stripe_token
                                         ]);
                                         $customer_id = $customer->id;
                                         $updateCustomerId = DB::table('users')->where('users_id',$user_id)->update(array('stripe_customer_id' => $customer_id));
                                     } else{
                                         // existing customer is being used
                                         $customer_id = $checkExistingCustomer->stripe_customer_id;
                                     }

                                     $pkg_selected_id   = "1";
                                     $currency     = 'USD';

                                     $charge = Charge::create(array(
                                         'customer' => $customer_id,
                                         'amount'   => $one_time_post_amount * 100,
                                         'currency' => $currency,
                                         'description' => 'Premium purchase',
                                         'metadata' => array(
                                             'one_time_post_id_temp' => $pkg_selected_id
                                         )
                                     ));

                                     if($charge->status == 'succeeded'){
                                         $oneTimePostUpdatedCount = $checkExistingCustomer->one_time_post_count + 1;



                                         //Transaction result being saved in Subscription Income after success
                                         $subscription_data = array(
                                             'sub_package_id' => 2,
                                             'sub_users_id' => $data->userId,
                                             'sub_total_amount' => $one_time_post_amount,
                                             'sub_payment_method'=>$data->paymentType,
                                             'sub_charge_id' => $charge->id,
                                         );
                                         $lastTransactionId = DB::table('subscription_income')->insertGetId($subscription_data);



                                         DB::table('users')->where('users_id', $data->userId)->update(array(
                                             'one_time_post_count' => $oneTimePostUpdatedCount,
                                             'package_updated_at' => date('Y-m-d H:i:s'),
                                             'subscription_package_id'=> 2
                                         ));
                                         $response["code"] = 200;
                                         $response["status"] = "success";
                                         $response["data"] = 'Package upgraded to Organizer type Premium successfully';


                                     } else{
                                         $response["code"] = 500;
                                         $response["status"] = "error";
                                         $response["one_time_post_count"] = '0';
                                         $response["message"] = "Error in transaction.";
                                     }

                                 } catch (\Exception $ex) {
                                     return $ex->getMessage().' error occured';
                                 }

                            }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "All Fields are needed";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }

//         nabeel start


    /*** ONE TIME POST PURCHASE ***/
    public function one_time_post_purchase(Request $req){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->stripeToken) && isset($data->paymentType)){
            $user_details = DB::table('users')->where('users_id', $data->userId)->first();
            $checkPremium = $user_details->subscription_package_id;

            if($checkPremium == '2'){
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Your package is already subscribed to premium";
            } else{
                $one_time_package_details = DB::table('subscription_packages')
                    ->where('subscription_package_id','4')
                    ->first();

                $one_time_post_amount = $one_time_package_details->subscription_price;

                $user_id = $data->userId;
                $stripe_token = $data->stripeToken;

                // stripe payment
                try {
                    require_once('vendor/stripe-php/init.php');

                    Stripe::setApiKey("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");

                    $checkExistingCustomer = DB::table('users')->where('users_id', $user_id)->first();

                    if($checkExistingCustomer->stripe_customer_id == null){
                        $customer = Customer::create([
                            'id' => $user_id,
                            'source'  => $stripe_token
                        ]);
                        $customer_id = $customer->id;
                        $updateCustomerId = DB::table('users')->where('users_id',$user_id)->update(array('stripe_customer_id' => $customer_id));
                    } else{
                        // existing customer is being used
                        $customer_id = $checkExistingCustomer->stripe_customer_id;
                    }

                    $pkg_selected_id   = "1";
                    $currency     = 'USD';

                    $charge = Charge::create(array(
                        'customer' => $customer_id,
                        'amount'   => $one_time_post_amount * 100,
                        'currency' => $currency,
                        'description' => 'One time post purchase',
                        'metadata' => array(
                            'one_time_post_id_temp' => $pkg_selected_id
                        )
                    ));

                    if($charge->status == 'succeeded'){
                        $oneTimePostUpdatedCount = $checkExistingCustomer->one_time_post_count + 1;



                        //Transaction result being saved in Subscription Income after success
                            $subscription_data = array(
                                'sub_package_id' => 4,
                                'sub_users_id' => $data->userId,
                                'sub_total_amount' => $one_time_post_amount,
                                'sub_payment_method'=>$data->paymentType,
                                'sub_charge_id' => $charge->id,
                            );
                            $lastTransactionId = DB::table('subscription_income')->insertGetId($subscription_data);



                        DB::table('users')->where('users_id', $data->userId)->update(array(
                            'one_time_post_count' => $oneTimePostUpdatedCount,
                            'package_updated_at' => date('Y-m-d H:i:s'),
                            'subscription_package_id'=> 4
                        ));
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = 'One time post purchased successfully';

                        $oneTimePostCountQuery = DB::table('users')->where('users_id', $data->userId)->first();
                        $response["one_time_post_count"] = $oneTimePostCountQuery->one_time_post_count;

                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["one_time_post_count"] = '0';
                        $response["message"] = "Error in transaction.";
                    }

                } catch (\Exception $ex) {
                    return $ex->getMessage().' error occured';
                }

            }

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["one_time_post_count"] = '0';
            $response["message"] = "All Fields are needed";
        }

        return response()
            ->json(array('one_time_post_count' => $response["one_time_post_count"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /*** CHECK USER SUBSCRIPTION ***/

//    nabeel end



         public function check_user_subscription(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->userId)){
                  $checkSubscription = DB::table('users')->select('subscription_package_id')->where('users_id',$data->userId)->first();

                  if($checkSubscription){
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $checkSubscription;
                } else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "User not found.";
                }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "All fields are needed";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
         /*** CHECK USER SUBSCRIPTION ***/


         /*** CANCEL PACKAGE SUBSCRIPTION ***/
         public function cancel_subscription(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->userId)){
                  $userDetails = DB::table('users')->where('users_id', $data->userId)->first();

                if($userDetails->subscription_package_id == '2' || $userDetails->subscription_package_id == '4'){
                    DB::table('users')->where('users_id', $data->userId)->update(array('subscription_package_id' => '1',
                                                                                        'package_updated_at' => date('Y-m-d H:i:s')) );

                            if($userDetails->subscription_package_id == '4'){
                                 //in business premium case
                                 DB::table('users')->where('users_id', $data->userId)->update(['business_identification_number' => '']);
                                 $cancel_data = array(
                                    'users_id' => $data->userId,
                                    'subscription_package_id' => '4',
                                    'canceled_at' =>  date('Y-m-d H:i:s')
                                );

                            }else{
                                //Organizer premium case
                                $cancel_data = array(
                                    'users_id' => $data->userId,
                                    'subscription_package_id' => '2',
                                    'canceled_at' =>  date('Y-m-d H:i:s')
                                );
                            }

                    DB::table('canceled_subscriptions')->insert($cancel_data);

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Premium subscription is canceled successfully';
                } else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Your package is not premium.";
                }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "All fields are needed";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
         /*** CANCEL PACKAGE SUBSCRIPTION ***/


     /*** UPLOAD VIDEOS API ***/
     public function upload_video(Request $req){
        $response = array();
        $postfix = 'conn_events_vid';
        $upd_prefix = date('d-m-y');
        $random = rand(1,1000000);

        $file_name = $upd_prefix.$random.$postfix.'.mp4';
        $video_path_url= config('base_urls.relative_path_event_videos');

        $uploadedVideo = $req->file('video')->move($video_path_url,$file_name);
         if($uploadedVideo){
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $file_name;
         } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "Uploading failed";
         }

        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }

     /*** UPLOAD VIDEOS API ***/


        /*** DELETE VIDEO API ***/
        public function delete_video(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));
            $video_path_url= config('base_urls.relative_path_event_videos');

            if(isset($data->fileName)){
               $result =  File::delete($video_path_url.$data->fileName);
               if($result){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'file deleted successfully';
               } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Deleting failed";
               }

            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "All fields are needed.";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
        /*** DELETE VIDEO API ***/


        /*** CREATED EVENT DELETE IMAGE API ***/
        public function created_event_delete_image(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            $image_path_url= config('base_urls.relative_path_event_images');

            if(isset($data->fileName) && isset($data->eventPostId)){
               $result =  File::delete($image_path_url.$data->fileName);
               if($result){
                   $old_data = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->first();
                         if($old_data->first_image == $data->fileName){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('first_image' => ''));
                          }
                          if($old_data->second_image == $data->fileName){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('second_image' => ''));
                          }
                          if($old_data->third_image == $data->fileName){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('third_image' => ''));
                          }
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'file deleted successfully';
               } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Deleting failed";
               }

            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "All fields are needed.";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
        /*** CREATED EVENT DELETE IMAGE API ***/


        /*** CREATED EVENT DELETE VIDEO API ***/
        public function created_event_delete_video(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));
            $thumbnail_path_url= config('base_urls.relative_path_video_thumbnails');
            $video_path_url= config('base_urls.relative_path_event_videos');

            if(isset($data->videoName) && isset($data->thumbnailName) && isset($data->eventPostId)){
               $result1 =  File::delete($video_path_url.$data->videoName);
               $result2 =  File::delete($thumbnail_path_url.$data->thumbnailName);

               if($result1 && $result2){
                   $old_data = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->first();
                         if(($old_data->first_video == $data->videoName) && ($old_data->first_video_thumbnail == $data->thumbnailName) ){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('first_video' => '', 'first_video_thumbnail' => ''));
                          }
                          if(($old_data->second_video == $data->videoName) && ($old_data->second_video_thumbnail == $data->thumbnailName) ){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('second_video' => '', 'second_video_thumbnail' => ''));
                          }
                          if(($old_data->third_video == $data->videoName) && ($old_data->third_video_thumbnail == $data->thumbnailName) ){
                            DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->update(array('third_video' => '', 'third_video_thumbnail' => ''));
                          }
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'file deleted successfully';
               } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Deleting failed";
               }

            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "All fields are needed.";
            }

            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
        /*** CREATED EVENT DELETE VIDEO API ***/


     /*** FAVOURITE EVENT ***/
     public function favourite_event_post(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->eventPostId)){
                $fav_data = array(
                    'users_id' => $data->userId,
                    'event_post_id' => $data->eventPostId
                );

              $checkEvent = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->where('status','Active')->first();
              if($checkEvent){
                    $favDataQuery = DB::table('event_favourites')->insert($fav_data);
                    if($favDataQuery){
                                //sending push notification
                                $senderDetails= DB::table('users')->where([ ['users_id', $data->userId], ['status', 'Active'] ])->first();
                                $receiverDetails= DB::table('users')->where([ ['users_id', $checkEvent->users_id], ['status', 'Active'] ])->first();

                                  if($senderDetails->users_id != $receiverDetails->users_id){
                                    $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Posts'], ['users_id', $receiverDetails->users_id ]])->first();
                                            if($checkNotificationSetting->status == 'On'){
                                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' favourite your event post ', 'eventFavourite');
                                                if($notificationResponse){
                                                    $notificationData = array(
                                                        'sender_users_id' => $senderDetails-> users_id,
                                                        'receiver_users_id' => $receiverDetails-> users_id,
                                                        'notification_type' => 'EventFavourite',
                                                        'message' => ' favourite your event post ',
                                                        'event_post_id' => $data->eventPostId,
                                                        'datetime' => date('Y-m-d H:i:s'),
                                                        'status' => 'Unread'

                                                    );
                                                    $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                                }
                                            }

                                  }

                      $response["code"] = 200;
                      $response["status"] = "success";
                      $response["data"] = "Added to favourites successfully";
                    } else{
                      $response["code"] = 500;
                      $response["status"] = "error";
                      $response["message"] = "Something went wrong";
                    }
              } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Event Not found";
              }
        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
     /*** FAVOURITE EVENT ***/

     /*** UN_FAVOURITE EVENT ***/
     public function unfavourite_event_post(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->eventPostId)){
                $fav_data = array(
                    'users_id' => $data->userId,
                    'event_post_id' => $data->eventPostId
                );

              $checkFav = DB::table('event_favourites')->where('event_post_id',$data->eventPostId)
                                                        ->where('users_id',$data->userId)->first();
              if($checkFav){
                  $unfavDataQuery = DB::table('event_favourites')->where('event_post_id',$data->eventPostId)
                                                         ->where('users_id',$data->userId)->delete();
                        if($unfavDataQuery){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = "Removed from favourites successfully";
                         } else{
                            $response["code"] = 500;
                            $response["status"] = "error";
                            $response["message"] = "Something went wrong";
                         }
              } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Event Not found in favourites";
              }
        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }
     /*** UN_FAVOURITE EVENT ***/


           /*** GET ALL FAVOURITE EVENTS WITH FILTER ***/
public function get_all_favourites(){
            $data = json_decode(file_get_contents("php://input"));
            $response = array();
            $fav_events_array = array();
            $filtered_miles_response = array();
            $filtered_miles_count = array();


            if(isset($data->offset) && isset($data->userId) && isset($data->userLat) && isset($data->userLong)){

                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                $user_timezone = $response["timeZoneId"];

      $checkFavourites = DB::table('event_favourites')->where('users_id',$data->userId)->get();
        if(sizeof($checkFavourites) > 0){
                    //loop for whereIn array
                    foreach($checkFavourites as $k){
                        array_push($fav_events_array, $k->event_post_id);
                    }
            //Query to get total posts with filters for pagination
            $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                                                ->whereIn('event_posts.event_post_id', $fav_events_array)
                                                                ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                                                // ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                                                                ->where('event_posts.status', 'Active')->get();

      //Main filtering query with limit
        $filtered_posts =    DB::table('event_posts')
                                            ->select('event_posts.*','addresses.*')
                                            ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                                ->whereIn('event_posts.event_post_id', $fav_events_array)
                                                ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                            //  ->whereTime('event_posts.event_end_time','>', date('H:i:s'))
                                                    ->where('event_posts.status', 'Active')
                                            // ->skip($data->offset)->take(5)
                                            ->get();


               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {
                    //count of total posts after checking  miles check for premium
                    if(sizeof($total_posts) == 0){
                        $total_posts_count_after_miles = 0;
                    } else{
                        foreach($total_posts as $k){
                            $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                            $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                            $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                            if($organizerDetails->subscription_package_id == '1' || $organizerDetails->subscription_package_id == '4'){
                                //other case, 50 miles
                                if($eventDistanceFromUser <= 75){
                                    if($k->event_ticket_type == 'NotMyEvent'){
                                        //Remove the event created week ago in this scenario
                                        $daysAgo = Helper::get_days_difference($k->created_at);
                                        if($daysAgo <= '7'){
                                            array_push($filtered_miles_count, $k);
                                        }
                                    }else{
                                        array_push($filtered_miles_count, $k);
                                    }
                                }

                            } else{
                                    //premium organizer and business , both 300 miles
                                        if($eventDistanceFromUser <= 300){
                                            if($k->event_ticket_type == 'NotMyEvent'){
                                                //Remove the event created week ago in this scenario
                                                $daysAgo = Helper::get_days_difference($k->created_at);
                                                if($daysAgo <= '7'){
                                                    array_push($filtered_miles_count, $k);
                                                }
                                            }else{
                                                array_push($filtered_miles_count, $k);
                                            }
                                        }
                            }
                        }

                        $total_posts_count_after_miles = sizeof($filtered_miles_count);
                    }


                    foreach($filtered_posts as $key => $event_post){
                         //appending images urls fields in response
                         $image_base_url = config('base_urls.event_image_base_url');
                         if(!empty( $event_post->first_image)){
                             $event_post->first_image= $image_base_url.$event_post->first_image;
                         }
                          if(!empty( $event_post->second_image)){
                             $event_post->second_image = $image_base_url.$event_post->second_image;
                         }
                          if(!empty( $event_post->third_image)){
                             $event_post->third_image = $image_base_url.$event_post->third_image;
                         }

                   //updating Videos thumbnail urls fields in response
                   $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                         if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                         }
                         if(!empty( $event_post->second_video_thumbnail)){
                              $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                          }
                          if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }

                   //appending Videos urls fields in response
                   $video_base_url = config('event_video_base_url');

                             if(!empty( $event_post->first_video)){
                                 $event_post->first_video= $video_base_url.$event_post->first_video;
                             }
                             if(!empty( $event_post->second_video)){
                                 $event_post->second_video = $video_base_url.$event_post->second_video;
                             }
                             if(!empty( $event_post->third_video)){
                                 $event_post->third_video = $video_base_url.$event_post->third_video;
                             }

                             //time conversion for minutes/hours/days ago
                             foreach($filtered_posts as $k){
//                                $k->time_ago = Helper::time_elapsed_string($k->created_at);
                                 $event_timezone = $k->timezone;
                                 $k->time_ago = Helper::user_time_elapsed_string($k->created_at,$event_timezone);
                            }

                              // appending tickets quantity info
                              foreach($filtered_posts as $k){
                                     $early_bird_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                                        if($early_bird_query){
                                            $k->early_bird_available = $early_bird_query->quantity;
                                        }

                                     $regular_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                                        if($regular_query){
                                            $k->regular_available = $regular_query->quantity;
                                        }

                                     $vip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                                        if($vip_query){
                                            $k->vip_available = $vip_query->quantity;
                                        }

                                  $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                                  if($skip_query){
                                      $k->skipping_line_available = $skip_query->quantity;
                                  }


                                  $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                                  $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                                  $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                                  $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                                  $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;
                            }

                               //appending Category data from categories in response array after success
                               $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                                   if($category){
                                       $k->category = $category;
                                   }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                                    if($event_type){
                                        $k->event_type = $event_type;
                                    }

                            if($event_post->dress_code_id != 0){
                                  //appending Dress Code data from dress_code in response array after success
                              $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                              if($dress_code){
                                  $k->dress_code = $dress_code;
                              }
                            } else{
                                $k->dress_code = null;
                            }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                             ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $event_post->event_tags = $tags;
                                }


                        //fetching tickets from tickets table of each event post
                        $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                        $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                        $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                        $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                        if($ticket1){
                            $event_post->Early_bird = $ticket1;
                        }
                        if($ticket2){
                            $event_post->Regular = $ticket2;
                        }
                        if($ticket3){
                            $event_post->VIP = $ticket3;
                        } if($ticket4){
                            $event_post->skipping_line = $ticket4;
                        }


                         //fetching address from address table of each event post
                         $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $event_post->event_address = $address;
                            }

                            //appending liked event post hint
                        $checkLike = DB::table('event_likes')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                            if($checkLike > 0){
                                $event_post->liked = 'true';
                            } else{
                                $event_post->liked = 'false';
                            }

                            //appending favourite post hint
                        $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->userId)->count();
                        if($isFavouriteQuery > 0){
                            $event_post->isFavourite = 'true';
                        } else{
                            $event_post->isFavourite = 'false';
                        }

                            //appending total likes on event
                            $total_likes =  DB::table('event_posts')->where('event_post_id', $event_post->event_post_id)->first();
                                if($total_likes){
                                    $event_post->total_likes = $total_likes->total_likes;
                                }

                                  //appending event distance in miles from user current location
                                if(isset($data->userLat) && isset($data->userLong)){
                                        $eventCordinates = DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->first();
                                        if($eventCordinates){
                                            $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                                $event_post->distance_miles = $distance_miles_calc;
                                                $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                                $event_post->miles = $miles_calc;
                                        }
                                }

                            }



                    //                    nabeel organizer
                    foreach($filtered_posts as $k){
                        $k->sales_end_datetime = $k->sales_end_date.' '.$k->sales_end_time;
                        // Date conversion in 12 hrs format in response
                        $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                        $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));
                        $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                        $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));

                        // time conversion in 12 hrs format in response
                        $k->event_start_time = date("g:iA", strtotime($k->event_start_time));
                        $k->event_end_time = date("g:iA", strtotime($k->event_end_time));
                        $k->sales_start_time = date("g:iA", strtotime($k->sales_start_time));
                        $k->sales_end_time = date("g:iA", strtotime($k->sales_end_time));

                        //Total comments count on Event Post
                        $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $k->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                        $k->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                        if($k->dress_code_id != 0){
                            // Appending dresscode color code for thumbnail bar color ease in event details
                            $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $k->dress_code_id)->first();
                            $k->dress_code_color_code = $dressCodeColor->dress_code_color;
                        }else{
                            $k->dress_code_color_code = null;
                        }


                        //Appeding organizer details
                        $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                        $k->organizer_users_id = $organizerDetails->users_id;
                        $k->organizer_user_name = $organizerDetails->user_name;

                        if($organizerDetails->profile_picture != ""){
                            $k->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                        }else{
                            $k->organizer_profile_picture =  "";
                        }

                        //following flag
                        $checkFollowing = DB::table('followers')->where([ ['following_to_user',$k->users_id] ,['followed_by_user' ,$data->userId] ])->first();
                        if($checkFollowing){
                            $k->is_following = true;
                        } else{
                            $k->is_following = false;
                        }

                        //totalFollowers
                        $totalFollowers = DB::table('followers')->where('following_to_user', $k->users_id)->count();
                        $k->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                        //CHECK event peeks available
                        $eventPeeks = DB::table('event_peeks')
                            ->select('event_peeks.*', 'event_posts.*')
                            ->join('event_posts', 'event_posts.event_post_id','=','event_peeks.event_post_id')
                            ->where('event_peeks.event_post_id', $k->event_post_id)
                            ->whereDate('event_posts.event_end_date', '>=', date('Y-m-d'))
                            ->where('event_peeks.created_at', '>=', Carbon::now()->subDay())
                            ->count();

                        if($eventPeeks > 0)
                            $k->is_peeks_available = true;
                        else
                            $k->is_peeks_available = false;

                    }


                    foreach($filtered_posts as $k){
                      // Date conversion in 12 hrs format in response
                        $k->event_start_date = date("M d, Y", strtotime($k->event_start_date));
                        $k->event_end_date = date("M d, Y", strtotime($k->event_end_date));
                        $k->sales_start_date = date("M d, Y", strtotime($k->sales_start_date));
                        $k->sales_end_date = date("M d, Y", strtotime($k->sales_end_date));

                      // time conversion in 12 hrs format in response
                        $k->event_start_time = date("g:iA", strtotime($k->event_start_time));
                        $k->event_end_time = date("g:iA", strtotime($k->event_end_time));
                        $k->sales_start_time = date("g:iA", strtotime($k->sales_start_time));
                        $k->sales_end_time = date("g:iA", strtotime($k->sales_end_time));

                          //Total comments count on Event Post
                      $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $k->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                      $k->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);

                       // Appending dresscode color code for thumbnail bar color ease in event details
                       if($event_post->dress_code_id != 0){
                        //appending Dress Code data from dress_code in response array after success
                          $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->first();
                                    $k->dress_code = $dress_code;
                            }else{
                                $k->dress_code = null;
                            }

                  }






                    // display only post in 100 miles for premuium and 50 for other
                            foreach($filtered_posts as $k){
                                $eventAddressDetails = DB::table('addresses')->where('event_post_id',$k->event_post_id)->first();
                                $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                                $organizerDetails = DB::table('users')->where('users_id', $k->users_id)->first();


                                if($k->event_ticket_type == 'NotMyEvent'){
                                    //Remove the event created week ago in this scenario
                                    $daysAgo = Helper::get_days_difference($k->created_at);
                                    if($daysAgo <= '7'){
                                        array_push($filtered_miles_response, $k);
                                      }
                                    }else{
                                        array_push($filtered_miles_response, $k);
                                }

                                /*
                                if($organizerDetails->subscription_package_id == '2'){
                                    //premium case, 100 miles
                                    if($eventDistanceFromUser <= 100){
                                        if($k->event_ticket_type == 'NotMyEvent'){
                                            //Remove the event created week ago in this scenario
                                            $daysAgo = Helper::get_days_difference($k->created_at);
                                            if($daysAgo <= '7'){
                                                array_push($filtered_miles_response, $k);
                                              }
                                            }else{
                                                array_push($filtered_miles_response, $k);
                                            }
                                    }
                                } else{
                                    //other case non premium, 50 miles
                                    if($eventDistanceFromUser <= 50){
                                        if($k->event_ticket_type == 'NotMyEvent'){
                                            //Remove the event created week ago in this scenario
                                            $daysAgo = Helper::get_days_difference($k->created_at);
                                            if($daysAgo <= '7'){
                                                array_push($filtered_miles_response, $k);
                                              }
                                          }else{
                                                array_push($filtered_miles_response, $k);
                                            }
                                    }
                                }
                                */
                            }

                    $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('miles')->skip($data->offset)->take(5)->toArray());

                        if(sizeof($filtered_miles_response) > 0){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            // $response["total_posts"] = $total_posts_count_after_miles;
                            $response["total_posts"] = sizeof($total_posts);
                            $response["data"] = $filtered_miles_response;
                        }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["total_posts"] = 0;
                            $response["message"] = "No favourites found";
                        }


                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = 0;
                    $response["message"] = "No favourites found";
                }
            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = 0;
                $response["message"] = "No favourites found";
            }

            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = 0;
                $response["message"] = "All fields are needed";
            }

        return response()
        ->json(array('status' => $response["status"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }



           /***  REPORT EVENT POST ***/
           public function report_event(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->eventPostId) && isset($data->userId) && isset($data->comments)){
                  $checkReport = DB::table('event_reports')->where('users_id',$data->userId)->where('event_post_id',$data->eventPostId)->count();
                   if($checkReport == 0){
                       $report_data = array(
                           'event_post_id' => $data->eventPostId,
                           'users_id' => $data->userId,
                           'status' => 'Active'
                       );
    
                        $query = DB::table('event_reports')->insertGetId($report_data);
                        if($query){
                            //Inserting event report categories in child table
                            foreach($data->comments as $k){
                                $reportCommentData = array(
                                    'event_reports__id' => $query,
                                    'reports_category' => $k,
                                    'status' =>'Active'
                                );
                                DB::table('event_reports_categories')->insert($reportCommentData);
                            }
                          $response["code"] = 200;
                          $response["status"] = "success";
                          $response["message"] = "This event is reported successfully";
                        } else{
                          $response["code"] = 500;
                          $response["status"] = "error";
                          $response["message"] = "Something went wrong";
                        }
                  } else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "You already reported this event";
                  }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "All Fields are needed";
            }
            return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
           /***  REPORT EVENT POST ***/


          /***  LIKE EVENT POST ***/
          public function like_event(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->eventPostId) && isset($data->userId)){
                  $checkLike = DB::table('event_likes')->where('users_id',$data->userId)->where('event_post_id',$data->eventPostId)->count();
                   if($checkLike == 0){
                       $like_data = array(
                           'event_post_id' => $data->eventPostId,
                           'users_id' => $data->userId,
                           'liked_at' => date('Y-m-d H:i:s'),
                       );
                        $query = DB::table('event_likes')->insert($like_data);
                        if($query){
                            $previous_details = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                             if($previous_details){
                                 $updated_likes = $previous_details->total_likes + 1;
                                 DB::table('event_posts')->where('event_post_id',$data->eventPostId)->update(array('total_likes' => $updated_likes));


                                 $checkLike = DB::table('event_likes')->where('event_post_id', $data->eventPostId)->where('users_id', $data->userId)->count();
                                    if($checkLike == 0){
                                         $response["is_liked"] = "false";
                                    } else{
                                        $response["is_liked"] = "true";
                                    }

                                    $eventDetails = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                                    $response["like_count"] = $eventDetails->total_likes;

                                      //sending push notification
                                      $eventDetails = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                                      $senderDetails= DB::table('users')->where([ ['users_id', $data->userId], ['status', 'Active'] ])->first();
                                      $receiverDetails= DB::table('users')->where([ ['users_id', $eventDetails->users_id], ['status', 'Active'] ])->first();
                                      if($senderDetails->users_id != $receiverDetails->users_id){
                                              $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Posts'], ['users_id', $receiverDetails->users_id ]])->first();
                                         if($checkNotificationSetting->status == 'On'){
                                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Liked your event post', 'eventLike');
                                            if($notificationResponse){
                                                $notificationData = array(
                                                    'sender_users_id' => $senderDetails-> users_id,
                                                    'receiver_users_id' => $receiverDetails-> users_id,
                                                    'notification_type' => 'EventLike',
                                                    'message' => ' Liked your event post',
                                                    'event_post_id' => $data->eventPostId,
                                                    'datetime' => date('Y-m-d H:i:s'),
                                                    'status' => 'Unread'
                                                );
                                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                            }

                                         }

                                        }



                                    $response["code"] = 200;
                                    $response["status"] = "success";
                                    $response["data"] = "This event is Liked successfully";

                             } else{
                                $response["code"] = 500;
                                $response["is_liked"] = "false";
                                $response["like_count"] = "0";
                                $response["status"] = "error";
                                $response["message"] = "Event Post not found";
                             }

                        } else{
                          $response["code"] = 500;
                          $response["is_liked"] = "false";
                          $response["like_count"] = "0";
                          $response["status"] = "error";
                          $response["message"] = "Something went wrong";
                        }
                  } else{
                    $response["code"] = 404;
                    $response["is_liked"] = "false";
                    $response["like_count"] = "0";
                    $response["status"] = "error";
                    $response["message"] = "You already Liked this event";
                  }
            } else{
                $response["code"] = 500;
                $response["is_liked"] = "false";
                $response["like_count"] = "0";
                $response["status"] = "error";
                $response["message"] = "All Fields are needed";
            }
            return response()
            ->json(array('like_count' => $response["like_count"], 'is_liked' => $response["is_liked"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }
          /***  LIKE EVENT POST ***/

            /***  UNLIKE EVENT POST ***/
            public function unlike_event(){
                $response = array();
                $data = json_decode(file_get_contents("php://input"));

                if(isset($data->eventPostId) && isset($data->userId)){
                      $checkLike = DB::table('event_likes')->where('users_id',$data->userId)->where('event_post_id',$data->eventPostId)->count();
                       if($checkLike > 0){

                            $query = DB::table('event_likes')->where('users_id',$data->userId)->where('event_post_id',$data->eventPostId)->delete();
                            if($query){
                                    $previous_details = DB::table('event_posts')->where('event_post_id',$data->eventPostId)->first();
                                if($previous_details){
                                    $updated_likes = $previous_details->total_likes - 1;
                                        DB::table('event_posts')->where('event_post_id',$data->eventPostId)->update(array('total_likes' => $updated_likes));

                                        $checkLike = DB::table('event_likes')->where('event_post_id', $data->eventPostId)->where('users_id', $data->userId)->count();
                                            if($checkLike == 0){
                                                $response["is_liked"] = "false";
                                            } else{
                                                $response["is_liked"] = "true";
                                            }

                                            $eventDetails = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                                            $response["like_count"] = $eventDetails->total_likes;

                                        $response["code"] = 200;
                                        $response["status"] = "success";
                                        $response["data"] = "This event is unliked successfully";
                                } else{
                                    $response["code"] = 500;
                                    $response["like_count"] = "0";
                                    $response["is_liked"] = "false";
                                    $response["status"] = "error";
                                    $response["message"] = "Event Post not found";
                                }
                            } else{
                              $response["code"] = 500;
                              $response["is_liked"] = "false";
                              $response["like_count"] = "0";
                              $response["status"] = "error";
                              $response["message"] = "Something went wrong";
                            }
                      } else{
                        $response["code"] = 404;
                        $response["is_liked"] = "false";
                        $response["like_count"] = "0";
                        $response["status"] = "error";
                        $response["message"] = "You already unliked this event";
                      }
                } else{
                    $response["code"] = 500;
                    $response["is_liked"] = "false";
                    $response["like_count"] = "0";
                    $response["status"] = "error";
                    $response["message"] = "All Fields are needed";
                }
                return response()
                ->json(array('like_count' => $response["like_count"], 'is_liked' => $response["is_liked"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
             }
            /***  UNLIKE EVENT POST ***/


            /***  CHECK TICKET SALES ***/
            public function ticket_sales(){
                $response = array();
                $data = json_decode(file_get_contents("php://input"));

                if(isset($data->eventPostId) && isset($data->ticket)){
                           if($data->ticket == 'VIP' && !empty($ticketData->ticket_id)){
                               // VIP ticket type case statistics without table services
                                 $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','VIP')->first();
                                $totalSales =DB::table('user_tickets')->where([
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['ticket_id', $ticketData->ticket_id],
                                                                        ['total_ppl_tbl_services', '']
                                                                    ])
                                                            ->whereIn('status',['Active','Refund Requested','Used'])
                                                            ->sum('amount');

                                   $soldQuantity = DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
                                                                                ['total_ppl_tbl_services', '']
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->sum('quantity');

                                   //Check-in info
                               $checkinsQuantity = DB::table('event_checkins')
                                   ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                   ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                   ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                   ->join('event_posts','event_posts.event_post_id', '=', 'event_checkins.event_post_id')
                                   ->where([
                                       ['event_checkins.event_post_id', $data->eventPostId],
                                       ['tickets.ticket_id', $ticketData->ticket_id],
                                       ['event_posts.users_id','!=', 'event_checkins.users_id'],
                                   ])
                                   ->groupBy('event_posts.event_post_id')
                                   ->count();


//                               nabeel panga
//                               $checkinsQuantity = DB::table('user_tickets')
//                                   ->join('event_checkins','event_checkins.ticket_unique_number','user_tickets.ticket_unique_number')
//                                   ->where([
//                                   ['user_tickets.event_post_id', $data->eventPostId],
//                                   ['user_tickets.ticket_id', $ticketData->ticket_id],
//                                   ['user_tickets.total_ppl_tbl_services', '']
//                               ])
//                                   ->whereIn('user_tickets.status',['Active','Refund Requested','Used'])
//                                   ->sum('user_tickets.quantity');
//
//
//                                    $ticketData->total_checkins = (int)$soldQuantity;
//
//
//                               return response()
//                                   ->json(array('status' => "Success",  "total"=>$ticketData, "checkin"=>$checkinsQuantity))
//                                   ->header('Content-Type', 'application/json');


                           }

                           else if($data->ticket == 'Skipping Line' && !empty($ticketData->ticket_id)){

                               // VIP ticket type case statistics without table services
                               $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','Skipping Line')->first();
                               $totalSales =DB::table('user_tickets')->where([
                                   ['event_post_id', $data->eventPostId],
                                   ['ticket_id', $ticketData->ticket_id],
                                   ['total_ppl_tbl_services', '']
                               ])
                                   ->whereIn('status',['Active','Refund Requested','Used'])
                                   ->sum('amount');

                               $soldQuantity = DB::table('user_tickets')->where([
                                   ['event_post_id', $data->eventPostId],
                                   ['ticket_id', $ticketData->ticket_id],
                                   ['total_ppl_tbl_services', '']
                               ])
                                   ->whereIn('status',['Active','Refund Requested','Used'])
                                   ->sum('quantity');

                               $checkinsQuantity = DB::table('event_checkins')
                                   ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                   ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                   ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                   ->join('event_posts','event_posts.event_post_id', '=', 'event_checkins.event_post_id')
                                   ->where([
                                       ['event_checkins.event_post_id', $data->eventPostId],
                                       ['tickets.ticket_id', $ticketData->ticket_id],
                                       ['event_posts.users_id','!=', 'event_checkins.users_id'],
                                   ])
                                   ->groupBy('event_posts.event_post_id')
                                   ->count();

                               $ticketData->total_checkins = (int)$soldQuantity;

                           }

                           else{
                               if($data->ticket == 'Table for 4 People' && !empty($ticketData->ticket_id)){
                                        // 4 People table service statistics case
                                        $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','VIP')->first();
                                        $totalSales =DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
//                                                                                ['tbl_four_ppl_service', 'yes'],
//                                                                                ['tbl_six_ppl_service', 'no'],
//                                                                                ['tbl_eight_ppl_service', 'no'],
//                                                                                ['tbl_ten_ppl_service', 'no'],
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->sum('amount');

                                        $soldQuantity = DB::table('user_tickets')->where([
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['ticket_id', $ticketData->ticket_id],
//                                                                        ['tbl_four_ppl_service', 'yes'],
//                                                                        ['tbl_six_ppl_service', 'no'],
//                                                                        ['tbl_eight_ppl_service', 'no'],
//                                                                        ['tbl_ten_ppl_service', 'no'],
                                                                    ])
                                                            ->whereIn('status',['Active','Refund Requested','Used'])
                                                            ->count();
                                            $soldQuantity = $soldQuantity * 4;

                                         //Check-in info
                                    $checkinsQuantity = DB::table('event_checkins')
                                                                            ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                                                            ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                                                            ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                                                            ->where([
                                                                                        ['event_checkins.event_post_id', $data->eventPostId],
                                                                                        ['tickets.ticket_id', $ticketData->ticket_id],
//                                                                                        ['user_tickets.tbl_four_ppl_service', 'yes'],
//                                                                                        ['user_tickets.tbl_six_ppl_service', 'no'],
//                                                                                        ['user_tickets.tbl_eight_ppl_service', 'no'],
//                                                                                        ['user_tickets.tbl_ten_ppl_service', 'no'],

                                                                                    ])
                                                                            ->count();

                                        $ticketData->total_checkins = ((int)$soldQuantity)/4;

                               }else if($data->ticket == 'Table for 6 People' && !empty($ticketData->ticket_id)){
                                            // 6 People table service statistics case
                                        $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','VIP')->first();
                                        $totalSales = DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
//                                                                                ['tbl_four_ppl_service', 'no'],
//                                                                                ['tbl_six_ppl_service', 'yes'],
//                                                                                ['tbl_eight_ppl_service', 'no'],
//                                                                                ['tbl_ten_ppl_service', 'no'],
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->sum('amount');

                                     $soldQuantity = DB::table('user_tickets')->where([
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['ticket_id', $ticketData->ticket_id],
//                                                                        ['tbl_four_ppl_service', 'no'],
//                                                                        ['tbl_six_ppl_service', 'yes'],
//                                                                        ['tbl_eight_ppl_service', 'no'],
//                                                                        ['tbl_ten_ppl_service', 'no'],
                                                                    ])
                                                            ->whereIn('status',['Active','Refund Requested','Used'])
                                                            ->count();
                                            $soldQuantity = $soldQuantity * 6;
                                           //Check-in info
                                           $checkinsQuantity = DB::table('event_checkins')
                                                                        ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                                                        ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                                                        ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                                                        ->where([
                                                                                    ['event_checkins.event_post_id', $data->eventPostId],
                                                                                    ['tickets.ticket_id', $ticketData->ticket_id],
//                                                                                    ['user_tickets.tbl_four_ppl_service', 'no'],
//                                                                                    ['user_tickets.tbl_six_ppl_service', 'yes'],
//                                                                                    ['user_tickets.tbl_eight_ppl_service', 'no'],
//                                                                                    ['user_tickets.tbl_ten_ppl_service', 'no'],

                                                                                ])
                                                                        ->count();

                                    $ticketData->total_checkins = ((int)$soldQuantity)/6;

                               }else if($data->ticket == 'Table for 8 People' && !empty($ticketData->ticket_id)){
                                        // 8 People table service statistics case
                                        $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','VIP')->first();
                                        $totalSales =DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
//                                                                                ['tbl_four_ppl_service', 'no'],
//                                                                                ['tbl_six_ppl_service', 'no'],
//                                                                                ['tbl_eight_ppl_service', 'yes'],
//                                                                                ['tbl_ten_ppl_service', 'no'],
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->sum('amount');

                                        $soldQuantity = DB::table('user_tickets')->where([
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['ticket_id', $ticketData->ticket_id],
//                                                                        ['tbl_four_ppl_service', 'no'],
//                                                                        ['tbl_six_ppl_service', 'no'],
//                                                                        ['tbl_eight_ppl_service', 'yes'],
//                                                                        ['tbl_ten_ppl_service', 'no'],
                                                                    ])
                                                            ->whereIn('status',['Active','Refund Requested','Used'])
                                                            ->count();
                                            $soldQuantity = $soldQuantity * 8;
                                               //Check-in info
                                   $checkinsQuantity = DB::table('event_checkins')
                                       ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                       ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                       ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                       ->join('event_posts','event_posts.event_post_id', '=', 'event_checkins.event_post_id')
                                       ->where([
                                           ['event_checkins.event_post_id', $data->eventPostId],
                                           ['tickets.ticket_id', $ticketData->ticket_id],
                                           ['event_posts.users_id','!=', 'event_checkins.users_id'],
                                       ])
                                       ->groupBy('event_posts.event_post_id')
                                       ->count();

                                    $ticketData->total_checkins = ((int)$soldQuantity)/8;

                               }else if($data->ticket == 'Table for 10 People' && !empty($ticketData->ticket_id)){
                                        // 10 People table service statistics case
                                        $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket','VIP')->first();
                                        $totalSales =DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
//                                                                                ['tbl_four_ppl_service', 'no'],
//                                                                                ['tbl_six_ppl_service', 'no'],
//                                                                                ['tbl_eight_ppl_service', 'no'],
//                                                                                ['tbl_ten_ppl_service', 'yes'],
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->sum('amount');

                                        $soldQuantity = DB::table('user_tickets')->where([
                                                                                ['event_post_id', $data->eventPostId],
                                                                                ['ticket_id', $ticketData->ticket_id],
//                                                                                ['tbl_four_ppl_service', 'no'],
//                                                                                ['tbl_six_ppl_service', 'no'],
//                                                                                ['tbl_eight_ppl_service', 'no'],
//                                                                                ['tbl_ten_ppl_service', 'yes'],
                                                                            ])
                                                                    ->whereIn('status',['Active','Refund Requested','Used'])
                                                                    ->count();
                                            $soldQuantity = $soldQuantity * 10;
                                              //Check-in info
                                   $checkinsQuantity = DB::table('event_checkins')
                                       ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                       ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                       ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                       ->join('event_posts','event_posts.event_post_id', '=', 'event_checkins.event_post_id')
                                       ->where([
                                           ['event_checkins.event_post_id', $data->eventPostId],
                                           ['tickets.ticket_id', $ticketData->ticket_id],
                                           ['event_posts.users_id','!=', 'event_checkins.users_id'],
                                       ])
                                       ->groupBy('event_posts.event_post_id')
                                       ->count();

                                    $ticketData->total_checkins = ((int)$soldQuantity)/10;

                               }else{
                                   // Regular and Early bird statistics case

                                 $ticketData = DB::table('tickets')->where('event_post_id',$data->eventPostId)->where('ticket',$data->ticket)->first();
                                // echo json_encode($ticketData); exit;

                                   if($ticketData){



                                       $totalSales =DB::table('user_tickets')->where([
                                           ['event_post_id', $data->eventPostId],
                                           ['ticket_id', $ticketData->ticket_id],
                                       ])
                                           ->whereIn('status',['Active','Refund Requested','Used'])
                                           ->sum('amount');

                                       $soldQuantity = DB::table('user_tickets')->where([
                                           ['event_post_id', $data->eventPostId],
                                           ['ticket_id', $ticketData->ticket_id],
                                       ])
                                           ->whereIn('status',['Active','Refund Requested','Used'])
                                           ->sum('quantity');




//                                       //Check-in info
//                                       $checkinsQuantity = DB::table('event_checkins')
//                                           ->select('event_checkins.*',  'user_tickets.*' )
//                                           ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
//                                           ->where([
//                                               ['event_checkins.event_post_id', $data->eventPostId],
//                                               ['user_tickets.ticket_id', $ticketData->ticket_id],
//                                           ])
//                                           ->count();


////                                       changed by nabeel
//                                           $checkinsQuantity = DB::table('event_checkins')
//                                           ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
//                                           ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
//                                           ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
//                                           ->where([
//                                               ['event_checkins.event_post_id', $data->eventPostId],
//                                               ['tickets.ticket_id', $ticketData->ticket_id],
//                                               ['tickets.ticket_id', $ticketData->ticket_id],
////                                                                            ['user_tickets.tbl_four_ppl_service', 'no'],
////                                                                            ['user_tickets.tbl_six_ppl_service', 'no'],
////                                                                            ['user_tickets.tbl_eight_ppl_service', 'no'],
////                                                                            ['user_tickets.tbl_ten_ppl_service', 'yes'],
//
//                                           ])
//                                           ->count();


                                       $checkinsQuantity = DB::table('event_checkins')
                                           ->select('event_checkins.*',  'tickets.*', 'user_tickets.*' )
                                           ->join('tickets','tickets.event_post_id', '=', 'event_checkins.event_post_id')
                                           ->join('user_tickets','user_tickets.ticket_unique_number', '=', 'event_checkins.ticket_unique_number')
                                           ->join('event_posts','event_posts.event_post_id', '=', 'event_checkins.event_post_id')
                                           ->where([
                                               ['event_checkins.event_post_id', $data->eventPostId],
                                               ['tickets.ticket_id', $ticketData->ticket_id],
                                               ['event_posts.users_id','!=', 'event_checkins.users_id'],
                                           ])
                                           ->groupBy('event_posts.event_post_id')
                                           ->count();


                                       $ticketData->total_checkins = (int)$soldQuantity;




                                   } else{
                                        $final_json['ticket_id'] = 0; 
                                        $final_json['event_post_id'] = $data->eventPostId; 
                                        $final_json['ticket'] = $data->ticket; 
                                        $final_json['quantity'] = 0; 
                                        $final_json['total_quantity'] = 0; 
                                        $final_json['price'] = 0; 
                                        $final_json['closing_date'] = date('Y-m-d'); 
                                        $final_json['status'] = "Active";
                                        $final_json['total_checkins'] = 0; 
                                        $final_json['sold_tickets'] = 0; 
                                        $final_json['total_sales'] = 0; 
                                        $final_json['checkins'] = 0; 

                                        $response["code"] = 200;
                                        $response["status"] = "success";
                                        $response["data"] = $final_json;

                                       return response()
                                           ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                                           ->header('Content-Type', 'application/json');
                                   }

                               }

                           }


                                    $ticketData->sold_tickets = (int) $soldQuantity;
                                    $ticketData-> total_sales = $totalSales;
                                    $ticketData->checkins = $checkinsQuantity;



                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $ticketData;


                }




                else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "All Fields are needed";
                }
                return response()
                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
             }
            /***  CHECK TICKET SALES ***/


  /////// COMMENT / REPLY ON EVENT POST //////////
  public function comment_on_event(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventPostId) && isset($data->usersId) && isset($data->comment) && isset($data->commentType)){

        $encodedComment = json_encode($data->comment);

            if($data->commentType == 'comment'){
                $commentData = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->usersId,
                    'comment' => $encodedComment,
                    'comment_type' => $data->commentType,
                    'mentioned_user' => (isset($data->mentionedUserId) ? $data->mentionedUserId : null),
                    'total_likes' => '0',
                    'commented_datetime' => date('Y-m-d H:i:s'),
                    'status' => 'Active',
                );
            }
            if($data->commentType == 'reply'){
                $commentData = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->usersId,
                    'comment' => $encodedComment,
                    'comment_type' => $data->commentType,
                    'replying_to_comment_id' => $data->replyingToCommentId,
                    'mentioned_user' => (isset($data->mentionedUserId) ? $data->mentionedUserId : null),
                    'total_likes' => '0',
                    'commented_datetime' => date('Y-m-d H:i:s'),
                    'status' => 'Active',
                );
            }

            $commentQuery = DB::table('event_comments')->insert($commentData);

            if($commentQuery){
                   //sending push notification

                    if($data->commentType == 'comment'){
                        $eventPostDetails = DB::table('event_posts')->where('event_post_id', $data->eventPostId)->first();
                        $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                        $receiverDetails= DB::table('users')->where([ ['users_id', $eventPostDetails->users_id], ['status', 'Active'] ])->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Comments and replies'], ['users_id', $receiverDetails->users_id ]])->first();
                            if($checkNotificationSetting->status == 'On'){
                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Commented on your event post ', 'postComment');
                                if($notificationResponse){
                                    $notificationData = array(
                                     'sender_users_id' => $senderDetails-> users_id,
                                     'receiver_users_id' => $receiverDetails-> users_id,
                                     'notification_type' => 'PostComment',
                                        'message' => ' Commented on your event post ',
                                        'event_post_id' => $data->eventPostId,
                                        'datetime' => date('Y-m-d H:i:s'),
                                        'status' => 'Unread'

                                    );
                                    $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                }
                            }
                        }

                     } else{
                         $commentDetails = DB::table('event_comments')->where('event_comment_id', $data->replyingToCommentId)->first();
                        $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                        $receiverDetails= DB::table('users')->where([ ['users_id', $commentDetails->users_id], ['status', 'Active'] ])->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Comments and replies'], ['users_id', $receiverDetails->users_id ]])->first();
                            if($checkNotificationSetting->status == 'On'){
                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' replied to your comment ', 'commentReply');
                                    if($notificationResponse){
                                        $notificationData = array(
                                        'sender_users_id' => $senderDetails-> users_id,
                                        'receiver_users_id' => $receiverDetails-> users_id,
                                        'notification_type' => 'CommentReply',
                                            'message'=> ' replied to your comment ',
                                            'event_post_id' => $data->eventPostId,
                                            'datetime' => date('Y-m-d H:i:s'),
                                            'status' => 'Unread'
                                        );
                                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                    }
                               }

                        }
                     }

                 //sending push notification to mentioned if any
                if(isset($data->mentionedUserId)){
                    echo json_encode("here in func"); exit;

                    $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                    $receiverDetails= DB::table('users')->where([ ['users_id', $data->mentionedUserId], ['status', 'Active'] ])->first();

                    if($senderDetails->users_id != $receiverDetails->users_id){
                        $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Mentions'], ['users_id', $receiverDetails->users_id ]])->first();
                        echo json_encode($checkNotificationSetting); exit;
                        if($checkNotificationSetting->status == 'On'){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' mentioned you in a comment ', 'commentMention');
                                if($notificationResponse){
                                    $notificationData = array(
                                        'sender_users_id' => $senderDetails-> users_id,
                                        'receiver_users_id' => $receiverDetails-> users_id,
                                        'notification_type' => 'CommentMention',
                                        'message' => ' mentioned you in a comment ',
                                        'event_post_id' => $data->eventPostId,
                                        'datetime' => date('Y-m-d H:i:s'),
                                        'status' => 'Unread'
                                    );
                                    $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                }
                        }
                    }
                }

                          $response["code"] = 200;
                        $response["status"] = "success";
                            if($data->commentType == 'comment'){ $response["data"] = 'Commented successfully'; }
                            else{ $response["data"] = 'Replied successfully'; }
                }else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Error in query ";
                }
    } else{
        $response["code"] = 500;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }

   /////// GET COMMENT ENGAGED MENTIONS  //////////
   public function get_comment_mentions(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventPostId) && isset($data->usersId)){
          $commentEngagedUsers = DB::table('event_comments')
                                        ->select('event_comments.users_id','users.user_name','users.profile_picture')
                                        ->join('users', 'users.users_id', '=', 'event_comments.users_id')
                                        ->where([ ['event_comments.event_post_id', $data->eventPostId], ['event_comments.users_id','!=', $data->usersId], ['event_comments.status', 'Active'] ])
                                        ->distinct()
                                        ->get();

            if(sizeof($commentEngagedUsers)>0){
                   foreach($commentEngagedUsers as $k){
                        if($k->profile_picture != ""){
                            $k->profile_picture =  config('base_urls.profile_images_base_url').$k->profile_picture;
                        } else{
                            $k->profile_picture =  "";
                        }
                   }
                         $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $commentEngagedUsers;
            }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No user exists";
            }

    } else{
        $response["code"] = 500;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }

/////// GET ALL COMMENTS ON EVENT POST //////////
public function get_all_comments(){
//      gp
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventPostId) && isset($data->usersId) && isset($data->userLat) && isset($data->userLong) ){

            $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
            $user_timezone = $response["timeZoneId"];


//        cmm
        $eventPostComments = DB::table('event_comments')->where([
                                                                    ['event_post_id', $data->eventPostId],
                                                                    ['comment_type', 'comment'],
                                                                    ['status', 'Active']
                                                            ])
                ->orderByRaw('IF(Live_Status = 1, 0,1)')->orderBy('event_comment_id','asc')
                ->get();

//nabeel panga
        // add by nabeel
        $room = DB::table('host_rooms')->where([
                ['event_post_id', $data->eventPostId], ['status', "Active"]
            ])->get();
//        $room = DB::table('host_rooms')->where('event_post_id', $data->eventPostId)->get();


        foreach ($room as $item) {

            $event_post = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where([ ['event_posts.event_post_id', $item->event_post_id], ['event_posts.status', 'Active'] ])->first();

            $event_timezone = $event_post->timezone;

//            Find differenec between event and user timestamp
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
            $eventSaleStartDateTime = $item->live_end_date . " " . $item->live_end_time;
            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
            $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;

//            $currentDate = date('Y-m-d H:i:s');
//            $currentTimeSpam = strtotime($currentDate);
//            $eventSaleStartDateTime = $item->live_end_date . " " . $item->live_end_time;
//            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);


            if ($currentTimeSpam > $eventSaleStartDateTimeSpam) {
//                return response($item);

//                $room = DB::table('host_rooms')->where('host_room_id', $data->eventPostId)->first();
                $commentQuery = DB::table('event_comments')->where([["Live_Status", 1], ["event_post_id", $item->event_post_id], ['status', 'Active']]);

                $commentQuery->delete();

            } else {

            }
        }


        $eventPostComments = DB::table('event_comments')->where([
            ['event_post_id', $data->eventPostId],
            ['comment_type', 'comment'],
            ['status', 'Active']
        ])
            ->orderByRaw('IF(Live_Status = 1, 0,1)')->orderBy('event_comment_id','asc')
            ->get();


        if(sizeof($eventPostComments) > 0){

                foreach($eventPostComments as $comment){

                    //user profile and username for each comment
                $commentUserDetails = DB::table('users')->where([ ['users_id', $comment->users_id], ['status', 'Active'] ])->first();
                        if($commentUserDetails->profile_picture != "" || $commentUserDetails->profile_picture != null){
                            $comment->comment_user_profile =  config('base_urls.profile_images_base_url').$commentUserDetails->profile_picture;
                        } else{
                            $comment->comment_user_profile =  "";
                        }
                    $comment->comment_user_name = $commentUserDetails->user_name;

                      //appending liked comment hint
                      $checkLike = DB::table('event_comment_likes')->where([ ['event_comment_id', $comment->event_comment_id], ['users_id', $data->usersId] ])->count();
                      if($checkLike > 0){
                          $comment->comment_liked = 'true';
                      } else{
                          $comment->comment_liked = 'false';
                      }

                      //decoding json for emoji
                      $comment->comment = json_decode($comment->comment);

                    //Comment time conversion for minutes/hours/days ago
//                    jaga 1
                    $comment->comment_time_ago = Helper::time_elapsed_string($comment->commented_datetime);

                        //Total Replies count in every comment
                    $totalReplies = DB::table('event_comments')->where([
                                                                        ['replying_to_comment_id', $comment->event_comment_id],
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['comment_type', 'reply'],
                                                                        ['status', 'Active']
                                                                    ])->count();
                        $comment->total_replies_count = $totalReplies;

                        //Mentioned User details in each comment
                        if($comment->mentioned_user != null){
                            $mentionedUserDetails = DB::table('users')-> where([ ['users_id', $comment->mentioned_user], ['status', 'Active'] ])->first();
                            $comment->mentioned_user_id = $comment->mentioned_user;
                            $comment->mentioned_user_name = $mentionedUserDetails->user_name;
                        } else{
                            $comment->mentioned_user_id = 0;
                            $comment->mentioned_user_name = '';
                        }

                    // COMMENT REPLIES //
                    //Comment Replies array in each comment object
                $commentReplies = DB::table('event_comments')->where([
                                                                    ['replying_to_comment_id', $comment->event_comment_id],
                                                                    ['event_post_id', $data->eventPostId],
                                                                    ['comment_type', 'reply'],
                                                                    ['status', 'Active']
                                                                ])->get();
                        //user profile and username for each Reply in every comment
                            foreach($commentReplies as $reply){

                                 //decoding json for emoji
                                 $reply->comment = json_decode($reply->comment);


                                $replyUserDetails = DB::table('users')->where([ ['users_id', $reply->users_id], ['status', 'Active'] ])->first();

                                if($replyUserDetails->profile_picture != "" || $replyUserDetails->profile_picture != null){
                                    $reply->reply_user_profile =  config('base_urls.profile_images_base_url').$replyUserDetails->profile_picture;
                                } else{
                                    $reply->reply_user_profile =  "";
                                }

                                $reply->reply_user_name = $replyUserDetails->user_name;


                                 //appending liked reply hint
                                $checkLike = DB::table('event_comment_likes')->where([ ['event_comment_id', $reply->event_comment_id], ['users_id', $data->usersId] ])->count();
                                    if($checkLike > 0){
                                        $reply->reply_liked = 'true';
                                    } else{
                                        $reply->reply_liked = 'false';
                                    }

                                //Comment time conversion for minutes/hours/days ago
                            $reply->reply_time_ago = Helper::time_elapsed_string($reply->commented_datetime);

                                        //Mentioned User details in each reply
                                    if($reply->mentioned_user != null){
                                        $mentionedUserDetails = DB::table('users')-> where([ ['users_id', $reply->mentioned_user], ['status', 'Active'] ])->first();
                                        $reply->mentioned_user_id = $reply->mentioned_user;
                                        $reply->mentioned_user_name = $mentionedUserDetails->user_name;
                                    } else{
                                        $reply->mentioned_user_id = 0;
                                        $reply->mentioned_user_name = '';
                                    }

                            }

                $comment->comment_replies = $commentReplies;
            }

            //Total comments count on Event Post
            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $data->eventPostId], ['comment_type', 'comment'], ['status', 'Active'] ])->count();

              //                    Add by Nabeel
//              $host_desc = DB::table('host_rooms')->where([
//                  ['event_post_id', $data->eventPostId],
//                  ['status', 'Active']
//              ])->pluck("description");

//              $description = "";
//              if(isset($host_desc[0])){
//                  $description = $host_desc[0];
//              }else{
//                  $description = "0";
//              }


            $response["code"] = 200;
            $response["status"] = "success";
              $response["total_post_comments"] = $totalPostComments;
            $response["comments"] = $eventPostComments;

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["total_post_comments"] = '0';
            $response["message"] = "No comments Exists";
          }




    } else{
        $response["code"] = 500;
        $response["status"] = "error";
        $response["total_post_comments"] = '0';
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('total_post_comments' => $response["total_post_comments"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'comments' => isset($response["message"]) ? $response["message"] : $response["comments"]))
    ->header('Content-Type', 'application/json');
 }


/////// GET ALL COMMENTS ON EVENT POST //////////
public function get_comment_replies(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventCommentId) && isset($data->usersId) && isset($data->eventPostId)){
            $eventCommentReplies = DB::table('event_comments')->where([
                                                                    ['event_post_id', $data->eventPostId],
                                                                    ['comment_type', 'reply'],
                                                                    ['replying_to_comment_id', $data->eventCommentId],
                                                                    ['status', 'Active']
                                                            ])->get();

          if(sizeof($eventCommentReplies) > 0){
                foreach($eventCommentReplies as $reply){

                                //decoding json for emoji
                                $reply->comment = json_decode($reply->comment);


                                $replyUserDetails = DB::table('users')->where([ ['users_id', $reply->users_id], ['status', 'Active'] ])->first();

                                if($replyUserDetails->profile_picture != "" || $replyUserDetails->profile_picture != null){
                                    $reply->reply_user_profile =  config('base_urls.profile_images_base_url').$replyUserDetails->profile_picture;
                                } else{
                                    $reply->reply_user_profile =  "";
                                }

                                $reply->reply_user_name = $replyUserDetails->user_name;


                                 //appending liked reply hint
                                $checkLike = DB::table('event_comment_likes')->where([ ['event_comment_id', $reply->event_comment_id], ['users_id', $data->usersId] ])->count();
                                    if($checkLike > 0){
                                        $reply->reply_liked = 'true';
                                    } else{
                                        $reply->reply_liked = 'false';
                                    }

                                //Comment time conversion for minutes/hours/days ago
                             $reply->reply_time_ago = Helper::time_elapsed_string($reply->commented_datetime);

                                        //Mentioned User details in each reply
                                    if($reply->mentioned_user != null){
                                        $mentionedUserDetails = DB::table('users')-> where([ ['users_id', $reply->mentioned_user], ['status', 'Active'] ])->first();
                                        $reply->mentioned_user_id = $reply->mentioned_user;
                                        $reply->mentioned_user_name = $mentionedUserDetails->user_name;
                                    } else{
                                        $reply->mentioned_user_id = 0;
                                        $reply->mentioned_user_name = '';
                                    }
            }

            //Total comments count on Event Post
            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $data->eventPostId], ['comment_type', 'comment'], ['status', 'Active'] ])->count();

            $totalCommentRepliesQuery = DB::table('event_comments')->where([
                                                                        ['replying_to_comment_id', $data->eventCommentId],
                                                                        ['event_post_id', $data->eventPostId],
                                                                        ['comment_type', 'reply'],
                                                                        ['status', 'Active']
                                                                    ])->get();

                        if(sizeof($totalCommentRepliesQuery) > 0){
                            $totalCommentReplies = sizeof($totalCommentRepliesQuery);
                        }else{
                            $totalCommentReplies = 0;
                        }
            $response["code"] = 200;
            $response["status"] = "success";
            $response["total_post_comments"] = $totalPostComments;
            $response["total_comment_replies"] = $totalCommentReplies;
            $response["comments"] = $eventCommentReplies;

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["total_post_comments"] = 0;
            $response["total_comment_replies"] = 0;
            $response["message"] = "No replies exists on this comment";
          }
    } else{
        $response["code"] = 500;
        $response["status"] = "error";
        $response["total_post_comments"] = 0;
        $response["total_comment_replies"] = 0;
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('total_post_comments' => $response["total_post_comments"], 'total_comment_replies' => $response["total_comment_replies"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'comments' => isset($response["message"]) ? $response["message"] : $response["comments"]))
    ->header('Content-Type', 'application/json');
 }


   /////// DELETE COMMENT //////////
   public function delete_comment(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventCommentId) && isset($data->usersId) ){

        //Queries to get details for event owner
        $eventIdQuery = DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->first();
        $eventpostId = $eventIdQuery->event_post_id;
        $eventDetailsQuery = DB::table('event_posts')->where('event_post_id',$eventpostId)->first();

            //Event owner-- Can delete any comment or reply
        if($data->usersId == $eventDetailsQuery->users_id){
            $commentDetails = DB::table('event_comments')->where([ ['event_comment_id', $data->eventCommentId], ['status', 'Active'] ])->first();
            $commentReplies = DB::table('event_comments')->where([ ['replying_to_comment_id', $data->eventCommentId], ['status', 'Active'] ])->get();

                   DB::table('event_comments')->where([ ['event_comment_id', $data->eventCommentId] ])->update(array('status' => 'Deleted'));
                   if(sizeof($commentReplies) > 0){
                       foreach($commentReplies as $k){
                           DB::table('event_comments')->where([ ['replying_to_comment_id', $data->eventCommentId], ['status', 'Active'] ])->update(array('status' => 'Deleted'));
                       }
                   }
        }
        else{
            //Case in which user is not event owner so he can delete his own only
            $commentDetails = DB::table('event_comments')->where([ ['event_comment_id', $data->eventCommentId], ['users_id', $data->usersId], ['status', 'Active'] ])->first();
            $commentReplies = DB::table('event_comments')->where([ ['replying_to_comment_id', $data->eventCommentId], ['status', 'Active'] ])->get();

                   DB::table('event_comments')->where([ ['event_comment_id', $data->eventCommentId] ])->update(array('status' => 'Deleted'));
                   if(sizeof($commentReplies) > 0){
                       foreach($commentReplies as $k){
                           DB::table('event_comments')->where([ ['replying_to_comment_id', $data->eventCommentId], ['status', 'Active'] ])->update(array('status' => 'Deleted'));
                       }
                   }
        }
                          $response["code"] = 200;
                        $response["status"] = "success";
                            if($commentDetails->comment_type == 'comment'){ $response["data"] = 'Comment Deleted successfully'; }
                            else{ $response["data"] = 'Reply Deleted successfully'; }

    } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }

    /////// LIKE COMMENT //////////
    public function like_comment(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventCommentId) && isset($data->eventPostId) && isset($data->usersId) ){
              $commentLikeData = array(
                  'event_comment_id' => $data->eventCommentId,
                  'event_post_id' => $data->eventPostId,
                  'users_id' => $data->usersId,
                  'date_time' => date('Y-m-d H:i:s')
              );

             $insertCommentLikeQuery = DB::table('event_comment_likes')->insert($commentLikeData);
                if($insertCommentLikeQuery){
                                //sending push notification
                                $commentOrReplyDetails = DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->first();
                                $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                                $receiverDetails= DB::table('users')->where([ ['users_id', $commentOrReplyDetails->users_id], ['status', 'Active'] ])->first();
                         if($senderDetails->users_id != $receiverDetails->users_id){
                                if($commentOrReplyDetails->comment_type == 'comment'){
                                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Liked your comment ', 'commentLike');
                                    if($notificationResponse){
                                        $notificationData = array(
                                            'sender_users_id' => $senderDetails-> users_id,
                                            'receiver_users_id' => $receiverDetails-> users_id,
                                            'notification_type' => 'CommentLike',
                                            'message' => ' Liked your comment ',
                                            'event_post_id' => $data->eventPostId,
                                            'comment_id' => $data->eventCommentId,
                                            'datetime' => date('Y-m-d H:i:s'),
                                            'status' => 'Unread'
                                        );
                                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                    }
                                } else{
                                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Liked your reply ', 'replyLike');
                                    if($notificationResponse){
                                        //comment details which is replied
                                        $commentDetails = DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)-> first();

                                        $notificationData = array(
                                            'sender_users_id' => $senderDetails-> users_id,
                                            'receiver_users_id' => $receiverDetails-> users_id,
                                            'notification_type' => 'ReplyLike',
                                            'message' => ' Liked your reply ',
                                            'event_post_id' => $data->eventPostId,
                                            'comment_id' => $commentDetails->replying_to_comment_id,
                                            'datetime' => date('Y-m-d H:i:s'),
                                            'status' => 'Unread'
                                        );
                                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                    }
                                }
                         }

                       $commentDetails = DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->first();
                       $previousLikedCount = $commentDetails->total_likes;
                       $updatedLikesCount = $previousLikedCount + 1;

                       DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->update(array('total_likes' => $updatedLikesCount));

                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = 'Liked successfully';
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Error in liking comment";
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


         /////// UNLIKE COMMENT //////////
    public function unlike_comment(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventCommentId) && isset($data->eventPostId) && isset($data->usersId) ){


             $checkCommentLike = DB::table('event_comment_likes')->where([ ['event_comment_id',$data->eventCommentId], ['event_post_id', $data->eventPostId], ['users_id', $data->usersId] ])->first();
                if($checkCommentLike){
                        DB::table('event_comment_likes')->where([ ['event_comment_id',$data->eventCommentId], ['event_post_id', $data->eventPostId], ['users_id', $data->usersId] ])->delete();

                        $commentDetails = DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->first();
                        $previousLikedCount = $commentDetails->total_likes;
                        $updatedLikesCount = $previousLikedCount - 1;

                        DB::table('event_comments')->where('event_comment_id', $data->eventCommentId)->update(array('total_likes' => $updatedLikesCount));

                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = 'Unliked successfully';
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Like not found";
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

 /////// REPORT COMMENT //////////
 public function report_comment(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventCommentId) && isset($data->usersId) && isset($data->reportCategories)){
         $checkReport = DB::table('event_comment_reports')->where([ ['event_comment_id', $data->eventCommentId], ['users_id', $data->usersId] ])->count();
         if($checkReport == '0'){
                    $commentReportData = array(
                        'event_comment_id' => $data->eventCommentId,
                        'users_id' => $data->usersId,
                        'date_time' => date("Y-m-d H:i:s"),
                        'status' => 'Active',
                    );

                $reportInsertedId = DB::table('event_comment_reports')->insertGetId($commentReportData);
                    if($reportInsertedId){
                        //Inserting report categories in child table
                        foreach($data->reportCategories as $k){
                            $reportCategoryData = array(
                                'comment_report_id' => $reportInsertedId,
                                'report_category' => $k,
                                'status' =>'Active'
                            );
                            DB::table('event_comment_report_categories')->insert($reportCategoryData);
                        }

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = 'Reported successfully';
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "Something wrong in insert query";
                    }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Already reported";
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

    /////// REPORT Peek //////////
    public function report_peek(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventId) && isset($data->usersId) && isset($data->reportCategories) && isset($data->peekId)){
            $checkReport = DB::table('peek_reports')->where([ ['event_post_id', $data->eventId], ['users_id', $data->usersId] , ['peek_id', $data->peekId] ])->count();
            if($checkReport == '0'){
                $commentReportData = array(
                    'event_post_id' => $data->eventId,
                    'users_id' => $data->usersId,
                    'peek_id' => $data->peekId,
                    'datetime' => date("Y-m-d H:i:s"),
                    'status' => 'Active',
                );

                $reportInsertedId = DB::table('peek_reports')->insertGetId($commentReportData);
                if($reportInsertedId){
                    //Inserting report categories in child table
                    foreach($data->reportCategories as $k){
                        $reportCategoryData = array(
                            'peek_report_id' => $reportInsertedId,
                            'peek_report_category' => $k,
                            'status' =>'Active'
                        );
                        DB::table('peek_reports_category')->insert($reportCategoryData);
                    }

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Reported successfully';
                } else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Something wrong in insert query";
                }
            } else{
                $response["code"] = 500;
                $response["status"] = "error";
                $response["message"] = "Already reported";
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


 /////// TICKET HISTORY PREVIOUS //////////
 public function ticket_history(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->historyType)){
    $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');

      if($data->historyType == 'previous'){
                $ticketHistory =  DB::table('user_tickets')
                    ->select('user_tickets.*', 'event_posts.users_id as event_organizer_id','users.first_name','users.last_name', 'users.users_id', 'users.user_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 'event_posts.event_start_date', 'event_posts.event_start_time', 'tickets.ticket')
                                                ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                    ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                    ->join('users', 'users.users_id','=','user_tickets.users_id')

                    ->where('event_posts.users_id', $data->usersId)
                                                ->where(function($query) {
                                                    if($this->event_type_filter != '')
                                                    {
                                                        $query->where('event_posts.event_type_id',$this->event_type_filter);
                                                    }
                                                })
                                                ->whereDate('event_end_date','>=', date('Y-m-d') )
                                                ->get();
      }

      if($data->historyType == 'upcomming'){
                $ticketHistory =  DB::table('user_tickets')
                    ->select('user_tickets.*', 'event_posts.users_id as event_organizer_id','users.first_name','users.last_name', 'users.users_id', 'users.user_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 'event_posts.event_start_date', 'event_posts.event_start_time', 'tickets.ticket')
                                        ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                    ->join('users', 'users.users_id','=','user_tickets.users_id')
                    ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')

                    ->where('event_posts.users_id', $data->usersId)
                                        ->where(function($query) {
                                            if($this->event_type_filter != '')
                                            {
                                                $query->where('event_posts.event_type_id',$this->event_type_filter);
                                            }
                                        })
                                        ->whereDate('event_start_date','>', date('Y-m-d') )
                                        ->get();
      }

         if(sizeof($ticketHistory) > 0){
                    foreach($ticketHistory as $k){



                        //                         panga ticket details
                        $ticket_token =  DB::table('user_tickets')
                            ->select('user_tickets.*','ticket_token.*', 'users.users_id', 'users.user_name','users.first_name','users.last_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title',
                            DB::raw('DATE_FORMAT(event_posts.event_start_date, "%d-%b-%Y") as event_start_date'),
                             'event_posts.event_start_time', 'tickets.ticket')
                            ->join('users', 'users.users_id','=','user_tickets.users_id')
                            ->join('ticket_token', 'ticket_token.tt_user_ticket_id','=','user_tickets.user_ticket_id')
                            ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                            ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                            ->where('event_posts.users_id', $data->usersId)
                            ->where('user_tickets.user_ticket_id', $k->user_ticket_id)
                            ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                            ->get();

                        foreach($ticket_token as $l) {

                            if($l->ticket_unique_number == null){
                                $l->is_checked_in = false;
                            }else {
                                $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $l->tt_token)->count();
                                if ($checkAlreadyCheckedIn == 0) {
                                    $l->is_checked_in = false;
                                } else {
                                    $l->is_checked_in = true;
                                }
                            }
                        }
                        $k->ticket_details = $ticket_token;
//                         panga ticket details












                        $eventDetails = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();
                        if($eventDetails){
                            $k->event_title = $eventDetails->title;
                        }
                        $k->purchase_date = date('m/d/Y', strtotime($k->transaction_date));
                        $k->purchase_time = date('H:i A', strtotime($k->transaction_date));
                    $eventLocationDetails = DB::table('addresses')->where('event_post_id', $k->event_post_id)->first();
                    $k->location_long = $eventLocationDetails->location_long;
                    $k->location_lat = $eventLocationDetails->location_lat;



//                    nabeel sub

                        $k->purchase_date = date('m/d/Y', strtotime($k->transaction_date));
                        $k->purchase_time = date('H:i A', strtotime($k->transaction_date));

                        if($k->ticket_unique_number == null){
                            $k->is_checked_in = false;
                        }else {
                            $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $k->ticket_unique_number)->count();
                            if ($checkAlreadyCheckedIn == 0) {
                                $k->is_checked_in = false;
                            } else {
                                $k->is_checked_in = true;
                            }
                        }




//                    nabeel sub










                }
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $ticketHistory;
           } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No tickets found in event history";
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



 /////// EVENT GUESTS LIST //////////
 public function event_guests_list(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
    $this->search_filter = $search_filter = (isset($data->searchFilter)? $data->searchFilter: '');

                $eventGuestsList =  DB::table('user_tickets')
                                        ->select('user_tickets.*', 'users.users_id', 'users.user_name','users.first_name','users.last_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 'event_posts.event_start_date', 'event_posts.event_start_time', 'tickets.ticket')
                                        ->join('users', 'users.users_id','=','user_tickets.users_id')
//                                        ->join('ticket_token', 'ticket_token.tt_user_ticket_id','=','user_tickets.user_ticket_id')
                                        ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                                        ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                                        ->where(function($query) {
                                            if($this->search_filter != '')
                                            {
                                                $query->where('users.user_name','like', '%' . trim($this->search_filter) . '%')->orWhere('user_tickets.ticket_unique_number','like', '%' . trim($this->search_filter) . '%');
                                            }
                                        })
                                        ->where('event_posts.users_id', $data->usersId)
                                        ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                        ->get();





         if(sizeof($eventGuestsList) > 0){
                    foreach($eventGuestsList as $k){

//                         panga ticket details
                        $ticket_token =  DB::table('user_tickets')
                            ->select('user_tickets.*','ticket_token.*', 'users.users_id', 'users.user_name','users.first_name','users.last_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 'event_posts.event_start_date', 'event_posts.event_start_time', 'tickets.ticket')
                            ->join('users', 'users.users_id','=','user_tickets.users_id')
                                        ->join('ticket_token', 'ticket_token.tt_user_ticket_id','=','user_tickets.user_ticket_id')
                            ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                            ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                            ->where(function($query) {
                                if($this->search_filter != '')
                                {
                                    $query->where('users.user_name','like', '%' . trim($this->search_filter) . '%')->orWhere('user_tickets.ticket_unique_number','like', '%' . trim($this->search_filter) . '%');
                                }
                            })
                            ->where('event_posts.users_id', $data->usersId)
                            ->where('user_tickets.user_ticket_id', $k->user_ticket_id)
                            ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                            ->get();

                        foreach($ticket_token as $l) {

                            if($l->ticket_unique_number == null){
                                $l->is_checked_in = false;
                            }else {
                                $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $l->tt_token)->count();
                                if ($checkAlreadyCheckedIn == 0) {
                                    $l->is_checked_in = false;
                                } else {
                                    $l->is_checked_in = true;
                                }
                            }
                        }
                            $k->ticket_details = $ticket_token;
//                         panga ticket details

                        if($k->ticket_unique_number == null){
                            $k->is_checked_in = false;
                        }else {
                            $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $k->ticket_unique_number)->count();
                            if ($checkAlreadyCheckedIn == 0) {
                                $k->is_checked_in = false;
                            } else {
                                $k->is_checked_in = true;
                            }
                        }






                        $eventDetails = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();


                        if($k->ticket_unique_number == null){
                            $k->is_checked_in = false;
                        }else {
                            $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $k->ticket_unique_number)->count();
                            if ($checkAlreadyCheckedIn == 0) {
                                $k->is_checked_in = false;
                            } else {
                                $k->is_checked_in = true;
                            }
                        }


                        if($eventDetails){
                            $k->event_title = $eventDetails->title;
                        }
                        $k->purchase_date = date('m/d/Y', strtotime($k->transaction_date));
                        $k->purchase_time = date('H:i A', strtotime($k->transaction_date));
                    $eventLocationDetails = DB::table('addresses')->where('event_post_id', $k->event_post_id)->first();
                    $k->location_long = $eventLocationDetails->location_long;
                    $k->location_lat = $eventLocationDetails->location_lat;
                }
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $eventGuestsList;
           } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No guests found of this search";
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


 /////// CHECKIN EVENT //////////
 public function checkin(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->ticketUniqueNumber) && isset($data->usersId)){
//            $ticketDetails = DB::table('user_tickets')->where('ticket_unique_number', $data->ticketUniqueNumber)->first();
            $ticketDetails = DB::table('user_tickets')->join('ticket_token', 'ticket_token.tt_user_ticket_id','=','user_tickets.user_ticket_id')
                ->where('ticket_token.tt_token', $data->ticketUniqueNumber)->first();


            if($ticketDetails){
                $eventDetails = DB::table('event_posts')->where('event_post_id', $ticketDetails->event_post_id)->first();
                  if($eventDetails->users_id == $data->usersId){
                    $currentDate = date('Y-m-d');



                    $eventDetails = DB::table('event_posts')->where('event_post_id', $ticketDetails->event_post_id)
                                                           ->whereDate('event_start_date', '<=', $currentDate)
                                                           ->whereDate('event_end_date', '>=', $currentDate)
                                                           ->first();
                     if($eventDetails){
                        $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number' , $data->ticketUniqueNumber)->count();
                        if($checkAlreadyCheckedIn == 0){
                           $checkinData = array(
                               'event_post_id' => $ticketDetails->event_post_id,
                               'users_id' => $ticketDetails->users_id,
                               'ticket_unique_number' => $data->ticketUniqueNumber,
                               'date_time' => date('Y-m-d H:i:s')
                           );
                           $checkinInsertQuery = DB::table('event_checkins')->insert($checkinData);
                           if($checkinInsertQuery){
                               //updating User tickets status
                               $updateTicketStatus = DB::table('user_tickets')->where('transaction_id', $ticketDetails->transaction_id)->update(['status' => 'Used']);


                               $response["code"] = 200;
                               $response["status"] = "success";
                               $response["data"] = $ticketDetails;
                           }
                        } else{
                           $response["code"] = 404;
                           $response["status"] = "error";
                           $response["message"] = "This ticket is already checked in";
                        }
                      }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "Ticket can only checkedin during event";
                        }
                  }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Ticket not available for this event";
                }

            }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Ticket not found";
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
    function cb($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    }

            /*** UPLOAD ATTACHMENT API ***/
    public function upload_attachment(Request $req){
                $response = array();
                $postfix = 'connevents_attachment';
                $upd_prefix = date('d-m-y');
                $random = rand(1,1000000);

                $file_name = $upd_prefix.$random.$postfix.'.jpeg';
                $attachment_path_url= config('base_urls.relative_path_chat_attachments');

                $uploadedAttachment = $req->file('attachment')->move($attachment_path_url,$file_name);
                if($uploadedAttachment){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $file_name;

                } else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Uploading failed";
                }

                return response()
                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
            }



                            /*** CHAT ***/
                            public function chat(){
                                $response = array();
                                $data = json_decode(file_get_contents("php://input"));

                                if (isset($data->requestType)) {
                                    $request_type = $data->requestType;
                                                switch ($request_type) {
                                                    case "delete_Message":
                                                        if(isset($data->sender_id) && isset($data->receiver_id)){
                                                            DB::table('chat_list')->where([ ['receiver_id', $data->sender_id], ['sender_id', $data->receiver_id] ])->delete();
                                                            DB::table('chat_messages')->where([ ['receiver_id', $data->sender_id], ['sender_id', $data->receiver_id] ])->delete();
                                                            
                                                            DB::table('chat_list')->where([ ['sender_id', $data->sender_id], ['receiver_id', $data->receiver_id] ])->delete();
                                                            DB::table('chat_messages')->where([ ['sender_id', $data->sender_id], ['receiver_id', $data->receiver_id] ])->delete();

                                                            $response["code"] = 200;
                                                            $response["status"] = "success";
                                                            $response["message"] = "Chat Deleted";
                                                        } else {
                                                            $response["code"] = 404;
                                                            $response["status"] = "error";
                                                            $response["message"] = "All fields are needed";
                                                        }
                                                    break;

                                                    case "startChat":
                                                        if(isset($data->userId) && isset($data->otherUserId)){
                                                                $check_request = DB::table('chat_list')->where([
                                                                                                                ['sender_id',$data->userId],
                                                                                                                ['receiver_id',$data->otherUserId],
                                                                                                            ])->orWhere([
                                                                                                                ['sender_id',$data->otherUserId],
                                                                                                                ['receiver_id',$data->userId],
                                                                                                            ])->count();
                                                                 if($check_request > 0){
                                                                        $response['status'] = 'AlreadySent';
                                                                      }
                                                                      else{
                                                                            $data_save = array(
                                                                                'sender_id'=> $data->userId,
                                                                                'receiver_id'=> $data->otherUserId,
                                                                                'date_request'=> date('Y-m-d'),
                                                                                'status'=> 'Active'
                                                                            );
                                                                            $requestSend = DB::table('chat_list')->insert($data_save);

                                                                            if($requestSend){
                                                                                $chat_status = 'RequestSend';
                                                                            } else {
                                                                                $chat_status = 'RequestNotSend';
                                                                            }
                                                                        }
                                                                             $other_us_details = DB::table('users')->where('users_id',$data->otherUserId)->first();
                                                                            if($other_us_details){
                                                                                if($other_us_details->profile_picture != null || $other_us_details->profile_picture != '' ){
                                                                                    $profileImage = config('base_urls.profile_images_base_url').$other_us_details->profile_picture;
                                                                                } else{
                                                                                    $profileImage = '';
                                                                                }

                                                                                $response["code"] = 200;
                                                                                $response["status"] = "success";
                                                                                $response["data"] = array(
                                                                                        "user_name" => $other_us_details->user_name,
                                                                                        "profile_picture" => $profileImage
                                                                                );
                                                                             }
                                                                                else{
                                                                                    $response["code"] = 404;
                                                                                    $response["status"] = "error";
                                                                                    $response["message"] = "Details not found";
                                                                                }
                                                                    } else{
                                                                        $response["code"] = 404;
                                                                        $response["status"] = "error";
                                                                        $response["message"] = "All fields are needed";
                                                                        }
                                                                break;

                                                                      case "sendMessage":
                                                                        $dt = Carbon::now();
                                                                       $time= $dt->toTimeString();

                                                                                $message_details = array(
                                                                                    'sender_id'=> $data->userId,
                                                                                    'receiver_id'=> $data->otherUserId,
                                                                                    'message'=> json_encode($data->content),
                                                                                    'message_type'=> $data->messageType,
                                                                                    'send_date'=> date('Y-m-d H:m:s'),
                                                                                    'send_time'=>$time,
                                                                                    'status'=> 'Unread'
                                                                                );

                                                                                        $result = DB::table('chat_messages')->insertGetId($message_details);
                                                                                        if($result){
                                                                                        // Add Notification /
                                                                                            if($data->messageType == "text"){
                                                                                                $notification = 'New Message Received';
                                                                                            }

                                                                                            else{
                                                                                                $notification = 'New attachment Received';
                                                                                            }

                                                                                           $senderDetails =  DB::table('users')->where('users_id', $data->userId)->first();
                                                                                           $receiverDetails =  DB::table('users')->where('users_id', $data->otherUserId)->first();

                                                                                        if($result){

                                                                                            $message = DB::table('chat_messages')->where('chat_message_id',$result)->first();
                                                                                                  $message->message = json_decode($message->message);

                                                                                                if($message->message_type == 'attachment'){
                                                                                                    $message->message = config('base_urls.chat_attachments_base_url').$message->message;
                                                                                                }

                                                                                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, 'New message received from '.$senderDetails->user_name, 'newMessage');
                                                                                                if($notificationResponse){
                                                                                                    $notificationData = array(
                                                                                                        'sender_users_id' => $senderDetails-> users_id,
                                                                                                        'receiver_users_id' => $receiverDetails-> users_id,
                                                                                                        'notification_type' => 'New Message',
                                                                                                        'message' => ' New message received from '.$senderDetails->user_name,
                                                                                                        'datetime' => date('Y-m-d H:i:s'),
                                                                                                        'status' => 'Unread'
                                                                                                    );
                                                                                                    $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                                                                                }

                                                                                                $response["code"] = 200;
                                                                                                $response["status"] = "success";
                                                                                                $response["data"] = $message;
                                                                                            }

                                                                                            else{
                                                                                                $response["code"] = 500;
                                                                                                $response["status"] = "error";
                                                                                                $response["message"] = "Something went wrong in update query";
                                                                                             }
                                                                                } else{
                                                                                    $response["code"] = 500;
                                                                                    $response["status"] = "error";
                                                                                    $response["message"] = "Something went wrong in Insert query";
                                                                                }

                                                                        break;

                                                                        case "getChatList":

                                                                            if(isset($data->userId)){
                                                                                $user_id = $data->userId;
                                                                                $check_list = DB::table('chat_list')->where('receiver_id',$user_id)->orWhere('sender_id', $user_id)->orderBy('chat_list_id','DESC')->get();

                                                                                if(sizeof($check_list) >= 1){
                                                                                    $count_chat_list = 0;
                                                                                    $other_users_id_array = array();

                                                                                    foreach($check_list as $fetch_chat_list){
                                                                                        $sender_id = $fetch_chat_list->sender_id;
                                                                                        $receiver_id = $fetch_chat_list->receiver_id;


                                                                                        if($sender_id == $user_id){
                                                                                            $other_user_details = DB::table('users')->where('users_id',$receiver_id)->first();
                                                                                        }

                                                                                        else if($receiver_id == $user_id){
                                                                                            $other_user_details = DB::table('users')->where('users_id',$sender_id)->first();
                                                                                        }

                                                                                           if($other_user_details){
                                                                                            $other_user_id_chat_list = $other_user_details->users_id;
                                                                                            array_push($other_users_id_array, $other_user_id_chat_list);
                                                                                           }
                                                                                    }

                                                                                    $user_list = DB::table('users')->whereIn('users_id',$other_users_id_array)->get();
                                                                                    $chat_list_array =array();
                                                                                    $profile_pic_url = config('base_urls.relative_path_profile_images');
                                                                                    $response['user_list']  = $user_list;

                                                                                    foreach($user_list as $list){
                                                                                                $other_notes = DB::table('chat_messages')->where([
                                                                                                                                            ['sender_id',$list->users_id],
                                                                                                                                            ['receiver_id',$user_id],
                                                                                                                                        ])->orWhere([
                                                                                                                                            ['receiver_id',$list->users_id],
                                                                                                                                            ['sender_id',$user_id],
                                                                                                                                        ])->orderBy('chat_message_id','DESC')->get();

                                                                                        $chat_list_data['badge']   = DB::table('chat_messages')->where([
                                                                                                                                                        ['sender_id', $list->users_id],
                                                                                                                                                        ['receiver_id', $user_id],
                                                                                                                                                        ['status','Unread']
                                                                                                                                                        ])->count();

                                                                                        $chat_list_data['time']   = ' ';
                                                                                        $chat_list_data['msg_time']   = ' ';
                                                                                        $chat_list_data['message']   = ' ';

                                                                                        if(sizeof($other_notes) > 0){
                                                                                            $other_notes = $other_notes->first();
                                                                                            $other_notes->message = json_decode($other_notes->message);
                                                                                            if(!empty($other_notes)){
                                                                                                $chat_list_data['time']   		   = Helper::get_day_difference($other_notes->send_date);
                                                                                                $chat_list_data['datetime']   		   = $other_notes->send_date;
                                                                                                $chat_list_data['message'] 		   = $other_notes->message;
                                                                                                $chat_list_data['msg_time']       = $other_notes->send_time;
                                                                                                $chat_list_data['msgType']       = $other_notes->message_type;

                                                                                            }
                                                                                        }

                                                                                        $chat_list_data['name']   = $list->user_name;
                                                                                        $profile_pic = $list->profile_picture;
                                                                                            if(!empty($profile_pic)){
                                                                                                $profImage = config('base_urls.profile_images_base_url') . $profile_pic;
                                                                                            }
                                                                                        $chat_list_data['user_id']    	   = $list->users_id;
                                                                                        array_push($chat_list_array, $chat_list_data);

                                                                                    }
                                                                                    $list_status = 'ListFound';
                                                                                } else if(sizeof($check_list) <= 0){
                                                                                    $list_status = 'NoListFound';
                                                                                }

                                                                                if($list_status == 'ListFound'){
                                                                                    foreach($chat_list_array as $key => $k){
                                                                                        $senderDetails = DB::table('users')->where('users_id',$k['user_id'])->where('status','Active')->first();
                                                                                        $chat_list_array[$key]['name']     = $senderDetails->user_name;
                                                                                        if($senderDetails->profile_picture != '' or $senderDetails->profile_picture != null){
                                                                                            $chat_list_array[$key]['profile_pic']     = config('base_urls.profile_images_base_url') . $senderDetails->profile_picture;
                                                                                        } else{
                                                                                            $chat_list_array[$key]['profile_pic']     = '';
                                                                                        }
                                                                                    }

//sort panga
//                                                                                    $this->usort($chat_list_array, 'cb');
//                                                                                    $this->usort($chat_list_array, array($this, 'cb'));

//                                                                                    $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('miles')->take(5)->toArray());


                                                                                    $sortedArr = array_values(collect($chat_list_array)->sortByDesc('datetime')->toArray());
//                                                                                    return response($sortedArr);
//                                                                                    $hello = $sortedArr['status'];
                                                                                    $hi = gettype($sortedArr[0]);

                                                                                    $response["code"] = 200;
                                                                                    $response["status"] = "success";
                                                                                    $response["data"] = $sortedArr;
                                                                                }

                                                                                else{
                                                                                    $response["code"] = 500;
                                                                                    $response["status"] = "error";
                                                                                    $response["data"] = "error";
                                                                                }
                                                                            } else{
                                                                                $response["code"] = 404;
                                                                                $response["status"] = "error";
                                                                                $response["data"] = "All fields are needed";
                                                                            }
                                                                        break;

                                                                        case "getMessages":
                                                                            if(isset($data->userId) && isset($data->otherUserId)){
                                                                                        $chat_array =array();
                                                                                        $day_array =array();

                                                                                        $result = DB::table('chat_messages')->where([
                                                                                                                        ['sender_id',$data->otherUserId],
                                                                                                                        ['receiver_id', $data->userId]
                                                                                                                      ])->update(array('status' => 'Read'));

                                                                                    $all_chat = DB::table('chat_messages')->where([
                                                                                                                                    ['sender_id',$data->userId],
                                                                                                                                    ['receiver_id',$data->otherUserId],
                                                                                                                                ])->orWhere([
                                                                                                                                    ['sender_id',$data->otherUserId],
                                                                                                                                    ['receiver_id',$data->userId],
                                                                                                                                ])->orderBy('chat_message_id','ASC')->get();
                                                                                    if(sizeof($all_chat) > 0){
                                                                                        foreach($all_chat as $key => $chat){

                                                                                            $chat->message = json_decode($chat->message);

                                                                                            $day = Helper::get_day_difference($chat->send_date);

                                                                                            if (in_array($day, $day_array, TRUE)){
                                                                                                $get_data['date']= '';
                                                                                            }else {
                                                                                                array_push($day_array, $day);
                                                                                                $get_data['date']= $day;
                                                                                            }

                                                                                            if($data->userId == $chat->receiver_id){
                                                                                                    $get_data['userId'] = $data->otherUserId;
                                                                                                    $get_data['time'] =  date('h:i A',strtotime($chat->send_time));
                                                                                                    $get_data['msgType'] = $chat->message_type;

                                                                                                    if($chat->message_type=='attachment'){
                                                                                                            $attachment = config('base_urls.chat_attachments_base_url') . $chat->message;
                                                                                                            $get_data['message'] = $attachment;
                                                                                                            }
                                                                                                            else{
                                                                                                                $get_data['message'] = $chat->message;
                                                                                                            }
                                                                                                        array_push($chat_array, $get_data);
                                                                                            }
                                                                                            else if($data->userId == $chat->sender_id ){
                                                                                                    $get_data['userId']= $data->userId;
                                                                                                    $get_data['time'] =   date('h:i A',strtotime($chat->send_time));
                                                                                                    $get_data['msgType'] = $chat->message_type;

                                                                                                    if($chat->message_type =='attachment'){
                                                                                                            $attachment = config('base_urls.chat_attachments_base_url') . $chat->message;
                                                                                                            $get_data['message'] = $attachment;
                                                                                                            }
                                                                                                            else{
                                                                                                                $get_data['message'] = $chat->message;
                                                                                                            }
                                                                                                        array_push($chat_array, $get_data);
                                                                                                }

                                                                                                if(!empty($chat_array)){
                                                                                                    $result = DB::table('chat_messages')->where([
                                                                                                                                                ['sender_id',$data->otherUserId],
                                                                                                                                                ['receiver_id',$data->userId]
                                                                                                                                            ])->update(array('status'=>'Read'));
                                                                                                }
                                                                                        }

                                                                                        foreach($chat_array as $key => $k){
                                                                                            $senderDetails = DB::table('users')->where('users_id',$k['userId'])->where('status','Active')->first();
                                                                                            $chat_array[$key]['name']     = $senderDetails->user_name;

                                                                                            if($senderDetails->profile_picture != '' or $senderDetails->profile_picture != null){
                                                                                                $chat_array[$key]['profile_pic']     = config('base_urls.profile_images_base_url') . $senderDetails->profile_picture;
                                                                                            } else{
                                                                                                $chat_array[$key]['profile_pic']     = '';
                                                                                            }

                                                                                        }
                                                                                        $response['chat_messages'] = $chat_array;

                                                                                        if($chat_array){
                                                                                            $response["code"] = 200;
                                                                                            $response["status"] = "success";
                                                                                            $response["data"] =  $chat_array;

                                                                                        }
                                                                                        else{
                                                                                            $response["code"] = 404;
                                                                                            $response["status"] = "error";
                                                                                        }
                                                                                    } else{
                                                                                        $response["code"] = 404;
                                                                                        $response["status"] = "error";
                                                                                        $response["message"] = "No chat found";
                                                                                    }
                                                                            } else{
                                                                                $response["code"] = 404;
                                                                                $response["status"] = "error";
                                                                                $response["message"] = "All fields are needed";
                                                                            }
                                                                        break;

                                                    case "updateMessages":
                                                        if(isset($data->userId) && isset($data->otherUserId)){
                                                            $user_id = $data->userId;
                                                            $other_user_id 	= $data->otherUserId;
                                                            $chat_array =array();
                                                            $all_chat = DB::table('chat_messages')->where([
                                                                                                        ['sender_id', $other_user_id],
                                                                                                        ['receiver_id',$user_id],
                                                                                                        ['status','Unread']
                                                                                                            ])->orderBy('chat_message_id', 'ASC')->get();
                                                                if(sizeof($all_chat) > 0){
                                                                        foreach($all_chat as $chat){
                                                                            $chat->message = json_decode($chat->message);

                                                                            $get_data['userId']= $other_user_id;
                                                                            $get_data['time'] =  date('h:i A',strtotime($chat->send_time));
                                                                            $get_data['msgType'] = $chat->message_type;

                                                                            if($chat->message_type =='attachment'){
                                                                                $image = config('base_urls.chat_attachments_base_url') . $chat->message;
                                                                                $get_data['message'] = $image;
                                                                            }
                                                                            else{
                                                                                $get_data['message'] = $chat->message;
                                                                                }
                                                                                array_push($chat_array, $get_data);
                                                                        }
                                                                        if(!empty($chat_array)){
                                                                            $result = DB::table('chat_messages')->where([
                                                                                                                        ['sender_id',$other_user_id],
                                                                                                                        ['receiver_id',$user_id]
                                                                                                                    ])->update(array('status'=>'Read'));
                                                                        }
                                                                        $chat_length 	 = DB::table('chat_messages')->where([
                                                                                                                            ['sender_id', $user_id],
                                                                                                                            ['receiver_id',$other_user_id]
                                                                                                                            ])->orWhere([
                                                                                                                                ['sender_id', $other_user_id],
                                                                                                                                ['receiver_id',$user_id]
                                                                                                                            ])->orderBy('chat_messages_id','ASC')->count();
                                                                        if($chat_array){
                                                                            foreach($chat_array as $key => $k){
                                                                                $senderDetails = DB::table('users')->where('users_id',$k['userId'])->where('status','Active')->first();
                                                                                $chat_array[$key]['name']     = $senderDetails->user_name;
                                                                                if($senderDetails->profile_picture != '' or $senderDetails->profile_picture != null){
                                                                                    $chat_array[$key]['profile_pic']     = config('base_urls.profile_images_base_url') . $senderDetails->profile_picture;
                                                                                } else{
                                                                                    $chat_array[$key]['profile_pic']     = '';
                                                                                }
                                                                            }
                                                                            $response["code"] = 200;
                                                                            $response["status"] = "success";
                                                                            $response["data"] = array(
                                                                                    "chat_length" => $chat_length,
                                                                                    "unread_messages" => $chat_array,
                                                                            );
                                                                        }
                                                                        else{
                                                                            $response["code"] = 200;
                                                                            $response["status"] = "error";
                                                                            $response["data"] = "Unupdated Chat not found";
                                                                        }
                                                                } else{
                                                                    $response["code"] = 404;
                                                                    $response["status"] = "error";
                                                                    $response["message"] = "No chat found";
                                                                }
                                                            } else{
                                                                $response["code"] = 404;
                                                                $response["status"] = "error";
                                                                $response["message"] = "All fields are needed";
                                                            }
                                                            break;
                                                            default:
                                                                $response["code"] = 404;
                                                                $response["status"] = "error";
                                                                $response["message"] = "Silence is the golden key to success.";
                                                                break;
                                                 }
                                } else{
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["message"] = "Request type not set";
                                }
                                return response()
                                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                                ->header('Content-Type', 'application/json');

            }
            /*** CHAT ***/


         /////// GET TIME POST COUNT //////////
    public function post_count_available(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) ){


             $checkPostCountAvailable = DB::table('users')->where([ ['users_id',$data->usersId], ['status', 'Active'] ])->first();
                if($checkPostCountAvailable){
                        $postCountAvailable = $checkPostCountAvailable->one_time_post_count;
                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $postCountAvailable;
                    } else{
                        $response["code"] = 500;
                        $response["status"] = "error";
                        $response["message"] = "User not found";
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



 /////// Get user unread notifications badge count //////////
 public function get_user_notifications_badge_count(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
           $UnreadNotificationsCount = DB::table('notifications')->where([ ['receiver_users_id' , $data->usersId] , ['status', 'Unread'] ])->count();

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $UnreadNotificationsCount;

        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


 /////// Get user Notification Preferences //////////
 public function get_user_notification_preferences(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
           $userNotificationPreferences = DB::table('user_notifications_settings')->where('users_id', $data->usersId)->get();

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $userNotificationPreferences;

        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


 /////// Update Notification Setting //////////
 public function update_notification_setting(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->userNotificationSettingId) && isset($data->status)){

           $query = DB::table('user_notifications_settings')->where('user_notification_setting_id', $data->userNotificationSettingId)->update(['status'=> $data->status]);
                if($query){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'settings updated successfully';
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



         /////// GET ALL NOTIFICATIONS //////////
    public function get_all_notifications(){
        $response = array();
        $result = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) && isset($data->userLat) && isset($data->userLong) ){
//            gp
                $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
                $user_timezone = $response["timeZoneId"];


//            $allNotifications = DB::table('notifications')->where('receiver_users_id', $data->usersId)->orderBy('notification_id', 'DESC')->limit(25)->get();
            $allNotifications = DB::table('notifications')->where('receiver_users_id', $data->usersId)->orderBy('notification_id', 'DESC')->limit(25)->get();


//            $allNotifications = DB::table('notifications')->where('receiver_users_id', $data->usersId)->orderBy('notification_id', 'DESC')->limit(25)->get();
//            $total_posts =    DB::table('event_posts')->select('event_posts.*','addresses.*')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')


            if(sizeof($allNotifications) > 0){
                foreach($allNotifications as $key => $k){

//                    nabeel
                    if($k->event_post_id != null){
//                        $event_post = DB::table('event_posts')->where([ ["event_post_id",$k->event_post_id], ['status', 'Active']])->first();

//yaha 45
                        $event_post = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where("event_posts.event_post_id","=",$k->event_post_id)->first();


                        if($event_post->status == "Active"){



//gp
//                            $currentDate = date('Y-m-d H:i:s');
//                            $currentTimeSpam = strtotime($currentDate);
//                            $eventSaleStartDateTime = $event_post->event_end_date." ".$event_post->event_end_time;
//                            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

//                            return response($event_post->timezone);


                            //            Find differenec between event and user timestamp
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
                            $eventSaleStartDateTime = $event_post->event_end_date." ".$event_post->event_end_time;
                            $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                            $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;


                            if($currentTimeSpam < $eventSaleStartDateTimeSpam){
//Main Code
//panga start
                                $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->latest()->first();
                                if($liveStreamRoom){


                                    date_default_timezone_set($event_timezone);
                                    $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
                                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                                    $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;

//                                    $currentDate = date('Y-m-d H:i:s');
//                                    $currentTimeSpam = strtotime($currentDate);
//                                    $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
//                                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                                    if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                        $k->is_room_created = true;
                                        unset($liveStreamRoom->updated_at);
                                        unset($liveStreamRoom->created_at);
                                        $liveStreamRoom->converted_start_time = date("g:i A", strtotime($liveStreamRoom->live_start_time));
                                        $liveStreamRoom->converted_end_time = date("g:i A", strtotime($liveStreamRoom->live_end_time));
                                        $k->room_details = $liveStreamRoom;                                        }
                                    else{
                                        $k->is_room_created = false;
                                    }
                                }else{
                                    $k->is_room_created = false;
                                }
//panga end


                                $senderDetails = DB::table('users')->where('users_id', $k->sender_users_id)->first();
                                $k->sender_name = $senderDetails->user_name;
                                $k->date = date('d/m/Y', strtotime($k->datetime));
                                $k->time = date('H:i A', strtotime($k->datetime));



                                if($k->notification_type == "PeekComment") {

                                    $dateTime = new DateTime();
                                    $CurrentDateAddOne = $dateTime->format('Y-m-d');

                                    $peekDetails = DB::table('event_peeks')->where('event_peek_id', $k->peek_id)->whereDate('created_at','=',$CurrentDateAddOne)->first();

                                    if($peekDetails) {

                                        $peekDetails->total_likes = (string)$peekDetails->total_likes;
                                        $peekDetails->total_comments = (string)$peekDetails->total_comments;
                                        $peekDetails->total_views = (string)$peekDetails->total_views;

                                        $peekDetails->video_url = config('base_urls.event_peeks_base_url') . $peekDetails->video_name;
                                        $peekDetails->peek_thumbnail = config('base_urls.event_peek_thumbnails_base_url') . $peekDetails->thumbnail;

//                           changed start

                                        $peekDetails->total_likes = (string)Helper::thousandsCurrencyFormat($peekDetails->total_likes);
                                        $peekDetails->total_comments = (string)Helper::thousandsCurrencyFormat($peekDetails->total_comments);
                                        $peekDetails->total_views = (string)Helper::thousandsCurrencyFormat($peekDetails->total_views);


                                        $userDetails = DB::table('users')->where('users_id', $peekDetails->users_id)->first();
                                        $eventDetails = DB::table('event_posts')->where('event_post_id', $peekDetails->event_post_id)->first();

                                        $peekDetails->user_name = $userDetails->user_name;
                                        $peekDetails->first_name = $userDetails->first_name;
                                        $peekDetails->event_title = $eventDetails->title;
                                        if ($userDetails->profile_picture != "") {
                                            $peekDetails->profile_picture = config('base_urls.profile_images_base_url') . $userDetails->profile_picture;
                                        } else {
                                            $peekDetails->profile_picture = "";
                                        }

//                           changed end


                                        $k->peek_details = $peekDetails;
                                    }else{
                                        array_push($response, $k);
                                    }
                                }

                                //Fetching event Details for notification navigation
                                $eventDetails = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where('event_posts.event_post_id', $k->event_post_id)->first();
                                //appending images urls fields in response
                                $image_base_url = config('base_urls.event_image_base_url');
                                if(!empty( $eventDetails->first_image)){
                                    $eventDetails->first_image= $image_base_url.$eventDetails->first_image;
                                }
                                if(!empty( $eventDetails->second_image)){
                                    $eventDetails->second_image = $image_base_url.$eventDetails->second_image;
                                }
                                if(!empty( $eventDetails->third_image)){
                                    $eventDetails->third_image = $image_base_url.$eventDetails->third_image;
                                }

                                //updating Videos thumbnail urls fields in response
                                $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                                if(!empty( $eventDetails->first_video_thumbnail)){
                                    $eventDetails->first_video_thumbnail= $video_thumbnail_base_url.$eventDetails->first_video_thumbnail;
                                }
                                if(!empty( $eventDetails->second_video_thumbnail)){
                                    $eventDetails->second_video_thumbnail = $video_thumbnail_base_url.$eventDetails->second_video_thumbnail;
                                }
                                if(!empty( $eventDetails->third_video_thumbnail)){
                                    $eventDetails->third_video_thumbnail = $video_thumbnail_base_url.$eventDetails->third_video_thumbnail;
                                }

                                //appending Videos urls fields in response
                                $video_base_url = config('event_video_base_url');

                                if(!empty( $eventDetails->first_video)){
                                    $eventDetails->first_video= $video_base_url.$eventDetails->first_video;
                                }
                                if(!empty( $eventDetails->second_video)){
                                    $eventDetails->second_video = $video_base_url.$eventDetails->second_video;
                                }
                                if(!empty( $eventDetails->third_video)){
                                    $eventDetails->third_video = $video_base_url.$eventDetails->third_video;
                                }

                                //time conversion for minutes/hours/days ago
                                $eventDetails->time_ago = Helper::time_elapsed_string($eventDetails->created_at);

                                $early_bird_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                                if($early_bird_query){
                                    $eventDetails->early_bird_available = $early_bird_query->quantity;
                                }

                                $regular_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                                if($regular_query){
                                    $eventDetails->regular_available = $regular_query->quantity;
                                }

                                $vip_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                                if($vip_query){
                                    $eventDetails->vip_available = $vip_query->quantity;
                                }

                                $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                                if($skip_query){
                                    $k->skipping_line_available = $skip_query->quantity;
                                }


                                $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                                $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                                $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                                $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                                $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;

                                //appending Category data from categories in response array after success
                                $category =   DB::table('categories')->where('category_id',$eventDetails->category_id)->where('status',"Active")->first();
                                if($category){
                                    $eventDetails->category = $category;
                                }

                                //appending Event Type data from Event_type in response array after success
                                $event_type =   DB::table('event_type')->where('event_type_id',$eventDetails->event_type_id)->where('status',"Active")->first();
                                if($event_type){
                                    $eventDetails->event_type = $event_type;
                                }

                                //appending Dress Code data from dress_code in response array after success
                                $dress_code =   DB::table('dress_code')->where('dress_code_id',$eventDetails->dress_code_id)->where('status',"Active")->first();
                                if($dress_code){
                                    $eventDetails->dress_code = $dress_code;
                                }

                                //fetching tags from tags table of each event post
                                $tags =   DB::table('event_tags')
                                    ->select('event_tags.*','tags.*')
                                    ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                    ->where('event_tags.event_post_id',$eventDetails->event_post_id)
                                    ->where('event_tags.status','Active')->get();
                                if($tags){
                                    $eventDetails->event_tags = $tags;
                                }

                                //fetching tickets from tickets table of each event post
                                /*    $tickets =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get();
                                        if($tickets){
                                            $tickets_array = array();
                                            foreach($tickets as $key => $k){
                                            $event_post->event_tickets[$key][$tickets[$key]->ticket] = $tickets[$key];


                                            }

                                        }   */
                                //fetching tickets from tickets table of each event post
                                $ticket1 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                                $ticket2 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                                $ticket3 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                                $ticket4 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                                if($ticket1){
                                    $eventDetails->Early_bird = $ticket1;
                                }
                                if($ticket2){
                                    $eventDetails->Regular = $ticket2;
                                }
                                if($ticket3){
                                    $eventDetails->VIP = $ticket3;
                                } if($ticket4){
                                    $eventDetails->skipping_line = $ticket4;
                                }


                                //fetching address from address table of each event post
                                $address =   DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->where('status',"Active")->get()->first();
                                if($address){
                                    $eventDetails->event_address = $address;
                                }


                                if(isset($data->userLat) && isset($data->userLong)){
                                    $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                    if($eventCordinates){
                                        $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->distance_miles = $distance_miles_calc;
                                        $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->miles = $miles_calc;
                                    }
                                }





                                //appending liked event post hint
                                $checkLike = DB::table('event_likes')->where('event_post_id', $eventDetails->event_post_id)->where('users_id', $k->receiver_users_id)->count();
                                if($checkLike > 0){
                                    $eventDetails->liked = 'true';
                                } else{
                                    $eventDetails->liked = 'false';
                                }

                                //appending favourite post hint
                                $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $eventDetails->event_post_id)->where('users_id', $k->receiver_users_id)->count();
                                if($isFavouriteQuery > 0){
                                    $eventDetails->isFavourite = 'true';
                                } else{
                                    $eventDetails->isFavourite = 'false';
                                }

                                //appending total likes on event
                                $total_likes =  DB::table('event_posts')->where('event_post_id', $eventDetails->event_post_id)->first();
                                if($total_likes){
                                    $eventDetails->total_likes = $total_likes->total_likes;
                                }

                                //appending event distance in miles from user current location
                                if(isset($data->userLat) && isset($data->userLong)){
                                    $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                    if($eventCordinates){
                                        $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->distance_miles = $distance_miles_calc;
                                        $miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->miles = $miles_calc;
                                    }
                                }


                                //Total comments count on Event Post
                                $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $eventDetails->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                                $eventDetails->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


//                                            changed start

                                //appending event distance in miles from user current location
                                if(isset($data->userLat) && isset($data->userLong)){
                                    $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                    if($eventCordinates){
                                        $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->distance_miles = $distance_miles_calc;
                                        $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $eventDetails->miles = $miles_calc;
                                    }
                                }


                                $eventDetails->sales_end_datetime = $eventDetails->sales_end_date.' '.$eventDetails->sales_end_time;
                                // Date conversion in 12 hrs format in response
                                $eventDetails->event_start_date = date("M d, Y", strtotime($eventDetails->event_start_date));
                                $eventDetails->event_end_date = date("M d, Y", strtotime($eventDetails->event_end_date));
                                $eventDetails->sales_start_date = date("M d, Y", strtotime($eventDetails->sales_start_date));
                                $eventDetails->sales_end_date = date("M d, Y", strtotime($eventDetails->sales_end_date));

                                // time conversion in 12 hrs format in response
                                $eventDetails->event_start_time = date("g:iA", strtotime($eventDetails->event_start_time));
                                $eventDetails->event_end_time = date("g:iA", strtotime($eventDetails->event_end_time));
                                $eventDetails->sales_start_time = date("g:iA", strtotime($eventDetails->sales_start_time));
                                $eventDetails->sales_end_time = date("g:iA", strtotime($eventDetails->sales_end_time));

                                //Total comments count on Event Post
                                $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $eventDetails->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                                $eventDetails->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                                if($eventDetails->dress_code_id != 0){
                                    // Appending dresscode color code for thumbnail bar color ease in event details
                                    $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $eventDetails->dress_code_id)->first();
                                    $eventDetails->dress_code_color_code = $dressCodeColor->dress_code_color;
                                }else{
                                    $eventDetails->dress_code_color_code = null;
                                }


                                //Appeding organizer details
                                $organizerDetails = DB::table('users')->where('users_id', $eventDetails->users_id)->first();
                                $eventDetails->organizer_users_id = $organizerDetails->users_id;
                                $eventDetails->organizer_user_name = $organizerDetails->user_name;

                                if($organizerDetails->profile_picture != ""){
                                    $eventDetails->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                                }else{
                                    $eventDetails->organizer_profile_picture =  "";
                                }

                                //following flag
                                $checkFollowing = DB::table('followers')->where([ ['following_to_user',$eventDetails->users_id] ,['followed_by_user' ,$data->usersId] ])->first();
                                if($checkFollowing){
                                    $eventDetails->is_following = true;
                                } else{
                                    $eventDetails->is_following = false;
                                }

                                //totalFollowers
                                $totalFollowers = DB::table('followers')->where('following_to_user', $eventDetails->users_id)->count();
                                $eventDetails->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);




//                       $k->event_post_details = $eventDetails;




//                                            changed end

                                $k->event_details = $eventDetails;

                                // Comment details in each notification object if any
                                if($k->comment_id != null){
                                    $comment = DB::table('event_comments')->where([
                                        ['event_comment_id', $k->comment_id],
                                        ['status', 'Active']
                                    ])->first();
                                    //user profile and username for each comment
                                    $commentUserDetails = DB::table('users')->where([ ['users_id', $comment->users_id ?? 'user_id not found'], ['status', 'Active'] ])->first();
                                    if($commentUserDetails->profile_picture ?? 'profile_picture is null' != "" || $commentUserDetails->profile_picture ?? 'profile_picture is null' != null){
                                        $comment->comment_user_profile =  config('base_urls.profile_images_base_url').$commentUserDetails->profile_picture;
                                    } else{
                                        $comment->comment_user_profile =  "";
                                    }
                                    $comment->comment_user_name = $commentUserDetails->user_name;

                                    //appending liked comment hint
                                    $checkLike = DB::table('event_comment_likes')->where([ ['event_comment_id', $comment->event_comment_id], ['users_id', $data->usersId] ])->count();
                                    if($checkLike > 0){
                                        $comment->comment_liked = 'true';
                                    } else{
                                        $comment->comment_liked = 'false';
                                    }

                                    //Comment time conversion for minutes/hours/days ago
                                    $comment->comment_time_ago = Helper::time_elapsed_string($comment->commented_datetime);


                                    //Mentioned User details in each comment
                                    if($comment->mentioned_user != null){
                                        $mentionedUserDetails = DB::table('users')-> where([ ['users_id', $comment->mentioned_user], ['status', 'Active'] ])->first();
                                        $comment->mentioned_user_id = $comment->mentioned_user;
                                        $comment->mentioned_user_name = $mentionedUserDetails->user_name;
                                    } else{
                                        $comment->mentioned_user_id = 0;
                                        $comment->mentioned_user_name = '';
                                    }
                                    //Total Replies count in every comment
                                    $totalReplies = DB::table('event_comments')->where([
                                        ['replying_to_comment_id', $comment->event_comment_id],
                                        ['event_post_id', $comment->event_post_id],
                                        ['comment_type', 'reply'],
                                        ['status', 'Active']
                                    ])->count();
                                    $comment->total_replies_count = $totalReplies;

                                    //Appending comment object
                                    $k->comment_details = $comment;
                                } else{
                                    $k->comment_details = null;
                                }


//                                Check time of liveStream if it is greater than current than show notification otherwise dont do it
                                if($k->notification_type == "LiveStreamStarted"){
                                    $host_rooms = DB::table('host_rooms')->where("event_post_id","=",$k->event_post_id)->where("status","=","Active")->first();

                                    if($host_rooms != null){

                                        date_default_timezone_set($event_timezone);
                                        $eventSaleStartDateTime = $host_rooms->live_end_date." ".$host_rooms->live_end_time;
                                        $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                                        $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;

//                                        $currentDate = date('Y-m-d H:i:s');
//                                        $currentTimeSpam = strtotime($currentDate);
//                                        $eventSaleStartDateTime = $host_rooms->live_end_date." ".$host_rooms->live_end_time;
//                                        $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                                        if($currentTimeSpam < $eventSaleStartDateTimeSpam) {

                                            array_push($result, $k);
                                        }else{

                                        }
                                    }
                                }else{
                                    array_push($result, $k);
                                }

                            }
                            else{

                            }
                        }else{

                        }


                    }else{

//                        Main Code
                        $senderDetails = DB::table('users')->where('users_id', $k->sender_users_id)->first();
                        $k->sender_name = $senderDetails->user_name;
                        $k->date = date('d/m/Y', strtotime($k->datetime));
                        $k->time = date('H:i A', strtotime($k->datetime));

                        if($k->event_post_id != null){


                            //                                        panga start
                            $liveStreamRoom = DB::table('host_rooms')->where([ ['event_post_id', $k->event_post_id], ['status', 'Active'] ])->latest()->first();
                            if($liveStreamRoom){


//                                gp
                                $event_post = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where([ ['event_posts.event_post_id', $k->event_post_id], ['status', 'Active'] ])->first();

                                    //            Find differenec between event and user timestamp
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
                                $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
                                    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
                                    $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;



//                                $currentDate = date('Y-m-d H:i:s');
//                                $currentTimeSpam = strtotime($currentDate);
//                                $eventSaleStartDateTime = $liveStreamRoom->live_end_date." ".$liveStreamRoom->live_end_time;
//                                $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

                                if($currentTimeSpam < $eventSaleStartDateTimeSpam){
                                    $k->is_room_created = true;
                                    unset($liveStreamRoom->updated_at);
                                    unset($liveStreamRoom->created_at);
                                    $liveStreamRoom->converted_start_time = date("g:i A", strtotime($liveStreamRoom->live_start_time));
                                    $liveStreamRoom->converted_end_time = date("g:i A", strtotime($liveStreamRoom->live_end_time));
                                    $k->room_details = $liveStreamRoom;                                        }
                                else{
                                    $k->is_room_created = false;
                                }
                            }else{
                                $k->is_room_created = false;
                            }

//                                        panga end






                            if($k->notification_type == "PeekComment") {

                                $dateTime = new DateTime();
                                $CurrentDateAddOne = $dateTime->format('Y-m-d');

                                $peekDetails = DB::table('event_peeks')->where('event_peek_id', $k->peek_id)->whereDate('created_at','=',$CurrentDateAddOne)->first();

                                if($peekDetails) {

                                    $peekDetails->total_likes = (string)$peekDetails->total_likes;
                                    $peekDetails->total_comments = (string)$peekDetails->total_comments;
                                    $peekDetails->total_views = (string)$peekDetails->total_views;

                                    $peekDetails->video_url = config('base_urls.event_peeks_base_url') . $peekDetails->video_name;
                                    $peekDetails->peek_thumbnail = config('base_urls.event_peek_thumbnails_base_url') . $peekDetails->thumbnail;

//                           changed start

                                    $peekDetails->total_likes = (string)Helper::thousandsCurrencyFormat($peekDetails->total_likes);
                                    $peekDetails->total_comments = (string)Helper::thousandsCurrencyFormat($peekDetails->total_comments);
                                    $peekDetails->total_views = (string)Helper::thousandsCurrencyFormat($peekDetails->total_views);


                                    $userDetails = DB::table('users')->where('users_id', $peekDetails->users_id)->first();
                                    $eventDetails = DB::table('event_posts')->where('event_post_id', $peekDetails->event_post_id)->first();

                                    $peekDetails->user_name = $userDetails->user_name;
                                    $peekDetails->first_name = $userDetails->first_name;
                                    $peekDetails->event_title = $eventDetails->title;
                                    if ($userDetails->profile_picture != "") {
                                        $peekDetails->profile_picture = config('base_urls.profile_images_base_url') . $userDetails->profile_picture;
                                    } else {
                                        $peekDetails->profile_picture = "";
                                    }

//                           changed end


                                    $k->peek_details = $peekDetails;
                                }else{
                                    array_push($response, $k);
                                }
                            }

                            //Fetching event Details for notification navigation
                            $eventDetails = DB::table('event_posts')->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')->where('event_posts.event_post_id', $k->event_post_id)->first();
                            //appending images urls fields in response
                            $image_base_url = config('base_urls.event_image_base_url');
                            if(!empty( $eventDetails->first_image)){
                                $eventDetails->first_image= $image_base_url.$eventDetails->first_image;
                            }
                            if(!empty( $eventDetails->second_image)){
                                $eventDetails->second_image = $image_base_url.$eventDetails->second_image;
                            }
                            if(!empty( $eventDetails->third_image)){
                                $eventDetails->third_image = $image_base_url.$eventDetails->third_image;
                            }

                            //updating Videos thumbnail urls fields in response
                            $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                            if(!empty( $eventDetails->first_video_thumbnail)){
                                $eventDetails->first_video_thumbnail= $video_thumbnail_base_url.$eventDetails->first_video_thumbnail;
                            }
                            if(!empty( $eventDetails->second_video_thumbnail)){
                                $eventDetails->second_video_thumbnail = $video_thumbnail_base_url.$eventDetails->second_video_thumbnail;
                            }
                            if(!empty( $eventDetails->third_video_thumbnail)){
                                $eventDetails->third_video_thumbnail = $video_thumbnail_base_url.$eventDetails->third_video_thumbnail;
                            }

                            //appending Videos urls fields in response
                            $video_base_url = config('event_video_base_url');

                            if(!empty( $eventDetails->first_video)){
                                $eventDetails->first_video= $video_base_url.$eventDetails->first_video;
                            }
                            if(!empty( $eventDetails->second_video)){
                                $eventDetails->second_video = $video_base_url.$eventDetails->second_video;
                            }
                            if(!empty( $eventDetails->third_video)){
                                $eventDetails->third_video = $video_base_url.$eventDetails->third_video;
                            }

                            //time conversion for minutes/hours/days ago
                            $eventDetails->time_ago = Helper::time_elapsed_string($eventDetails->created_at);

                            $early_bird_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                            if($early_bird_query){
                                $eventDetails->early_bird_available = $early_bird_query->quantity;
                            }

                            $regular_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                            if($regular_query){
                                $eventDetails->regular_available = $regular_query->quantity;
                            }

                            $vip_query = DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                            if($vip_query){
                                $eventDetails->vip_available = $vip_query->quantity;
                            }

                            $skip_query = DB::table('tickets')->where('event_post_id', $k->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
                            if($skip_query){
                                $k->skipping_line_available = $skip_query->quantity;
                            }


                            $val1 = (isset($k->early_bird_available) ? $k->early_bird_available : 0);
                            $val2 = (isset($k->regular_available) ? $k->regular_available : 0);
                            $val3 = (isset($k->vip_available) ? $k->vip_available : 0);
                            $val4 = (isset($k->skipping_line_available) ? $k->skipping_line_available : 0);

                            $k->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;

                            //appending Category data from categories in response array after success
                            $category =   DB::table('categories')->where('category_id',$eventDetails->category_id)->where('status',"Active")->first();
                            if($category){
                                $eventDetails->category = $category;
                            }

                            //appending Event Type data from Event_type in response array after success
                            $event_type =   DB::table('event_type')->where('event_type_id',$eventDetails->event_type_id)->where('status',"Active")->first();
                            if($event_type){
                                $eventDetails->event_type = $event_type;
                            }

                            //appending Dress Code data from dress_code in response array after success
                            $dress_code =   DB::table('dress_code')->where('dress_code_id',$eventDetails->dress_code_id)->where('status',"Active")->first();
                            if($dress_code){
                                $eventDetails->dress_code = $dress_code;
                            }

                            //fetching tags from tags table of each event post
                            $tags =   DB::table('event_tags')
                                ->select('event_tags.*','tags.*')
                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                ->where('event_tags.event_post_id',$eventDetails->event_post_id)
                                ->where('event_tags.status','Active')->get();
                            if($tags){
                                $eventDetails->event_tags = $tags;
                            }

                            //fetching tickets from tickets table of each event post
                            /*    $tickets =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get();
                                    if($tickets){
                                        $tickets_array = array();
                                        foreach($tickets as $key => $k){
                                        $event_post->event_tickets[$key][$tickets[$key]->ticket] = $tickets[$key];


                                        }

                                    }   */
                            //fetching tickets from tickets table of each event post
                            $ticket1 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
                            $ticket2 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
                            $ticket3 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
                            $ticket4 =   DB::table('tickets')->where('event_post_id', $eventDetails->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

                            if($ticket1){
                                $eventDetails->Early_bird = $ticket1;
                            }
                            if($ticket2){
                                $eventDetails->Regular = $ticket2;
                            }
                            if($ticket3){
                                $eventDetails->VIP = $ticket3;
                            } if($ticket4){
                                $eventDetails->skipping_line = $ticket4;
                            }


                            //fetching address from address table of each event post
                            $address =   DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->where('status',"Active")->get()->first();
                            if($address){
                                $eventDetails->event_address = $address;
                            }


                            if(isset($data->userLat) && isset($data->userLong)){
                                $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                if($eventCordinates){
                                    $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->distance_miles = $distance_miles_calc;
                                    $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->miles = $miles_calc;
                                }
                            }





                            //appending liked event post hint
                            $checkLike = DB::table('event_likes')->where('event_post_id', $eventDetails->event_post_id)->where('users_id', $k->receiver_users_id)->count();
                            if($checkLike > 0){
                                $eventDetails->liked = 'true';
                            } else{
                                $eventDetails->liked = 'false';
                            }

                            //appending favourite post hint
                            $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $eventDetails->event_post_id)->where('users_id', $k->receiver_users_id)->count();
                            if($isFavouriteQuery > 0){
                                $eventDetails->isFavourite = 'true';
                            } else{
                                $eventDetails->isFavourite = 'false';
                            }

                            //appending total likes on event
                            $total_likes =  DB::table('event_posts')->where('event_post_id', $eventDetails->event_post_id)->first();
                            if($total_likes){
                                $eventDetails->total_likes = $total_likes->total_likes;
                            }

                            //appending event distance in miles from user current location
                            if(isset($data->userLat) && isset($data->userLong)){
                                $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                if($eventCordinates){
                                    $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->distance_miles = $distance_miles_calc;
                                    $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->miles = $miles_calc;
                                }
                            }


                            //Total comments count on Event Post
                            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $eventDetails->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                            $eventDetails->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


//                                            changed start

                            //appending event distance in miles from user current location
                            if(isset($data->userLat) && isset($data->userLong)){
                                $eventCordinates = DB::table('addresses')->where('event_post_id', $eventDetails->event_post_id)->first();
                                if($eventCordinates){
                                    $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->distance_miles = $distance_miles_calc;
                                    $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                    $eventDetails->miles = $miles_calc;
                                }
                            }


                            $eventDetails->sales_end_datetime = $eventDetails->sales_end_date.' '.$eventDetails->sales_end_time;
                            // Date conversion in 12 hrs format in response
                            $eventDetails->event_start_date = date("M d, Y", strtotime($eventDetails->event_start_date));
                            $eventDetails->event_end_date = date("M d, Y", strtotime($eventDetails->event_end_date));
                            $eventDetails->sales_start_date = date("M d, Y", strtotime($eventDetails->sales_start_date));
                            $eventDetails->sales_end_date = date("M d, Y", strtotime($eventDetails->sales_end_date));

                            // time conversion in 12 hrs format in response
                            $eventDetails->event_start_time = date("g:iA", strtotime($eventDetails->event_start_time));
                            $eventDetails->event_end_time = date("g:iA", strtotime($eventDetails->event_end_time));
                            $eventDetails->sales_start_time = date("g:iA", strtotime($eventDetails->sales_start_time));
                            $eventDetails->sales_end_time = date("g:iA", strtotime($eventDetails->sales_end_time));

                            //Total comments count on Event Post
                            $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $eventDetails->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                            $eventDetails->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                            if($eventDetails->dress_code_id != 0){
                                // Appending dresscode color code for thumbnail bar color ease in event details
                                $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $eventDetails->dress_code_id)->first();
                                $eventDetails->dress_code_color_code = $dressCodeColor->dress_code_color;
                            }else{
                                $eventDetails->dress_code_color_code = null;
                            }


                            //Appeding organizer details
                            $organizerDetails = DB::table('users')->where('users_id', $eventDetails->users_id)->first();
                            $eventDetails->organizer_users_id = $organizerDetails->users_id;
                            $eventDetails->organizer_user_name = $organizerDetails->user_name;

                            if($organizerDetails->profile_picture != ""){
                                $eventDetails->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                            }else{
                                $eventDetails->organizer_profile_picture =  "";
                            }

                            //following flag
                            $checkFollowing = DB::table('followers')->where([ ['following_to_user',$eventDetails->users_id] ,['followed_by_user' ,$data->usersId] ])->first();
                            if($checkFollowing){
                                $eventDetails->is_following = true;
                            } else{
                                $eventDetails->is_following = false;
                            }

                            //totalFollowers
                            $totalFollowers = DB::table('followers')->where('following_to_user', $eventDetails->users_id)->count();
                            $eventDetails->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);




//                       $k->event_post_details = $eventDetails;




//                                            changed end

                            $k->event_details = $eventDetails;

                            // Comment details in each notification object if any
                            if($k->comment_id != null){
                                $comment = DB::table('event_comments')->where([
                                    ['event_comment_id', $k->comment_id],
                                    ['status', 'Active']
                                ])->first();
                                //user profile and username for each comment
                                $commentUserDetails = DB::table('users')->where([ ['users_id', $comment->users_id], ['status', 'Active'] ])->first();
                                if($commentUserDetails->profile_picture != "" || $commentUserDetails->profile_picture != null){
                                    $comment->comment_user_profile =  config('base_urls.profile_images_base_url').$commentUserDetails->profile_picture;
                                } else{
                                    $comment->comment_user_profile =  "";
                                }
                                $comment->comment_user_name = $commentUserDetails->user_name;

                                //appending liked comment hint
                                $checkLike = DB::table('event_comment_likes')->where([ ['event_comment_id', $comment->event_comment_id], ['users_id', $data->usersId] ])->count();
                                if($checkLike > 0){
                                    $comment->comment_liked = 'true';
                                } else{
                                    $comment->comment_liked = 'false';
                                }

                                //Comment time conversion for minutes/hours/days ago
                                $comment->comment_time_ago = Helper::time_elapsed_string($comment->commented_datetime);


                                //Mentioned User details in each comment
                                if($comment->mentioned_user != null){
                                    $mentionedUserDetails = DB::table('users')-> where([ ['users_id', $comment->mentioned_user], ['status', 'Active'] ])->first();
                                    $comment->mentioned_user_id = $comment->mentioned_user;
                                    $comment->mentioned_user_name = $mentionedUserDetails->user_name;
                                } else{
                                    $comment->mentioned_user_id = 0;
                                    $comment->mentioned_user_name = '';
                                }
                                //Total Replies count in every comment
                                $totalReplies = DB::table('event_comments')->where([
                                    ['replying_to_comment_id', $comment->event_comment_id],
                                    ['event_post_id', $comment->event_post_id],
                                    ['comment_type', 'reply'],
                                    ['status', 'Active']
                                ])->count();
                                $comment->total_replies_count = $totalReplies;

                                //Appending comment object
                                $k->comment_details = $comment;
                            } else{
                                $k->comment_details = null;
                            }
                        }else {
                            //business Details here
                            $business = DB::table('businesses')->where('business_id', $k->business_id)->first();
                            //appending Logo url fields in response
                            $logo_base_url = config('base_urls.business_logo_base_url');
                            if (!empty($business->business_logo)) {
                                $business->business_logo = $logo_base_url . $business->business_logo;
                            }

                            //appending images urls fields in response
                            $image_base_url = config('base_urls.business_image_base_url');
                            if (!empty($business->first_image)) {
                                $business->first_image = $image_base_url . $business->first_image;
                            }
                            if (!empty($business->second_image)) {
                                $business->second_image = $image_base_url . $business->second_image;
                            }
                            if (!empty($business->third_image)) {
                                $business->third_image = $image_base_url . $business->third_image;
                            }

                            //appending Video thumbnails urls fields in response
                            $video_thumbnail_base_url = config('base_urls.business_video_thumbnails_base_url');
                            if (!empty($business->first_video_thumbnail)) {
                                $business->first_video_thumbnail = $video_thumbnail_base_url . $business->first_video_thumbnail;
                            }
                            if (!empty($business->second_video_thumbnail)) {
                                $business->second_video_thumbnail = $video_thumbnail_base_url . $business->second_video_thumbnail;
                            }
                            if (!empty($business->third_video_thumbnail)) {
                                $business->third_video_thumbnail = $video_thumbnail_base_url . $business->third_video_thumbnail;
                            }

                            //appending Videos urls fields in response
                            $video_base_url = config('base_urls.business_video_base_url');
                            if (!empty($business->first_video)) {
                                $business->first_video = $video_base_url . $business->first_video;
                            }
                            if (!empty($business->second_video)) {
                                $business->second_video = $video_base_url . $business->second_video;
                            }
                            if (!empty($business->third_video)) {
                                $business->third_video = $video_base_url . $business->third_video;
                            }


                            if (!empty($business->business_type)) {
                                $business_type = DB::table('business_types')->where('type', $business->business_type)->where('status', "Active")->first();
                            }

                            if ($business_type) {
                                $business->business_type = $business_type;
                            }

                            //time conversion for minutes/hours/days ago
                            $business->time_ago = Helper::time_elapsed_string($business->created_at);

                            $business->business_lat = (double)$business->business_lat;
                            $business->business_long = (double)$business->business_long;

                            //Total comments count on Business Post
                            $totalPostComments = DB::table('business_comments')->where([['business_id', $business->business_id], ['comment_type', 'comment'], ['status', 'Active']])->count();
                            $business->total_post_comments = (string)Helper::thousandsCurrencyFormat($totalPostComments);
                            $business->total_likes = (string)Helper::thousandsCurrencyFormat($business->total_likes);

                            //appending liked event post hint
                            $checkLike = DB::table('business_likes')->where('business_id', $business->business_id)->where('users_id', $data->usersId)->count();
                            if ($checkLike > 0) {
                                $business->liked = true;
                            } else {
                                $business->liked = false;
                            }

                            //appending favourite post hint
                            $isFavouriteQuery = DB::table('business_favourites')->where('business_id', $business->business_id)->where('users_id', $data->usersId)->count();
                            if ($isFavouriteQuery > 0) {
                                $business->isFavourite = true;
                            } else {
                                $business->isFavourite = false;
                            }


                            $k->business_details = $business;

                            // business Comment details in each notification object if any
                            if ($k->comment_id != null) {
                                $comment = DB::table('business_comments')->where([
                                    ['business_comment_id', $k->comment_id],
                                    ['status', 'Active']
                                ])->first();
                                //user profile and username for each comment
                                $commentUserDetails = DB::table('users')->where([['users_id', $comment->users_id], ['status', 'Active']])->first();
                                if ($commentUserDetails->profile_picture != "" || $commentUserDetails->profile_picture != null) {
                                    $comment->comment_user_profile = config('base_urls.profile_images_base_url') . $commentUserDetails->profile_picture;
                                } else {
                                    $comment->comment_user_profile = "";
                                }
                                $comment->comment_user_name = $commentUserDetails->user_name;

                                //appending liked comment hint
                                $checkLike = DB::table('business_comment_likes')->where([['business_comment_id', $comment->business_comment_id], ['users_id', $data->usersId]])->count();
                                if ($checkLike > 0) {
                                    $comment->comment_liked = 'true';
                                } else {
                                    $comment->comment_liked = 'false';
                                }

                                //Comment time conversion for minutes/hours/days ago
                                $comment->comment_time_ago = Helper::time_elapsed_string($comment->commented_datetime);


                                //Mentioned User details in each comment
                                if ($comment->mentioned_user != null) {
                                    $mentionedUserDetails = DB::table('users')->where([['users_id', $comment->mentioned_user], ['status', 'Active']])->first();
                                    $comment->mentioned_user_id = $comment->mentioned_user;
                                    $comment->mentioned_user_name = $mentionedUserDetails->user_name;
                                } else {
                                    $comment->mentioned_user_id = 0;
                                    $comment->mentioned_user_name = '';
                                }
                                //Total Replies count in every comment
                                $totalReplies = DB::table('business_comments')->where([
                                    ['replying_to_comment_id', $comment->business_comment_id],
                                    ['business_id', $comment->business_id],
                                    ['comment_type', 'reply'],
                                    ['status', 'Active']
                                ])->count();
                                $comment->total_replies_count = $totalReplies;

                                //Appending comment object
                                $k->business_comment_details = $comment;
                            } else {
                                $k->business_comment_details = null;
                            }
                        }
                        array_push($result, $k);
                    }

                }


                DB::table('notifications')->where('receiver_users_id', $data->usersId)->update(['status' => 'Read']);

                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $result;
            } else{
                $response["code"] = 200;
                $response["status"] = "error";
                $response["message"] = "No notifications available";
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


         /////// GET EVENT LIBRARY LIST //////////
         public function get_event_library_list(){
            $response = array();
            $result = array();
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->usersId) ){

                $givenUserDetails = DB::table('users')->where('users_id', $data->usersId)->first();
                    if($givenUserDetails->subscription_package_id == 1)
                        $basicFlag = true;
                    else
                        $basicFlag = false;

                $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');


                $eventList = DB::table('event_posts')
                              ->join('user_tickets','user_tickets.event_post_id','event_posts.event_post_id')
                                                    ->where(function($query) {
                                                        if($this->event_type_filter != '')
                                                        {
                                                            $query->where('event_posts.event_type_id',$this->event_type_filter);
                                                        }
                                                    })
                                                     ->where('event_posts.event_ticket_type' ,'Paid')
                                                     ->where('user_tickets.users_id' , $data->usersId)
                                                     ->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                                                     ->where('event_posts.status', 'Active')->get();


//                               return response()
//                                        ->json(array('is_basic' => "hy", 'status' => $eventList))
//                                        ->header('Content-Type', 'application/json');



                    if(sizeof($eventList) > 0){
                            foreach($eventList as $key => $k){

                                $userData = DB::table('users')->where('users_id', $k->users_id)->first();

                                $realeventList = DB::table('event_posts')
                                    ->where('event_posts.status', 'Active')
                                    ->where('event_post_id', $k->event_post_id)
                                    ->first();
                                $Eventuser = DB::table('users')->where('users_id', $realeventList->users_id)->first();


//                                added by nabeel
//                                check premium or onetime subscription
                                if($Eventuser->subscription_package_id == 2){
                                    $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                                    $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                                    //user checkin check for event library items upload
                                    $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->first();
                                    if($check_checkedin){
                                        $k->user_checkedin = true;
                                    } else{
                                        $k->user_checkedin = false;
                                    }

//                                    return response()
//                                        ->json(array('is_basic' => $check_checkedin, 'status' => $eventList))
//                                        ->header('Content-Type', 'application/json');

                                    $createdAtDatetime = new Datetime($k->created_at);
                                    $currentDatetime = new Datetime();
                                    $diff = $createdAtDatetime->diff($currentDatetime);

                                    if($diff->m >= 1)
                                        unset($eventList[$key]);
                                }else if($Eventuser->subscription_package_id == 4){

                                    if($realeventList->onetime == 1){
                                        $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                                        $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                                        //user checkin check for event library items upload
                                        $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->first();
                                        if($check_checkedin){
                                            $k->user_checkedin = true;
                                        } else{
                                            $k->user_checkedin = false;
                                        }

                                        $createdAtDatetime = new Datetime($k->created_at);
                                        $currentDatetime = new Datetime();
                                        $diff = $createdAtDatetime->diff($currentDatetime);

                                        if($diff->m >= 1)
                                            unset($eventList[$key]);
                                    }else{
                                        unset($eventList[$key]);
                                    }
                                }else if($Eventuser->subscription_package_id == 1){
                                    $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                                    $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                                    //user checkin check for event library items upload
                                    $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->first();
                                    if($check_checkedin){
                                        $k->user_checkedin = true;
                                    } else{
                                        $k->user_checkedin = false;
                                    }

//                                    return response()
//                                        ->json(array('is_basic' => $check_checkedin, 'status' => $eventList))
//                                        ->header('Content-Type', 'application/json');

                                    $createdAtDatetime = new Datetime($k->created_at);
                                    $currentDatetime = new Datetime();
                                    $diff = $createdAtDatetime->diff($currentDatetime);

                                    if($diff->m >= 1)
                                        unset($eventList[$key]);
                                } else{
                                    unset($eventList[$key]);
                                }


//                              Not need premium account to see posts
//                              if($userData) {
//                                  if ($userData->subscription_package_id == '1')
//                                      unset($eventList[$key]);
//                              }



                            }


                        $eventList = array_values(collect($eventList)->toArray());



                                 $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = $eventList;
                                $response["is_basic"] = $basicFlag;
                        } else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "No events found";
                            $response["is_basic"] = $basicFlag;
                        }

            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "All Fields are needed";
                $response["is_basic"] = false;
            }
            return response()
            ->json(array('is_basic' => $response["is_basic"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
         }


         /////// GET EVENT LIBRARY LIST //////////
    public function get_my_event_library_list(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->usersId) ){

            $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');

                      $eventList = DB::table('event_posts')
                                                    ->where(function($query) {
                                                        if($this->event_type_filter != '')
                                                        {
                                                            $query->where('event_type_id',$this->event_type_filter);
                                                        }
                                                    })
                                                  ->where('users_id', $data->usersId)
                                                  ->where('status', 'Active')
                                                  ->take(10)
                                                  ->latest()
                                                  ->get();

                if(sizeof($eventList) > 0){
                        foreach($eventList as $k){
                            $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                            $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                            //user checkin check for event library items upload
                            $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->count();
                            if($check_checkedin > 0){
                                $k->user_checkedin = true;
                            } else{
                                $k->user_checkedin = false;
                            }
                        }
                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $eventList;
                    } else{
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "No events found";
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




         /////// GET ORGANIZER PORTFOLIO LIST //////////
    public function organizer_portfolio(){
        $response = array();
        $result = array();
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->usersId) ){

            $this->event_type_filter = $event_type_filter = (isset($data->eventTypeFilter)? $data->eventTypeFilter: '');

                      $eventList = DB::table('event_posts')
                                                    ->where(function($query) {
                                                        if($this->event_type_filter != '')
                                                        {
                                                            $query->where('event_type_id',$this->event_type_filter);
                                                        }
                                                    })
                                                  ->where('users_id', $data->usersId)
                                                  ->where('event_ticket_type', 'Paid')
                                                  ->orderBy('event_post_id', 'DESC')
                                                  ->limit(10)
                                                  ->where('status', 'Active')->get();





            if(sizeof($eventList) > 0){

                        foreach($eventList as $key => $k){


//                            Conditions for premium and one time subscription by nabeel
                            $Eventuser = DB::table('users')->where('users_id', $k->users_id)->first();


                            if($Eventuser->subscription_package_id == 2){
                                $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                                $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                                //user checkin check for event library items upload
                                $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->count();
                                if($check_checkedin > 0){
                                    $k->user_checkedin = true;
                                } else{
                                    $k->user_checkedin = false;
                                }
                                array_push($result, $k);
                            }else
                                if($Eventuser->subscription_package_id == 4){

                                if($k->onetime == 1){
                                    $k->event_start_date = date('d/m/Y', strtotime($k->event_start_date));
                                    $k->event_start_time = date('H:i A', strtotime($k->event_start_time));

                                    //user checkin check for event library items upload
                                    $check_checkedin = DB::table('event_checkins')->where([ ['event_post_id', $k->event_post_id], ['users_id', $data->usersId] ])->count();
                                    if($check_checkedin > 0){
                                        $k->user_checkedin = true;
                                    } else{
                                        $k->user_checkedin = false;
                                    }
                                    array_push($result, $k);

                                }
                                else{
                                    unset($eventList[$key]);
                                }
                            }else{
                                unset($eventList[$key]);
                            }
                        }

                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $result;
                    } else{
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "No events found";
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

        /////// GET MY EVENT LIBRARY ITEMS //////////
    public function get_my_event_library_items(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) && isset($data->eventPostId) ){
             $MyPostedItems = DB::table('event_library_items')->where([ ['event_post_id',$data->eventPostId], ['users_id', $data->usersId] ])->get();
                if(sizeof($MyPostedItems) > 0){
                        foreach($MyPostedItems as $k){
                            if($k->file_type == "Image"){
                                $k->file_name =  config('base_urls.event_library_images_base_url').$k->file_name;
                            }
                             if($k->file_type == "Video"){
                                $k->thumbnail_name =  config('base_urls.event_library_thumbnails_base_url').$k->thumbnail_name;
                                $k->file_name =  config('base_urls.event_library_videos_base_url').$k->file_name;
                             }

                             $postUserDetails  = DB::table('users')->select('users_id','user_name', 'profile_picture')->where('users_id', $k->users_id)->first();
                             $k->post_user_id = $postUserDetails->users_id;
                             $k->post_user_name = $postUserDetails->user_name;

                             if($postUserDetails->profile_picture != ""){
                                $k->profile_picture =  config('base_urls.profile_images_base_url').$postUserDetails->profile_picture;
                             }

                             //total likes on each uploaded item
                             $totalLikes = DB::table('library_item_likes')->where('event_library_item_id', $k->event_library_item_id)->count();
                             $k->total_likes_on_item = $totalLikes;


                             //check logged in user liked this item or not
                             $checkLike = DB::table('library_item_likes')->where([ ['event_library_item_id', $k->event_library_item_id], ['users_id', $data->usersId] ])->count();
                             if($checkLike == 0){
                                 $k->is_liked = false;
                             } else{
                                 $k->is_liked = true;
                             }

                        }

                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $MyPostedItems;
                    } else{
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["message"] = "No items found";
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

        /////// GET OTHER EVENT LIBRARY ITEMS //////////
    public function get_others_event_library_items(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) && isset($data->eventPostId) ){
             $MyPostedItems = DB::table('event_library_items')->where([ ['event_post_id',$data->eventPostId], ['users_id', '!=', $data->usersId] ])->get();
                if(sizeof($MyPostedItems) > 0){
                        foreach($MyPostedItems as $k){
                            if($k->file_type == "Image"){
                                $k->file_name =  config('base_urls.event_library_images_base_url').$k->file_name;
                            }
                             if($k->file_type == "Video"){
                                $k->thumbnail_name =  config('base_urls.event_library_thumbnails_base_url').$k->thumbnail_name;
                                $k->file_name =  config('base_urls.event_library_videos_base_url').$k->file_name;
                             }

                             $postUserDetails  = DB::table('users')->select('users_id','user_name', 'profile_picture')->where('users_id', $k->users_id)->first();
                             $k->post_user_id = $postUserDetails->users_id;
                             $k->post_user_name = $postUserDetails->user_name;

                             if($postUserDetails->profile_picture != ""){
                                $k->profile_picture =  config('base_urls.profile_images_base_url').$postUserDetails->profile_picture;
                             }

                             //total likes on each uploaded item
                             $totalLikes = DB::table('library_item_likes')->where('event_library_item_id', $k->event_library_item_id)->count();
                             $k->total_likes_on_item = $totalLikes;

                             //check logged in user liked this item or not
                             $checkLike = DB::table('library_item_likes')->where([ ['event_library_item_id', $k->event_library_item_id], ['users_id', $data->usersId] ])->count();
                             if($checkLike == 0){
                                 $k->is_liked = false;
                             } else{
                                 $k->is_liked = true;
                             }

                            }

                             $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $MyPostedItems;
                    } else{
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["message"] = "No items found";
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


                 /*** UPLOAD LIBRARY ITEM API ***/
    public function upload_library_item(Request $req){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        $postfix = 'connevents_library';
        $upd_prefix = date('d-m-y');
        $random = rand(1,1000000);

        if($req->fileType == "Image"){
            $file_name = $upd_prefix.$random.$postfix.'.jpeg';
            $file_path_url= config('base_urls.relative_path_event_library_images');
        }
        if($req->fileType == "Video"){
                $thumbnail_name = $upd_prefix.$random.$postfix.'_thumbnail.jpeg';
                $thumbnail_path = config('base_urls.relative_path_event_library_thumbnails');
                $uploadedThumbnail = $req->file('thumbnail')->move($thumbnail_path, $thumbnail_name);

                $file_name = $upd_prefix.$random.$postfix.'.mp4';
                $file_path_url= config('base_urls.relative_path_event_library_videos');
        }


        $uploadedItem = $req->file('item')->move($file_path_url,$file_name);

        if($uploadedItem){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $file_name;
           if($req->fileType == 'Video'){
                $response["thumbnail"] = $thumbnail_name;
           }else{
                $response["thumbnail"] = null;
           }

        } else{
            $response["code"] = 500;
            $response["status"] = "error";
            $response["message"] = "Uploading failed";
            $response["thumbnail"] = null;
        }

        return response()
        ->json(array('status' => $response["status"], 'thumbnail' => $response["thumbnail"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }

        /*** SUBMIT UPLOADED LIBRARY ITEM DETAILS API ***/
public function submit_library_item_details(Request $request){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) && isset($data->eventPostId) && isset($data->fileName) && isset($data->fileType) ){

                $libraryData = array(
                    'event_post_id' => $data->eventPostId,
                    'users_id' => $data->usersId,
                    'file_name' => $data->fileName,
                    'file_type' => $data->fileType,
                    'thumbnail_name' => (isset($data->thumbnailName) ? $data->thumbnailName : ""),
                    'uploaded_datetime'=> date('Y-m-d H:i:s')
                );
                    $insertQuery = DB::table('event_library_items')->insert($libraryData);
                    if($insertQuery){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = 'Item uploaded successfully';
                    }
    } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All fields are needed";
    }

        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }

        /////// LIKE EVENT LIBRARY ITEMS //////////
        public function like_library_item(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->usersId) && isset($data->eventLibraryItemId) ){
                $checkLike = DB::table('library_item_likes')->where([ ['event_library_item_id', $data->eventLibraryItemId], ['users_id', $data->usersId] ])->count();
                if($checkLike == 0){
                    //like case
                        $likeData = array(
                          'event_library_item_id' => $data->eventLibraryItemId,
                          'users_id' => $data->usersId,
                          'datetime' => date('Y-m-d H:i:s')
                        );

                    $likeQuery = DB::table('library_item_likes')->insert($likeData);
                    if($likeQuery){
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = "Item liked successfully";
                    }
                } else{
                    //unlike Case
                    $unlikeQuery = DB::table('library_item_likes')->where([ ['event_library_item_id', $data->eventLibraryItemId], ['users_id', $data->usersId] ])->delete();
                    if($unlikeQuery){
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = "Item unliked successfully";
                    }
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

     /////// GET USER CONNCASH POINTS AND DOLLARS FOR REDEEM//////////
     public function get_user_conncash_points(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId)){
            $user_details = DB::table('users')->select('users_id', 'user_name', 'email', 'total_conncash', 'conncash_dollars')->where([ ['users_id', $data->usersId] ])->first();
            if($user_details){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $user_details;
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



     /////// DELETE LIBRARY ITEM //////////
     public function delete_library_item(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->eventLibraryItemId)){
            $deleteItemLikesQuery = DB::table('library_item_likes')->where('event_library_item_id', $data->eventLibraryItemId)->delete();
            $deleteQuery = DB::table('event_library_items')->where('event_library_item_id', $data->eventLibraryItemId)->delete();
            if($deleteQuery){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = 'Item deleted successfully';
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

             /////// REDEEM CONNCASH POINTS REWARDS //////////
     public function redeem_conncash_points(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));

            if(isset($data->usersId)){
                $user_details = DB::table('users')->where([ ['users_id', $data->usersId] ])->first();
                if($user_details->total_conncash >= 500){
                        $dollarDetails =   DB::table('system_settings')->where('type','dollars_for_five_hundred_conncash')->first();
                        $dollarsForFiveHundredConncash = $dollarDetails->description;

                        $updatedDollars = $user_details->conncash_dollars + $dollarsForFiveHundredConncash;
                        $updatedPoints = $user_details->total_conncash - 500;
                        $updateUserDetailsQuery = DB::table('users')->where('users_id', $data->usersId)->update([ 'total_conncash' => $updatedPoints, 'conncash_dollars' => $updatedDollars]);

                        if($updateUserDetailsQuery){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = "Points redeem successfully";
                        }
                } else{
                        $response["code"] = 200;
                        $response["status"] = "error";
                        $response["message"] = "Conncash points is not enough for redeem";
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



    /////// GET MY TICKETS TRANSACTIONS FOR REFUND LIST //////////
    public function my_purchased_tickets_for_refund(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId)){
            $myTicketsList =  DB::table('transactions')
                                ->select('transactions.*','ticket_unique_number','user_tickets.user_ticket_id','user_tickets.quantity','ticket' ,'users.first_name','users.last_name','users.users_id', 'users.user_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 
                                DB::raw('DATE_FORMAT(event_posts.event_start_date, "%d-%m-%Y") as event_start_date'), 'event_posts.event_start_time')
                                ->join('users', 'users.users_id','=','transactions.users_id')
                                ->join('event_posts', 'event_posts.event_post_id','=','transactions.event_post_id')
                                ->join('user_tickets', 'transactions.transaction_id','=','user_tickets.transaction_id')
                                ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                                ->where('transactions.users_id', $data->usersId)
                                ->whereDate('event_posts.event_end_date','>=' , date('Y-m-d'))
                                ->get();
            
            if(sizeof($myTicketsList) > 0){
                foreach($myTicketsList as $k){
                    $ticket_token =  DB::table('user_tickets')
                        ->select('user_tickets.*','ticket_token.*', 'users.users_id', 'users.user_name','users.first_name','users.last_name', 'event_posts.event_post_id','event_posts.users_id', 'event_posts.title', 
                            DB::raw('DATE_FORMAT(event_posts.event_start_date, "%d-%m-%Y") as event_start_date'), 'event_posts.event_start_time', 'tickets.ticket')
                        ->join('users', 'users.users_id','=','user_tickets.users_id')
                        ->join('ticket_token', 'ticket_token.tt_user_ticket_id','=','user_tickets.user_ticket_id')
                        ->join('event_posts', 'event_posts.event_post_id','=','user_tickets.event_post_id')
                        ->join('tickets', 'tickets.ticket_id','=','user_tickets.ticket_id')
                        ->where('user_tickets.user_ticket_id', $k->user_ticket_id)
                        //->whereDate('event_posts.event_end_date','>=', date('Y-m-d'))
                        //->whereDate('event_posts.event_start_date','>=', date('d-m-Y'))
                        ->get();

                    foreach($ticket_token as $l) {
                        if($l->ticket_unique_number == null){
                            $l->is_checked_in = false;
                        }else {
                            $checkAlreadyCheckedIn = DB::table('event_checkins')->where('ticket_unique_number', $l->tt_token)->count();
                            if ($checkAlreadyCheckedIn == 0) {
                                $l->is_checked_in = false;
                            } else {
                                $l->is_checked_in = true;
                            }
                        }

                        $l->event_start_date = date('Y-m-d H:i:s' , strtotime($l->event_start_date . ' ' . $l->event_start_time));
                    }
                    
                    $k->ticket_details = $ticket_token;

                    $listofIdsQuery = DB::table('user_tickets')->select('user_ticket_id')->where('transaction_id', $k->transaction_id)->get();
                    $listOfTicketIds = Arr::pluck($listofIdsQuery, 'user_ticket_id');
                    $k->list_of_ids = $listOfTicketIds;

                    $eventDetails = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();
                    if($eventDetails){
                        $k->event_title = $eventDetails->title;
                        $k->refundable = $eventDetails->refundable;
                    }
                    $k->purchase_date = date('d-m-Y', strtotime($k->created_at));
                    $k->purchase_time = date('H:i A', strtotime($k->created_at));
                    $eventLocationDetails = DB::table('addresses')->where('event_post_id', $k->event_post_id)->first();
                    $k->location_long = $eventLocationDetails->location_long;
                    $k->location_lat = $eventLocationDetails->location_lat;
                }
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $myTicketsList;
            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No purchased tickets for this user";
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


  /////// GET RECENT INVITED CONTACTS //////////
  public function get_recent_invited_contacts(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
        $recentInvitedContacts = DB::table('recent_invited_contacts')->where('sender_id', $data->usersId)->orderBy('invited_contact_id', 'DESC')->get();
        if(sizeof($recentInvitedContacts) > 0){
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $recentInvitedContacts;
        } else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No recent invites found";
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


  /////// STORE INVITED CONTACTS //////////
 public function store_invited_contacts(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->invitedContacts)){
            foreach($data->invitedContacts as $k){
                $contactData = array(
                    'sender_id' => $k->senderId,
                    'receiver_contact_name' => $k->contactName,
                    'receiver_contact_number' => $k->contactNumber
                );
                DB::table('recent_invited_contacts')->insert($contactData);
            }
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = 'Contacts info stored successfully';
        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');

 }

 /////// ADD INVITE //////////
 public function add_invite(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->senderId) && isset($data->receiverId) && isset($data->eventPostId) ){
            $eventInviteData = array(
                'sender_id' => $k->senderId,
                'receiver_id' => $k->receiverId,
                'event_post_id' => $k->eventPostId,
                'status'=> 'Invited'
            );
            $insertQuery = DB::table('event_invites')->insert($eventInviteData);

            if($insertQuery){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = 'Invite details stored successfully';
            } else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = 'Error in executing query';
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

   /*** GET SPECIFIC EVENT POST DETAILS FOR INVITE ***/
   public function specific_event_details(){
    $data = json_decode(file_get_contents("php://input"));
    $response = array();


    if(isset($data->usersId) && isset($data->eventPostId)){

                //Main filtering query with limit
                $event_post = DB::table('event_posts')
                                  ->select('event_posts.*','addresses.*')
                                ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                ->where('event_posts.event_post_id', $data->eventPostId)
                                ->where('event_posts.status', 'Active')
                               ->first();

        if ($event_post) {
                 //appending images urls fields in response
                 $image_base_url = config('base_urls.event_image_base_url');
                 if(!empty( $event_post->first_image)){
                     $event_post->first_image= $image_base_url.$event_post->first_image;
                 }
                  if(!empty( $event_post->second_image)){
                     $event_post->second_image = $image_base_url.$event_post->second_image;
                 }
                  if(!empty( $event_post->third_image)){
                     $event_post->third_image = $image_base_url.$event_post->third_image;
                 }

                //updating Videos thumbnail urls fields in response
                $video_thumbnail_base_url = config('base_urls.event_video_thumbnails_base_url');
                        if(!empty( $event_post->first_video_thumbnail)){
                            $event_post->first_video_thumbnail= $video_thumbnail_base_url.$event_post->first_video_thumbnail;
                        }
                        if(!empty( $event_post->second_video_thumbnail)){
                            $event_post->second_video_thumbnail = $video_thumbnail_base_url.$event_post->second_video_thumbnail;
                        }
                        if(!empty( $event_post->third_video_thumbnail)){
                            $event_post->third_video_thumbnail = $video_thumbnail_base_url.$event_post->third_video_thumbnail;
                        }

                    //appending Videos urls fields in response
                    $video_base_url = config('event_video_base_url');

                     if(!empty( $event_post->first_video)){
                         $event_post->first_video= $video_base_url.$event_post->first_video;
                     }
                     if(!empty( $event_post->second_video)){
                         $event_post->second_video = $video_base_url.$event_post->second_video;
                     }
                     if(!empty( $event_post->third_video)){
                         $event_post->third_video = $video_base_url.$event_post->third_video;
                     }

                     //time conversion for minutes/hours/days ago
                        $event_post->time_ago = Helper::time_elapsed_string($event_post->created_at);

                      // appending tickets quantity info
                             $early_bird_query = DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket','Early bird')->where('status','Active')->first();
                                if($early_bird_query){
                                    $event_post->early_bird_available = $early_bird_query->quantity;
                                }

                             $regular_query = DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket','Regular')->where('status','Active')->first();
                                if($regular_query){
                                    $event_post->regular_available = $regular_query->quantity;
                                }

                             $vip_query = DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket','VIP')->where('status','Active')->first();
                                if($vip_query){
                                    $event_post->vip_available = $vip_query->quantity;
                                }

            $skip_query = DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket','Skipping Line')->where('status','Active')->first();
            if($skip_query){
                $event_post->skipping_line_available = $skip_query->quantity;
            }


            $val1 = (isset($event_post->early_bird_available) ? $event_post->early_bird_available : 0);
            $val2 = (isset($event_post->regular_available) ? $event_post->regular_available : 0);
            $val3 = (isset($event_post->vip_available) ? $event_post->vip_available : 0);
            $val4 = (isset($event_post->skipping_line_available) ? $event_post->skipping_line_available : 0);

            $event_post->total_available_ticket_quantity = $val1 + $val2 + $val3+ $val4;

                       //appending Category data from categories in response array after success
                       $category =   DB::table('categories')->where('category_id',$event_post->category_id)->where('status',"Active")->first();
                           if($category){
                               $event_post->category = $category;
                           }

                        //appending Event Type data from Event_type in response array after success
                        $event_type =   DB::table('event_type')->where('event_type_id',$event_post->event_type_id)->where('status',"Active")->first();
                            if($event_type){
                                $event_post->event_type = $event_type;
                            }

                      //appending Dress Code data from dress_code in response array after success
                      $dress_code =   DB::table('dress_code')->where('dress_code_id',$event_post->dress_code_id)->where('status',"Active")->first();
                          if($dress_code){
                              $event_post->dress_code = $dress_code;
                          }

                        //fetching tags from tags table of each event post
                        $tags =   DB::table('event_tags')
                                            ->select('event_tags.*','tags.*')
                                                ->join('tags','tags.tag_id','=','event_tags.tag_id')
                                                ->where('event_tags.event_post_id',$event_post->event_post_id)
                                                ->where('event_tags.status','Active')->get();
                        if($tags){
                            $event_post->event_tags = $tags;
                        }

                  //fetching tickets from tickets table of each event post
                 /*    $tickets =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get();
                        if($tickets){
                            $tickets_array = array();
                            foreach($tickets as $key => $k){
                            $event_post->event_tickets[$key][$tickets[$key]->ticket] = $tickets[$key];


                            }

                        }   */
            //fetching tickets from tickets table of each event post
            $ticket1 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Early Bird')->where('status',"Active")->first();
            $ticket2 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Regular')->where('status',"Active")->first();
            $ticket3 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'VIP')->where('status',"Active")->first();
            $ticket4 =   DB::table('tickets')->where('event_post_id', $event_post->event_post_id)->where('ticket', 'Skipping Line')->where('status',"Active")->first();

            if($ticket1){
                $event_post->Early_bird = $ticket1;
            }
            if($ticket2){
                $event_post->Regular = $ticket2;
            }
            if($ticket3){
                $event_post->VIP = $ticket3;
            } if($ticket4){
                $event_post->skipping_line = $ticket4;
            }


                 //fetching address from address table of each event post
                 $address =   DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->where('status',"Active")->get()->first();
                    if($address){
                        $event_post->event_address = $address;
                    }

                    //appending liked event post hint
                $checkLike = DB::table('event_likes')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->usersId)->count();
                    if($checkLike > 0){
                        $event_post->liked = 'true';
                    } else{
                        $event_post->liked = 'false';
                    }

                    //appending favourite post hint
                $isFavouriteQuery = DB::table('event_favourites')->where('event_post_id', $event_post->event_post_id)->where('users_id', $data->usersId)->count();
                if($isFavouriteQuery > 0){
                    $event_post->isFavourite = 'true';
                } else{
                    $event_post->isFavourite = 'false';
                }

                    //appending total likes on event
                    $total_likes =  DB::table('event_posts')->where('event_post_id', $event_post->event_post_id)->first();
                        if($total_likes){
                            $event_post->total_likes = $total_likes->total_likes;
                        }

                          //appending event distance in miles from user current location
                        if(isset($data->userLat) && isset($data->userLong)){
                                $eventCordinates = DB::table('addresses')->where('event_post_id', $event_post->event_post_id)->first();
                                if($eventCordinates){
                                    $distance_miles_calc = Helper::calculate_miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $event_post->distance_miles = $distance_miles_calc;
                                        $miles_calc = Helper::miles($data->userLat, $data->userLong, $eventCordinates->location_lat, $eventCordinates->location_long, "M");
                                        $event_post->miles = $miles_calc;
                                }
                        }

                        $event_post->sales_end_datetime = $event_post->sales_end_date.' '.$event_post->sales_end_time;
                          // Date conversion in 12 hrs format in response
                        $event_post->event_start_date = date("M d, Y", strtotime($event_post->event_start_date));
                        $event_post->event_end_date = date("M d, Y", strtotime($event_post->event_end_date));
                        $event_post->sales_start_date = date("M d, Y", strtotime($event_post->sales_start_date));
                        $event_post->sales_end_date = date("M d, Y", strtotime($event_post->sales_end_date));

                          // time conversion in 12 hrs format in response
                        $event_post->event_start_time = date("g:iA", strtotime($event_post->event_start_time));
                        $event_post->event_end_time = date("g:iA", strtotime($event_post->event_end_time));
                        $event_post->sales_start_time = date("g:iA", strtotime($event_post->sales_start_time));
                        $event_post->sales_end_time = date("g:iA", strtotime($event_post->sales_end_time));

                        //Total comments count on Event Post
                          $totalPostComments = DB::table('event_comments')->where([ ['event_post_id', $event_post->event_post_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                        $event_post->total_post_comments = $totalPostComments;

                        // Appending dresscode color code for thumbnail bar color ease in event details
                        $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $event_post->dress_code_id)->first();
                        $event_post->dress_code_color_code = $dressCodeColor->dress_code_color;

                        //Appeding organizer details
                        $organizerDetails = DB::table('users')->where('users_id', $event_post->users_id)->first();
                        $event_post->organizer_users_id = $organizerDetails->users_id;
                        $event_post->organizer_user_name = $organizerDetails->user_name;

                        if($organizerDetails->profile_picture != ""){
                            $event_post->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                        }else{
                            $event_post->organizer_profile_picture =  "";
                        }


            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $event_post;


        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Event Post does not exists.";
        }

    } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All fields are needed";
    }

return response()
->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
->header('Content-Type', 'application/json');
}




 /////// GET ORGANIZER WITHDRAW ABLE EARNING //////////
 public function get_organizer_earning(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));
    $mainDataset = new stdClass();

    if(isset($data->usersId) ){

        $paypal_email = DB::table('users')->where([ ['users_id' , $data->usersId]])->pluck("paypal_email");


        $withdrawableEarning = DB::table('earnings')->select('earnings.*', 'transactions.*')
                                                        ->join('transactions', 'transactions.transaction_id' , '=' , 'earnings.transaction_id')
                                                        ->where([ ['earnings.users_id', $data->usersId],
                                                                    ['earnings.status', 'Earned'] ])
                                                        ->sum('total_amount');


        $withdraw = DB::table('bank_accounts')->where([ ['users_id' , $data->usersId], ['status', 'Successful']])->sum('withdraw_amount');


        $checkPendingAmount = DB::table('bank_accounts')->where([ ['users_id' , $data->usersId], ['status', 'Pending']])->get();
            if(sizeof($checkPendingAmount) > 0){
                    $mainDataset->pending_flag = true;
                foreach($checkPendingAmount as $k){
                    $withdrawableEarning = $withdrawableEarning - $k->withdraw_amount;
                }
            }else{
                $mainDataset->pending_flag = false;
            }




            $roundedEarning = round($withdrawableEarning, 0);

            $pendingWithdraw = DB::table('bank_accounts')->where([ ['users_id' , $data->usersId], ['status', 'Pending']])->sum('withdraw_amount');
            $roundedPending = round($pendingWithdraw, 0);


            $remaining = $roundedEarning - $withdraw;

            $mainDataset->earning = (int) $remaining;
            $mainDataset->pending_withdraw = (int) $roundedPending;
            $mainDataset->paypal_email =  $paypal_email[0];
            $mainDataset->withdraw = (int) $withdraw;
            $mainDataset->total = (int) $roundedEarning;

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $mainDataset;


        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }

/*
 /////// WITHDRAW EARNING //////////
 public function withdraw_earning(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->earning) && isset($data->stripeWithdrawEmail) ){
            $withdrawableEarning = DB::table('earnings')->select('earnings.*', 'transactions.*')
                                                        ->join('transactions', 'transactions.transaction_id' , '=' , 'earnings.transaction_id')
                                                        ->where([ ['earnings.users_id', $data->usersId],
                                                                    ['earnings.status', 'Earned'] ])
                                                        ->sum('total_amount');

                    $withdraw_data = array(
                        'users_id' => $data->usersId,
                        'amount' => $withdrawableEarning,
                        'stripe_withdraw_email' => $data->stripeWithdrawEmail,
                        'date_time' => date('Y-m-d H:i:s'),
                    );
                    $withdrawQuery =  DB::table('bank_accounts')->insert($withdraw_data);
                    if($withdrawQuery){
                             $updateEarningStatusQuery = DB::table('earnings')
                                                                ->where([ ['users_id', $data->usersId],
                                                                            ['status', 'Earned'] ])
                                                                ->update([ 'status' => 'Withdrawn'] );

                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = 'Withdrawn successfully';
                            }
                    //<<<<<  DATA TO BE IN STRIPE SUCCESS CASE  <<<<<<<//


        } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 } */


 /////// Follow/Unfollow user //////////
 public function follow_user(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

//    Uncomment this things on new app upload

    if(isset($data->usersId) && isset($data->followingToUser)
//        && isset($data->eventPostId)
    )
    {
         $userDetails = DB::table('users')->where('users_id', $data->usersId)->first();
           $checkFollow = DB::table('followers')->where([ ['following_to_user' , $data->followingToUser] , ['followed_by_user', $data->usersId] ])->count();
            if($checkFollow == 0){
                    $followData = array(
                        'following_to_user' => $data->followingToUser,
                        'followed_by_user' => $data->usersId
                    );
                    $query = DB::table('followers')->insert($followData);
                    if($query) {


//                        send notification
                        $senderDetails=  DB::table('users')->where('users_id',$data->usersId)->first();
                        $receiverDetails= DB::table('users')->where('users_id',$data->followingToUser)->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Started Following You', 'FollowYou');

                            if($notificationResponse){
                                $notificationData = array(
                                    'sender_users_id' => $senderDetails-> users_id,
                                    'receiver_users_id' => $receiverDetails-> users_id,
                                    'notification_type' => 'FollowYou',
                                    'message' => $senderDetails->user_name.' Started Following You',
//                                    'event_post_id' => $data->eventPostId,
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'status' => 'Unread'
                                );
//                                $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                            }
                        }






                         //following flag
                         $checkFollowing = DB::table('followers')->where([ ['following_to_user',$data->followingToUser] ,['followed_by_user' ,$data->usersId] ])->first();
                         if($checkFollowing){
                             $isFollowing = true;
                         } else{
                             $isFollowing = false;
                         }

                         //totalFollowers
                         $totalFollowers = DB::table('followers')->where('following_to_user', $data->followingToUser)->count();
                         $total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                        $response["is_following"] = $isFollowing;
                        $response["total_followers"] = $total_followers;
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = 'Followed successfully';
                    }
            }else{
                $query = DB::table('followers')->where([ ['following_to_user' , $data->followingToUser] , ['followed_by_user', $data->usersId] ])->delete();
                if($query){
                    //following flag
                    $checkFollowing = DB::table('followers')->where([ ['following_to_user',$data->followingToUser] ,['followed_by_user' ,$data->usersId] ])->first();
                    if($checkFollowing){
                        $isFollowing = true;
                    } else{
                        $isFollowing = false;
                    }

                    //totalFollowers
                    $totalFollowers = DB::table('followers')->where('following_to_user', $data->followingToUser)->count();
                    $total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                    $response["is_following"] = $isFollowing;
                    $response["total_followers"] = $total_followers;
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Unfollowed successfully';
                }
            }
        } else{
        $response["is_following"] = false;
        $response["total_followers"] = '0';
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('is_following' => $response["is_following"], 'total_followers' => $response["total_followers"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }

 /////// GET FOLLOWERS COUNT  //////////
 public function get_followers_count(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
                         //totalFollowers
                         $totalFollowers = DB::table('followers')->where('following_to_user', $data->usersId)->count();
                         $total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);


        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $total_followers;

        } else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }


 /////// GET ORGANIZER FOLLOWERS LIST   //////////
 public function get_organizer_followers_list(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){
                         //totalFollowers
                         $totalFollowers = DB::table('followers')->where('following_to_user', $data->usersId)->count();
                         $total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                         $followersList = DB::table('followers')->where('following_to_user', $data->usersId)->get();
                         if(sizeof($followersList) > 0 ){
                              foreach($followersList as $k){
                                    $followerDetail = DB::table('users')->where('users_id', $k->followed_by_user)->first();

                                    $k->user_name = $followerDetail->user_name;
                                    $k->email = $followerDetail->email;
                                    if($followerDetail->profile_picture != ""){
                                        $k->profile_picture = config('base_urls.profile_images_base_url').$followerDetail->profile_picture;
                                    }else{
                                        $k->profile_picture = "";
                                    }
                              }


                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $followersList;
                            $response["total_followers"] = $total_followers;

                         }else{
                            $response["code"] = 404;
                            $response["total_followers"] = $total_followers;
                            $response["status"] = "error";
                            $response["message"] = "No Followers Found";
                         }
        } else{
            $response["code"] = 404;
            $response["total_followers"] = '0';
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('status' => $response["status"],'total_followers' => $response["total_followers"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }

   /////// GET ORGANIZER EVENT LIBRARY ITEMS //////////
   public function get_organizer_event_library_items(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->eventPostId) ){
         $MyPostedItems = DB::table('event_library_items')->where('event_post_id',$data->eventPostId)->get();
            if(sizeof($MyPostedItems) > 0){
                    foreach($MyPostedItems as $k){
                        if($k->file_type == "Image"){
                            $k->file_name =  config('base_urls.event_library_images_base_url').$k->file_name;
                        }
                         if($k->file_type == "Video"){
                            $k->thumbnail_name =  config('base_urls.event_library_thumbnails_base_url').$k->thumbnail_name;
                            $k->file_name =  config('base_urls.event_library_videos_base_url').$k->file_name;
                         }

                         $postUserDetails  = DB::table('users')->select('users_id','user_name', 'profile_picture')->where('users_id', $k->users_id)->first();
                         $k->post_user_id = $postUserDetails->users_id;
                         $k->post_user_name = $postUserDetails->user_name;

                         if($postUserDetails->profile_picture != ""){
                            $k->profile_picture =  config('base_urls.profile_images_base_url').$postUserDetails->profile_picture;
                         }

                         //total likes on each uploaded item
                         $totalLikes = DB::table('library_item_likes')->where('event_library_item_id', $k->event_library_item_id)->count();
                         $k->total_likes_on_item = $totalLikes;

                            //check logged in user liked this item or not
                            $checkLike = DB::table('library_item_likes')->where([ ['event_library_item_id', $k->event_library_item_id], ['users_id', $data->usersId] ])->count();
                            if($checkLike == 0){
                                $k->is_liked = false;
                            } else{
                                $k->is_liked = true;
                            }

                        }

                         $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $MyPostedItems;
                } else{
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "No items found";
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




 /////// EXPORT ORGANIZER FOLLOWERS EMAILS   //////////
 public function export_organizer_followers_emails(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    $emailArray = array();

    if(isset($data->usersId)){

                         //totalFollowers
                         $totalFollowers = DB::table('followers')->where('following_to_user', $data->usersId)->count();
                         $total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);

                         $followersList = DB::table('followers')->where('following_to_user', $data->usersId)->get();
                         if(sizeof($followersList) > 0 ){
                              foreach($followersList as $k){
                                    $followerDetail = DB::table('users')->where('users_id', $k->followed_by_user)->first();
                                       $email = $followerDetail->email;
                                    array_push($emailArray, $email);
                              }

                              if(sizeof($emailArray) > 0){
                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = $emailArray;
                              }else{
                                $response["code"] = 404;
                                $response["status"] = "error";
                                $response["message"] = "No Followers Found";
                             }

                         }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "No Followers Found";
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




 /////// GET UNREAD MESSAGES COUNT   //////////
 public function get_unread_messages_count(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId)){

            $totalUnreadMessagesCount = DB::table('chat_messages')->where([ ['receiver_id', $data->usersId], ['status', 'Unread'] ])->count();

            if($totalUnreadMessagesCount == 0){

                $response["code"] = 200;
                $response["status"] = "error";
                $response["data"] = $totalUnreadMessagesCount;

            }else{
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $totalUnreadMessagesCount;
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



 /////// STORE WITHDRAW DETAILS   //////////
 public function store_withdraw_details(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->withdrawType) && isset($data->withdrawAmount) && isset($data->accountNo) && isset($data->accountHolderName) && isset($data->bankName)){
            $withdrawDetails = array(
                'users_id'=> $data->usersId,
                'withdraw_type'=> $data->withdrawType,
                'withdraw_amount'=> $data->withdrawAmount,
                'account_no'=> $data->accountNo,
                'account_holder_name'=> $data->accountHolderName,
                'bank_name'=> $data->bankName,
                'created_at'=> date('Y-m-d H:i:s'),
                'status'=> ($data->withdrawType == 'BankAccount' ? 'Pending' : 'Successful')
            );
            $insertQuery = DB::table('bank_accounts')->insert($withdrawDetails);
            if($insertQuery){
                $userData = DB::table('users')->where('users_id', $data->usersId)->first();
                $updatedBalance = (int)$userData->conncash_dollars - (int)$data->withdrawAmount;

                $updateQuery = DB::table('users')->where('users_id', $data->usersId)->update(['conncash_dollars' => $updatedBalance]);
                if($updateQuery){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Saved successfully';
                }else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = 'Error in updating user balance';
                }
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

    
    /////// GET BANK WITHDRAW DETAILS   //////////
    public function get_bank_accounts(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId)){
            $accountsdata = DB::table('bank_accounts')->where('users_id', $data->usersId)->get();
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $accountsdata;
        } else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All Fields are needed";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /////// GET BANK WITHDRAW DETAILS   //////////

 /////// GET PAYPAL CONFIG   //////////
 public function get_paypal_config(){

            $domain = DB::table('system_settings')->where('type','domain')->first();
            $clientId = DB::table('system_settings')->where('type','client_id')->first();
            $secret = DB::table('system_settings')->where('type','secret')->first();

            $customObj = new stdClass();
            $customObj->domain = $domain->description;
            $customObj->client_id = $clientId->description;
            $customObj->secret = $secret->description;

                   $response["code"] = 200;
                   $response["status"] = "success";
                   $response["data"] = $customObj;


    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }



 /////// STORE PAYPAL TRANSACTION DETAILS  //////////
 public function store_paypal_transaction_details(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->withdrawType) ){
        $accHolderName = $data->accHolderName;

        DB::table('users')->where('users_id', $data->usersId)->update(array('paypal_email' => $data->paypal_email));

        $insertWithdrawQuery = DB::table('bank_accounts')->insertGetId([
                                'users_id' => $data->usersId,
                                'withdraw_type' => 'Paypal',
                                'withdraw_amount' => $data->amount,
                                'account_no' => $data->payout_batch_id,
                                'account_holder_name' => $accHolderName,
                                'bank_name' => 'Paypal',
                                'created_at' => date('Y-m-d H:i:s'),
                                'status' => 'Successful'
                            ]);

                if($insertWithdrawQuery){
                    DB::table('paypal_withdraw_details')->insert([
                                    'withdraw_id' => $insertWithdrawQuery,
                                    'paypal_status' => "Transfered",
                                    'payee_email' => $data->paypal_email,
                                    'amount' => $data->amount,
                                    'payout_batch_id' => $data->payout_batch_id,
                                    'batch_status' => $data->batch_status,
                                    'sender_batch_id' => $data->sender_batch_id,
                                    'href' => $data->href,
                                    'transaction_datetime' => date('Y-m-d H:i:s')
                                ]);

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Transaction Saved Successfully';
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

 /***  Check social media status ***/

 public function check_social_login(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));
    $check_social_login = DB::table('system_settings')->where('type','social_login_status')->first();
    if($check_social_login){
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = array(
            'login_status' => $check_social_login->description
        );
    } else{
        $response["code"] = 500;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    }
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }
       /***  Check social media status ***/







}
