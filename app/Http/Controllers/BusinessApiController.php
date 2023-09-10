<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
//use File;
use Illuminate\Support\Facades\File;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\StripeClient;
use DateTime;
use Illuminate\Support\Arr;
use \stdClass;






class BusinessApiController extends Controller {
    public $successStatus = 200;
    public $errorStatus = 401;


 /////// CREATE BUSINESS//////////
 public function create_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));


   // if(isset($data->title) && isset($data->businessLogo) && isset($data->usersId) && isset($data->hyperlink) && isset($data->discount) && isset($data->address) && isset($data->city) && isset($data->state) && isset($data->description) && isset($data->zip) && isset($data->businessLat) && isset($data->businessLong) ){
      /*    $userDetails = DB::table('users')->where('users_id', $data->usersId)->first();
            if($userDetails->subscription_package_id == '3'){ */
                $businessData = array(
                    'title' => $data->title,
                    'socialLink' => (isset($data->socialLink) ? $data->socialLink : ''),

                    'business_logo' => '',
                    'users_id' => $data->usersId,
                    'first_image' => '',
                    'second_image' => '',
                    'third_image' => '',
                    'fourth_image' => '',
                    'fifth_image' => '',
                    'sixth_image' => '',
                    'first_video_thumbnail' => '',
                    'second_video_thumbnail' => '',
                    'third_video_thumbnail' => '',
                    'first_video' => (isset($data->firstVideo) ? $data->firstVideo : ''),
                    'second_video' => (isset($data->secondVideo) ? $data->secondVideo : ''),
                    'third_video' => (isset($data->thirdVideo) ? $data->thirdVideo : ''),
                    'hyperlink' => $data->hyperlink,
                    'discount' => $data->discount,
                    // 'business_identification_no' => (isset($data->businessIdentificationNo) ? $data->businessIdentificationNo : ''),
                    'description' => $data->description,
                    'address' => $data->address,
                    'city' =>$data->city,
                    'state' => $data->state,
                    'zip' => $data->zip,
                    'business_lat' => $data->businessLat,
                    'business_long' => $data->businessLong,
                    'business_type' => $data->businessType,
                    'total_likes' => '0',
                    'verified' => 'No',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'RequestedVerification',
                    'active_status' => 'Active'
               );

               $lastInsertedId = DB::table('businesses')->insertGetId($businessData);
               if($lastInsertedId){

                        //upload business logo
                            $upload_business = array(
                                'business_logo' => (isset($data->businessLogo) ? $data->businessLogo: ''),
                            );
                            $postfix = 'business_logo';
                            $upd_prefix = date('d-m-y');
                            $random = rand(1,1000000);
                            $image_path_url= config('base_urls.relative_path_business_logo');
                                $uploaded_image = Helper::decode_image($upload_business['business_logo'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('business_logo' => $uploaded_image));

                            //upload Images
                                $upload_images = array(
                                    'first_image' => (isset($data->firstImageBasecode) ? $data->firstImageBasecode: ''),
                                    'second_image' => (isset($data->secondImageBasecode) ? $data->secondImageBasecode: ''),
                                    'third_image' => (isset($data->thirdImageBasecode) ? $data->thirdImageBasecode: ''),
                                    'fourth_image' => (isset($data->fourthImageBasecode) ? $data->fourthImageBasecode: ''),
                                    'fifth_image' => (isset($data->fifthImageBasecode) ? $data->fifthImageBasecode: ''),
                                    'sixth_image' => (isset($data->sixthImageBasecode) ? $data->sixthImageBasecode: ''),
                                );
                            $imagesCount = 0;
                                if(isset($data->firstImageBasecode)){  $imagesCount++; }
                                if(isset($data->secondImageBasecode)){  $imagesCount++; }
                                if(isset($data->thirdImageBasecode)){  $imagesCount++; }
                                if(isset($data->fourthImageBasecode)){  $imagesCount++; }
                                if(isset($data->fifthImageBasecode)){  $imagesCount++; }
                                if(isset($data->sixthImageBasecode)){  $imagesCount++; }


                            for($i=0;$i<$imagesCount;$i++){
                                    $postfix = 'business';
                                    $upd_prefix = date('d-m-y');
                                    $random = rand(1,1000000);
                                    $image_path_url= config('base_urls.relative_path_business_images');

                                    if($i == 0){
                                        $uploaded_image = Helper::decode_image($upload_images['first_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('first_image' => $uploaded_image));
                                    }
                                        if($i == 1){
                                        $uploaded_image = Helper::decode_image($upload_images['second_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('second_image' => $uploaded_image));
                                        }
                                    if ($i == 2){
                                        $uploaded_image = Helper::decode_image($upload_images['third_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('third_image' => $uploaded_image));
                                    }
                                    if ($i == 3){
                                        $uploaded_image = Helper::decode_image($upload_images['fourth_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('fourth_image' => $uploaded_image));
                                    }
                                    if ($i == 4){
                                        $uploaded_image = Helper::decode_image($upload_images['fifth_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('fifth_image' => $uploaded_image));
                                    }
                                    if ($i == 5){
                                        $uploaded_image = Helper::decode_image($upload_images['sixth_image'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('sixth_image' => $uploaded_image));
                                    }
                                }

                                    //Upload thumbnails
                                    $upload_thumbnails = array(
                                        'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail: ''),
                                        'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail: ''),
                                        'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail: ''),
                                    );

                                    $thumbnailCount = 0;
                                    if(isset($data->firstVideoThumbnail)){  $thumbnailCount++; }
                                    if(isset($data->secondVideoThumbnail)){  $thumbnailCount++; }
                                    if(isset($data->thirdVideoThumbnail)){  $thumbnailCount++; }

                                    for($i=0;$i<$thumbnailCount;$i++){
                                           $postfix = 'conn_events';
                                           $upd_prefix = date('d-m-y');
                                           $random = rand(1,1000000);
                                           $image_path_url= config('base_urls.relative_path_business_video_thumbnails');

                                           if($i == 0){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['first_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('first_video_thumbnail' => $uploaded_image));
                                           }
                                            if($i == 1){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['second_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('second_video_thumbnail' => $uploaded_image));
                                            }
                                           if ($i == 2){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['third_video_thumbnail'], $lastInsertedId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $lastInsertedId)->update(array('third_video_thumbnail' => $uploaded_image));
                                           }
                                       }
                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = 'Business Added successfully';
               }
    /*         }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "You have to purchase Business premium subscription for posting businesses";
            } */

      /*   } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    } */
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }



 /////// EDIT BUSINESS//////////
 public function edit_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

   // if(isset($data->title) && isset($data->businessLogo) && isset($data->usersId) && isset($data->hyperlink) && isset($data->discount) && isset($data->address) && isset($data->city) && isset($data->state) && isset($data->description) && isset($data->zip) && isset($data->businessLat) && isset($data->businessLong) ){
     $old_data = DB::table('businesses')->where('business_id', $data->businessId)->first();

     $businessData = array(
                    'title' => $data->title,
         'socialLink' => (isset($data->socialLink) ? $data->socialLink : ''),

         'business_logo' => (isset($data->businessLogo) ? $data->businessLogo : $old_data->business_logo),
                    'users_id' => $data->usersId,
                        'first_image' => (isset($data->firstImage) ? $data->firstImage : $old_data->first_image),
                        'second_image' => (isset($data->secondImage) ? $data->secondImage : $old_data->second_image),
                        'third_image' => (isset($data->thirdImage) ? $data->thirdImage : $old_data->third_image),
                        'fourth_image' => (isset($data->fourthImage) ? $data->fourthImage : $old_data->fourth_image),
                        'fifth_image' => (isset($data->fifthImage) ? $data->fifthImage : $old_data->fifth_image),
                        'sixth_image' => (isset($data->sixthImage) ? $data->sixthImage : $old_data->sixth_image),
                        'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail : $old_data->first_video_thumbnail),
                        'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail : $old_data->second_video_thumbnail),
                        'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail : $old_data->third_video_thumbnail),
                        'first_video' => (isset($data->firstVideo) ? $data->firstVideo : $old_data->first_video),
                        'second_video' => (isset($data->secondVideo) ? $data->secondVideo: $old_data->second_video),
                        'third_video' => (isset($data->thirdVideo) ? $data->thirdVideo : $old_data->third_video),
                    'hyperlink' => $data->hyperlink,
                    'discount' => $data->discount,
                    // 'business_identification_no' => (isset($data->businessIdentificationNo) ? $data->businessIdentificationNo : ''),
                    'description' => $data->description,
                    'address' => $data->address,
                    'city' =>$data->city,
                    'state' => $data->state,
                    'zip' => $data->zip,
                    'business_lat' => $data->businessLat,
                    'business_long' => $data->businessLong,
                    'business_type' => $data->businessType,
                    
               );

               $updateQuery = DB::table('businesses')->where('business_id', $data->businessId)->update($businessData);

                     if(isset($data->businessLogo)){
                                //upload business logo
                                $upload_business = array(
                                    'business_logo' => (isset($data->businessLogo) ? $data->businessLogo: ''),
                                );

                                $postfix = 'business_logo';
                                $upd_prefix = date('d-m-y');
                                $random = rand(1,1000000);
                                $image_path_url= config('base_urls.relative_path_business_logo');
                                    $uploaded_image = Helper::decode_image($upload_business['business_logo'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                    $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('business_logo' => $uploaded_image));

                     }

                            //upload Images
                                $upload_images = array(
                                    'first_image' => (isset($data->firstImageBasecode) ? $data->firstImageBasecode: ''),
                                    'second_image' => (isset($data->secondImageBasecode) ? $data->secondImageBasecode: ''),
                                    'third_image' => (isset($data->thirdImageBasecode) ? $data->thirdImageBasecode: ''),
                                    'fourth_image' => (isset($data->fourthImageBasecode) ? $data->fourthImageBasecode: ''),
                                    'fifth_image' => (isset($data->fifthImageBasecode) ? $data->fifthImageBasecode: ''),
                                    'sixth_image' => (isset($data->sixthImageBasecode) ? $data->sixthImageBasecode: ''),
                                );
                            $imagesCount = 0;
                                if(isset($data->firstImageBasecode)){  $imagesCount++; }
                                if(isset($data->secondImageBasecode)){  $imagesCount++; }
                                if(isset($data->thirdImageBasecode)){  $imagesCount++; }
                                if(isset($data->fourthImageBasecode)){  $imagesCount++; }
                                if(isset($data->fifthImageBasecode)){  $imagesCount++; }
                                if(isset($data->sixthImageBasecode)){  $imagesCount++; }


                            for($i=0;$i<$imagesCount;$i++){
                                    $postfix = 'business';
                                    $upd_prefix = date('d-m-y');
                                    $random = rand(1,1000000);
                                    $image_path_url= config('base_urls.relative_path_business_images');

                                    if($i == 0){
                                        $uploaded_image = Helper::decode_image($upload_images['first_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('first_image' => $uploaded_image));
                                    }
                                        if($i == 1){
                                        $uploaded_image = Helper::decode_image($upload_images['second_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('second_image' => $uploaded_image));
                                        }
                                    if ($i == 2){
                                        $uploaded_image = Helper::decode_image($upload_images['third_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('third_image' => $uploaded_image));
                                    }
                                    if ($i == 3){
                                        $uploaded_image = Helper::decode_image($upload_images['fourth_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('fourth_image' => $uploaded_image));
                                    }
                                    if ($i == 4){
                                        $uploaded_image = Helper::decode_image($upload_images['fifth_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('fifth_image' => $uploaded_image));
                                    }
                                    if ($i == 5){
                                        $uploaded_image = Helper::decode_image($upload_images['sixth_image'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                        $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('sixth_image' => $uploaded_image));
                                    }
                                }

                                    //Upload thumbnails
                                    $upload_thumbnails = array(
                                        'first_video_thumbnail' => (isset($data->firstVideoThumbnail) ? $data->firstVideoThumbnail: ''),
                                        'second_video_thumbnail' => (isset($data->secondVideoThumbnail) ? $data->secondVideoThumbnail: ''),
                                        'third_video_thumbnail' => (isset($data->thirdVideoThumbnail) ? $data->thirdVideoThumbnail: ''),
                                    );

                                    $thumbnailCount = 0;
                                    if(isset($data->firstVideoThumbnail)){  $thumbnailCount++; }
                                    if(isset($data->secondVideoThumbnail)){  $thumbnailCount++; }
                                    if(isset($data->thirdVideoThumbnail)){  $thumbnailCount++; }

                                    for($i=0;$i<$thumbnailCount;$i++){
                                           $postfix = 'conn_events';
                                           $upd_prefix = date('d-m-y');
                                           $random = rand(1,1000000);
                                           $image_path_url= config('base_urls.relative_path_business_video_thumbnails');

                                           if($i == 0){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['first_video_thumbnail'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('first_video_thumbnail' => $uploaded_image));
                                           }
                                            if($i == 1){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['second_video_thumbnail'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('second_video_thumbnail' => $uploaded_image));
                                            }
                                           if ($i == 2){
                                               $uploaded_image = Helper::decode_image($upload_thumbnails['third_video_thumbnail'], $data->businessId, $image_path_url, $upd_prefix, $random, $postfix);
                                               $query = DB::table('businesses')->where('business_id', $data->businessId)->update(array('third_video_thumbnail' => $uploaded_image));
                                           }
//

                                       }

                                $response["code"] = 200;
                                $response["status"] = "success";
                                $response["data"] = 'Business Updated successfully';


      /*   } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are needed";
    } */
    return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }



 public function delete_business(Request $req)
 {

     $businessId = $req->businessId;

     try {

        DB::table('businesses')->where('business_id', $businessId)->update(['status' => 'Deleted']);

        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = 'Business Deleted successfully';

     } catch (Exception $e) {

        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Something is wrong, business not deleted. MESSAGE: " . $e;

     }


     return response()
                ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');


 }

   /*** CREATED BUSINESS DELETE IMAGE API ***/
   public function created_business_delete_image(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    $image_path_url= config('base_urls.relative_path_business_images');

    if(isset($data->fileName) && isset($data->businessId)){
       $result =  File::delete($image_path_url.$data->fileName);
       if($result){
           $old_data = DB::table('businesses')->where('business_id',$data->businessId)->first();
                 if($old_data->first_image == $data->fileName){
                    DB::table('businesses')->where('business_id',$data->businessId)->update(array('first_image' => ''));
                  }
                  if($old_data->second_image == $data->fileName){
                    DB::table('businesses')->where('business_id',$data->businessId)->update(array('second_image' => ''));
                  }
                  if($old_data->third_image == $data->fileName){
                    DB::table('businesses')->where('business_id',$data->businessId)->update(array('third_image' => ''));
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


/*** CREATED BUSINESS DELETE VIDEO API ***/
public function created_business_delete_video(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));
//    nabeelpy
//    $video_base_url = config('base_urls.event_video_base_url');
//    $video_base_url = config('base_urls.business_video_thumbnails_base_url');


    $thumbnail_path_url= config('base_urls.relative_path_business_video_thumbnails');
    $video_path_url= config('base_urls.relative_path_event_videos');

    if(isset($data->videoName) && isset($data->thumbnailName) && isset($data->businessId)){
        $result1 =  File::delete($video_path_url.$data->videoName);
        $result2 =  File::delete($thumbnail_path_url.$data->thumbnailName);

        if($result1 && $result2){
            $old_data = DB::table('businesses')->where('business_id',$data->businessId)->first();
//            return response()->json(["data" => $old_data,"bussiness_id"=>$data->businessId]);

                    if(($old_data->first_video == $data->videoName) && ($old_data->first_video_thumbnail == $data->thumbnailName) ){
                        DB::table('businesses')->where('business_id',$data->businessId)->update(array('first_video' => '', 'first_video_thumbnail' => ''));
                    }
                    if(($old_data->second_video == $data->videoName) && ($old_data->second_video_thumbnail == $data->thumbnailName) ){
                        DB::table('businesses')->where('business_id',$data->businessId)->update(array('second_video' => '', 'second_video_thumbnail' => ''));
                    }
                    if(($old_data->third_video == $data->videoName) && ($old_data->third_video_thumbnail == $data->thumbnailName) ){
                        DB::table('businesses')->where('business_id',$data->businessId)->update(array('third_video' => '', 'third_video_thumbnail' => ''));
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


   /*** BUSINESSES SEARCH FILTER ***/
   public function business_search_filter(){
    $data = json_decode(file_get_contents("php://input"));
    $response = array();
    $filtered_miles_response = array();
    $filtered_miles_count = array();


    if(isset($data->offset) && isset($data->userId) && isset($data->userLat) && isset($data->userLong)){


        $this->city_filter = $city_filter = (isset($data->cityFilter)? $data->cityFilter: '');
        $this->date_filter = $date_filter = (isset($data->dateFilter)? $data->dateFilter: '');
        $this->title_filter = $title_filter = (isset($data->titleFilter)? $data->titleFilter: '');

 //Query to get total posts with filters for pagination
    $total_posts =    DB::table('businesses')
                               ->where(function($query) {
                                    if($this->city_filter != '')
                                    {
                                        $query->where('city',trim($this->city_filter));
                                    }
                                })->where(function($query) {
                                    if( $this->date_filter != '')
                                    {
                                        $query->where('created_at','like', '%' . trim($this->date_filter) . '%');
                                    }
                                })->where(function($query) {
                                    if($this->title_filter != '')
                                    {
                                        $query->orWhere('title','like', '%' . trim($this->title_filter) . '%')
                                        ->orWhere('description','like', '%' . trim($this->title_filter) . '%');
                                    }
                                })

        ->whereIn('status', ['Active', 'RequestedVerification', 'CanceledVerification', 'Verified'])->get();


        //Main filtering query with limit
        /*$filtered_posts =    DB::table('businesses')
                            ->where(function($query) {
                                if($this->city_filter != '')
                                {
                                    $query->where('city',trim($this->city_filter));
                                }
                            })
                            ->where(function($query) {
                                if( $this->date_filter != '')
                                {
                                    $query->where('created_at','like', '%' . trim($this->date_filter) . '%');
                                }
                            })
                            ->where(function($query) {
                                if($this->title_filter != '')
                                {
                                    $query->where('name','like', '%' . trim($this->title_filter) . '%');
                                }
                            })
                            ->skip($data->offset)->take(5)
                            ->whereIn('status', ['Active', 'RequestedVerification', 'CanceledVerification','Verified'])
                            ->orderBy('business_id', 'DESC')
                            ->get();*/


                            $filtered_posts =    DB::table('businesses')
                            ->where(function($query) {
                                if($this->city_filter != '')
                                {
                                    $query->where('city',trim($this->city_filter));
                                }
                            })
                            ->where(function($query) {
                                if( $this->date_filter != '')
                                {
                                    $query->where('created_at','like', '%' . trim($this->date_filter) . '%');
                                }
                            })
                            ->where(function($query) {
                                if($this->title_filter != '')
                                {
                                    $query->orWhere('title','like', '%' . trim($this->title_filter) . '%')
                                    ->orWhere('description','like', '%' . trim($this->title_filter) . '%');
                                }
                            })
                            ->whereIn('status', ['Active', 'RequestedVerification', 'CanceledVerification','Verified'])
                            ->orderBy('business_id', 'DESC')
                            ->get();

       //Result if any filter is set then filtered , otherwise all posts will be displayed
        if (sizeof($filtered_posts)>0) {
                    //count of total posts after checking Miles check for premium
                    if(sizeof($total_posts) == 0){
                        $total_posts_count_after_miles = 0;
                    } else{
                        foreach($total_posts as $k){
                            $businessDistanceFromUser = Helper::calculate_miles($k->business_lat, $k->business_long, $data->userLat,$data->userLong, 'M' );
                                if($businessDistanceFromUser <= 75){
                                    array_push($filtered_miles_count, $k);
                                }
                        }
                        $total_posts_count_after_miles = sizeof($filtered_miles_count);
                    }


            foreach($filtered_posts as $key => $business){
                 //appending Logo url fields in response
                 $logo_base_url = config('base_urls.business_logo_base_url');
                 if(!empty( $business->business_logo)){
                     $business->business_logo= $logo_base_url.$business->business_logo;
                 }

                 //appending images urls fields in response
                 $image_base_url = config('base_urls.business_image_base_url');
                 if(!empty( $business->first_image)){
                     $business->first_image= $image_base_url.$business->first_image;
                 }
                  if(!empty( $business->second_image)){
                     $business->second_image = $image_base_url.$business->second_image;
                 }
                  if(!empty( $business->third_image)){
                     $business->third_image = $image_base_url.$business->third_image;
                 }

                $image_base_url = config('base_urls.business_image_base_url');
                if(!empty( $business->fourth_image)){
                    $business->fourth_image= $image_base_url.$business->fourth_image;
                }
                if(!empty( $business->fifth_image)){
                    $business->fifth_image = $image_base_url.$business->fifth_image;
                }
                if(!empty( $business->sixth_image)){
                    $business->sixth_image = $image_base_url.$business->sixth_image;
                }


                //appending Video thumbnails urls fields in response
                 $video_thumbnail_base_url = config('base_urls.business_video_thumbnails_base_url');
                 if(!empty( $business->first_video_thumbnail)){
                     $business->first_video_thumbnail = $video_thumbnail_base_url.$business->first_video_thumbnail ;
                 }
                  if(!empty( $business->second_video_thumbnail)){
                     $business->second_video_thumbnail = $video_thumbnail_base_url.$business->second_video_thumbnail;
                 }
                  if(!empty( $business->third_video_thumbnail)){
                     $business->third_video_thumbnail = $video_thumbnail_base_url.$business->third_video_thumbnail;
                 }

                 //appending Videos urls fields in response
                 $video_base_url = config('base_urls.event_video_base_url');
                 if(!empty( $business->first_video)){
                     $business->first_video = $video_base_url.$business->first_video ;
                 }
                  if(!empty( $business->second_video)){
                     $business->second_video = $video_base_url.$business->second_video;
                 }
                  if(!empty( $business->third_video)){
                     $business->third_video = $video_base_url.$business->third_video;
                 }

                        //time conversion for minutes/hours/days ago
                        $business->time_ago = Helper::time_elapsed_string($business->created_at);

                        //miles away
                        $milesAway = Helper::calculate_miles($business->business_lat, $business->business_long, $data->userLat,$data->userLong, 'M' );
//                        $business->distance_miles = round($milesAway, 2);
                        $business->distance_miles = $milesAway;

                        $business->business_lat = (double) $k->business_lat;
                        $business->business_long = (double) $k->business_long;

                        //Total comments count on Business Post
                        $totalPostComments = DB::table('business_comments')->where([ ['business_id', $business->business_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                        $business->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);
                        $business->total_likes = (string) Helper::thousandsCurrencyFormat($business->total_likes);

                            //appending liked business post hint
                            $checkLike = DB::table('business_likes')->where('business_id', $business->business_id)->where('users_id', $data->userId)->count();
                            if($checkLike > 0){
                                $business->liked = true;
                            } else{
                                $business->liked = false;
                            }

                                 //appending favourite post hint
                        $isFavouriteQuery = DB::table('business_favourites')->where('business_id', $business->business_id)->where('users_id', $data->userId)->count();
                        if($isFavouriteQuery > 0){
                            $business->isFavourite = true;
                        } else{
                            $business->isFavourite = false;
                        }

                        $business_type =   DB::table('business_types')->where('type', $business->business_type)->where('status',"Active")->first();

                        if($business_type){
                            $business->business_type = $business_type;
                        }

              }

                    // display only post in 50 miles
                    foreach($filtered_posts as $k){
                        $businessDistanceFromUser = Helper::calculate_miles($k->business_lat, $k->business_long, $data->userLat,$data->userLong, 'M' );
                            // if($businessDistanceFromUser <= 50){
                                array_push($filtered_miles_response, $k);
                            // }
                    }


                    $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('distance_miles')->skip($data->offset)->take(5)->toArray());

                if(sizeof($filtered_miles_response) > 0){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    // $response["total_posts"] = $total_posts_count_after_miles;
                    $response["total_posts"] = sizeof($total_posts);
                    $response["data"] = $filtered_miles_response;
                }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = "0";
                    $response["message"] = "Business Post does not exists.";
                }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["total_posts"] = "0";
            $response["message"] = "Business Post does not exists.";
        }

    } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["total_posts"] = "0";
        $response["message"] = "All fields are needed";
    }

return response()
->json(array('status' => $response["status"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
->header('Content-Type', 'application/json');
}


 /*** USER BUSINESS POST WITH BUSINESS TYPE FILTER ***/
 public function user_business_posts(){
    $data = json_decode(file_get_contents("php://input"));
    $response = array();

    if(isset($data->userId)){

//Query to get total posts with filters for pagination
 $total_posts =   DB::table('businesses')->where([ ['users_id', $data->userId], ['active_status', 'Active'] ])->orWhere([ ['users_id',$data->userId], ['status', 'RequestedVerification']])->count();

//Main filtering query with limit
$filtered_posts =    DB::table('businesses')->where([ ['users_id',$data->userId], ['active_status', 'Active'], ['status','!=', 'Deleted']])->get();

       //Result if any filter is set then filtered , otherwise all posts will be displayed
        if (sizeof($filtered_posts)>0) {

            foreach($filtered_posts as $business_post){

                //Logo URL
                $business_post->business_logo = config('base_urls.business_logo_base_url').$business_post->business_logo;

                 //appending images urls fields in response
                 $image_base_url = config('base_urls.business_image_base_url');

                 if(!empty( $business_post->first_image)){
                     $business_post->first_image= $image_base_url.$business_post->first_image;
                 }
                  if(!empty( $business_post->second_image)){
                     $business_post->second_image = $image_base_url.$business_post->second_image;
                 }
                  if(!empty( $business_post->third_image)){
                     $business_post->third_image = $image_base_url.$business_post->third_image;
                 }
                 if(!empty( $business_post->fourth_image)){
                    $business_post->fourth_image = $image_base_url.$business_post->fourth_image;
                }
                if(!empty( $business_post->fifth_image)){
                    $business_post->fifth_image = $image_base_url.$business_post->fifth_image;
                }
                if(!empty( $business_post->sixth_image)){
                    $business_post->sixth_image = $image_base_url.$business_post->sixth_image;
                }

                //appending video urls fields in response
                $video_base_url = config('base_urls.business_video_base_url');

//                Append post url with urls
                if(!empty( $business_post->first_video)){
                    $business_post->first_video= $video_base_url.$business_post->first_video;
                }
                if(!empty( $business_post->second_video)){
                    $business_post->second_video = $video_base_url.$business_post->second_video;
                }
                if(!empty( $business_post->third_video)){
                    $business_post->third_video = $video_base_url.$business_post->third_video;
                }


                //appending video urls fields in response
                $video_base_url = config('base_urls.business_video_thumbnails_base_url');

//                Append post url with urls
                if(!empty( $business_post->first_video_thumbnail)){
                    $business_post->first_video_thumbnail= $video_base_url.$business_post->first_video_thumbnail;
                }
                if(!empty( $business_post->second_video_thumbnail)){
                    $business_post->second_video_thumbnail = $video_base_url.$business_post->second_video_thumbnail;
                }
                if(!empty( $business_post->third_video_thumbnail)){
                    $business_post->third_video_thumbnail = $video_base_url.$business_post->third_video_thumbnail;
                }



                 $business_post->total_likes = (string)$business_post->total_likes;


                 $business_post->business_lat = (double) $business_post->business_lat;
                 $business_post->business_long = (double) $business_post->business_long;

                 //appending Business Type data from business_type in response array after success
                 $business_type =   DB::table('business_types')->where('type', $business_post->business_type)->where('status',"Active")->first();

                 if($business_type){
                     $business_post->business_type = $business_type;
                 }

            }


            $response["code"] = 200;
            $response["status"] = "success";
            $response["total_posts"] = $total_posts;
            $response["data"] =  $filtered_posts;


        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["total_posts"] = "0";
            $response["message"] = "Business Post does not exists.";
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


 /*** LIKE UNLIKE EVENT PEEK ***/
 public function like_unlike_business_post(){
    $response = array();
$data = json_decode(file_get_contents("php://input"));

if(isset($data->usersId) && isset($data->businessId) ){
    $checkLike = DB::table('business_likes')
                                 ->where([ ['users_id', $data->usersId], ['business_id', $data->businessId] ])
                                 ->count();
     if($checkLike == 0){
            //like case
        $likeData = array(
            'business_id' => $data->businessId,
            'users_id' => $data->usersId,
            'liked_at' => date('Y-m-d H:i:s')
        );
        $likeQuery = DB::table('business_likes')->insert($likeData);
        if($likeQuery){
             $businessDetails = DB::table('businesses')->where('business_id', $data->businessId)->first();
              $updatedLikesCount = $businessDetails->total_likes + 1;
              DB::table('businesses')->where('business_id', $data->businessId)->update(['total_likes' => $updatedLikesCount]);

                //sending push notification
                $businessDetails = DB::table('businesses')->where('business_id',$data->businessId)->first();
                $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                $receiverDetails= DB::table('users')->where([ ['users_id', $businessDetails->users_id], ['status', 'Active'] ])->first();
                if($senderDetails->users_id != $receiverDetails->users_id){
                        $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Posts'], ['users_id', $receiverDetails->users_id ]])->first();
                   if($checkNotificationSetting->status == 'On'){
                      $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' liked your business post ', 'businessLike');
                      if($notificationResponse){
                          $notificationData = array(
                              'sender_users_id' => $senderDetails-> users_id,
                              'receiver_users_id' => $receiverDetails-> users_id,
                              'notification_type' => 'BusinessLike',
                              'message' => ' liked your business post ',
                              'business_id' => $data->businessId,
                              'datetime' => date('Y-m-d H:i:s'),
                              'status' => 'Unread'
                          );
                          $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                      }
                   }
                  }

              //totalLikes on business post
              $businessDetails = DB::table('businesses')->where('business_id', $data->businessId)->first();
                $response["like_count"] = (string) $businessDetails->total_likes;
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = true;
            }
     }else{
         //unlike case
         $unlikeQuery = DB::table('business_likes')->where([ ['users_id', $data->usersId], ['business_id', $data->businessId] ])->delete();
         if($unlikeQuery){
                $businessDetails = DB::table('businesses')->where('business_id', $data->businessId)->first();
                $updatedLikesCount = $businessDetails->total_likes - 1;
                DB::table('businesses')->where('business_id', $data->businessId)->update(['total_likes' => $updatedLikesCount]);


              //totalLikes on business post
              $businessDetails = DB::table('businesses')->where('business_id', $data->businessId)->first();
              $response["like_count"] = (string) $businessDetails->total_likes;
                 $response["code"] = 200;
                 $response["status"] = "success";
                 $response["data"] = false;
             }
     }
 } else{
    $response["like_count"] = "0";
     $response["code"] = 404;
     $response["status"] = "error";
     $response["message"] = "All Fields are needed";
}

    return response()
    ->json(array('like_count' => $response["like_count"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }


     /*** FAVOURITE BUSINESS ***/
     public function favourite_business(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->businessId)){
                $fav_data = array(
                    'users_id' => $data->userId,
                    'business_id' => $data->businessId
                );

              $checkBusiness = DB::table('businesses')->where('business_id',$data->businessId)->first();
              if($checkBusiness){
                    $favDataQuery = DB::table('business_favourites')->insert($fav_data);
                    if($favDataQuery){
                                //sending push notification
                                $senderDetails= DB::table('users')->where([ ['users_id', $data->userId], ['status', 'Active'] ])->first();
                                $receiverDetails= DB::table('users')->where([ ['users_id', $checkBusiness->users_id], ['status', 'Active'] ])->first();

                                  if($senderDetails->users_id != $receiverDetails->users_id){
                                        $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Posts'], ['users_id', $receiverDetails->users_id ]])->first();
                                            if($checkNotificationSetting->status == 'On'){
                                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' favourite your business post ', 'businessFavourite');
                                                if($notificationResponse){
                                                    $notificationData = array(
                                                        'sender_users_id' => $senderDetails-> users_id,
                                                        'receiver_users_id' => $receiverDetails-> users_id,
                                                        'notification_type' => 'BusinessFavourite',
                                                        'message' => ' favourite your business post ',
                                                        'business_id' => $data->businessId,
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
                $response["message"] = "Business Not found";
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
     /*** FAVOURITE BUSINESS ***/

     /*** UN_FAVOURITE BUSINESS ***/
     public function unfavourite_business(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->businessId)){
                $fav_data = array(
                    'users_id' => $data->userId,
                    'business_id' => $data->businessId
                );

              $checkFav = DB::table('business_favourites')->where('business_id',$data->businessId)
                                                        ->where('users_id',$data->userId)->first();
              if($checkFav){
                  $unfavDataQuery = DB::table('business_favourites')->where('business_id',$data->businessId)
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
                $response["message"] = "Business Not found in favourites";
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
     /*** UN_FAVOURITE BUSINESS ***/


           /*** GET ALL FAVOURITE BUSINESS WITH FILTER ***/
           public function get_all_business_favourites(){

            $data = json_decode(file_get_contents("php://input"));
            $response = array();
            $fav_business_array = array();
            $filtered_miles_response = array();
            $filtered_miles_count = array();


            if(isset($data->offset) && isset($data->userId) && isset($data->userLat) && isset($data->userLong)){

                $checkFavourites = DB::table('business_favourites')->where('users_id',$data->userId)->get();
        if(sizeof($checkFavourites) > 0){
                    //loop for whereIn array
                    foreach($checkFavourites as $k){
                        array_push($fav_business_array, $k->business_id);
                    }

         //Query to get total posts with filters for pagination
            $total_posts =    DB::table('businesses')
                                         ->whereIn('business_id', $fav_business_array)
                                        ->whereIn('status', ['Active', 'RequestedVerification', 'CanceledVerification','Verified'])->get();


                //Main filtering query with limit
                $filtered_posts =    DB::table('businesses')
                                                    ->whereIn('business_id', $fav_business_array)
                                                    // ->skip($data->offset)->take(5)
                                                    ->whereIn('status', ['Active', 'RequestedVerification', 'CanceledVerification','Verified'])
                                                    ->get();

               //Result if any filter is set then filtered , otherwise all posts will be displayed
                if (sizeof($filtered_posts)>0) {
                            //count of total posts after checking Miles check for premium
                            if(sizeof($total_posts) == 0){
                                $total_posts_count_after_miles = 0;
                            } else{
                                foreach($total_posts as $k){
                                    $businessDistanceFromUser = Helper::calculate_miles($k->business_lat, $k->business_long, $data->userLat,$data->userLong, 'M' );
                                        if($businessDistanceFromUser <= 75){
                                            array_push($filtered_miles_count, $k);
                                        }
                                }
                                $total_posts_count_after_miles = sizeof($filtered_miles_count);
                            }


                    foreach($filtered_posts as $key => $business){
                         //appending Logo url fields in response
                         $logo_base_url = config('base_urls.business_logo_base_url');
                         if(!empty( $business->business_logo)){
                             $business->business_logo= $logo_base_url.$business->business_logo;
                         }

                         //appending images urls fields in response
                         $image_base_url = config('base_urls.business_image_base_url');
                         if(!empty( $business->first_image)){
                             $business->first_image= $image_base_url.$business->first_image;
                         }
                          if(!empty( $business->second_image)){
                             $business->second_image = $image_base_url.$business->second_image;
                         }
                          if(!empty( $business->third_image)){
                             $business->third_image = $image_base_url.$business->third_image;
                         }

                         //appending Video thumbnails urls fields in response
                         $video_thumbnail_base_url = config('base_urls.business_video_thumbnails_base_url');
                         if(!empty( $business->first_video_thumbnail)){
                             $business->first_video_thumbnail = $video_thumbnail_base_url.$business->first_video_thumbnail ;
                         }
                          if(!empty( $business->second_video_thumbnail)){
                             $business->second_video_thumbnail = $video_thumbnail_base_url.$business->second_video_thumbnail;
                         }
                          if(!empty( $business->third_video_thumbnail)){
                             $business->third_video_thumbnail = $video_thumbnail_base_url.$business->third_video_thumbnail;
                         }

                         //appending Videos urls fields in response
                         $video_base_url = config('base_urls.business_video_base_url');
                         if(!empty( $business->first_video)){
                             $business->first_video = $video_base_url.$business->first_video ;
                         }
                          if(!empty( $business->second_video)){
                             $business->second_video = $video_base_url.$business->second_video;
                         }
                          if(!empty( $business->third_video)){
                             $business->third_video = $video_base_url.$business->third_video;
                         }

                                //time conversion for minutes/hours/days ago
                                $business->time_ago = Helper::time_elapsed_string($business->created_at);

                                //miles away
                                $milesAway = Helper::calculate_miles($business->business_lat, $business->business_long, $data->userLat,$data->userLong, 'M' );
//                                $business->distance_miles = round($milesAway, 2);
                                $business->distance_miles = $milesAway;

                                $business->business_lat = (double) $k->business_lat;
                                $business->business_long = (double) $k->business_long;

                                //Total comments count on Business Post
                                $totalPostComments = DB::table('business_comments')->where([ ['business_id', $business->business_id], ['comment_type', 'comment'], ['status', 'Active'] ])->count();
                                $business->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);
                                $business->total_likes = (string) Helper::thousandsCurrencyFormat($business->total_likes);

                                    //appending liked business post hint
                                    $checkLike = DB::table('business_likes')->where('business_id', $business->business_id)->where('users_id', $data->userId)->count();
                                    if($checkLike > 0){
                                        $business->liked = true;
                                    } else{
                                        $business->liked = false;
                                    }



                                 //appending favourite post hint
                                $isFavouriteQuery = DB::table('business_favourites')->where('business_id', $business->business_id)->where('users_id', $data->userId)->count();
                                if($isFavouriteQuery > 0){
                                    $business->isFavourite = true;
                                } else{
                                    $business->isFavourite = false;
                                }


                        $business_type =   DB::table('business_types')->where('type', $business->business_type)->where('status',"Active")->first();

                        if($business_type){
                            $business->business_type = $business_type;
                        }


                      }

                            // display only post in 50 miles
                            foreach($filtered_posts as $k){
                                $businessDistanceFromUser = Helper::calculate_miles($k->business_lat, $k->business_long, $data->userLat,$data->userLong, 'M' );
                                    // if($businessDistanceFromUser <= 50){
                                        array_push($filtered_miles_response, $k);
                                    // }
                            }

                    $filtered_miles_response = array_values(collect($filtered_miles_response)->sortBy('distance_miles')->skip($data->offset)->take(5)->toArray());



                    if(sizeof($filtered_miles_response) > 0){
                            $response["code"] = 200;
                            $response["status"] = "success";
                            // $response["total_posts"] = $total_posts_count_after_miles;
                            $response["total_posts"] = sizeof($total_posts);
                            $response["data"] = $filtered_miles_response;
                        }else{
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["total_posts"] = "0";
                            $response["message"] = "Business Post does not exists.";
                        }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["total_posts"] = "0";
                    $response["message"] = "Business Post does not exists.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = "0";
                $response["message"] = "No favourites found";
            }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_posts"] = "0";
                $response["message"] = "All fields are needed";
            }

        return response()
        ->json(array('status' => $response["status"],'total_posts' => $response["total_posts"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
        }




  /////// COMMENT / REPLY ON BUSINESS POST //////////
  public function comment_on_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessId) && isset($data->usersId) && isset($data->comment) && isset($data->commentType)){

        $encodedComment = json_encode($data->comment);

            if($data->commentType == 'comment'){
                $commentData = array(
                    'business_id' => $data->businessId,
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
                    'business_id' => $data->businessId,
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

            $commentQuery = DB::table('business_comments')->insert($commentData);

            if($commentQuery){
                   //sending push notification
                    if($data->commentType == 'comment'){
                        $businessDetails = DB::table('businesses')->where('business_id', $data->businessId)->first();
                        $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                        $receiverDetails= DB::table('users')->where([ ['users_id', $businessDetails->users_id], ['status', 'Active'] ])->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Comments and replies'], ['users_id', $receiverDetails->users_id ]])->first();
                            if($checkNotificationSetting->status == 'On'){
                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Commented on your business post ', 'businessPostComment');
                                if($notificationResponse){
                                    $notificationData = array(
                                     'sender_users_id' => $senderDetails-> users_id,
                                     'receiver_users_id' => $receiverDetails-> users_id,
                                     'notification_type' => 'BusinessPostComment',
                                        'message' => ' Commented on your business post ',
                                        'business_id' => $data->businessId,
                                        'datetime' => date('Y-m-d H:i:s'),
                                        'status' => 'Unread'

                                    );
                                    $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                }
                            }
                        }

                     } else{
                         $commentDetails = DB::table('business_comments')->where('business_comment_id', $data->replyingToCommentId)->first();
                        $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                        $receiverDetails= DB::table('users')->where([ ['users_id', $commentDetails->users_id], ['status', 'Active'] ])->first();

                        if($senderDetails->users_id != $receiverDetails->users_id){
                            $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Comments and replies'], ['users_id', $receiverDetails->users_id ]])->first();
                            if($checkNotificationSetting->status == 'On'){
                                $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' replied to your comment ', 'businessCommentReply');
                                    if($notificationResponse){
                                        $notificationData = array(
                                        'sender_users_id' => $senderDetails-> users_id,
                                        'receiver_users_id' => $receiverDetails-> users_id,
                                        'notification_type' => 'BusinessCommentReply',
                                            'message'=> ' replied to your comment ',
                                            'business_id' => $data->businessId,
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
                    $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                    $receiverDetails= DB::table('users')->where([ ['users_id', $data->mentionedUserId], ['status', 'Active'] ])->first();

                    if($senderDetails->users_id != $receiverDetails->users_id){
                        $checkNotificationSetting = DB::table('user_notifications_settings')->where([ ['notification_type', 'Mentions'], ['users_id', $receiverDetails->users_id ]])->first();
                        if($checkNotificationSetting->status == 'On'){
                            $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' mentioned you in a comment ', 'commentMention');
                                if($notificationResponse){
                                    $notificationData = array(
                                        'sender_users_id' => $senderDetails-> users_id,
                                        'receiver_users_id' => $receiverDetails-> users_id,
                                        'notification_type' => 'CommentMention',
                                        'message' => ' mentioned you in a comment ',
                                        'business_id' => $data->businessId,
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
                    $response["message"] = "Errr in query ";
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
   public function get_comment_mentions_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessId) && isset($data->usersId)){
          $commentEngagedUsers = DB::table('business_comments')
                                        ->select('business_comments.users_id','users.user_name','users.profile_picture')
                                        ->join('users', 'users.users_id', '=', 'business_comments.users_id')
                                        ->where([ ['business_comments.business_id', $data->businessId], ['business_comments.users_id','!=', $data->usersId], ['business_comments.status', 'Active'] ])
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
                $response["message"] = "No users exists";
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


/////// GET ALL COMMENTS ON BUSINESS POST //////////
public function get_all_comments_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessId) && isset($data->usersId) ){
            $businessPostComments = DB::table('business_comments')->where([
                                                                    ['business_id', $data->businessId],
                                                                    ['comment_type', 'comment'],
                                                                    ['status', 'Active']
                                                            ])->get();
          if(sizeof($businessPostComments) > 0){

                foreach($businessPostComments as $comment){

                    //user profile and username for each comment
                $commentUserDetails = DB::table('users')->where([ ['users_id', $comment->users_id], ['status', 'Active'] ])->first();
                        if($commentUserDetails->profile_picture != "" || $commentUserDetails->profile_picture != null){
                            $comment->comment_user_profile =  config('base_urls.profile_images_base_url').$commentUserDetails->profile_picture;
                        } else{
                            $comment->comment_user_profile =  "";
                        }
                    $comment->comment_user_name = $commentUserDetails->user_name;

                      //appending liked comment hint
                      $checkLike = DB::table('business_comment_likes')->where([ ['business_comment_id', $comment->business_comment_id], ['users_id', $data->usersId] ])->count();
                      if($checkLike > 0){
                          $comment->comment_liked = 'true';
                      } else{
                          $comment->comment_liked = 'false';
                      }

                      //decoding json for emoji
                      $comment->comment = json_decode($comment->comment);

                    //Comment time conversion for minutes/hours/days ago
                        $comment->comment_time_ago = Helper::time_elapsed_string($comment->commented_datetime);

                        //Total Replies count in every comment
                    $totalReplies = DB::table('business_comments')->where([
                                                                        ['replying_to_comment_id', $comment->business_comment_id],
                                                                        ['business_id', $data->businessId],
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
                $commentReplies = DB::table('business_comments')->where([
                                                                    ['replying_to_comment_id', $comment->business_comment_id],
                                                                    ['business_id', $data->businessId],
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
                                $checkLike = DB::table('business_comment_likes')->where([ ['business_comment_id', $reply->business_comment_id], ['users_id', $data->usersId] ])->count();
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
            $totalPostComments = DB::table('business_comments')->where([ ['business_id', $data->businessId], ['comment_type', 'comment'], ['status', 'Active'] ])->count();

            $response["code"] = 200;
            $response["status"] = "success";
            $response["total_post_comments"] = $totalPostComments;
            $response["comments"] = $businessPostComments;

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


/////// GET ALL COMMENTS ON BUSINESS POST //////////
public function get_comment_replies_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessCommentId) && isset($data->usersId) && isset($data->businessId)){
            $businessCommentReplies = DB::table('business_comments')->where([
                                                                    ['comment_type', 'reply'],
                                                                    ['replying_to_comment_id', $data->businessCommentId],
                                                                    ['status', 'Active']
                                                            ])->get();

          if(sizeof($businessCommentReplies) > 0){
                foreach($businessCommentReplies as $reply){
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
                                $checkLike = DB::table('business_comment_likes')->where([ ['business_comment_id', $reply->business_comment_id], ['users_id', $data->usersId] ])->count();
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
            $totalPostComments = DB::table('business_comments')->where([ ['business_id', $data->businessId], ['comment_type', 'comment'], ['status', 'Active'] ])->count();

            $totalCommentRepliesQuery = DB::table('business_comments')->where([
                                                                    ['replying_to_comment_id', $data->businessCommentId],
                                                                    ['business_id', $data->businessId],
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
            $response["comments"] = $businessCommentReplies;

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
    ->json(array('total_post_comments' => $response["total_post_comments"],'total_comment_replies' => $response["total_comment_replies"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'comments' => isset($response["message"]) ? $response["message"] : $response["comments"]))
    ->header('Content-Type', 'application/json');
 }


   /////// DELETE COMMENT BUSINESS //////////
   public function delete_comment_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessCommentId) && isset($data->usersId) ){

        //Queries to get details for event owner
        $businessIdQuery = DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->first();
        $businessId = $businessIdQuery->business_id;
        $businessDetailsQuery = DB::table('businesses')->where('business_id',$businessId)->first();

            //Event owner-- Can delete any comment or reply
        if($data->usersId == $businessDetailsQuery->users_id){
            $commentDetails = DB::table('business_comments')->where([ ['business_comment_id', $data->businessCommentId], ['status', 'Active'] ])->first();
            $commentReplies = DB::table('business_comments')->where([ ['replying_to_comment_id', $data->businessCommentId], ['status', 'Active'] ])->get();

                   DB::table('business_comments')->where([ ['business_comment_id', $data->businessCommentId] ])->update(array('status' => 'Deleted'));
                   if(sizeof($commentReplies) > 0){
                       foreach($commentReplies as $k){
                           DB::table('business_comments')->where([ ['replying_to_comment_id', $data->businessCommentId], ['status', 'Active'] ])->update(array('status' => 'Deleted'));
                       }
                   }
        }
        else{
            //Case in which user is not event owner so he can delete his own only
            $commentDetails = DB::table('business_comments')->where([ ['business_comment_id', $data->businessCommentId], ['users_id', $data->usersId], ['status', 'Active'] ])->first();
            $commentReplies = DB::table('business_comments')->where([ ['replying_to_comment_id', $data->businessCommentId], ['status', 'Active'] ])->get();

                   DB::table('business_comments')->where([ ['business_comment_id', $data->businessCommentId] ])->update(array('status' => 'Deleted'));
                   if(sizeof($commentReplies) > 0){
                       foreach($commentReplies as $k){
                           DB::table('business_comments')->where([ ['replying_to_comment_id', $data->businessCommentId], ['status', 'Active'] ])->update(array('status' => 'Deleted'));
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

 /////// LIKE COMMENT BUSINESS//////////
 public function like_comment_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessCommentId) && isset($data->businessId) && isset($data->usersId) ){
          $commentLikeData = array(
              'business_comment_id' => $data->businessCommentId,
              'business_id' => $data->businessId,
              'users_id' => $data->usersId,
              'date_time' => date('Y-m-d H:i:s')
          );

         $insertCommentLikeQuery = DB::table('business_comment_likes')->insert($commentLikeData);
            if($insertCommentLikeQuery){
                            //sending push notification
                            $commentOrReplyDetails = DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->first();
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
                                            'business_id' => $data->businessId,
                                            'comment_id' => $data->businessCommentId,
                                            'datetime' => date('Y-m-d H:i:s'),
                                            'status' => 'Unread'
                                        );
                                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                    }
                                 } else{
                                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' Liked your reply ', 'replyLike');
                                    if($notificationResponse){
                                        //comment details which is replied
                                         $commentDetails = DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)-> first();

                                        $notificationData = array(
                                            'sender_users_id' => $senderDetails-> users_id,
                                            'receiver_users_id' => $receiverDetails-> users_id,
                                            'notification_type' => 'ReplyLike',
                                            'message' => ' Liked your reply ',
                                            'business_id' => $data->businessId,
                                            'comment_id' => $commentDetails->replying_to_comment_id,
                                            'datetime' => date('Y-m-d H:i:s'),
                                            'status' => 'Unread'
                                        );
                                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                                    }
                                 }
                            }



                   $commentDetails = DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->first();
                   $previousLikedCount = $commentDetails->total_likes;
                   $updatedLikesCount = $previousLikedCount + 1;

                   DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->update(array('total_likes' => $updatedLikesCount));

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


     /////// UNLIKE COMMENT BUSINESS //////////
public function unlike_comment_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessCommentId) && isset($data->businessId) && isset($data->usersId) ){


         $checkCommentLike = DB::table('business_comment_likes')->where([ ['business_comment_id',$data->businessCommentId], ['business_id', $data->businessId], ['users_id', $data->usersId] ])->first();
            if($checkCommentLike){
                    DB::table('business_comment_likes')->where([ ['business_comment_id',$data->businessCommentId], ['business_id', $data->businessId], ['users_id', $data->usersId] ])->delete();

                    $commentDetails = DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->first();
                    $previousLikedCount = $commentDetails->total_likes;
                    $updatedLikesCount = $previousLikedCount - 1;

                    DB::table('business_comments')->where('business_comment_id', $data->businessCommentId)->update(array('total_likes' => $updatedLikesCount));

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
    return response()->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }


  /***  REPORT BUSINESS POST ***/
  public function report_business(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessId) && isset($data->userId) && isset($data->comments)){
          $checkReport = DB::table('business_reports')->where('users_id',$data->userId)->where('business_id',$data->businessId)->count();
           if($checkReport == 0){
               $report_data = array(
                   'business_id' => $data->businessId,
                   'users_id' => $data->userId,
                   'comments' => $data->comments,
                   'date_time' => date('Y-m-d H:i:s'),
                   'status' => 'Requested'
               );

                $query = DB::table('business_reports')->insert($report_data);
                if($query){
                  $response["code"] = 200;
                  $response["status"] = "success";
                  $response["data"] = "This Business is reported successfully";
                } else{
                  $response["code"] = 500;
                  $response["status"] = "error";
                  $response["message"] = "Something went wrong";
                }
          } else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "You already reported this Business";
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
       /***  REPORT BUSINESS POST ***/


 /////// REPORT COMMENT //////////
 public function report_business_comment(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->businessCommentId) && isset($data->usersId) && isset($data->reportCategories)){
         $checkReport = DB::table('business_comment_reports')->where([ ['business_comment_id', $data->businessCommentId], ['users_id', $data->usersId] ])->count();
         if($checkReport == '0'){
                    $commentReportData = array(
                        'business_comment_id' => $data->businessCommentId,
                        'users_id' => $data->usersId,
                        'date_time' => date("Y-m-d H:i:s"),
                        'status' => 'Active',
                    );

                $reportInsertedId = DB::table('business_comment_reports')->insertGetId($commentReportData);
                    if($reportInsertedId){
                        //Inserting report categories in child table
                        foreach($data->reportCategories as $k){
                            $reportCategoryData = array(
                                'business_comment_report_id' => $reportInsertedId,
                                'report_category' => $k,
                                'status' =>'Active'
                            );
                            DB::table('business_comment_report_categories')->insert($reportCategoryData);
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
    return response()->json(array(
        'status' => $response["status"],
        isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]
        )
        )->header('Content-Type', 'application/json');
 }



 public function get_business_types()
 {
     $data = DB::table('business_types')->get();

     if ($data) {

        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $data;

     } else {

        $response["code"] = 500;
        $response["status"] = "error";
        $response["message"] = "Already reported";

     }

     return response()->json(array(
        'status' => $response["status"],
        isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]
        )
        )->header('Content-Type', 'application/json');

 }



     ////////////////////////////////////////////////////

           /***TEST CA**/
    public function test_get(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        $calculatedDistance =   Helper::calculate_miles($data->latitude1, $data->longitude1, $data->latitude2, $data->longitude2, 'M');

                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] =  $calculatedDistance.' miles';

    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
           /***TEST GET API**/






}
