<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthenticationApiController;
use App\Http\Controllers\BusinessApiController;
use App\Http\Controllers\EventApiController;
use App\Http\Controllers\EventPeeksApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewHomeController;
use App\Http\Controllers\LiveStreamingController;
use App\Helpers\Helper;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ----------------------------------- WEB API PANEL --------------------------------------------- */

 Route::get('/clear', function() {
     $exitCode = Artisan::call('route:clear');
     echo 'Routes cache cleared';
     
     
          $exitCode = Artisan::call('config:cache');
     echo 'Config cache cleared';
     
     
          $exitCode = Artisan::call('cache:clear');
     echo 'Application cache cleared';
     
     
         $exitCode = Artisan::call('view:clear');
     echo 'View cache cleared';
     
         $exitCode = Artisan::call('optimize:clear');
     echo 'optimize cleared';
 });



//start sheraz practice routes
Route::post('/api/add_tag_practice_api', [EventApiController::class, 'add_tag_practice']);
Route::get('/api/show_tag_practice_api', [EventApiController::class, 'show_tag_practice']);
Route::post('/api/delete_tag_practice_api/{id}', [EventApiController::class, 'delete_tag_practice']);

Route::post('/api/update_tag_practice_api/{id}', [EventApiController::class, 'update_tag_practice']);

Route::get('api/testMail', [LoginAuthenticationApiController::class, 'test_function']);

//end sheraz practice routes

Route::post('/api/test_get', [EventApiController::class, 'test_get']);
Route::post('/api/add_log',    [EventApiController::class, 'add_log']);


Route::get('/api/token', [LoginAuthenticationApiController::class, 'get_token']);

//USER AUTHENTICATION
Route::post('/api/signup', [LoginAuthenticationApiController::class, 'signup']);
Route::post('/api/signup_with_acc', [LoginAuthenticationApiController::class, 'signup_with_acc']);
Route::post('/api/login', [LoginAuthenticationApiController::class, 'login']);
Route::post('/api/forgot_password', [LoginAuthenticationApiController::class, 'forgot_password']);
Route::post('/api/otp', [LoginAuthenticationApiController::class, 'otp']);

//USER PROFILE
Route::post('/api/update_profile_picture', [LoginAuthenticationApiController::class, 'update_profile_picture']);
Route::post('/api/update_profile', [LoginAuthenticationApiController::class, 'update_profile']);

//USER SOCIAL LINKS
Route::post('/api/update_users_social_links', [LoginAuthenticationApiController::class, 'update_users_social_links']);
Route::post('/api/get_users_social_links', [LoginAuthenticationApiController::class, 'get_users_social_links']);

//USER CATEGORIES
Route::post('/api/user_categories', [EventApiController::class, 'user_categories']);
Route::post('/api/specific_user_categories', [EventApiController::class, 'specific_user_categories']);
Route::get('/api/categories', [EventApiController::class, 'categories']);

//TAGS
Route::get('/api/tags', [EventApiController::class, 'tags']);
//Custom Tags
Route::get('/api/get_all_tags', [EventApiController::class, 'get_all_tags']);
Route::post('/api/get_all_tags_with_custom', [EventApiController::class, 'get_all_tags_with_custom']);
Route::post('/api/add_custom_tag', [EventApiController::class, 'add_custom_tag']);
Route::post('/api/remove_custom_tag', [EventApiController::class, 'remove_custom_tag']);


//EVENT TYPES
Route::get('/api/event_types', [EventApiController::class, 'event_types']);

//DRESS CODES
Route::get('/api/dress_codes', [EventApiController::class, 'dress_codes']);


//CARD AND CONNCASH DETAILS
Route::post('/api/get_card_details', [EventApiController::class, 'get_card_details']);
Route::post('/api/store_card_details', [EventApiController::class, 'store_card_details']);
Route::post('/api/delete_card_details', [EventApiController::class, 'delete_card_details']);
Route::post('/api/get_conncash_details', [EventApiController::class, 'get_conncash_details']);


//VIDEO UPLOADING BEFORE EVENT CREATED
Route::post('/api/upload_video', [EventApiController::class, 'upload_video']);
Route::post('/api/delete_video', [EventApiController::class, 'delete_video']);


//DELETE IMAGE AND VIDEOS AFTER EVENT CREATED
Route::post('/api/created_event_delete_image', [EventApiController::class, 'created_event_delete_image']);
Route::post('/api/created_event_delete_video', [EventApiController::class, 'created_event_delete_video']);

