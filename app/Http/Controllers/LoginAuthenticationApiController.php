<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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







class LoginAuthenticationApiController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;


    //================ USER AUTHENTICATION AND REGISTRATION ===================//

    /*** GET USER TOKEN ***/
    public function get_token(){
        $response["code"] = 200;
        $response["status"] = "success";
        $response["token"] = csrf_token();
        return $response;
    }
    /*** GET USER TOKEN ***/

    /*** GENERATE CODE ***/
    public function generate_code($limit){
        $permitted_chars = '0123456789';
        return substr(str_shuffle($permitted_chars), 0, $limit);
    }
    /*** GENERATE CODE ***/

    /*** SEND MAIL ***/
    public function send_email($to, $subject, $message) {

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: <support@eigix.com>' . "\r\n";
        $mail = mail($to, $subject, $message, $headers);
        //$mail = mail('salmanbhatti52@hotmail.com', $subject, $message, $headers);
        //$mail = mail('saqibmahay@yahoo.com', $subject, $message, $headers);
        return $mail;
    }
    /*** SEND MAIL ***/

    /*** SIGNUP USER ***/
    public  function signup(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->userEmail) && isset($data->userName) && isset($data->userPassword) && isset($data->userType) && isset($data->oneSignalId)) {

            $username_exists = DB::table('users')->where('user_name', $data->userName)->get()->count();
            if ($username_exists == 0) {
                $useremail_exists = DB::table('users')->where('email', $data->userEmail)->get()->count();
                if ($useremail_exists == 0) {
                    if($data->userPassword == $data->confirmPassword){
                        $user_data = array(
                            'user_name' => $data->userName,
                            'email' => $data->userEmail,
                            'first_name' =>  $data->firstName,
                            'last_name' =>  $data->lastName,
                            'date_added' => date('Y-m-d H:i:s'),
                            'password' => password_hash($data->userPassword, PASSWORD_DEFAULT),
                            'confirm_password' => password_hash($data->confirmPassword, PASSWORD_DEFAULT),
                            'roles_id' => $data->userType,
                            'total_conncash'=> 0,
                            'conncash_dollars'=> 0,
                            'status' => 'Active',
                            'account_type' => 'SignupWithApp',
                            'one_signal_id' => $data->oneSignalId,
                            'subscription_package_id' => '1',
                            'one_time_post_count' => '1',
                            'package_updated_at' => date('Y-m-d H:i:s'),
                            'verify_code' => '',
                            'social_acc_type' => 'None',
                            'profile_picture' => '',

                        );


                        if (DB::table('users')->insert($user_data)) {

                            $user_details = DB::table('users')->where('email', $data->userEmail)->get();
                            if (!empty($user_details)) {

                                 //updating one signal id
                                DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                 $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                    if($user_details[0]->profile_picture != ""){
                                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                                        $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                        }

                                 //count of event posts that user posted
                                 $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();


                                 //Inserting default in user notification settings
                                      $checkPreviousAddedSettings = DB::table('user_notifications_settings')->where('users_id', $user_details[0]->users_id)->count();
                                        if($checkPreviousAddedSettings == 0){
                                            $modifiableNotificationstypes = ['Posts','Comments and replies', 'Mentions', 'Messages'];
                                            foreach($modifiableNotificationstypes as $k){
                                                $defaultNotificationSettings = array(
                                                    'notification_type' => $k,
                                                    'users_id' => $user_details[0]->users_id,
                                                    'status' => 'On'
                                                );
                                                DB::table('user_notifications_settings')->insert($defaultNotificationSettings);
                                            }
                                      }




                                $response["code"] = 200;
                                $response["hint_flag"] = "0";
                                $response["total_user_event_posts"] = $userEventCount;
                                $response["status"] = "success";
                                $response["data"] = array("user" => $user_details);
                            } else {
                                $response["code"] = 404;
                                $response["status"] = "error";
                                $response["hint_flag"] = "0";
                                $response["total_user_event_posts"] = 0;
                                $response["message"] = "User does not exists.";
                            }
                        } else {
                            $response["code"] = 500;
                            $response["status"] = "error";
                            $response["hint_flag"] = "0";
                            $response["total_user_event_posts"] = 0;
                            $response["message"] = "Something went wrong.";
                        }
                    }else{
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["hint_flag"] = "0";
                        $response["total_user_event_posts"] = 0;
                        $response["message"] = "Password  did not Match.";
                    }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["hint_flag"] = "0";
                    $response["total_user_event_posts"] = 0;
                    $response["message"] = "Email address already exists.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["hint_flag"] = "0";
                $response["total_user_event_posts"] = 0;
                $response["message"] = "User name already exists.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["hint_flag"] = "0";
            $response["total_user_event_posts"] = 0;
            $response["message"] = "All fields are needed.";
        }

        return response()
        ->json(array('hint_flag' => $response["hint_flag"], 'total_user_event_posts' => $response["total_user_event_posts"] , 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
}
    /*** SIGNUP USER ***/



    /*** SIGNUP with Google/facebook  ***/
    public  function signup_with_acc(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->accountType) && isset($data->oneSignalId)) {

                switch($data->accountType){
                    case 'google':
                        $accesstoken_exists = DB::table('users')->where('google_access_token', $data->googleAccessToken)->whereNotNull('google_access_token')->get()->count();
                        $useremail_exists = DB::table('users')->where('email', $data->userEmail)->get()->count();

                     // echo json_encode($accesstoken_exists); exit;
                   // echo json_encode($previous_acc); exit;


                        if($useremail_exists == 1){

                            //returning old data
                               if($accesstoken_exists == 0){
                                      //updating user info with google access token and giving old data
                                      $query = DB::table('users')->where('email',$data->userEmail)->update(array('account_type'=> 'Both','google_access_token' => $data->googleAccessToken));
                                          if($query){
                                            $user_details = DB::table('users')->where('google_access_token', $data->googleAccessToken)->get();
                                            if($user_details){
                                                if(password_verify('123456', $user_details[0]->password)){
                                                    $response["hint_flag"] = "1";
                                                } else{
                                                    $response["hint_flag"] = "0";
                                                }

                                                   //updating one signal id
                                                DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                                DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                        $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();

                                                if($user_details[0]->profile_picture != ""){
                                                    $base_url_profile_images = config('base_urls.profile_images_base_url');
                                                    $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                                    }

                                                DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                                DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                              //count of event posts that user posted
                                            $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();



                                            $response["total_user_event_posts"] = $userEventCount;
                                            $response["code"] = 200;
                                            $response["status"] = "success / Signed in with Google";
                                            $response["data"] = array("user" => $user_details);
                                                }
                                          }

                                    } else{
                                       //simple returning old data
                                        $user_details = DB::table('users')->where('google_access_token', $data->googleAccessToken)->get();
                                        if($user_details){
                                            if(password_verify('123456', $user_details[0]->password)){
                                                $response["hint_flag"] = "1";
                                            } else{
                                                $response["hint_flag"] = "0";
                                            }

                                             //updating one signal id
                                            DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                            DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                        $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                            if($user_details[0]->profile_picture != ""){
                                                $base_url_profile_images = config('base_urls.profile_images_base_url');
                                                $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                                }

                                            DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                            DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                            //count of event posts that user posted
                                            $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();



                                        $response["total_user_event_posts"] = $userEventCount;
                                        $response["code"] = 200;
                                        $response["status"] = "success / Signed in with Google";
                                        $response["data"] = array("user" => $user_details);

                                    }

                     }


                        } else{
                            //new google register
                            $user_data = array(
                                'user_name' => (isset($data->userName) ? $data->userName: ''),
                                'email' => (isset($data->userEmail) ? $data->userEmail: ''),
                                'first_name' => (isset($data->firstName) ? $data->firstName: ''),
                                'last_name' => (isset($data->lastName) ? $data->lastName: ''),
                                'date_added' => date('Y-m-d H:i:s'),
                                'password' => password_hash('123456', PASSWORD_DEFAULT),
                                'confirm_password' => password_hash('123456', PASSWORD_DEFAULT),
                                'roles_id' => $data->userType,
                                'status' => 'Active',
                                'total_conncash'=> 0,
                                'conncash_dollars' => 0,
                                'account_type' => 'SignupWithSocial',
                                'social_acc_type'=> 'Google',
                                'one_signal_id' => $data->oneSignalId,
                                'subscription_package_id' => '1',
                                'one_time_post_count' => '1',
                                'package_updated_at' => date('Y-m-d H:i:s'),
                                'verify_code' => '',
                                'profile_picture' => '',
                                'google_access_token' => $data->googleAccessToken,

                            );
                            $query =  DB::table('users')->insert($user_data);
                            if($query){
                                $user_details = DB::table('users')->where('google_access_token', $data->googleAccessToken)->get();
                                   if (!empty($user_details)) {

                                          //updating one signal id
                                       DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                       DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                    $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                    if($user_details[0]->profile_picture != ""){
                                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                                        $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                        }

                                        if($user_details[0]->profile_picture != ""){
                                            $base_url_profile_images = config('base_urls.profile_images_base_url');
                                            $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                            }

                                       DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                       DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                            //count of event posts that user posted
                                            $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();

                                            //Inserting default in user notification settings
                                                $checkPreviousAddedSettings = DB::table('user_notifications_settings')->where('users_id', $user_details[0]->users_id)->count();
                                                if($checkPreviousAddedSettings == 0){
                                                    $modifiableNotificationstypes = ['Posts','Comments and replies', 'Mentions', 'Messages'];
                                                    foreach($modifiableNotificationstypes as $k){
                                                        $defaultNotificationSettings = array(
                                                            'notification_type' => $k,
                                                            'users_id' => $user_details[0]->users_id,
                                                            'status' => 'On'
                                                        );
                                                        DB::table('user_notifications_settings')->insert($defaultNotificationSettings);
                                                    }
                                                }


                                        $response["total_user_event_posts"] = $userEventCount;
                                        $response["code"] = 200;
                                        $response["status"] = "success / Signed in with Google";
                                        $response["hint_flag"] = "1";
                                        $response["data"] = array("user" => $user_details);
                                   } else{
                                    $response["code"] = 404;
                                    $response["hint_flag"] = "0";
                                    $response["total_user_event_posts"] = 0;
                                    $response["status"] = "error";
                                    $response["message"] = "User not found";
                                   }
                            } else{
                                $response["code"] = 500;
                            $response["status"] = "error";
                            $response["hint_flag"] = "0";
                            $response["total_user_event_posts"] = 0;
                            $response["message"] = "Something went wrong while inserting data.";
                            }

                        }

                break;

                    case 'facebook':
                        $facebookid_exists = DB::table('users')->where('facebook_id', $data->facebookId)->get()->count();
                        if(isset($data->userEmail)){
                            $useremail_exists = DB::table('users')->where('email', $data->userEmail)->get()->count();
                        } else{
                            $useremail_exists = "0";
                        }
                        //echo json_encode($useremail_exists); exit;


                        if($useremail_exists == 1){
                            //returning old data
                               if($facebookid_exists == 0){
                                      //updating user info with facebook id and giving old data
                                      $query = DB::table('users')->where('email',  $data->userEmail)->update(array('account_type'=> 'Both','facebook_id' => $data->facebookId));
                                      if($query){
                                        $user_details = DB::table('users')->where('facebook_id', $data->facebookId)->get();
                                        if($user_details){
                                            $response["code"] = 200;
                                            if(password_verify('123456', $user_details[0]->password)){
                                                $response["hint_flag"] = "1";
                                            } else{
                                                $response["hint_flag"] = "0";
                                            }

                                            //updating one signal id
                                            DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                            DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();

                                            if($user_details[0]->profile_picture != ""){
                                                $base_url_profile_images = config('base_urls.profile_images_base_url');
                                                $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                                }

                                            DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                            DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                                //count of event posts that user posted
                                        $userEventCount = DB::table('event_posts')->where([ ['users_id',$user_details[0]->users_id], ['status', 'Active'] ])->count();



                                        $response["total_user_event_posts"] = $userEventCount;
                                        $response["status"] = "success / Signed in with Facebook";
                                        $response["data"] = array("user" => $user_details);
                                            }
                                      }
                                } else{
                                    //simple returning old data
                            $user_details = DB::table('users')->where('facebook_id', $data->facebookId)->get();
                            if($user_details){
                                $response["code"] = 200;
                                            if(password_verify('123456', $user_details[0]->password)){
                                    $response["hint_flag"] = "1";
                                } else{
                                    $response["hint_flag"] = "0";
                                }

                                 //updating one signal id
                                DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                 $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                if($user_details[0]->profile_picture != ""){
                                    $base_url_profile_images = config('base_urls.profile_images_base_url');
                                    $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                    }

                                DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                      //count of event posts that user posted
                                      $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();



                                      $response["total_user_event_posts"] = $userEventCount;
                                      $response["status"] = "success / Signed in with Facebook";
                                      $response["data"] = array("user" => $user_details);
                        }
                     }
                        }  else {
                            if($facebookid_exists == 1){
                                //updating user info with facebook id and giving old data
                                  $user_details = DB::table('users')->where('facebook_id', $data->facebookId)->get();
                                  if($user_details){
                                      $response["code"] = 200;
                                      if(password_verify('123456', $user_details[0]->password)){
                                          $response["hint_flag"] = "1";
                                      } else{
                                          $response["hint_flag"] = "0";
                                      }

                                        //updating one signal id
                                      DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                      DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                    $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                      if($user_details[0]->profile_picture != ""){
                                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                                        $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                        }

                                      DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                      DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                //count of event posts that user posted
                                $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();



                                $response["total_user_event_posts"] = $userEventCount;
                                  $response["status"] = "success / Signed in with Facebook";
                                  $response["data"] = array("user" => $user_details);
                                      }

                          } else{
                            //new Facebook register
                            $user_data = array(
                                'user_name' => (isset($data->userName) ? $data->userName: ''),
                                'email' => (isset($data->userEmail) ? $data->userEmail: ''),
                                'first_name' => (isset($data->firstName) ? $data->firstName: ''),
                                'last_name' => (isset($data->lastName) ? $data->lastName: ''),
                                'date_added' => date('Y-m-d H:i:s'),
                                'password' => password_hash('123456', PASSWORD_DEFAULT),
                                'confirm_password' => password_hash('123456', PASSWORD_DEFAULT),
                                'roles_id' => $data->userType,
                                'status' => 'Active',
                                'total_conncash'=> 0,
                                'conncash_dollars' => 0,
                                'account_type' => 'SignupWithSocial',
                                'one_signal_id' => $data->oneSignalId,
                                'social_acc_type'=> 'Facebook',
                                'subscription_package_id' => '1',
                                'one_time_post_count' => '1',
                                'package_updated_at' => date('Y-m-d H:i:s'),
                                'verify_code' => '',
                                'profile_picture' => '',
                                'facebook_id' => $data->facebookId
                            );
                            $query =  DB::table('users')->insert($user_data);
                            if($query){
                                $user_details = DB::table('users')->where('facebook_id', $data->facebookId)->get();
                                   if (!empty($user_details)) {

                                      //updating one signal id
                                       DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                       DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                                      $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                                    if($user_details[0]->profile_picture != ""){
                                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                                        $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                        }

                                       DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                                       DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));

                                          //count of event posts that user posted
                                          $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_details[0]->users_id], ['status', 'Active'] ])->count();

                                          //Inserting default in user notification settings
                                            $checkPreviousAddedSettings = DB::table('user_notifications_settings')->where('users_id', $user_details[0]->users_id)->count();
                                            if($checkPreviousAddedSettings == 0){
                                                $modifiableNotificationstypes = ['Posts','Comments and replies', 'Mentions', 'Messages'];
                                                foreach($modifiableNotificationstypes as $k){
                                                    $defaultNotificationSettings = array(
                                                        'notification_type' => $k,
                                                        'users_id' => $user_details[0]->users_id,
                                                        'status' => 'On'
                                                    );
                                                    DB::table('user_notifications_settings')->insert($defaultNotificationSettings);
                                                }
                                            }




                                          $response["total_user_event_posts"] = $userEventCount;
                                        $response["code"] = 200;
                                        $response["hint_flag"] = "1";
                                        $response["status"] = "success / Signed in with Facebook";
                                        $response["data"] = array("user" => $user_details);
                                   } else{
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["hint_flag"] = "0";
                                    $response["total_user_event_posts"] = 0;
                                    $response["message"] = "User not found";
                                   }
                            } else{
                                $response["code"] = 500;
                            $response["status"] = "error";
                            $response["hint_flag"] = "0";
                            $response["total_user_event_posts"] = 0;
                            $response["message"] = "Something went wrong while inserting data.";
                            }
                        }
                    }
                break;

                default:
                $response["code"] = 500;
                $response["status"] = "error";
                $response["hint_flag"] = "0";
                $response["total_user_event_posts"] = 0;
                $response["message"] = "Invalid Account Type.";
                    }

        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["hint_flag"] = "0";
            $response["total_user_event_posts"] = 0;
            $response["message"] = "All fields are needed.";
        }

        return response()
                    ->json(array('hint_flag' => $response["hint_flag"], 'total_user_event_posts' => $response["total_user_event_posts"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** SIGNUP with Google/facebook ***/

      /*** UPDATE PROFILE PICTURE ***/
      public  function update_profile_picture(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if(isset($data->userId) && isset($data->profilePicture)){

            $user_id            = $data->userId;
            $profile_picture = $data->profilePicture;

            //updating profile picture

                        $postfix = 'conn_events';
                        $upd_prefix = date('d-m-y');
                        $random = rand(1,10000);
                        $image_path_url= config('base_urls.relative_path_profile_images');;

                            $uploaded_image = $this->decode_image($profile_picture, $user_id, $image_path_url, $upd_prefix, $random, $postfix);
                            $query = DB::table('users')->where('users_id', $user_id)->update(array('profile_picture' => $uploaded_image));

                    if($query){
                        $updated_user_details   = DB::table('users')->where('users_id', $user_id)->get();

                        if($updated_user_details[0]->profile_picture != ""){
                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                        $updated_user_details[0]->profile_picture = $base_url_profile_images.$updated_user_details[0]->profile_picture;
                        }

                        $response["code"] = 200;
                        $response["hint_flag"] = "0";
                        $response["status"] = "success";
                        $response["data"] = array(
                            "user" => $updated_user_details
                        );

                } else {
                    $response["code"] = 404;
                    $response["hint_flag"] = "0";
                    $response["status"] = "error";
                    $response["message"] = "User does not exists.";
                }
        } else {
            $response["code"] = 404;
            $response["hint_flag"] = "0";
            $response["status"] = "error";
            $response["message"] = "All fields are needed.";
        }

        return response()
                ->json(array('hint_flag' => $response["hint_flag"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                ->header('Content-Type', 'application/json');
    }
    /*** UPDATE PROFILE PICTURE ***/


           /*** UPDATE USER PROFILE ***/
           public  function update_profile(){
            $response = array();
            $data = json_decode(file_get_contents("php://input"));


            if (isset($data->userId)) {
                $user_id   = $data->userId;
                $old_data = DB::table('users')->where('users_id', $user_id)->get()->first();

                if(isset($data->name)){  $explodedName =  explode(" ", $data->name, 2);
                 }
                $first_name = (isset($data->name) ? $explodedName[0] : $old_data->first_name);


                if(sizeof($explodedName) > 1){
                    $last_name = (isset($data->name) ? $explodedName[1] : $old_data->last_name);
                }

                $user_old_password  = (isset($data->userOldPassword) ?  $data->userOldPassword : "");
                if(isset($data->userNewPassword) && isset($data->userConfirmPassword)){
                    $user_new_password  = $data->userNewPassword;
                    $user_confirm_password = $data->userConfirmPassword;
                }

                 if(isset($data->userEmail)){ $user_email = $data->userEmail;}
                $user_details       = DB::table('users')->where('users_id', $user_id)->get()->first();


                    switch($user_details->account_type){
                        case 'Both':
                       case 'SignupWithApp':
                        //Signup user type
                        if($user_old_password == ""){
                                  //update other details without password authentication
                                  $user_data = array(
                                    'first_name' =>  $first_name,
                                    'last_name' =>  (isset($last_name) ? $last_name : ''),
                                    'date_added' => date('Y-m-d h:i:s'),
                                    'status' => 'Active',
                                );
                                $query =   DB::table('users')->where('users_id', '=', $user_id)->update($user_data);
                                if ($query) {
                                    if (!empty($user_details)) {

                                        $updated_user_details   = DB::table('users')->where('users_id', $user_id)->get();

                                        if($updated_user_details[0]->profile_picture != ""){
                                        $base_url_profile_images = config('base_urls.profile_images_base_url');
                                        $updated_user_details[0]->profile_picture = $base_url_profile_images.$updated_user_details[0]->profile_picture;
                                        }

                                        $response["code"] = 200;
                                        $response["hint_flag"] = "0";
                                        $response["status"] = "success";
                                        $response["data"] = array(
                                            "user" => $updated_user_details
                                        );
                                    } else {
                                        $response["code"] = 404;
                                        $response["hint_flag"] = "0";
                                        $response["status"] = "error";
                                        $response["message"] = "User does not exists.";
                                    }
                                } else {
                                    $response["code"] = 500;
                                    $response["status"] = "error";
                                    $response["hint_flag"] = "0";
                                    $response["message"] = "Something went wrong.";
                                }
                        }
                         else if(password_verify($user_old_password, $user_details->password)){
                               // scenario updating password as well
                            if(!(isset($user_new_password) == isset($user_confirm_password))){
                                        $response["code"] = 404;
                                        $response["status"] = "error";
                                        $response["hint_flag"] = "0";
                                        $response["message"] = "Password and Confirm Password not matched.";
                                    } else{
                                        $user_data = array(
                                            'first_name' =>  $first_name,
                                            'last_name' =>  (isset($last_name) ? $last_name : ''),
                                            'date_added' => date('Y-m-d h:i:s'),
                                             'password' => (isset($user_new_password) ? password_hash($user_new_password, PASSWORD_DEFAULT) : $user_details->password),
                                             'confirm_password' => (isset($user_confirm_password) ? password_hash($user_new_password, PASSWORD_DEFAULT) : $user_details->confirm_password),
                                             'status' => 'Active',
                                        );
                                        $query =   DB::table('users')->where('users_id', '=', $user_id)->update($user_data);
                                        if ($query) {
                                            if (!empty($user_details)) {

                                                $updated_user_details   = DB::table('users')->where('users_id', $user_id)->get();

                                                if($updated_user_details[0]->profile_picture != ""){
                                                $base_url_profile_images = config('base_urls.profile_images_base_url');
                                                $updated_user_details[0]->profile_picture = $base_url_profile_images.$updated_user_details[0]->profile_picture;
                                                }

                                                $response["code"] = 200;
                                                $response["hint_flag"] = "0";
                                                $response["status"] = "success";
                                                $response["data"] = array(
                                                    "user" => $updated_user_details
                                                );
                                            } else {
                                                $response["code"] = 404;
                                                $response["hint_flag"] = "0";
                                                $response["status"] = "error";
                                                $response["message"] = "User does not exists.";
                                            }
                                        } else {
                                            $response["code"] = 500;
                                            $response["status"] = "error";
                                            $response["hint_flag"] = "0";
                                            $response["message"] = "Something went wrong.";
                                        }
                                    }
                            } else {
                                $response["code"] = 500;
                                $response["status"] = "error";
                                $response["hint_flag"] = "0";
                                $response["message"] = "Old password not matched";
                            }

                        break;

                        case 'SignupWithSocial':
                                //Signup user type
                                $check_email_query = 0;
                                if(isset($data->userEmail)){
                                    $check_email_query = DB::table('users')->where('email', $user_email)->whereNotNull('email')->get()->count();
                                }
                             if($check_email_query > 0){
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["hint_flag"] = "0";
                                    $response["message"] = "This email already exists.";
                             } else{
                                if($user_old_password == ""){
                                    //update other details without password authentication
                                    $user_data = array(
                                      'first_name' =>  $first_name,
                                      'last_name' =>  (isset($last_name) ? $last_name : ''),
                                      'email' => (isset($user_email) ? $user_email : $user_details->email),
                                      'date_added' => date('Y-m-d h:i:s'),
                                      'status' => 'Active',
                                  );
                                  $query =   DB::table('users')->where('users_id', '=', $user_id)->update($user_data);
                                  if ($query) {
                                      if (!empty($user_details)) {

                                          $updated_user_details   = DB::table('users')->where('users_id', $user_id)->get();

                                          if($updated_user_details[0]->profile_picture != ""){
                                          $base_url_profile_images = config('base_urls.profile_images_base_url');
                                          $updated_user_details[0]->profile_picture = $base_url_profile_images.$updated_user_details[0]->profile_picture;
                                          }

                                          $response["code"] = 200;
                                          $response["hint_flag"] = "0";
                                          $response["status"] = "success";
                                          $response["data"] = array(
                                              "user" => $updated_user_details
                                          );
                                      } else {
                                          $response["code"] = 404;
                                          $response["hint_flag"] = "0";
                                          $response["status"] = "error";
                                          $response["message"] = "User does not exists.";
                                      }
                                  } else {
                                      $response["code"] = 500;
                                      $response["status"] = "error";
                                      $response["hint_flag"] = "0";
                                      $response["message"] = "Something went wrong.";
                                  }
                          }
                         else if(password_verify($user_old_password, $user_details->password)){
                                   //updating password as well with previous authentication
                                if(!(isset($user_new_password) == isset($user_confirm_password))){
                                    $response["code"] = 404;
                                    $response["status"] = "error";
                                    $response["hint_flag"] = "0";
                                    $response["message"] = "Password and Confirm Password not matched.";
                                } else{
                                    $user_data = array(
                                        'first_name' =>  $first_name,
                                        'last_name' =>  (isset($last_name) ? $last_name : ''),
                                        'email' => (isset($user_email) ? $user_email : $user_details->email),
                                        'date_added' => date('Y-m-d h:i:s'),
                                        'password' => (isset($user_new_password) ? password_hash($user_new_password, PASSWORD_DEFAULT) : $user_details->password),
                                        'confirm_password' => (isset($user_confirm_password) ? password_hash($user_new_password, PASSWORD_DEFAULT) : $user_details->confirm_password),
                                        'status' => 'Active',
                                    );
                                    $query =   DB::table('users')->where('users_id', '=', $user_id)->update($user_data);
                                    if ($query) {
                                        if (!empty($user_details)) {

                                            $updated_user_details   = DB::table('users')->where('users_id', $user_id)->get();
                                             if($updated_user_details[0]->profile_picture != ""){
                                            $base_url_profile_images = config('base_urls.profile_images_base_url');
                                            $updated_user_details[0]->profile_picture = $base_url_profile_images.$updated_user_details[0]->profile_picture;
                                        }

                                            if(password_verify('123456', $updated_user_details[0]->password)){
                                                $response["hint_flag"] = "1";
                                            } else{
                                                $response["hint_flag"] = "0";
                                            }

                                            $response["code"] = 200;
                                            $response["status"] = "success";
                                            $response["data"] = array(
                                                "user" => $updated_user_details
                                            );
                                        } else {
                                            $response["code"] = 404;
                                            $response["hint_flag"] = "0";
                                            $response["status"] = "error";
                                            $response["message"] = "User does not exists.";
                                        }
                                    } else {
                                        $response["code"] = 500;
                                        $response["hint_flag"] = "0";
                                        $response["status"] = "error";
                                        $response["message"] = "Something went wrong.";
                                    }
                                }
                        } else {
                            $response["code"] = 500;
                            $response["hint_flag"] = "0";
                            $response["status"] = "error";
                            $response["message"] = "Old password not matched";
                        }
                    }
                        break;

                            default:
                                    $response["code"] = 500;
                                    $response["hint_flag"] = "0";
                                    $response["status"] = "error";
                                    $response["message"] = "Some fault occured";
                            break;
                /// end switch case google/signup
                    }

            } else {
                $response["code"] = 404;
                $response["hint_flag"] = "0";
                $response["status"] = "error";
                $response["message"] = "All fields are needed.";
            }

            return response()
                    ->json(array('hint_flag' => $response["hint_flag"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
        }
        /*** UPDATE USER PROFILE ***/

    /*** LOGIN USER ***/
    public  function login(){
        $data = json_decode(file_get_contents("php://input"));
        $response = array();
        if (isset($data->userName) && isset($data->userPassword) && isset($data->oneSignalId)) {
            $checkUser = DB::table('users')->where('user_name', $data->userName)->get()->count();
            $user_details = DB::table('users')->where('user_name', $data->userName)->get();

            if ($checkUser > 0) {
                if (password_verify($data->userPassword, $user_details[0]->password)) {
                    $user_info = DB::table('users')->where('users_id', $user_details[0]->users_id)->first();
                    if (isset($user_info) &&  $user_info  != null) {
                        if ($user_details[0]->roles_id == '1') {

                             //updating one signal id
                            DB::table('users')->where('one_signal_id', $data->oneSignalId)->update(array('one_signal_id' => ""));
                            DB::table('users')->where('users_id', $user_details[0]->users_id)->update(array('one_signal_id' => $data->oneSignalId));
                             $user_details = DB::table('users')->where('users_id', $user_details[0]->users_id)->get();


                            if($user_details[0]->profile_picture != ""){
                                $base_url_profile_images = config('base_urls.profile_images_base_url');
                                $user_details[0]->profile_picture = $base_url_profile_images.$user_details[0]->profile_picture;
                                }


                                //count of event posts that user posted
                                $userEventCount = DB::table('event_posts')->where([ ['users_id', $user_info->users_id], ['status', 'Active'] ])->count();




                            $response["code"] = 200;
                            $response["status"] = "success";
                            $response["hint_flag"] = "0";
                            $response["total_user_event_posts"] = $userEventCount;
                            $response["data"] = array(
                                "user" =>  $user_details
                            );
                        }
                    } else {
                        $response["code"] = 200;
                        $response["status"] = "success";
                        $response["hint_flag"] = "0";
                        $response["total_user_event_posts"] = 0;
                        $response["data"] = array(
                            "isProfileSet" => false
                        );
                    }
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["hint_flag"] = "0";
                    $response["total_user_event_posts"] = 0;
                    $response["message"] = "User name or password are not correct";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["hint_flag"] = "0";
                $response["total_user_event_posts"] = 0;
                $response["message"] = "User name or password are not correct";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["hint_flag"] = "0";
            $response["total_user_event_posts"] = 0;
            $response["message"] = "All fields are needed.";
        }

        return response()
                    ->json(array('hint_flag' => $response["hint_flag"],'total_user_event_posts' => $response["total_user_event_posts"],'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** LOGIN USER ***/

    /*** FORGOT PASSWORD USER ***/
    public function forgot_password(){
		$postdata=file_get_contents("php://input");
		$post_data=json_decode($postdata);
		$response=array();
		$requestType=$post_data->requestType;

		switch($requestType){
			case 'forgot_password':
			    $email	    =   $post_data->email;
			    if(isset($email)){
			        $get_query = DB::table('users')->where('email', $email)->get()->count();
			        if($get_query > 0){
        				$update['verify_code'] = $this->generate_code(4);
                        $query =   DB::table('users')->where('email', '=', $email)->update($update);
    					if($query){
    						$email_template = "Dear,
    						Here is your verification code. This code can only be used for one time only.
    						{verify_code}";
    						$subject = "Verification Code";
    						$message = $email_template;
    						$message = str_replace("{verify_code}", $update['verify_code'], $message);
    						$result = $this->send_email($email, $subject, $message);
    						if($result){
    							$response['code']		= 200;
    							$response['status'] ='Success';
    							$response['message']='Verification Code is Successfully sent to your Email Address';
    						} else {
    							$response['code']		= 404;
    							$response['status'] ='error';
    							$response['message']='Verification Code is not sent to your Email Address';
    						}
    					} else {
    						$response['code']		= 404;
    						$response['status'] = "Error";
    						$response['msg']    = "Code is not generated";
    					}
			        } else {
        				$response['code']		= 404;
        				$response['status']="error";
        				$response['message']="This email is not present in database,Try with another email";
        			}
    			} else{
    				$response['code']		= 404;
    				$response['status'] ='error';
    				$response['message']='email is required';
			    }

				return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
		    break;

    		case 'match_code':
    			$email      = $post_data->email;
    			$code       = $post_data->code;
    			$email_get  = DB::table('users')->where('email', $email)->first();
    			if($email_get){
    				$matchCode=$email_get->verify_code;
    				if($matchCode == $code){
    					$response['code']		= 200;
    					$response['status'] = 'Success';
    					$response['message']= 'Your Code is Successfully Verified';
    				} else{
    					$response['code']		= 404;
    					$response['status'] = 'error';
    					$response['message']= 'Code doesnot match,Try Again Please';
    				}
    			} else{
    				$response['code']		= 404;
    				$response['status'] = 'error';
    				$response['message']= 'No such Email is present in Database';
    			}

    			return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    		break;

    		case 'reset_password':
    			$email	 	    = $post_data->email;
    			$password	    = $post_data->password;
    			$c_password	    = $post_data->c_password;
    			$email_get      = DB::table('users')->where('email', $email)->get()->first();
    			$old_password   = $email_get->password;
    			if($email_get){
    				if($password == $c_password){
    					if($old_password != $password){
    						$update['password'] = password_hash($password,PASSWORD_BCRYPT);
                            $query =   DB::table('users')->where('email', '=', $email)->update($update);
    						if($query){
    							$response['code']		= 200;
    							$response['status'] = 'Success';
    							$response['message']= 'You have successfully Updated Your Password';
    						} else {
    							$response['code']		= 404;
    							$response['status'] = 'error';
    							$response['message']= 'Password is not Updated';
    						}
    					} else {
    						$response['code']		= 404;
    						$response['status'] = 'error';
    						$response['message']= 'This is your OLD password,Please try with new Strong Password';
    					}
    				} else {
    					$response['code']	= 404;
    					$response['status'] = 'error';
    					$response['message']= 'Password and confirm password are not Matching';
    				}
    			} else {
    				$response['code']	= 404;
    				$response['status'] = 'error';
    				$response['message']= 'No such Email is present in Database';
    			}

    			return response()
                    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    		break;
		}
    }
    /*** FORGOT PASSWORD USER ***/
public function test_function()
{
    $result = $this->send_email('sherazrazajafery@gmail.com', 'Verification Code', 'Dear,
    Total withdraw amount you requested is');
    return $result;
}

    /*** OTP ***/
    public function otp(){
        $postdata=file_get_contents("php://input");
        $post_data=json_decode($postdata);
        $response=array();

                $email	    =   $post_data->email;
                $paypal_email	    =   $post_data->paypal_email;
                $amount	    =   $post_data->amount;
                if(isset($email)){

                        $otpp = $this->generate_code(4);

                            $email_template = "Dear,
                            Total withdraw amount you requested is $".$amount." which is transfered into".$paypal_email.". To conform the transaction
    						Here is your verification code. This code can only be used for one time only.
    						{verify_code}";
                            $subject = "Verification Code";
                            $message = $email_template;
                            $message = str_replace("{verify_code}", $otpp, $message);
                            $result = $this->send_email($paypal_email, $subject, $message);
                            if($result){
                                $response['code']		= 200;
                                $response['status'] ='Success';
                                $response['message']='Verification Code is Successfully sent to your Email Address';
                                $response['code']=$otpp;
                            } else {
                                $response['code']		= 404;
                                $response['status'] ='error';
                                $response['message']='Verification Code is not sent to your Email Address';
                            }

                } else{
                    $response['code']		= 404;
                    $response['status'] ='error';
                    $response['message']='email is required';
                }

                return response()
                    ->json(array('status' => $response["status"], 'code' => $response["code"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                    ->header('Content-Type', 'application/json');
    }
    /*** OTP ***/


     ////////////////////////////////////////////////////

           /***TEST CA**/
    public function test_get(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        $calculatedDistance =   $this->calculate_miles($data->latitude1, $data->longitude1, $data->latitude2, $data->longitude2, 'M');

                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] =  $calculatedDistance.' miles';

    return response()
    ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
}
           /***TEST GET API**/


    //  ======================== FUNCTIONS =============== //
    public function decode_image($img , $unique_id, $path_url, $prefix, $random, $postfix){

            $data = base64_decode($img);
            $file_name = $prefix.$unique_id.$random.$postfix.'.jpeg';
            $file = $path_url.$file_name;
            $success = file_put_contents($file, $data);
            return $file_name;

    }
       public function convert_conncash($conncash){
                $detail =   DB::table('system_settings')->where('type','one_dollar_conncash_rate')->first();
                $convertedDollars = $conncash / $detail->description;
                return $convertedDollars;
    }
        public function convert_dollars($dollars){
                        $detail =   DB::table('system_settings')->where('type','one_dollar_conncash_rate')->first();
                        $convertedConncash = $dollars * $detail->description;
                        return $convertedConncash;
            }


    public function sendpushnotification($oneSignalDeviceId, $message, $type)
    {
        $content = array(
            "en" => $message,
        );

        $fields = array(
            'app_id'    =>  env('ONE_SIGNAL_APP_ID'),
            'include_player_ids' => [$oneSignalDeviceId],
            'data' => array("type" => $type),
            'contents' => $content
        );

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }



   public function get_day_difference($timestamp){
    $today = new DateTime(date('Y-m-d h:i:s'));
    $thatDay = new DateTime($timestamp);
    $dt = $today->diff($thatDay);
            if ($dt->y > 0){
                $number = $dt->y;
                $unit = "year";
            } else if ($dt->m > 0) {
                $number = $dt->m;
                $unit = "month";
            } else if ($dt->d > 0){
                $number = $dt->d;
                $unit = "day";
            } else if ($dt->h > 0) {
                $number = $dt->h;
                $unit = "hour";
            } else if ($dt->i > 0) {
                $number = $dt->i;
                $unit = "minute";
            } else if ($dt->s > 0) {
                $number = $dt->s;
                $unit = "second";
            }
            $unit .= $number > 1 ? "s" : "";
            $ret = $number." ".$unit." "."ago";
            if($unit == 'hours' && $number <=24){
                return 'Today';
            }else if($unit =='minutes' && $number <=60){
                return 'Today';
            }else if($unit == 'day' && $number ==1){
                return 'Yesterday';
            }else{
                return $ret;
            }
        }




   public function get_days_difference($timestamp){
            $today = new DateTime(date('Y-m-d h:i:s'));
            $thatDay = new DateTime($timestamp);
            $dt = $today->diff($thatDay);
              return $dt->d;
        }


            function time_elapsed_string($datetime, $full = false) {
                $now = new DateTime;
                $ago = new DateTime($datetime);
                $diff = $now->diff($ago);

                $diff->w = floor($diff->d / 7);
                $diff->d -= $diff->w * 7;

                $string = array(
                    'y' => 'year',
                    'm' => 'month',
                    'w' => 'week',
                    'd' => 'day',
                    'h' => 'hour',
                    'i' => 'minute',
                    's' => 'second',
                );
                foreach ($string as $k => &$v) {
                    if ($diff->$k) {
                        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                    } else {
                        unset($string[$k]);
                    }
                }

                if (!$full) $string = array_slice($string, 0, 1);
                return $string ? implode(', ', $string) . ' ago' : 'just now';
            }


            function calculate_miles($lat1, $lon1, $lat2, $lon2, $unit) {
                if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                    $miles = 0;
                  return $miles;
                }
                else {
                  $theta = $lon1 - $lon2;
                  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $unit = strtoupper($unit);

                  if ($unit == "K") {
                    return ($miles / 0.62137);
                  } else {
                    return $miles;
                  }
                }
              }


              function thousandsCurrencyFormat($num) {

                if($num>1000) {
                      $x = round($num);
                      $x_number_format = number_format($x);
                      $x_array = explode(',', $x_number_format);
                      $x_parts = array(' k', ' m', ' b', ' t');
                      $x_count_parts = count($x_array) - 1;
                      $x_display = $x;
                      $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
                      $x_display .= $x_parts[$x_count_parts - 1];
                      return $x_display;

                }

                return $num;
              }


        function generateRandomString($length) {
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }


             //--- Create stripe account
 public function create_stripe_account($email){
        $secret_key       = env('STRIPE_SECRET');
        $publishable_key = env('STRIPE_PUBLISHABLE');
        require_once('vendor/stripe-php/init.php');

        $stripe = new \Stripe\StripeClient(
            $secret_key
          );
          $customer = $stripe->accounts->create([
            'type' => 'express',
            'country' => 'US',
            'email' => $email,
            'capabilities' => [
              'card_payments' => ['requested' => true],
              'transfers' => ['requested' => true],
            ],
            'business_type' => 'individual'
          ]);
         $customerJSON = $customer->jsonSerialize();
          return $customerJSON['id'];

}
//./--- Create stripe account


// For transfering stripe payments
public function transfer_stripe_payment($amount,$stripe_account_id){

    $secret_key       = env('STRIPE_SECRET');
    $publishable_key = env('STRIPE_PUBLISHABLE');
    require_once('vendor/stripe-php/init.php');

    $stripe = new \Stripe\StripeClient(
        $secret_key
      );
    $transfer_response = '';
      try {
        $transfer_response = $stripe->transfers->create([
            'amount' => $amount,
            'currency' => 'usd',
            'destination' => $stripe_account_id,
            'transfer_group' => 'ORDER_95',
            'description' => 'With draw ammount',
          ]);
        $response['message']  = "done";
      }
      catch(\Stripe\Exception\CardException $e) {
            $response['code']    = $e->getError()->code;
            $response['type']    = $e->getError()->type;
            $response['param']   = $e->getError()->param;
            $response['message'] = $e->getError()->message;

      } catch (\Stripe\Exception\RateLimitException $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      } catch (\Stripe\Exception\InvalidRequestException $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      } catch (\Stripe\Exception\AuthenticationException $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      } catch (\Stripe\Exception\ApiConnectionException $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      } catch (\Stripe\Exception\ApiErrorException $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      } catch (Exception $e) {
            $response['code'] = $e->getError()->code;
            $response['type'] = $e->getError()->type;
            $response['param'] = $e->getError()->param;
            $response['message'] = $e->getError()->message;
      }
        $send_data['response'] = $response;
        $send_data['transfer_response'] = $transfer_response;
        return $send_data;

}



    // For Generating stripe account link
    public function generate_stripe_account_link($account_id){
        $secret_key       = env('STRIPE_SECRET');
        $publishable_key = env('STRIPE_PUBLISHABLE');
            require_once('vendor/stripe-php/init.php');
            
        $stripe = new \Stripe\StripeClient(
                $secret_key
            );
            $stripe_link =  $stripe->accountLinks->create(
                [
                'account' => $account_id,
                'refresh_url' => base_url(),
                'return_url' => base_url(),
                'type' => 'account_onboarding',
                ]
            );
            return $stripe_link;
    }

    // For Generating stripe account link

    /*** USER SOCIAL LINKS ***/
    public function update_users_social_links()
    {
        $response = array();
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->userId)) {
            $user_exists = DB::table('users')->where('users_id', $data->userId)->first();

            if ($user_exists) {
                $update_data = array();

                if (isset($data->facebook_url)) {
                    $update_data['facebook_url'] = $data->facebook_url;
                }

                if (isset($data->instagram_url)) {
                    $update_data['instagram_url'] = $data->instagram_url;
                }

                if (isset($data->twitter_url)) {
                    $update_data['twitter_url'] = $data->twitter_url;
                }

                if (isset($data->youtube_url)) {
                    $update_data['youtube_url'] = $data->youtube_url;
                }

                if (isset($data->tiktok_url)) {
                    $update_data['tiktok_url'] = $data->tiktok_url;
                }

                if (count($update_data)>0) {
                    $update_social_links = DB::table('users')->where('users_id', $data->userId)->update($update_data);

                    if ($update_social_links) {
                        $update_social_links = DB::table('users')
                            ->select("facebook_url", "twitter_url", "instagram_url","youtube_url","tiktok_url")
                            ->where('users_id', $data->userId)
                            ->first();

                        $response["code"] = 200;
                        $response["hint_flag"] = "0";
                        $response["status"] = "success";
                        $response["data"] = $update_social_links;
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["hint_flag"] = "0";
                        $response["message"] = "Failed to update social links.";
                    }
                } else {
                    $response["code"] = 200; // Assuming it should be 200 for this case
                    $response["status"] = "success";
                    $response["hint_flag"] = "0";
                    $response["message"] = "No social links to update.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["hint_flag"] = "0";
                $response["message"] = "User does not exist.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["hint_flag"] = "0";
            $response["message"] = "All fields are needed.";
        }

        return response()
            ->json(array('hint_flag' => $response["hint_flag"], 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    /*** USER SOCIAL LINKS ***/

    /*** GET USER SOCIAL LINKS ***/
    public  function get_users_social_links(){
        $response = array();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->userId)) {
            $username_exists = DB::table('users')->where('users_id', $data->userId)->first();
            if ($username_exists) {
                    $update_social_links = DB::table('users')->select("facebook_url","twitter_url","instagram_url","tiktok_url","youtube_url")->where('users_id', $data->userId)->first();

                    if ($update_social_links) {
                        $response["code"] = 200;
                        $response["hint_flag"] = "0";
                        $response["status"] = "success";
                        $response["data"] = $update_social_links;
                    } else {
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["hint_flag"] = "0";
                        $response["message"] = "User does not exists.";
                    }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["hint_flag"] = "0";
                $response["message"] = "User does not exists.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["hint_flag"] = "0";
            $response["message"] = "All fields are needed.";
        }

        return response()
        ->json(array('hint_flag' => $response["hint_flag"] , 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
    }
    /*** GET USER SOCIAL LINKS ***/

}
