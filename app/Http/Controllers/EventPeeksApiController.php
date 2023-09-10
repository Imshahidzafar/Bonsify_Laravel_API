<?php
namespace App\Http\Controllers;
use App\Helpers\Helper;
use http\Env\Response;
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
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\StripeClient;
use DateTime;
use Illuminate\Support\Arr;
use \stdClass;


class EventPeeksApiController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;



     /*** UPLOAD EVENT PEEK VIDEO API ***/
     public function upload_event_peek_video(Request $req){
                $response = array();
                $postfix = 'conn_events_peek';
                $upd_prefix = date('d-m-y');
                $random = rand(1,1000000);

                $file_name = $upd_prefix.$random.$postfix.'.mp4';
                $thumbnail_name = $upd_prefix.$random.$postfix.'.jpeg';

                $video_path_url= config('base_urls.relative_path_event_peeks');
                $thumbnail_path_url= config('base_urls.relative_path_event_peek_thumbnails');

                $uploadedVideo = $req->file('event_peek')->move($video_path_url,$file_name);
                $uploadedThumbnail = $req->file('event_peek_thumbnail')->move($thumbnail_path_url,$thumbnail_name);

                if($uploadedVideo && $uploadedThumbnail){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["video"] = $file_name;
                    $response["thumbnail"] = $thumbnail_name;
                } else{
                    $response["code"] = 500;
                    $response["status"] = "error";
                    $response["message"] = "Uploading failed";
                    $response["video"] = $file_name;
                    $response["thumbnail"] = $thumbnail_name;
                }

        return response()
        ->json(array('status' => $response["status"],'thumbnail' => $response["thumbnail"],  isset($response["message"]) ? 'message' : 'video' => isset($response["message"]) ? $response["message"] : $response["video"]))
        ->header('Content-Type', 'application/json');
     }


 /////// Upload Event Peek DETAILS //////////
 public function upload_event_peek_details(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->eventPostId) && isset($data->videoName) && isset($data->descriptionText)){
                $peekData = array(
                    'event_post_id'=> $data->eventPostId,
                    'users_id'=> $data->usersId,
                    'video_name' => $data->videoName,
                    'thumbnail' => $data->thumbnail,
                    'description_text' => $data->descriptionText,
                    'total_likes' => '0',
                    'total_comments' => '0',
                    'total_views' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'Active'
                );
                $insertQuery = DB::table('event_peeks')->insert($peekData);
                if($insertQuery){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Event peek uploaded successfully';
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



 /////// Get event Peeks //////////
 public function get_event_peeks(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

 $finalDataset = array();

    if(isset($data->usersId) && isset($data->eventPostId) && isset($data->userLat) && isset($data->userLong) ){
        $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
        $user_timezone = $response["timeZoneId"];


               $totalCount = DB::table('event_peeks')
                                            ->where('event_post_id', $data->eventPostId)
                                            ->where('created_at', '>=', Carbon::now()->subDay())
                                            ->count();

                $randomPeeks = DB::table('event_peeks')
                                            ->where('event_post_id', $data->eventPostId)
                                            ->where('created_at', '>=', Carbon::now()->subDay())
                                            ->inRandomOrder()
                                            ->limit(5)
                                            ->get();


            if(sizeof($randomPeeks) > 0){
                    foreach($randomPeeks as $k){
                        $checkAlreadyViewed = DB::table('event_peek_views')->where([
                                                                                    ['event_peek_id', $k->event_peek_id],
                                                                                    ['users_id', $data->usersId]
                                                                                ])->count();
                        if($checkAlreadyViewed == 0){
                            $k->video_url = config('base_urls.event_peeks_base_url').$k->video_name;
                            $k->peek_thumbnail = config('base_urls.event_peek_thumbnails_base_url').$k->thumbnail;

                            $k->total_likes = (string) Helper::thousandsCurrencyFormat($k->total_likes);
                            $k->total_comments = (string) Helper::thousandsCurrencyFormat($k->total_comments);
                            $k->total_views = (string) Helper::thousandsCurrencyFormat($k->total_views);


                            $userDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                            $eventDetails  = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();

                            $k->user_name = $userDetails->user_name;
                            $k->first_name = $userDetails->first_name;
                            $k->event_title = $eventDetails->title;
                            if($userDetails->profile_picture != ""){
                                $k->profile_picture =  config('base_urls.profile_images_base_url').$userDetails->profile_picture;
                            } else{
                                $k->profile_picture =  "";
                            }

                            array_push($finalDataset, $k);

                            //insert peek view
                            $viewData = array(
                                'event_peek_id'=> $k->event_peek_id,
                                'users_id' => $data->usersId,
                                'status' => 'Delivered'
                            );
                            $insertQuery = DB::table('event_peek_views')->insert($viewData);
                        }

                    }

                    if(sizeof($finalDataset) > 0) {
                        foreach($finalDataset as $k){
                                            //Appending event Details for each peek
                            $event_post =    DB::table('event_posts')
                                                ->select('event_posts.*','addresses.*')
                                                ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                                ->where('event_posts.event_post_id', $k->event_post_id)
                                                ->first();


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

                            $event_post->total_available_ticket_quantity = $val1 + $val2 + $val3 + $val4;


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
                                        $event_post->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                                        if($event_post->dress_code_id != 0){
                                            // Appending dresscode color code for thumbnail bar color ease in event details
                                                $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $event_post->dress_code_id)->first();
                                                $event_post->dress_code_color_code = $dressCodeColor->dress_code_color;
                                        }else{
                                            $event_post->dress_code_color_code = null;
                                        }


                                        //Appeding organizer details
                                        $organizerDetails = DB::table('users')->where('users_id', $event_post->users_id)->first();
                                        $event_post->organizer_users_id = $organizerDetails->users_id;
                                        $event_post->organizer_user_name = $organizerDetails->user_name;

                                        if($organizerDetails->profile_picture != ""){
                                            $event_post->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                                        }else{
                                            $event_post->organizer_profile_picture =  "";
                                        }

                                        //following flag
                                        $checkFollowing = DB::table('followers')->where([ ['following_to_user',$event_post->users_id] ,['followed_by_user' ,$data->usersId] ])->first();
                                        if($checkFollowing){
                                            $event_post->is_following = true;
                                        } else{
                                            $event_post->is_following = false;
                                        }

                                        //totalFollowers
                                        $totalFollowers = DB::table('followers')->where('following_to_user', $event_post->users_id)->count();
                                        $event_post->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);


                                    // display only post in 100 miles for premuium and 50 for other
                                    /*  foreach($filtered_posts as $event_post){
                                        $eventAddressDetails = DB::table('addresses')->where('event_post_id',$event_post->event_post_id)->first();
                                        $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                                        $organizerDetails = DB::table('users')->where('users_id', $event_post->users_id)->first();
                                        if($organizerDetails->subscription_package_id == '2'){
                                            //premium case, 100 miles
                                            if($eventDistanceFromUser <= 100){
                                                if($event_post->event_ticket_type == 'NotMyEvent'){
                                                    //Remove the event created week ago in this scenario
                                                    $daysAgo = Helper::get_days_difference($event_post->created_at);
                                                    if($daysAgo <= '7'){
                                                        array_push($filtered_miles_response, $event_post);
                                                    }
                                                }else{
                                                        array_push($filtered_miles_response, $event_post);
                                                    }
                                            }
                                        } else{
                                            //other case non premium, 50 miles
                                            if($eventDistanceFromUser <= 50){
                                                if($event_post->event_ticket_type == 'NotMyEvent'){
                                                    //Remove the event created week ago in this scenario
                                                    $daysAgo = Helper::get_days_difference($event_post->created_at);
                                                    if($daysAgo <= '7'){
                                                        array_push($filtered_miles_response, $event_post);
                                                    }
                                                }else{
                                                        array_push($filtered_miles_response, $event_post);
                                                    }
                                            }
                                        }
                                    } */



                                    $k->event_post_details = $event_post;
                        }
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["total_count"] = $totalCount;
                        $response["data"] = $finalDataset;
                    }else{
                        //No updated videos,  DIsplay old videos
                        $oldDataSet = DB::table('event_peeks')
                            ->where('event_post_id', $data->eventPostId)
                            ->where('created_at', '>=', Carbon::now()->subDay())
                                        ->inRandomOrder()
                                        ->limit(5)
                                        ->get();

                        foreach($oldDataSet as $k){
                            $k->video_url = config('base_urls.event_peeks_base_url').$k->video_name;
                            $k->peek_thumbnail = config('base_urls.event_peek_thumbnails_base_url').$k->thumbnail;

                            $k->total_likes = (string) Helper::thousandsCurrencyFormat($k->total_likes);
                            $k->total_comments = (string) Helper::thousandsCurrencyFormat($k->total_comments);
                            $k->total_views = (string) Helper::thousandsCurrencyFormat($k->total_views);

                            $userDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                            $eventDetails  = DB::table('event_posts')->where('event_post_id', $k->event_post_id)->first();

                            $k->user_name = $userDetails->user_name;
                            $k->first_name = $userDetails->first_name;
                            $k->event_title = $eventDetails->title;
                            if($userDetails->profile_picture != ""){
                                $k->profile_picture =  config('base_urls.profile_images_base_url').$userDetails->profile_picture;
                            } else{
                                $k->profile_picture =  "";
                            }

                        }

            foreach($oldDataSet as $k){
                         //Appending event Details for each peek
                    $event_post =    DB::table('event_posts')
                                        ->select('event_posts.*','addresses.*')
                                        ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
                                        ->where('event_posts.event_post_id', $k->event_post_id)
                                        ->first();


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

                $event_post->total_available_ticket_quantity = $val1 + $val2 + $val3 + $val4;


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

                    if($ticket1){
                        $event_post->Early_bird = $ticket1;
                    }
                    if($ticket2){
                        $event_post->Regular = $ticket2;
                    }
                    if($ticket3){
                        $event_post->VIP = $ticket3;
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
                        $event_post->total_post_comments = (string) Helper::thousandsCurrencyFormat($totalPostComments);


                        if($event_post->dress_code_id != 0){
                            // Appending dresscode color code for thumbnail bar color ease in event details
                                $dressCodeColor = DB::table('dress_code')->where('dress_code_id', $event_post->dress_code_id)->first();
                                $event_post->dress_code_color_code = $dressCodeColor->dress_code_color;
                        }else{
                            $event_post->dress_code_color_code = null;
                        }


                        //Appeding organizer details
                        $organizerDetails = DB::table('users')->where('users_id', $event_post->users_id)->first();
                        $event_post->organizer_users_id = $organizerDetails->users_id;
                        $event_post->organizer_user_name = $organizerDetails->user_name;

                        if($organizerDetails->profile_picture != ""){
                            $event_post->organizer_profile_picture =  config('base_urls.profile_images_base_url').$organizerDetails->profile_picture;
                        }else{
                            $event_post->organizer_profile_picture =  "";
                        }

                        //following flag
                        $checkFollowing = DB::table('followers')->where([ ['following_to_user',$event_post->users_id] ,['followed_by_user' ,$data->usersId] ])->first();
                        if($checkFollowing){
                            $event_post->is_following = true;
                        } else{
                            $event_post->is_following = false;
                        }

                        //totalFollowers
                        $totalFollowers = DB::table('followers')->where('following_to_user', $event_post->users_id)->count();
                        $event_post->total_followers = (string) Helper::thousandsCurrencyFormat($totalFollowers);


                    // display only post in 100 miles for premuium and 50 for other
                    /*  foreach($filtered_posts as $event_post){
                        $eventAddressDetails = DB::table('addresses')->where('event_post_id',$event_post->event_post_id)->first();
                        $eventDistanceFromUser = Helper::calculate_miles($eventAddressDetails->location_lat, $eventAddressDetails->location_long, $data->userLat,$data->userLong, 'M' );

                        $organizerDetails = DB::table('users')->where('users_id', $event_post->users_id)->first();
                        if($organizerDetails->subscription_package_id == '2'){
                            //premium case, 100 miles
                            if($eventDistanceFromUser <= 100){
                                if($event_post->event_ticket_type == 'NotMyEvent'){
                                    //Remove the event created week ago in this scenario
                                    $daysAgo = Helper::get_days_difference($event_post->created_at);
                                    if($daysAgo <= '7'){
                                        array_push($filtered_miles_response, $event_post);
                                    }
                                }else{
                                        array_push($filtered_miles_response, $event_post);
                                    }
                            }
                        } else{
                            //other case non premium, 50 miles
                            if($eventDistanceFromUser <= 50){
                                if($event_post->event_ticket_type == 'NotMyEvent'){
                                    //Remove the event created week ago in this scenario
                                    $daysAgo = Helper::get_days_difference($event_post->created_at);
                                    if($daysAgo <= '7'){
                                        array_push($filtered_miles_response, $event_post);
                                    }
                                }else{
                                        array_push($filtered_miles_response, $event_post);
                                    }
                            }
                        }
                    } */



                    $k->event_post_details = $event_post;
        }

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["total_count"] = $totalCount;
                        $response["data"] = $oldDataSet;
                    }
            }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["total_count"] = $totalCount;
                $response["message"] = "No videos found uploaded in 24 hours";
            }
        } else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["total_count"] = 0;
            $response["message"] = "All Fields are needed";
    }
    return response()
            ->json(array('total_count' => $response["total_count"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

 }

    /*** GET PEEK COMMENTS COUNT ***/
    public function get_peek_comments_count(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventPeekId) ){

        $commentsCount = DB::table('event_peek_comments')->where('event_peek_id', $data->eventPeekId)
                                                            ->count();

                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["data"] = $commentsCount;

     } else{
         $response["code"] = 404;
         $response["status"] = "error";
         $response["message"] = "All Fields are needed";
    }
        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }


 /*** PEEK DROPDOWN EVENT LIST ***/
   public function peek_dropdown_event_list(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

    $allowUser = false;

if(isset($data->usersId) && isset($data->userLat) && isset($data->userLong) ){
    $response = Http::get('https://maps.googleapis.com/maps/api/timezone/json?location='.$data->userLat.','.$data->userLong.'&timestamp=1331161200&key=AIzaSyDgU-FmmQj01yc5PZlX4ENRTouhX7CY-RI')->json();
    $user_timezone = $response["timeZoneId"];


    $mainList = array();

    $dateTime = new DateTime();
////    $dateTime->modify('+1 day');
    $CurrentDateAddOne = $dateTime->format('Y-m-d');

//cretical
    $hostEvents = DB::table('event_posts')
        ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
        ->where([ ['event_posts.status', 'Active'], ['event_posts.event_ticket_type', 'MyFreeEvent']])
        ->where('event_end_date', '>=', date('Y-m-d'))
        ->where('event_start_date', '<=', $CurrentDateAddOne)
//        ->orWhere('event_end_time', '>=', date('H:i:s'))
//        ->orWhere('event_start_time', '<=', date('H:i:s'))
        ->orderBy('created_at', 'DESC');


//    return response(["data" => $hostEvents->get()]);

    //user is not a host so only  checked in events are displayed
    $userCheckedinEvents = DB::table('event_checkins')->select('event_posts.*','addresses.*')
        ->join('event_posts','event_posts.event_post_id','=','event_checkins.event_post_id')
        ->join('addresses','addresses.event_post_id','=','event_posts.event_post_id')
        ->where('event_posts.event_end_date', '>=', date('Y-m-d'))
        ->where('event_start_date', '<=', $CurrentDateAddOne)
//        ->orWhere('event_end_time', '>=', date('H:i:s'))
//        ->orWhere('event_start_time', '<=', date('H:i:s'))
        ->where('event_posts.event_ticket_type', 'Paid')
        ->where('event_checkins.users_id', $data->usersId)
        ->union($hostEvents)
        ->distinct()
        ->get();


    if(sizeof($userCheckedinEvents) > 0){
        foreach($userCheckedinEvents as $k){



if($k->sales_start_date == null){
    $customObj = new stdClass();
    $customObj->event_post_id = $k->event_post_id;
    $customObj->title = $k->title;
    array_push($mainList, $customObj);

}else{
    // add by nabeel
//    $currentDate = date('Y-m-d H:i:s');
//    $currentTimeSpam = strtotime($currentDate);
//    $eventSaleStartDateTime = $k->sales_start_date." ".$k->sales_start_time;
//    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);

    $event_timezone = $k->timezone;
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
    $eventSaleStartDateTime = $k->sales_start_date." ".$k->sales_start_time;
    $eventSaleStartDateTimeSpam = strtotime($eventSaleStartDateTime);
    $eventSaleStartDateTimeSpam = $eventSaleStartDateTimeSpam - $diff;


    if($currentTimeSpam <= $eventSaleStartDateTimeSpam) {
        unset($k);
    }else {
        $customObj = new stdClass();
        $customObj->event_post_id = $k->event_post_id;
        $customObj->title = $k->title;
        array_push($mainList, $customObj);
    }
}



        }
    }


                if(sizeof($mainList) > 0){
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $mainList;
                    $response["allow_user"] = $allowUser;
                }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "No events found to upload peek";
                }

 } else{
     $response["code"] = 404;
     $response["status"] = "error";
     $response["message"] = "All Fields are needed";

}
    return response()
    ->json(array('status' => $response["status"],  isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }




   /*** Check User checked in API for upload peeks ***/
  /*  public function check_user_checkedin_event(){
    $response = array();
    $data = json_decode(file_get_contents("php://input"));

if(isset($data->usersId) ){
    $result = new stdClass();

    //checking if user is a host of any event so that no need to checking him in for peek upload
   $userEvents = DB::table('event_posts')->where([ ['users_id', $data->usersId], ['status', 'Active'], ['event_ticket_type', 'Paid']])
                                        ->whereDate('event_start_date','<=', date('Y-m-d'))
                                        ->whereDate('event_end_date','>=', date('Y-m-d'))
                                        ->orderBy('created_at', 'DESC')
                                        ->get();

        if(sizeof($userEvents) > 0){
            // host created event that is on going so no need to check him
                $checkedinEventDetails = DB::table('event_posts')->where('event_post_id', $userEvents[0]->event_post_id)->first();
                $result->is_user_checkedin = true;
                $result->checkedin_event_id = (string) $checkedinEventDetails->event_post_id;
                $result->checkedin_event_title = $checkedinEventDetails->title;
        }else{
            //user is not a host so in this case checkin should be verified
            $checkinDetails = DB::table('event_checkins')->select('event_checkins.*', 'event_posts.*')
                                                            ->join('event_posts','event_posts.event_post_id','=','event_checkins.event_post_id')
                                                            ->where('event_checkins.users_id', $data->usersId)
                                                            ->get();


                    echo json_encode("checkin detail");
                    echo json_encode($checkinDetails); exit;

                if(sizeof($checkinDetails) > 0){
                    foreach($checkinDetails as $k){

                    }
                        $checkedinEventDetails = DB::table('event_posts')->where('event_post_id', $checkinDetails->event_post_id)->first();
                        $result->is_user_checkedin = true;
                        $result->checkedin_event_id = (string) $checkedinEventDetails->event_post_id;
                        $result->checkedin_event_title = $checkedinEventDetails->title;
                }else{
                        $result->is_user_checkedin = false;
                        $result->checkedin_event_id = "";
                        $result->checkedin_event_title = "";
                }
        }

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $result;

 } else{
     $response["code"] = 404;
     $response["status"] = "error";
     $response["message"] = "All Fields are needed";
}
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }

 */


  /*** VIEW EVENT PEEK ***/
  public function view_event_peek(){
    $response = array();
$data = json_decode(file_get_contents("php://input"));

if(isset($data->usersId) && isset($data->eventPeekId) ){

        $viewPeek = DB::table('event_peek_views')
                                                ->where([ ['users_id', $data->usersId], ['event_peek_id', $data->eventPeekId] ])
                                                ->update(['status'=> 'Viewed']);

        $peekViewsCount = DB::table('event_peek_views')->where([ ['event_peek_id', $data->eventPeekId], ['status', 'Viewed']])->count();

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $peekViewsCount;

 } else{
     $response["code"] = 404;
     $response["status"] = "error";
     $response["message"] = "All Fields are needed";
}
    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }


     /*** LIKE UNLIKE EVENT PEEK ***/
     public function like_unlike_event_peek(){
        $response = array();
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->eventPeekId) ){
        $checkLike = DB::table('event_peek_likes')
                                     ->where([ ['users_id', $data->usersId], ['event_peek_id', $data->eventPeekId] ])
                                     ->count();
         if($checkLike == 0){
                //like case
            $likeData = array(
                'event_peek_id' => $data->eventPeekId,
                'users_id' => $data->usersId
            );
            $likeQuery = DB::table('event_peek_likes')->insert($likeData);
            if($likeQuery){
                 $eventPeekDetails = DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->first();
                  $updatedLikesCount = $eventPeekDetails->total_likes + 1;
                  DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->update(['total_likes' => $updatedLikesCount]);

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = true;
                }
         }else{
             //unlike case
             $unlikeQuery = DB::table('event_peek_likes')->where([ ['users_id', $data->usersId], ['event_peek_id', $data->eventPeekId] ])->delete();
             if($unlikeQuery){
                    $eventPeekDetails = DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->first();
                    $updatedLikesCount = $eventPeekDetails->total_likes - 1;
                    DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->update(['total_likes' => $updatedLikesCount]);

                     $response["code"] = 200;
                     $response["status"] = "success";
                     $response["data"] = false;
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



     /*** COMMENT ON  PEEK ***/
     public function comment_on_peek(){
        $response = array();
      $data = json_decode(file_get_contents("php://input"));

    if(isset($data->usersId) && isset($data->eventPeekId) ){

        $comment = json_encode($data->comment);


            $commentDetails = array(
                'event_peek_id'=> $data->eventPeekId,
                'comment'=> $comment,
                'comment_type'=> (isset($data->emojiType) ? 'Emoji' : 'Text'),
                'users_id' => $data->usersId,
                'commented_on' => date('Y-m-d H:i:s')
            );

        $insertQuery = DB::table('event_peek_comments')->insert($commentDetails);

            if($insertQuery){
                 $eventPeekDetails = DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->first();

//                 send notification
                $senderDetails= DB::table('users')->where([ ['users_id', $data->usersId], ['status', 'Active'] ])->first();
                $receiverDetails= DB::table('users')->where([ ['users_id', $eventPeekDetails->users_id], ['status', 'Active'] ])->first();
                if($senderDetails->users_id != $receiverDetails->users_id){
                    $notificationResponse =  Helper::sendpushnotification($receiverDetails->one_signal_id, $senderDetails->user_name.' commented on your peek', 'PeekComment');
                    if($notificationResponse){
                        $notificationData = array(
                            'sender_users_id' => $senderDetails-> users_id,
                            'receiver_users_id' => $receiverDetails-> users_id,
                            'notification_type' => 'PeekComment',
                            'message' => $senderDetails->user_name.' commented on your peek',
                            'event_post_id' => $eventPeekDetails->event_post_id,
                            'peek_id' => $data->eventPeekId,
                            'datetime' => date('Y-m-d H:i:s'),
                            'status' => 'Unread'
                        );
                        $notificationInsertQuery = DB::table('notifications')->insert($notificationData);
                    }
                }


//                  update total comments
                  $updatedCommentsCount = $eventPeekDetails->total_comments + 1;
                  DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->update(['total_comments' => $updatedCommentsCount]);

                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = 'Commented successfully';
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


          /*** GET USER PEEK COMMENTS ***/
          public function get_user_peek_comments(){
            $response = array();
          $data = json_decode(file_get_contents("php://input"));

        if(isset($data->usersId) && isset($data->eventPeekId) ){
                    $eventPeekDetails = DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->first();

                    if($eventPeekDetails->users_id == $data->usersId)
                        $userComments = DB::table('event_peek_comments')->where('event_peek_id', $data->eventPeekId)->get();
                    else
                         $userComments = DB::table('event_peek_comments')->where([ ['event_peek_id', $data->eventPeekId], ['users_id', $data->usersId] ])->get();


                          if(sizeof($userComments)  > 0){

                        foreach($userComments as $k){
                                $k->comment = json_decode($k->comment);

                            $commentUserDetails = DB::table('users')->where('users_id', $k->users_id)->first();
                            $k->user_name = $commentUserDetails->user_name;

                            if($commentUserDetails->profile_picture != ""){
                                $k->profile_picture =  config('base_urls.profile_images_base_url').$commentUserDetails->profile_picture;
                            } else{
                                $k->profile_picture =  "";
                            }

                            $k->comment_time_ago =  Helper::time_elapsed_string($k->commented_on);
                        }

                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["data"] = $userComments;
                        $response["total_comments"] = sizeof($userComments);

                    }else{
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = "No comments found";
                        $response["total_comments"] = sizeof($userComments);

                    }

         } else{
             $response["code"] = 404;
             $response["status"] = "error";
             $response["message"] = "All Fields are needed";
             $response["total_comments"] = sizeof($userComments);
        }

            return response()
            ->json(array('status' => $response["status"],'total_comments' => $response["total_comments"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

        }

     /*** DELETE PEEK ***/
     public function delete_event_peek(){
        $response = array();
      $data = json_decode(file_get_contents("php://input"));

    if(isset($data->eventPeekId) ){
            DB::table('event_peek_comments')->where('event_peek_id', $data->eventPeekId)->delete();
            DB::table('event_peek_likes')->where('event_peek_id', $data->eventPeekId)->delete();
            DB::table('event_peek_views')->where('event_peek_id', $data->eventPeekId)->delete();
            DB::table('event_peeks')->where('event_peek_id', $data->eventPeekId)->delete();

            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = "Deleted successfully";

     } else{
         $response["code"] = 404;
         $response["status"] = "error";
         $response["message"] = "All Fields are needed";
    }

        return response()
        ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
     }




}