//EVENT POSTS
Route::post('/api/create_event_post', [EventApiController::class, 'create_event_post']);
Route::post('/api/get_event_posts', [EventApiController::class, 'get_event_posts']);
Route::post('/api/delete_event_post', [EventApiController::class, 'delete_event_post']);
Route::post('/api/search_event_post', [EventApiController::class, 'search_event_post']);
Route::post('/api/edit_event_post', [EventApiController::class, 'edit_event_post']);
Route::post('/api/get_user_event_posts', [EventApiController::class, 'get_user_event_posts']);
Route::post('/api/user_event_type_posts', [EventApiController::class, 'user_event_type_posts']);
Route::post('/api/host_room_event_posts', [EventApiController::class, 'host_room_event_posts']);
Route::post('/api/cancel_event_post', [EventApiController::class, 'cancel_event_post']);
Route::post('/api/favourite_event_post', [EventApiController::class, 'favourite_event_post']);
Route::post('/api/unfavourite_event_post', [EventApiController::class, 'unfavourite_event_post']);
Route::post('/api/get_all_favourites', [EventApiController::class, 'get_all_favourites']);
Route::post('/api/get_selected_event_category', [EventApiController::class, 'get_selected_event_category']);
Route::post('/api/get_event_type_categories', [EventApiController::class, 'get_event_type_categories']);

// Check social login
Route::get('/api/check_social_login', [EventApiController::class, 'check_social_login']);

//SEARCH FILTER
Route::get('/api/get_event_cities', [EventApiController::class, 'get_event_cities']);
Route::post('/api/get_event', [EventApiController::class, 'get_event']);
Route::post('/api/event_search_filter', [EventApiController::class, 'event_search_filter']);
Route::post('/api/event_search_bar_filter', [EventApiController::class, 'event_search_bar_filter']);

//PURCHASE TICKETS
Route::post('/api/user_purchase_tickets_card', [EventApiController::class, 'user_purchase_tickets_card']);
Route::post('/api/user_purchase_tickets_google_pay', [EventApiController::class, 'user_purchase_tickets_google_pay']);
Route::post('/api/user_purchase_tickets_conncash', [EventApiController::class, 'user_purchase_tickets_conncash']);

//REFUND TICKETS
Route::post('/api/request_refund', [EventApiController::class, 'request_refund']);
Route::post('/api/accept_refund_request', [EventApiController::class, 'accept_refund_request']);
Route::post('/api/get_refund_requests', [EventApiController::class, 'get_refund_requests']);

//REPORT EVENT
Route::post('/api/report_event', [EventApiController::class, 'report_event']);

//LIKE AND UNLIKE EVENT
Route::post('/api/like_event', [EventApiController::class, 'like_event']);
Route::post('/api/unlike_event', [EventApiController::class, 'unlike_event']);

//SALES MANAGEMENT
Route::post('/api/ticket_sales', [EventApiController::class, 'ticket_sales']);


// PACKAGE SUBSCRIPTION
Route::post('/api/subscribe_to_premium', [EventApiController::class, 'subscribe_to_premium']);
Route::post('/api/one_time_post_purchase', [EventApiController::class, 'one_time_post_purchase']);
Route::post('/api/cancel_subscription', [EventApiController::class, 'cancel_subscription']);
Route::post('/api/check_user_subscription', [EventApiController::class, 'check_user_subscription']);
Route::post('/api/post_count_available', [EventApiController::class, 'post_count_available']);

// EVENT COMMENTS ROUTES
Route::post('/api/comment_on_event', [EventApiController::class, 'comment_on_event']);
Route::post('/api/get_comment_mentions', [EventApiController::class, 'get_comment_mentions']);
Route::post('/api/get_all_comments', [EventApiController::class, 'get_all_comments']);
Route::post('/api/get_comment_replies', [EventApiController::class, 'get_comment_replies']);
Route::post('/api/delete_comment', [EventApiController::class, 'delete_comment']);
Route::post('/api/like_comment', [EventApiController::class, 'like_comment']);
Route::post('/api/unlike_comment', [EventApiController::class, 'unlike_comment']);
Route::post('/api/report_comment', [EventApiController::class, 'report_comment']);

Route::post('/api/report_peek', [EventApiController::class, 'report_peek']);

//chat
Route::post('/api/upload_attachment', [EventApiController::class, 'upload_attachment']);
Route::post('/api/chat', [EventApiController::class, 'chat']);
Route::post('/api/get_unread_messages_count', [EventApiController::class, 'get_unread_messages_count']);

//Ticket history-- Guest list-- purchased tickets for refund
Route::post('/api/ticket_history', [EventApiController::class, 'ticket_history']);
Route::post('/api/event_guests_list', [EventApiController::class, 'event_guests_list']);
Route::post('/api/my_purchased_tickets_for_refund', [EventApiController::class, 'my_purchased_tickets_for_refund']);

//Checkin
Route::post('/api/checkin', [EventApiController::class, 'checkin']);


//Notifications
Route::post('/api/get_user_notifications_badge_count', [EventApiController::class, 'get_user_notifications_badge_count']);
Route::post('/api/get_user_notification_preferences', [EventApiController::class, 'get_user_notification_preferences']);
Route::post('/api/update_notification_setting', [EventApiController::class, 'update_notification_setting']);
Route::post('/api/get_all_notifications', [EventApiController::class, 'get_all_notifications']);

//Event Library
Route::post('/api/get_event_library_list', [EventApiController::class, 'get_event_library_list']);
Route::post('/api/get_my_event_library_list', [EventApiController::class, 'get_my_event_library_list']);
Route::post('/api/get_my_event_library_items', [EventApiController::class, 'get_my_event_library_items']);
Route::post('/api/get_others_event_library_items', [EventApiController::class, 'get_others_event_library_items']);
Route::post('/api/submit_library_item_details', [EventApiController::class, 'submit_library_item_details']);
Route::post('/api/upload_library_item', [EventApiController::class, 'upload_library_item']);
Route::post('/api/like_library_item', [EventApiController::class, 'like_library_item']);
Route::post('/api/delete_library_item', [EventApiController::class, 'delete_library_item']);

//Redeem points
Route::post('/api/get_user_conncash_points', [EventApiController::class, 'get_user_conncash_points']);
Route::post('/api/redeem_conncash_points', [EventApiController::class, 'redeem_conncash_points']);

//Event invites
Route::post('/api/get_recent_invited_contacts', [EventApiController::class, 'get_recent_invited_contacts']);
Route::post('/api/specific_event_details', [EventApiController::class, 'specific_event_details']);
Route::post('/api/store_invited_contacts', [EventApiController::class, 'store_invited_contacts']);
Route::post('/api/add_invite', [EventApiController::class, 'add_invite']);

//Host Rooms API
Route::post('/api/create_host_room', [LiveStreamingController::class, 'create_host_room']);
Route::post('/api/get_rtc_token', [LiveStreamingController::class, 'get_rtc_token']);
Route::post('/api/start_live_stream', [LiveStreamingController::class, 'start_live_stream']);
Route::post('/api/end_live_stream_explicitly', [LiveStreamingController::class, 'end_live_stream_explicitly']);
Route::post('/api/end_live_stream_cron', [LiveStreamingController::class, 'end_live_stream_cron']);

//Earnings and withdraw
Route::post('/api/get_organizer_earning', [EventApiController::class, 'get_organizer_earning']);
Route::post('/api/withdraw_earning', [EventApiController::class, 'withdraw_earning']);

Route::post('/api/store_withdraw_details', [EventApiController::class, 'store_withdraw_details']);
Route::post('/api/get_bank_accounts', [EventApiController::class, 'get_bank_accounts']);

Route::post('/api/store_paypal_transaction_details', [EventApiController::class, 'store_paypal_transaction_details']);
Route::get('/api/get_paypal_config', [EventApiController::class, 'get_paypal_config']);

//Follow user
Route::post('/api/follow_user', [EventApiController::class, 'follow_user']);
Route::post('/api/get_followers_count', [EventApiController::class, 'get_followers_count']);

//Organizer portfolio and followers
Route::post('/api/organizer_portfolio', [EventApiController::class, 'organizer_portfolio']);
Route::post('/api/get_organizer_event_library_items', [EventApiController::class, 'get_organizer_event_library_items']);
Route::post('/api/get_organizer_followers_list', [EventApiController::class, 'get_organizer_followers_list']);
Route::post('/api/export_organizer_followers_emails', [EventApiController::class, 'export_organizer_followers_emails']);

//Event peeks
Route::post('/api/upload_event_peek_video', [EventPeeksApiController::class, 'upload_event_peek_video']);
Route::post('/api/upload_event_peek_details', [EventPeeksApiController::class, 'upload_event_peek_details']);
Route::post('/api/get_event_peeks', [EventPeeksApiController::class, 'get_event_peeks']);
Route::post('/api/check_user_checkedin_event', [EventPeeksApiController::class, 'check_user_checkedin_event']);
Route::post('/api/peek_dropdown_event_list', [EventPeeksApiController::class, 'peek_dropdown_event_list']);
Route::post('/api/view_event_peek', [EventPeeksApiController::class, 'view_event_peek']);
Route::post('/api/like_unlike_event_peek', [EventPeeksApiController::class, 'like_unlike_event_peek']);
Route::post('/api/comment_on_peek', [EventPeeksApiController::class, 'comment_on_peek']);
Route::post('/api/get_user_peek_comments', [EventPeeksApiController::class, 'get_user_peek_comments']);
Route::post('/api/get_peek_comments_count', [EventPeeksApiController::class, 'get_peek_comments_count']);
Route::post('/api/delete_event_peek', [EventPeeksApiController::class, 'delete_event_peek']);
Route::post('/api/specific_event_peek_details', [EventPeeksApiController::class, 'specific_event_peek_details']);



//Businesses
Route::post('/api/create_business', [BusinessApiController::class, 'create_business']);
Route::post('/api/edit_business', [BusinessApiController::class, 'edit_business']);
Route::post('/api/delete_business', [BusinessApiController::class, 'delete_business']);
Route::post('/api/business_search_filter', [BusinessApiController::class, 'business_search_filter']);
Route::post('/api/user_business_posts', [BusinessApiController::class, 'user_business_posts']);
Route::post('/api/like_unlike_business_post', [BusinessApiController::class, 'like_unlike_business_post']);
Route::post('/api/comment_on_business', [BusinessApiController::class, 'comment_on_business']);
Route::post('/api/report_business', [BusinessApiController::class, 'report_business']);
Route::post('/api/get_comment_mentions_business', [BusinessApiController::class, 'get_comment_mentions_business']);
Route::post('/api/get_all_comments_business', [BusinessApiController::class, 'get_all_comments_business']);
Route::post('/api/get_comment_replies_business', [BusinessApiController::class, 'get_comment_replies_business']);
Route::post('/api/delete_comment_business', [BusinessApiController::class, 'delete_comment_business']);
Route::post('/api/like_comment_business', [BusinessApiController::class, 'like_comment_business']);
Route::post('/api/unlike_comment_business', [BusinessApiController::class, 'unlike_comment_business']);
Route::post('/api/report_business_comment', [BusinessApiController::class, 'report_business_comment']);
Route::post('/api/favourite_business', [BusinessApiController::class, 'favourite_business']);
Route::post('/api/unfavourite_business', [BusinessApiController::class, 'unfavourite_business']);
Route::post('/api/get_all_business_favourites', [BusinessApiController::class, 'get_all_business_favourites']);

Route::post('/api/created_business_delete_image', [BusinessApiController::class, 'created_business_delete_image']);
Route::post('/api/created_business_delete_video', [BusinessApiController::class, 'created_business_delete_video']);
Route::get('/api/get_business_types', [BusinessApiController::class, 'get_business_types']);



/* ==================================================================================================================== */

/* ==================================================================================================================== */



/* ----------------------------------- WEB API PANEL --------------------------------------------- */
/* ----------------------------------- ADMIN PANEL --------------------------------------------- */
 // Base Authentication Routes
Route::get('/', [NewHomeController::class, 'index']);
Route::post('/login_submit', [HomeController::class, 'login']);
Route::get('/logout', [HomeController::class, 'logout']);


// Users CRUD management
Route::get('/manage_users', [HomeController::class, 'manage_users']);
Route::post('/user_update', [HomeController::class, 'user_update']);
Route::post('/delete_user', [HomeController::class, 'delete_user']);
//Route::post('/add_user', [HomeController::class, 'add_user']);

// Categories CRUD management
Route::get('/manage_categories', [HomeController::class, 'manage_categories']);
Route::post('/category_update', [HomeController::class, 'category_update']);
Route::post('/delete_category', [HomeController::class, 'delete_category']);
Route::post('/add_category', [HomeController::class, 'add_category']);


// Dress Code CRUD management
Route::get('/manage_dresscodes', [HomeController::class, 'manage_dresscodes']);
Route::post('/dresscode_update', [HomeController::class, 'dresscode_update']);
Route::post('/delete_dresscode', [HomeController::class, 'delete_dresscode']);
Route::post('/add_dresscode', [HomeController::class, 'add_dresscode']);


// Event Type CRUD management
Route::get('/manage_eventtypes', [HomeController::class, 'manage_eventtypes']);
Route::post('/eventtype_update', [HomeController::class, 'eventtype_update']);
Route::post('/delete_eventtype', [HomeController::class, 'delete_eventtype']);
Route::post('/add_eventtype', [HomeController::class, 'add_eventtype']);


// Predefined Tags CRUD management
Route::get('/manage_tags', [HomeController::class, 'manage_tags']);
Route::post('/tag_update', [HomeController::class, 'tag_update']);
Route::post('/delete_tag', [HomeController::class, 'delete_tag']);
Route::post('/add_tag', [HomeController::class, 'add_tag']);


// Usercategories CRUD management
Route::get('/manage_usercategories', [HomeController::class, 'manage_usercategories']);
Route::post('/usercategory_update', [HomeController::class, 'usercategory_update']);
Route::post('/delete_usercategory', [HomeController::class, 'delete_usercategory']);
//Route::post('/add_usercategory', [HomeController::class, 'add_usercategory']);


// Cards CRUD management
Route::get('/manage_cards', [HomeController::class, 'manage_cards']);
Route::post('/card_update', [HomeController::class, 'card_update']);
Route::post('/delete_card', [HomeController::class, 'delete_card']);
//Route::post('/add_card', [HomeController::class, 'add_card']);


// Conncash CRUD management
Route::get('/manage_conncash', [HomeController::class, 'manage_conncash']);
Route::post('/conncash_update', [HomeController::class, 'conncash_update']);
//Route::post('/delete_card', [HomeController::class, 'delete_card']);
Route::post('/add_conncash', [HomeController::class, 'add_conncash']);
Route::get('/request_payment', [HomeController::class, 'request_payment']);




// Addresses CRUD management
Route::get('/manage_addresses', [HomeController::class, 'manage_addresses']);
Route::post('/address_update', [HomeController::class, 'address_update']);
//Route::post('/delete_address', [HomeController::class, 'delete_address']);
//Route::post('/add_address', [HomeController::class, 'add_address']);

// Event Tickets CRUD management
Route::get('/manage_eventtickets', [HomeController::class, 'manage_eventtickets']);
Route::post('/eventticket_update', [HomeController::class, 'eventticket_update']);
Route::post('/delete_eventticket', [HomeController::class, 'delete_eventticket']);
Route::post('/add_eventticket',    [HomeController::class, 'add_eventticket']);

// Event Tags CRUD management
Route::get('/manage_eventtags', [HomeController::class, 'manage_eventtags']);
Route::post('/eventtag_update', [HomeController::class, 'eventtag_update']);
Route::post('/delete_eventtag', [HomeController::class, 'delete_eventtag']);
Route::post('/add_eventtag',    [HomeController::class, 'add_eventtag']);

// Event Post CRUD management
Route::get('/manage_eventposts', [HomeController::class, 'manage_eventposts']);
Route::post('/eventpost_update', [HomeController::class, 'eventpost_update']);
Route::post('/delete_eventpost', [HomeController::class, 'delete_eventpost']);
Route::post('/add_eventpost',    [HomeController::class, 'add_eventpost']);



/////// ========== PREVIOUS ADMIN PANEL ROUTES =============  /////

//Add LISTING
Route::get('/add_listing', [HomeController::class, 'add_listing']);
Route::post('/post_listing', [HomeController::class, 'post_listing']);
//Add LISTING

//EDIT  LISTING
Route::get('/editlisting/{id}/{status}', [HomeController::class, 'editlisting']);
Route::post('/updatelist/{id}', [HomeController::class, 'updatelist']);
//EDIT LISTING

//VIEW  LISTING
Route::get('/view_listings/{id}', [HomeController::class, 'view_listings']);
//VIEW LISTING

//DELETE LISTING
Route::get('/delete_listings/{id}', [HomeController::class, 'delete_listings']);
//DELETE LISTING

//MY LISTING
Route::get('/listing_my_pending', [HomeController::class, 'listing_my_pending']);
Route::get('/listing_my_active', [HomeController::class, 'listing_my_active']);
Route::get('/listing_my_inactive', [HomeController::class, 'listing_my_inactive']);
Route::get('/listing_my_expired', [HomeController::class, 'listing_my_expired']);
Route::get('/listing_my_inreview', [HomeController::class, 'listing_my_inreview']);
Route::get('/listing_my_deleted', [HomeController::class, 'listing_my_deleted']);
//MY LISTING



//Start GENERAl Settings
Route::get('/language_settings', [HomeController::class, 'language_settings']);
Route::get('/system_settings', [HomeController::class, 'system_settings']);
//End GENERAl Settings

//Start Update System Settings
Route::post('/system_update', [HomeController::class, 'system_update']);
//Start Update System Settings

//Start Account Settings
Route::get('/account_settings', [HomeController::class, 'account_settings']);
//End Account Settings

//Start Account  Update Settings
Route::post('/profile_update/{id}', [HomeController::class, 'profile_update']);


//email templates
Route::get('/email_templates', [HomeController::class, 'email_templates']);
Route::get('/post_template', [HomeController::class, 'post_template']);

Route::post('/add_template', [HomeController::class, 'add_template']);

Route::get('/view_templates/{id}', [HomeController::class, 'view_templates']);

Route::get('/edit_template/{id}', [HomeController::class, 'edit_template']);
Route::post('/update_template/{id}', [HomeController::class, 'update_template']);
Route::get('/delete_templates/{id}', [HomeController::class, 'delete_templates']);
//email templates

//new template route

Route::get('/wel', function () {
    return "hello";
    
     $exitCode = Artisan::call('route:cache');
     return 'Routes cache cleared';
    
         $exitCode = Artisan::call('config:cache');
     return 'Config cache cleared';
     
          $exitCode = Artisan::call('cache:clear');
     return 'Application cache cleared';
     
          $exitCode = Artisan::call('view:clear');
     return 'View cache cleared';
    
});
Route::get('admin', function () {
    return redirect('/new');
});
// Route::get('dashboard', function () {
//     return view('Dashboard');
// });

//new template route

//ajmal admin panel

/* ----------------------------------- WEB API PANEL --------------------------------------------- */
/* ----------------------------------- ADMIN PANEL --------------------------------------------- */
 // Base Authentication Routes
 Route::get('/new', [NewHomeController::class, 'index']);
 Route::post('/new_login_submit', [NewHomeController::class, 'login']);
 Route::get('/new_logout', [NewHomeController::class, 'logout']);
 
 //Account setting CRUD
//  Route::get('/account', [NewHomeController::class, 'account_setting']);
 Route::get('/dashboard', [NewHomeController::class, 'dash_board']);
 // Users CRUD management
 Route::get('/new_manage_users', [NewHomeController::class, 'manage_users']);
 Route::get('/show_new_manage_users', [NewHomeController::class, 'show_users'])->name('show_new_manage_users');

 Route::post('/new_user_update', [NewHomeController::class, 'user_update']);
 Route::post('/new_delete_user', [NewHomeController::class, 'delete_user']);
 //Route::post('/add_user', [HomeController::class, 'add_user']);


 // event_tags_crud_routes
//  Route::get('/event_tags_edit/{id}', [NewHomeController::class, 'event_tags_updation'])->name('event_tags_edit');

 
 // Categories CRUD management
 Route::get('/new_manage_categories', [NewHomeController::class, 'manage_categories']);
 Route::post('/new_category_update', [NewHomeController::class, 'category_update']);
 Route::post('/new_delete_category', [NewHomeController::class, 'delete_category']);
 Route::post('/new_add_category', [NewHomeController::class, 'add_category']);
 
 
 // Dress Code CRUD management
 Route::get('/new_manage_dress_codes', [NewHomeController::class, 'new_manage_dress_codes'])->name('new_manage_dresscodes');
 Route::post('/new_add_dresscode', [NewHomeController::class, 'new_add_dresscode']);
 Route::get('/new_edit_dresscodes/{id}', [NewHomeController::class, 'new_edit_dress_code'])->name('new_edit_dresscodes');
 Route::post('/new_update_dresscode/{id}', [NewHomeController::class, 'new_update_dress_code'])->name('new_update_dresscode');
 Route::get('/new_delete_dresscode/{id}', [NewHomeController::class, 'new_delete_dress_code'])->name('new_delete_dresscode');

 
 
 // Event Type CRUD management
 Route::get('/new_manage_eventtypes', [NewHomeController::class, 'manage_eventtypes']);
 Route::post('/new_eventtype_update', [NewHomeController::class, 'eventtype_update']);
 Route::post('/new_delete_eventtype', [NewHomeController::class, 'delete_eventtype']);
 Route::post('/new_add_eventtype', [NewHomeController::class, 'add_eventtype']);
 
 
 // Predefined Tags CRUD management
 Route::get('/new_manage_tags', [NewHomeController::class, 'manage_tags']);
 Route::post('/new_tag_update', [NewHomeController::class, 'tag_update']);
 Route::post('/new_delete_tag', [NewHomeController::class, 'delete_tag']);
 Route::post('/new_add_tag', [NewHomeController::class, 'add_tag']);
 
 
 // Usercategories CRUD management
 Route::get('/new_manage_usercategories', [NewHomeController::class, 'manage_usercategories']);
 Route::post('/new_usercategory_update', [NewHomeController::class, 'usercategory_update']);
 Route::post('/new_delete_usercategory', [NewHomeController::class, 'delete_usercategory']);
 //Route::post('/add_usercategory', [HomeController::class, 'add_usercategory']);
 
 
 // Cards CRUD management
 Route::get('/new_manage_cards', [NewHomeController::class, 'manage_cards']);
 Route::post('/new_card_update', [NewHomeController::class, 'card_update']);
 Route::post('/new_delete_card', [NewHomeController::class, 'delete_card']);
 //Route::post('/add_card', [HomeController::class, 'add_card']);
 
 
 // Conncash CRUD management
 Route::get('/new_manage_conncash', [NewHomeController::class, 'manage_conncash']);
 Route::post('/new_conncash_update', [NewHomeController::class, 'conncash_update']);
 //Route::post('/delete_card', [HomeController::class, 'delete_card']);
 Route::post('/new_add_conncash', [NewHomeController::class, 'add_conncash']);
 Route::get('/new_request_payment', [NewHomeController::class, 'request_payment']);
 
 
 
 
 // Addresses CRUD management
 Route::get('/new_manage_addresses', [NewHomeController::class, 'manage_addresses']);
 Route::post('/new_address_update', [NewHomeController::class, 'address_update']);
 //Route::post('/delete_address', [HomeController::class, 'delete_address']);
 //Route::post('/add_address', [HomeController::class, 'add_address']);
 
 // Event Tickets CRUD management
 Route::get('/new_manage_event_tickets', [NewHomeController::class, 'manage_event_tickets']);
 Route::post('/new_eventticket_update', [NewNewHomeController::class, 'eventticket_update']);
 Route::post('/new_delete_eventticket', [NewHomeController::class, 'delete_eventticket']);
 Route::post('/new_add_eventticket',    [NewHomeController::class, 'add_eventticket']);
 
 // Event Tags CRUD management
 Route::get('/new_manage_event_tags', [NewHomeController::class, 'manage_event_tags'])->name('new_manage_eventtags');
 Route::post('/add_event_tag', [NewHomeController::class, 'add_event_tag'])->name('add_event_tag');
 Route::get('/edit_event_tags/{id}', [NewHomeController::class, 'edit_tags'])->name('edit_event_tags');
 Route::post('/update_event_tags/{id}', [NewHomeController::class, 'update_tags'])->name('update_event_tags');
 Route::get('/delete_tags/{id}', [NewHomeController::class, 'delete_tags'])->name('delete_tags');


 // Event Categories routess....
 Route::get('/new_event_categories', [NewHomeController::class, 'event_categories'])->name('new_event_categories');
 Route::post('/new_event_categories', [NewHomeController::class, 'event_categories_event_type'])->name('new_event_categories');
 Route::post('/add_category', [NewHomeController::class, 'new_add_category'])->name('add_category');
 Route::get('/edit_category/{id}', [NewHomeController::class, 'edit_category'])->name('edit_category');
 Route::post('/update_category/{id}', [NewHomeController::class, 'update_category'])->name('update_category');
 Route::get('/delete_category/{id}', [NewHomeController::class, 'new_delete_category'])->name('delete_category');

 // event type crud routes....
 Route::get('/new_event_type', [NewHomeController::class, 'new_event_type'])->name('new_event_type');
 Route::post('/add_event_type', [NewHomeController::class, 'add_event_type'])->name('add_event_type');
 Route::get('/edit_event_type/{id}', [NewHomeController::class, 'edit_event_type'])->name('edit_event_type');
 Route::post('/update_event_type/{id}', [NewHomeController::class, 'update_event_type'])->name('update_event_type');
 Route::get('/new_delete_event_type/{id}', [NewHomeController::class, 'new_delete_event_type'])->name('new_delete_event_type');
  

 // business type routes
 Route::post('/add_business_type', [NewHomeController::class, 'add_new_business_type']);
 Route::get('/edit_new_business_type/{id}', [NewHomeController::class, 'edit_new_business_type'])->name('edit_new_business_type');
 Route::post('/update_new_business_type/{id}', [NewHomeController::class, 'update_new_business_type'])->name('update_new_business_type');
 Route::get('/delete_new_business_type/{id}', [NewHomeController::class, 'delete_new_business_type'])->name('delete_new_business_type');
 Route::post('/verifyModalAjax', [NewHomeController::class, 'verifyAjaxUpdate'])->name('verifyModalAjax');
 Route::post('/activeAjax', [NewHomeController::class, 'activeAjaxUpdate'])->name('activeAjax');
 Route::post('/inactiveAjax', [NewHomeController::class, 'inactiveAjaxUpdate'])->name('inactiveAjax');


 // REPORTS
 Route::get('/new_reports', [NewHomeController::class, 'new_reports']);
 Route::post('/respond_report', [NewHomeController::class, 'respond_report']);
 // REPORTS

 // REFUND REQUESTS
 Route::get('/new_refund_requests', [NewHomeController::class, 'new_refund_requests']);
 // REFUND REQUESTS










 






 Route::post('/new_eventtag_update', [NewHomeController::class, 'eventtag_update']);
 Route::post('/new_delete_eventtag', [NewHomeController::class, 'delete_eventtag']);
 Route::post('/new_add_eventtag',    [NewHomeController::class, 'add_eventtag']);
 
 // Event Post CRUD management
 Route::get('/new_manage_event_posts', [NewHomeController::class, 'manage_event_posts']);
 Route::get('/show_new_manage_eventposts', [NewHomeController::class, 'show_events'])->name('show_new_manage_eventposts');

 Route::post('/new_eventpost_update', [NewHomeController::class, 'eventpost_update']);
 Route::post('/new_delete_eventpost', [NewHomeController::class, 'delete_eventpost']);
 Route::post('/new_add_eventpost',    [NewHomeController::class, 'add_eventpost']);
 
    //-------------------Start Manage New Business-----------------//
Route::get('/new_all_business', [NewHomeController::class, 'new_business'])->name('new_all_business');
Route::get('/show_new_manage_business', [NewHomeController::class, 'show_new_manage_business'])->name('show_new_manage_business');
Route::get('/new_business_type', [NewHomeController::class, 'new_business_type'])->name('new_business_type');



 
    //-------------------End Manage New Business-----------------//

 
 /////// ========== PREVIOUS ADMIN PANEL ROUTES =============  /////
 
 //Add LISTING
 Route::get('/new_add_listing', [NewHomeController::class, 'add_listing']);
 Route::post('/new_post_listing', [NewHomeController::class, 'post_listing']);
 //Add LISTING
 
 //EDIT  LISTING
 Route::get('/new_editlisting/{id}/{status}', [NewHomeController::class, 'editlisting']);
 Route::post('/new_updatelist/{id}', [NewHomeController::class, 'updatelist']);
 //EDIT LISTING
 
 //VIEW  LISTING
 Route::get('/new_view_listings/{id}', [NewHomeController::class, 'view_listings']);
 //VIEW LISTING
 
 //DELETE LISTING
 Route::get('/new_delete_listings/{id}', [NewHomeController::class, 'delete_listings']);
 //DELETE LISTING
 
 //MY LISTING
 Route::get('/new_listing_my_pending', [NewHomeController::class, 'listing_my_pending']);
 Route::get('/new_listing_my_active', [NewHomeController::class, 'listing_my_active']);
 Route::get('/new_listing_my_inactive', [NewHomeController::class, 'listing_my_inactive']);
 Route::get('/new_listing_my_expired', [NewHomeController::class, 'listing_my_expired']);
 Route::get('/new_listing_my_inreview', [NewHomeController::class, 'listing_my_inreview']);
 Route::get('/new_listing_my_deleted', [NewHomeController::class, 'listing_my_deleted']);
 //MY LISTING
 
 
 
 //Start GENERAl Settings
 Route::get('/new_language_settings', [NewHomeController::class, 'language_settings']);
 Route::get('/new_system_settings', [NewHomeController::class, 'system_settings']);
 //End GENERAl Settings
 
 //Start Update System Settings
 Route::post('/new_system_update', [NewHomeController::class, 'system_update']);
 //Start Update System Settings
 
 //Start Account Settings
 Route::get('/new_account_settings', [NewHomeController::class, 'account_settings']);
 //End Account Settings
 
 //Start Account  Update Settings
 Route::post('/new_profile_update/{id}', [NewHomeController::class, 'profile_update'])->name('new_profile_update');
 
 
 //email templates
 Route::get('/new_email_templates', [NewHomeController::class, 'email_templates']);
 Route::get('/new_post_template', [NewHomeController::class, 'post_template']);
 
 Route::post('/new_add_template', [NewHomeController::class, 'add_template']);
 
 Route::get('/new_view_templates/{id}', [NewHomeController::class, 'view_templates']);
 
 Route::get('/new_edit_template/{id}', [NewHomeController::class, 'edit_template']);
 Route::post('/new_update_template/{id}', [NewHomeController::class, 'update_template']);
 Route::get('/new_delete_templates/{id}', [NewHomeController::class, 'delete_templates']);
 //email templates