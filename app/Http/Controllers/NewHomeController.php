<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Event_post;
use App\Models\Tag;
use App\Models\Event_tag;

use App\Http\Controllers\Controller;
use DB;
use App\User;
use Carbon\Carbon;

use App\Mail\RespondReportMail;
use App\Mail\ReportMail;
use Mail;

class NewHomeController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;


    // -------------- LOGIN AUTHENTICATION ------------- //
    public function index(Request $request){
        if ($request->session()->has('id')) {
           
            return view('admin.new_login');
        }
        else return view('admin.new_login');
    }

    public function login(Request $request){
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $postData = $request->all();
        $ifExists = DB::table('users_system')->where('email', $postData['email'])->where('password', $postData['password'])->first();
        if (!empty($ifExists)) {

            $request->session()->put([
                'id' => $ifExists->users_system_id,
                'users_roles_id'=>$ifExists->users_roles_id,
                'user_image' => $ifExists->user_image,
                'fname' => $ifExists->first_name,
                'lname' => '',
                'email' => $ifExists->email,
            ]);
            return redirect('dashboard');
        } else {
            echo '<script>alert("Invalid Email/Password")</script>';
         return redirect()->back();
        }
    }

    public function logout(Request $request){
        $request->session()->flush();
        return view('admin.new_login');
    }


    // ------------- MANAGE USERS CRUD -------------- //
    // Manage Users
    public function manage_users(Request $request){
        if ($request->session()->has('id')) {
            $users= db::table('users')->orderBy('users_id', 'DESC')->get();
            return view('admin.new_users.new_manage_users', compact('users'));
        }
        return view('admin.new_login');
    }

    public function show_users(Request $req)
    {
        
        $id=$req->all();
        
        $users['users']=DB::table('users')
        
        ->join('roles', 'roles.roles_id', '=', 'users.roles_id')
        ->join('subscription_packages', 'subscription_packages.subscription_package_id', '=', 'users.subscription_package_id')
        ->select("users.*","subscription_packages.subscription_package as subscription_package", "roles.name as role_name")
        ->where('users.users_id','=',$id)
        ->get();
        return response()->json($users);
    }
    // event_tags crud operation


/*
    // Add User
    public function add_user(Request $req){
        $insert=array();
        $insert['email'] = $req->email;
        $insert['first_name'] = $req->first_name;
        $insert['total_conncash'] = $req->total_conncash;
        $insert['status'] = $req->status;

        $a=DB::table('users')->insert($insert);
        if($a){
            return redirect('/manage_dresscodes')->with('success', 'Dress Code Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    } */

    // Update User details
    public function user_update(Request $req){
        $insert=array();
        $insert['total_conncash'] = $req->total_conncash;
        $insert['status'] = $req->status;

        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }

        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('users')->where('users_id','=',$req->users_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }

     // Delete user
    public function delete_user(Request $req){
        if(session()->has('id')){
            if(!empty($req->users_id)){
              $checkdata = DB::table('users')->where('users_id', $req->users_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('users')->where('users_id', '=', $req->users_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }

/*     // View specific user detail
    public function viewuser($id = ''){
        $user= db::table('users')->where('users_id', '=', $id)->first();
        return view('users.view_users', compact('user'));
    }
 */


 // ------------- MANAGE CATEGORIES CRUD -------------- //
    // Manage categories
    public function manage_categories(Request $request){
        if ($request->session()->has('id')) {
            $categories= db::table('categories')->get();
            return view('admin.new_manage_system.new_manage_categories', compact('categories'));
        }
        return view('admin.new_login');
    }


    // Add Category
    public function add_category(Request $req){
        $insert=array();
        $insert['category'] = $req->category;
        $insert['category_type'] = $req->category_type;
        $insert['status'] = $req->status;

        $a=DB::table('categories')->insert($insert);
        if($a){
            return redirect('/new_manage_categories')->with('success', 'Category Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update Category details
    public function category_update(Request $req){
        $insert=array();
        $insert['category'] = $req->category;
        $insert['category_type'] = $req->category_type;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('categories')->where('category_id','=',$req->category_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }

     // Delete Category
    public function delete_category(Request $req){
        if(session()->has('id')){
            if(!empty($req->category_id)){
              $checkdata = DB::table('categories')->where('category_id', $req->category_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('categories')->where('category_id', '=', $req->category_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }


    //-------------------Start Manage New Business-----------------//

    public function new_business(Request $request)
    {
        if ($request->session()->has('id')) {
     $business=db::table('businesses')
     ->join('users', 'users.users_id', '=','businesses.users_id')
    ->join('business_types', 'business_types.type', '=','businesses.business_type')
     ->select("businesses.*","users.user_name as user_name","business_types.type as business_type")
     ->orderBy('business_id', 'desc')
     ->get();
    
     return view('admin.new_business.new_manage_business',['business'=>$business]);   
        }
        return view('admin.new_login');
    }
    public function show_new_manage_business(Request $req)
    {
        $id=$req->all();
        $business=db::table('businesses')
     ->join('users', 'users.users_id', '=','businesses.users_id')
    ->join('business_types', 'business_types.type', '=','businesses.business_type')
     ->select("businesses.*","users.user_name as user_name","business_types.type as business_type","users.first_name as first_name", "users.last_name as last_name")
     ->where('business_id', $id)
     ->get();
     return response()->json([
        'business'=>$business,
     ]);
    }
    public function verifyAjaxUpdate(Request $req)
    {
        $id=$req->id;
        $data=$req->data;
        // $data=array(
        //   ,
        // );
       $business=DB::table('businesses')->where('business_id', '=', $id)->update(['status'=>$data]);
    
        return response()->json([
            'status'=>1,
            'res'=>$business,
        ]);
    }


    public function activeAjaxUpdate(Request $req)
    {
        $id=$req->id;
       
        // $data=array(
        //   ,
        // );
       $business=DB::table('businesses')->where('business_id', '=', $id)->update(['active_status'=>'Active']);
    
        return response()->json([
            'status'=>1,
            'res'=>$id,
        ]);
    }



    public function inactiveAjaxUpdate(Request $req)
    {
        $id=$req->id;
       
        // $data=array(
        //   ,
        // );
       $business=DB::table('businesses')->where('business_id', '=', $id)->update(['active_status'=>'Inactive']);
    
        return response()->json([
            'status'=>1,
            'res'=>$id,
        ]);
    }



    public function new_business_type(Request $request)
    {
        if ($request->session()->has('id')) {

        $business_type=DB::table('business_types')->get();
     return view('admin.new_business.new_business_type',['business_type'=>$business_type]);   
        }
        return view('admin.new_login');
    }
    public function add_new_business_type(Request $req)
    {
        $data=array(
            'type'=>$req->business_type,
            'status'=>$req->status,
            );
            DB::table('business_types')->insert($data);
            return redirect()->route('new_business_type');
    }
    public function edit_new_business_type($id)
    {
       $business_type= DB::table('business_types')->where('id', $id)->get();
       return view('admin.new_business.edit_new_business_type',['business_type'=>$business_type]);
    }
    public function update_new_business_type(Request $req, $id)
    {
        $current = Carbon::now();
        $data=array(
            'type'=>$req->business_type,
            'status'=>$req->status,
            'updated_at'=>$current,
            );
            DB::table('business_types')->where('id', $id)->update($data);
            return redirect()->route('new_business_type');
    }
    public function delete_new_business_type($id)
    {
        DB::table('business_types')->where('id', $id)->delete();
        return redirect()->route('new_business_type');
    }

    //-------------------End Manage New Business-----------------//



 // ------------- MANAGE DRESS CODES CRUD -------------- //
    // Manage Dresscodes
    public function new_manage_dress_codes(Request $request){
        if ($request->session()->has('id')) {
            $dresscodes= db::table('dress_code')->get();
            return view('admin.new_manage_system.new_manage_dress_codes', compact('dresscodes'));
        }
        return view('admin.new_login');
    }
    public function new_add_dresscode(Request $req)
    {
       
    $replace=  str_replace("#","0xff","$req->dress_code_color");

        $data=array(
            'dress_code'=>$req->dress_code,
            'dress_code_color'=>$replace,
            'status'=>$req->status,

        );
        DB::table('dress_code')->insert($data);
        return redirect()->route('new_manage_dresscodes');
    }

    public function new_edit_dress_code($id)
    {
        $dress_code=DB::table('dress_code')->where('dress_code_id', $id)->get();
       

       
     
        return view('admin.new_manage_system.new_edit_dresscode', ['dress_code'=>$dress_code]);
    }
    public function new_update_dress_code(Request $req, $id)
    {
        $replace=  str_replace("#","0xff","$req->dress_code_color");

        $data=array(
            'dress_code'=>$req->dress_code,
            'dress_code_color'=>$replace,
            'status'=>$req->status,

        );
        DB::table('dress_code')->where('dress_code_id', $id)->update($data);
        return redirect()->route('new_manage_dresscodes');
    }
    public function new_delete_dress_code($id)
    {
        DB::table('dress_code')->where('dress_code_id', $id)->delete();
        return redirect()->route('new_manage_dresscodes');
    }

    // Add Dresscode
    public function add_dresscode(Request $req){
        $insert=array();
        $insert['dress_code'] = $req->dress_code;
        $insert['status'] = $req->status;

        $a=DB::table('dress_code')->insert($insert);
        if($a){
            return redirect('/admin.new_manage_dresscodes')->with('success', 'Dress Code Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update Dresscode details
    public function dresscode_update(Request $req){
        $insert=array();
        $insert['dress_code'] = $req->dress_code;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('dress_code')->where('dress_code_id','=',$req->dress_code_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete Dresscode
    public function delete_dresscode(Request $req){
        if(session()->has('id')){
            if(!empty($req->dress_code_id)){
              $checkdata = DB::table('dress_code')->where('dress_code_id', $req->dress_code_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('dress_code')->where('dress_code_id', '=', $req->dress_code_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }



 // ------------- MANAGE EVENT TYPES CRUD -------------- //
    // Manage EventTypes
    public function manage_eventtypes(Request $request){
        if ($request->session()->has('id')) {
            $eventtypes= db::table('event_type')->get();
            return view('admin.new_manage_system.new_manage_eventtypes', compact('eventtypes'));
        }
        return view('admin.new_login');
    }


    // Add EventType
    public function add_eventtype(Request $req){
        $insert=array();
        $insert['event_type'] = $req->event_type;
        $insert['status'] = $req->status;

        $a=DB::table('event_type')->insert($insert);
        if($a){
            return redirect('/admin.new_manage_eventtypes')->with('success', 'Event Type Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update EventType details
    public function eventtype_update(Request $req){
        $insert=array();
        $insert['event_type'] = $req->event_type;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('event_type')->where('event_type_id','=',$req->event_type_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete EventType
    public function delete_eventtype(Request $req){
        if(session()->has('id')){
            if(!empty($req->event_type_id)){
              $checkdata = DB::table('event_type')->where('event_type_id', $req->event_type_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('event_type')->where('event_type_id', '=', $req->event_type_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }



 // ------------- MANAGE PREDEFINED TAGS CRUD -------------- //
    // Manage Tags
    public function manage_tags(Request $request){
        if ($request->session()->has('id')) {
            $tags= db::table('tags')->get();
            return view('admin.new_manage_system.new_manage_tags', compact('tags'));
        }
        return view('admin.new_login');
    }


    // Add Tag
    public function add_tag(Request $req){
        $insert=array();
        $insert['tag_name'] = $req->tag_name;
        $insert['status'] = $req->status;

        $a=DB::table('tags')->insert($insert);
        if($a){
            return redirect('admin.new_manage_tags')->with('success', 'Dress Code Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update Tag details
    public function tag_update(Request $req){
        $insert=array();
        $insert['tag_name'] = $req->tag_name;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('tags')->where('tag_id','=',$req->tag_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete Tag
    public function delete_tag(Request $req){
        if(session()->has('id')){
            if(!empty($req->tag_id)){
              $checkdata = DB::table('tags')->where('tag_id', $req->tag_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('tags')->where('tag_id', '=', $req->tag_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }


 // ------------- MANAGE USER CATEGORIES CRUD -------------- //
    // Manage user categories
    public function manage_usercategories(Request $request){
        if ($request->session()->has('id')) {
            $usercategories= db::table('user_categories')
                   ->select('user_categories.*','categories.category')
                   ->join('categories','categories.category_id','=','user_categories.category_id')
                   ->get();

            return view('admin.new_users.new_manage_usercategories', compact('usercategories'));
        }
        return view('admin.new_login');
    }


/*     // Add user category
    public function add_usercategory(Request $req){
        $insert=array();
        $insert['tag_name'] = $req->tag_name;
        $insert['status'] = $req->status;

        $a=DB::table('tags')->insert($insert);
        if($a){
            return redirect('/manage_tags')->with('success', 'Dress Code Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    } */


    // Update user category details
    public function usercategory_update(Request $req){
        $insert=array();
        $insert['category_id'] = $req->category_id;
        $insert['users_id'] = $req->users_id;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('user_categories')->where('user_category_id','=',$req->user_category_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete user category
    public function delete_usercategory(Request $req){
        if(session()->has('id')){
            if(!empty($req->user_category_id)){
              $checkdata = DB::table('user_categories')->where('user_category_id', $req->user_category_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('user_categories')->where('user_category_id', '=', $req->user_category_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('login');

    }



 // ------------- MANAGE CARDS CRUD -------------- //
    // Manage cards
    public function manage_cards(Request $request){
        if ($request->session()->has('id')) {
            $cards= db::table('card_details')->get();
            return view('admin.new_payments_new_management.manage_cards', compact('cards'));
        }
        return view('admin.new_login');
    }


/*     // Add card
    public function add_card(Request $req){
        $insert=array();
        $insert['card_'] = $req->event_type;
        $insert['status'] = $req->status;

        $a=DB::table('card_details')->insert($insert);
        if($a){
            return redirect('/manage_eventtypes')->with('success', 'Event Type Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }
          */

    // Update card details
    public function card_update(Request $req){
        $insert=array();
        $insert['card_number'] = $req->card_number;
        $insert['cvv'] = $req->cvv;
        $insert['card_holder_name'] = $req->card_holder_name;
        $insert['expiry_months'] = $req->expiry_months;
        $insert['expiry_years'] = $req->expiry_years;

        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('card_details')->where('card_id','=',$req->card_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }

     // Delete card
    public function delete_card(Request $req){
        if(session()->has('id')){
            if(!empty($req->card_id)){
              $checkdata = DB::table('card_details')->where('card_id', $req->card_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('card_details')->where('card_id', '=', $req->card_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }



 // ------------- MANAGE CONNCASH CRUD -------------- //
    // Manage cards
    public function manage_conncash(Request $request){
        if ($request->session()->has('id')) {
            $allconncash= db::table('conncash_details')->get();
            return view('admin.new_payments_new_management.new_manage_conncash', compact('allconncash'));

            // return view('admin.new_payments_new_management.new_manage_conncash', compact('allconncash'));
        }
        return view('admin.new_login');
    }


    public function request_payment(Request $request){


        $client_id = "ATk_blwcyxAi3luRCvJdmYYtt9lumgdP7zcNWx75rTN04CHan2dgv4_vDp_upRSbPzrRzFUdDoajFot0";
        $client_secret = "EImfCGc6Sl06DL2qVUo5_OPxRDxKLe6uvBzGVadhwFgoG_84obcVOQv2ndtE8hpH_10mBvXebvuvnsjW";
        $base_uri = "https://api.sandbox.paypal.com";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic QVRrX2Jsd2N5eEFpM2x1UkN2SmRtWVl0dDlsdW1nZFA3emNOV3g3NXJUTjA0Q0hhbjJkZ3Y0X3ZEcF91cFJTYlB6clJ6RlVkRG9hakZvdDA6RUltZkNHYzZTbDA2REwycVZVbzVfT1B4UkR4S0xlNnV2QnpHVmFkaHdGZ29HXzg0b2JjVk9RdjJuZHRFOGhwSF8xMG1CdlhlYnZ1dm5zalc=',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $token =  json_decode($response);
//        print_r($token->access_token);





        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/payments/payouts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
    "sender_batch_header": {
        "sender_batch_id": "Payouts_1658298843",
        "email_subject": "You have a payout!",
        "email_message": "You have received a payout! Thanks for using our service!"
    },
    "items": [
        {
            "recipient_type": "PAYPAL_ID",
            "amount": {
                "value": "50.00",
                "currency": "USD"
            },
            "note": "Thanks for your patronage!",
            "sender_item_id": "201403140003",
            "receiver": "RTQNBV6RXRUVA"
        }
    ]
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'PayPal-Request-Id: A v4 style guid',
                'Authorization: Basic QVRrX2Jsd2N5eEFpM2x1UkN2SmRtWVl0dDlsdW1nZFA3emNOV3g3NXJUTjA0Q0hhbjJkZ3Y0X3ZEcF91cFJTYlB6clJ6RlVkRG9hakZvdDA6RUltZkNHYzZTbDA2REwycVZVbzVfT1B4UkR4S0xlNnV2QnpHVmFkaHdGZ29HXzg0b2JjVk9RdjJuZHRFOGhwSF8xMG1CdlhlYnZ1dm5zalc='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;













//        // -- Generating access token -- //
//        $response = Http::asForm()->withBasicAuth($client_id, $client_secret)
//            ->post($base_uri."/v1/oauth2/token",[
//                'grant_type'=>"authorization_code",
//            ]);
//
//        dd($response);

    }

    // Add Conncash Details
    public function add_conncash(Request $req){
        $insert=array();
        $insert['users_id'] = $req->users_id;
        $insert['conncash'] = $req->conncash;
        $insert['conncash_source'] = $req->conncash_source;


            // Add new record
            $a=DB::table('conncash_details')->insert($insert);
            //batch query for users table updation
            $usersTableRecord = DB::table('users')->where('users_id', $req->users_id)->where('status','Active')->get();
              //  echo ($usersTableRecord); exit;

            $updatedConncash = ($req->conncash) + ($usersTableRecord[0]->total_conncash);

            $a = DB::table('users')->where('users_id','=',$req->users_id)->update(array( 'total_conncash' => $updatedConncash));

            if($a){
                return redirect('admin.new_manage_conncash')->with('success', 'Conncash Details Added successfully');
            }else{
                abort(403, "Forbidden");
            }

    }

    // Update Conncash details
    public function conncash_update(Request $req){
        $insert=array();
        $insert['conncash'] = $req->conncash;
        $insert['conncash_source'] = $req->conncash_source;

        $checkUserId = DB::table('users')->where('users_id', $req->users_id)->where('status','Active')->get();


    if(sizeof($checkUserId) > 0){
        $a = DB::table('conncash_details')->where('conncash_id','=',$req->conncash_id)->update($insert);
           //batch query for users table updation
           $usersTableRecord = DB::table('users')->where('users_id', $req->users_id)->where('status','Active')->get();
           $updatedConncash = ($req->conncash) + ($usersTableRecord[0]->total_conncash);
           $a = DB::table('users')->where('users_id','=',$req->users_id)->update(array( 'total_conncash' => $updatedConncash));

        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    } else{
        return back()->with('error', 'User ID doesnot exist');

    }

    }

/*      // Delete card
    public function delete_card(Request $req){
        if(session()->has('id')){
            if(!empty($req->card_id)){
              $checkdata = DB::table('card_details')->where('card_id', $req->card_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('card_details')->where('card_id', '=', $req->card_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('login');

    } */




 // ------------- MANAGE ADDRESSES CRUD -------------- //
    // Manage Addresses
    public function manage_addresses(Request $request){
        if ($request->session()->has('id')) {
            $addresses= db::table('addresses')->get();
            return view('admin.new_events.new_manage_addresses', compact('addresses'));
        }
        return view('admin.new_login');
    }


/*     // Add Address
    public function add_address(Request $req){
        $insert=array();
        $insert['dress_code'] = $req->dress_code;
        $insert['status'] = $req->status;

        $a=DB::table('dress_code')->insert($insert);
        if($a){
            return redirect('/manage_dresscodes')->with('success', 'Dress Code Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }
           */

    // Update Address details
    public function address_update(Request $req){
        $insert=array();
        $insert['full_address'] = $req->full_address;
        $insert['city'] = $req->city;
        $insert['state'] = $req->state;
        $insert['zip'] = $req->zip;


     /*    if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        } */
        $a = DB::table('addresses')->where('address_id','=',$req->address_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete Address
    public function delete_address(Request $req){
        if(session()->has('id')){
            if(!empty($req->address_id)){
              $checkdata = DB::table('addresses')->where('address_id', $req->address_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('addresses')->where('address_id', '=', $req->address_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }



 // ------------- MANAGE EVENT TICKETS CRUD -------------- //
    // Manage cards
    public function manage_event_tickets(Request $request){
        if ($request->session()->has('id')) {
            $event_tickets= db::table('tickets')->get();
            return view('admin.new_events.new_manage_event_tickets', compact('event_tickets'));
        }
        return view('admin.new_login');
    }


    // Add Event Ticket
    public function add_eventticket(Request $req){
        $insert=array();
        $insert['event_post_id'] = $req->event_post_id;
        $insert['ticket'] = $req->ticket;
        $insert['quantity'] = $req->quantity;
        $insert['price'] = $req->price;

        if($req->ticket == 'Early bird'){

            $date  = date("Y-m-d H:i:s", strtotime($req->closing_date));
            echo ($date); exit;
            $insert['closing_date'] =  $date;
        } else{
            $insert['closing_date'] =  '0';
        }

        $insert['status'] = 'Active';



        $a=DB::table('tickets')->insert($insert);
        
        if($a){
            return redirect('admin.new_manage_eventtickets')->with('success', 'Event Ticket Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }


    // Update Event Ticket details
    public function eventticket_update(Request $req){
        $insert=array();
        $insert['ticket'] = $req->ticket_edit;
        $insert['quantity'] = $req->quantity;
        $insert['price'] = $req->price;


        $previousData = DB::table('tickets')->where('ticket_id', $req->ticket_id)->get();
        

        //update case to select the DB previous datetime if not updated while edit
        if($req->closing_date_edit == ''){
            $insert['closing_date'] =  $previousData[0]->closing_date;
            } else{
                //updated datetime after conversion
            if($req->ticket_edit == 'Early bird'){
                $date  = date("Y-m-d H:i:s", strtotime($req->closing_date_edit));
                $insert['closing_date'] =  $date;
            } else{
                $insert['closing_date'] =  '0';
            }
        }

        $insert['status'] = $req->status;
        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('tickets')->where('ticket_id','=',$req->ticket_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }

     // Delete Event Ticket
    public function delete_eventticket(Request $req){
        if(session()->has('id')){
            if(!empty($req->ticket_id)){
              $checkdata = DB::table('tickets')->where('ticket_id', $req->ticket_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('tickets')->where('ticket_id', '=', $req->ticket_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }



 // ------------- MANAGE EVENT TAGS CRUD -------------- //
    // Manage Event Tags
    public function manage_event_tags(Request $request){
       
        if ($request->session()->has('id')) {
           
            $event_tags= db::table('event_tags')->get();
             
            $event_posts=db::table('event_posts')->get();
         
            $tags=db::table('tags')->get();
            
            return view('admin.new_events.new_manage_event_tags',['event_tags'=>$event_tags,'event_posts'=>$event_posts,'tag'=>$tags]);

        }
        return view('admin.new_login');
    }

    public function add_event_tag(Request $req)
    {
        $data = array(
            'tag_name' => $req->tag_name,
            'tag_type' => 'Custom',
            'custom_tag_users_id' => session('id'),
            'status' => $req->status
        );
        //print_r($data); die;
        DB::table('tags')->insert($data);
        return redirect()->route('new_manage_eventtags');
    }


    public function edit_tags($id){
       
     
      
        $event_tags= db::table('event_tags')->where('event_tag_id','=', $id)->get();
         $tags=db::table('tags')->get();
        
      
       
             
        $event_posts=db::table('event_posts')->get();
     
     
        return view('admin.new_events.new_edit_event_tags',['event_tags'=>$event_tags,'event_posts'=>$event_posts,'tag'=>$tags]);
    }

    public function update_tags(Request $req, $id){
     
        $data=array(

            'tag_id'=>$req->tag_id,
            'event_post_id'=>$req->event_post_id,
            'status'=>$req->status,


        );
        $result=DB::table('event_tags')->where('event_tag_id','=', $id)->update($data);
        return redirect()->route('new_manage_eventtags');
    }

    public function delete_tags(Request $req, $id)
    {
     
        $tags=DB::table('event_tags')->where('event_tag_id', $id)->delete();
      
        return redirect()->route('new_manage_eventtags');
    }


    // Add Event Tag
    public function add_eventtag(Request $req){
        $insert=array();
        $insert['event_tag_id'] = $req->event_tag_id;
        $insert['event_post_id'] = $req->event_post_id;
        $insert['tag_name'] = $req->tag_name;
        $insert['status'] = $req->status;

        $a=DB::table('event_tags')->insert($insert);
        if($a){
            return redirect('admin.new_manage_eventtags')->with('success', 'Event tag Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update Event Tag details
    public function eventtag_update(Request $req){
        $insert=array();
        $insert['event_post_id'] = $req->event_post_id;
        $insert['tag_name'] = $req->tag_name;
        $insert['status'] = $req->status;

        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }

        $a = DB::table('event_tags')->where('event_tag_id','=',$req->event_tag_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete Event Tag
    public function delete_eventtag(Request $req){
        if(session()->has('id')){
            if(!empty($req->event_tag_id)){
              $checkdata = DB::table('event_tags')->where('event_tag_id', $req->event_tag_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('event_tags')->where('event_tag_id', '=', $req->event_tag_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }


    //------------------------Event Categories Function--------------------------//

    public function event_categories(){

      
        $event=DB::table('categories')
        ->join('event_type','event_type.event_type_id','=','categories.event_type_id')
        ->select('categories.*','event_type.event_type as event_type','event_type.event_type_id as event_type_id')
        ->orderBy('category_id', 'DESC')
        ->get();
      
           
            // $event_tags= db::table('event_tags')->get();
             
            // $event_posts=db::table('event_posts')->get();
            $event_type=db::table('event_type')->get();
            $categories=db::table('categories')->get();

         
            // $tags=db::table('tags')->get();
            return view('admin.new_events.new_manage_event_categories',['event'=>$event,'event_type'=>$event_type])->with('no', 1);

     
      
    }
     public function event_categories_event_type(Request $request){
        $id=$request->get('event_type');
      
        $event=DB::table('categories')
        ->join('event_type','event_type.event_type_id','=','categories.event_type_id')
        ->select('categories.*','event_type.event_type as event_type')
        ->where('event_type.event_type_id', $id);
        $event_type_id = $request->event_type_id;
        $query=$event;
        if (isset($request->event_type_id)){
           $query->where('event_type.event_type_id', $request->event_type_id);
        }
        $query = $query->get();
       

        return response()->json([
            'query'=>$query,
        ]);



       
    }
    public function new_add_category(Request $req)
    {
        $data=array(

            'category'=>$req->category,
            'event_type_id'=>$req->event_type_id,
            'status'=>$req->status,


        );
        $result=DB::table('categories')->insert($data);
        return redirect()->route('new_event_categories');
    }
    public function edit_category($id)
    {
        $category=DB::table('categories')->where('category_id', $id)->get();
        $event_type=db::table('event_type')->get();
        return view('admin.new_events.new_edit_event_categories',['category'=>$category,'event_type'=>$event_type]);

    }
    public function update_category(Request $req, $id)
    {
        $data=array(

            'category'=>$req->category,
            'event_type_id'=>$req->event_type_id,
            'status'=>$req->status,


        );
        $result=DB::table('categories')->where('category_id','=', $id)->update($data);
        return redirect()->route('new_event_categories');
    }
    public function new_delete_category($id)
    {
        $categories=DB::table('categories')->where('category_id', $id)->delete();
      
        return redirect()->route('new_event_categories');
    }

    public function new_event_type()
    {
        $event_type=db::table('event_type')->get();
        return view('admin.new_events.new_event_type',['event_type'=>$event_type]);

    }
 public function add_event_type(Request $req)
 {
    $data=array(
    'event_type'=>$req->event_type,
    'status'=>$req->status,
    );
    DB::table('event_type')->insert($data);
    return redirect()->route('new_event_type');
 }
 public function edit_event_type($id)
 {
 
    $event_type=DB::table('event_type')->where('event_type_id', $id)->get();

    return view('admin.new_events.new_edit_event_type',['event_type'=>$event_type]);
 }
 public function update_event_type(Request $req, $id)
 {
    $data=array(
        'event_type'=>$req->event_type,
        'status'=>$req->status,
    );
    DB::table('event_type')->where('event_type_id', $id)->update($data);
    return redirect()->route('new_event_type');
 }
 public function new_delete_event_type($id)
 {
    DB::table('categories')->where('event_type_id', $id)->delete();
    DB::table('event_type')->where('event_type_id', $id)->delete();
    return redirect()->route('new_event_type');
 }




 // ------------- MANAGE EVENT POSTS CRUD -------------- //
    // Manage EventPosts
    public function manage_event_posts(Request $request){
        if ($request->session()->has('id')) {
            $eventPosts = db::table('event_posts')
            ->join('users', 'users.users_id', '=','event_posts.users_id')
            ->select("event_posts.*","users.user_name as user_name")
            ->orderBy('event_post_id', 'desc')
            ->get();
          
            return view('admin.new_events.new_manage_event_posts', compact('eventPosts'));
        }
        return view('admin.new_login');
    }
    public function show_events(Request $req)
    {
        $id=$req->all();
        $event_posts['event_posts']=DB::table('event_posts')
        
        ->join('users', 'users.users_id', '=', 'event_posts.users_id')
        ->join('event_type', 'event_type.event_type_id', '=', 'event_posts.event_type_id')
        ->join('categories', 'categories.category_id', '=', 'event_posts.category_id')
        ->join('dress_code', 'dress_code.dress_code_id', '=', 'event_posts.dress_code_id')
        ->join('addresses', 'addresses.event_post_id', '=', 'event_posts.event_post_id')
        
      ->select("event_posts.*","users.user_name as user_name", "event_type.event_type as event_type","dress_code.dress_code as dress_code","dress_code.dress_code_color as dress_code_color", "categories.category as category", "users.first_name as first_name", "users.last_name as last_name","addresses.full_address as full_address")
        ->where('event_posts.event_post_id','=',$id)
        ->get();
        return response()->json($event_posts);
    }


    // Add EventPost
    public function add_eventpost(Request $req){
        $insert=array();
        $insert['event_type'] = $req->event_type;
        $insert['status'] = $req->status;

        $a=DB::table('event_posts')->insert($insert);
        if($a){
            return redirect('admin.new_manage_eventposts')->with('success', 'Event Post Added successfully');
        }else{
            abort(403, "Forbidden");
        }
    }

    // Update EventPost details
    public function eventpost_update(Request $req){
        $insert=array();
        $insert['event_type'] = $req->event_type;
        $insert['status'] = $req->status;


        if($req->status=='Active'){
            $insert['status'] = 'Active';
        }
        if($req->status=='Inactive'){
            $insert['status'] = 'Inactive';
        }
        $a = DB::table('event_posts')->where('event_post_id','=',$req->event_post_id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
     // Delete EventPost
    public function delete_eventpost(Request $req){
        if(session()->has('id')){
            if(!empty($req->event_type_id)){
              $checkdata = DB::table('event_posts')->where('event_post_id', $req->event_post_id)->where('status', '!=','Deleted')->get();

              if(count($checkdata) != 0){
                        $del=DB::table('event_posts')->where('event_post_id', '=', $req->event_post_id)->update(array( 'status' => 'Deleted'));
                        if($del)
                        {
                            return back()->with('success', 'Data Deleted successfully');
                        }else{
                            abort(403, "Forbidden");
                        }
              } else{
                  return back()->with('success', 'This record is already deleted in status');
              }
            } else {
                abort(404, "Not Found");
            }
        } else return view('admin.new_login');

    }












// ================== PREVIOUS ADMIN PANEL FUNCTIONS =============== //

    /* Email templates*/
    public function email_templates(){
        $templates= db::table('email_templates')->get();
        return view('admin.new_email.email_listing', compact('templates'));
    }

    public function post_template(){
        return view('admin.new_email.add_email_templates');
    }

    public function view_templates($id=''){
        $templates= db::table('email_templates')->where('email_templates_id','=',$id)->first();
        return view('admin.new_email.view_templates', compact('templates'));
    }

    public function add_template(Request $req){
        $insert=array();
        $insert['type'] = $req->type;
        $insert['subject'] = $req->subject;
        $insert['body'] = $req->body;
        $a = DB::table('c')->insert($insert);
        if (!empty($a)) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }

    public function delete_templates($id = ''){
        if(!empty($id)){
            $del=DB::table('email_templates')->where('email_templates_id', '=', $id)->delete();
            if(!empty($del)){
                return back()->with('success', 'Data Deleted successfully');
            } else {
                 return back()->with('errors', 'oops! something went wrong');
            }
        } else {
            abort(404, "Not Found");
        }
    }

    /* MY Listings */
    public function listing_my_pending(){
        $listings=DB::table('listings')->where('status',"Pending")->where('user_id',session('id'))->where('created_by',"1")->get();
        return view('admin.new_listings.listing_my_pending', compact('listings'));
    }

    public function listing_my_active(){
        $listings=DB::table('listings')->where('status',"Active")->where('user_id',session('id'))->where('created_by',"1")->get();
        return view('admin.new_listings.listing_my_active', compact('listings'));
    }
    /* MY Listings */

    /* DELETE LISTING */
    public function delete_listings($id = ''){
        $insert=array();
        $insert['status'] = 'Deleted';
        if(!empty($id)){
            $update=DB::table('listings')->where('listings_id', '=', $id)->update($insert);
            if($update)
            {
                return redirect('/listing_my_pending');
            }else{
                abort(403, "Forbidden");
            }
        } else {
            abort(404, "Not Found");
        }
    }
    /* DELETE LISTING */

    /*  EDIT LISTING*/
    public function editlisting($id = '',$status=''){
       /* echo"here";*/
        if ((!empty($id)) && (!empty($status)))  {
            $listings = DB::table('listings')->where('listings_id', '=',$id)->first();
            if ($listings) {
                return view('listings.edit_listing', compact('listings','status'));
            } else {
                abort(403, "Forbidden");
            }
        } else {
            abort(404, "Not Found");
        }
    }
   /*  EDIT LISTING*/

    /*  UPDATE LISTING*/
    public function updatelist(Request $req){
       $insert=array();
        $insert['city_id'] = $req->city_id;
        $insert['state_id'] = $req->state_id;
        $insert['country_id'] = $req->country_id;
        $insert['register'] = $req->register;
        $insert['car_make_id'] = $req->car_make_id;
        $insert['car_model_id'] = $req->car_model_id;
        $insert['car_variation_id'] = $req->car_variation_id;
        $insert['car_years_id'] = $req->car_years_id;
        $insert['description'] = $req->description;
        $insert['register'] = $req->register;
        $insert['color_id'] = $req->color_id;
        $insert['km'] = $req->km;
        $insert['price'] = $req->price;
        $insert['mobile'] = $req->mobile;
        $insert['sec_mobile'] = $req->sec_mobile;
        $insert['date_added'] = date('Y-m-d H:i:s');
        $insert['date_modified']= date('Y-m-d H:i:s');
        $insert['created_by'] = session('users_roles_id');
       $insert['listings_id'] = $req->id;
        $insert['user_id'] =   session('id');
        if($req->status  == "Permanent"){
        $res= DB::table('listings')->where('listings_id', $req->id)->delete();
         if ($res) {
            return redirect('/listing_my_pending');
        } else {
            abort(403, "Forbidden");
        }

        }else{
            $insert['status'] = $req->status;
        }


        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $ext = $file->extension();
                $path = public_path('uploads/listings/');
                $prefix = 'blog-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                    $insert['listing_image'] = $img_name;
                }
            }
        }

        $a = DB::table('listings_abundant')->insert($insert);
        if ($a) {

            return redirect('admin.newlisting_my_pending');
        } else {
            abort(403, "Forbidden");
        }
    }
    /*  UPDATE LISTING*/

    public function add_listing(){
        return view('admin.new_listings.add_listing');
    }
     /*  VIEW LISTING*/

    public function view_listings(Request $req){
        $listings=DB::table('listings')->where('listings_id','=',$req->id)->get();
        return view('admin.new_listings.view_listings',compact('listings'));
    }

    /* Add Listings */
    public function post_listing(Request $req){

        $insert=array();
       $insert['city_id'] = 1;//$req->city_id;
        $insert['state_id'] = 1;//$req->state_id;
        $insert['country_id'] = 1;//$req->country_id;
        $insert['register'] = $req->register;
        $insert['car_make_id'] = $req->car_make_id;
        $insert['car_model_id'] = $req->car_model_id;
        $insert['car_variation_id'] = $req->car_variation_id;
        $insert['car_years_id'] = $req->car_years_id;
        $insert['description'] = $req->description;
        $insert['register'] = $req->register;
        $insert['color_id'] = $req->color_id;
        $insert['km'] = $req->km;
        $insert['price'] = $req->price;
        $insert['mobile'] = $req->mobile;
        $insert['sec_mobile'] = $req->sec_mobile;
        $insert['date_added'] = date('Y-m-d H:i:s');
        $insert['date_modified']= date('Y-m-d H:i:s');
        $insert['created_by'] = session('role_id');
        $id= session('id');
        $insert['user_id'] = $id  ;
        $insert['status'] = 'Inreview';
        if($req->status==1){
            $insert['status'] = 'Active';
        }

        if($req->status==0){
            $insert['status'] = 'Inactive';
        }


        DB::table('listings')->insert($insert);
        $list_id =DB::getPdo()->lastInsertId();

         if ($req->hasfile('files')) {
            $file = $req->file('files');
            //print_r($file);exit();
            foreach($file as $imagess){

                $ext = $imagess->extension();
                $path = public_path('uploads/listings/');
                $prefix = 'listings-' . md5(time());
                $img_name = $prefix . '.' . $ext;

                if ($imagess->move($path, $img_name)) {
                   $image['listings_id']= $list_id;
                    $image['make_cover']= $req->cover_image;
                    $image['image_name']=  $img_name;
                    $a =DB::table('listings_images')->insert($image);
                 }

            }
            if($a){
                         return back()->with('success', 'Data Added successfully');
                        //return redirect('/add_listing');
                    }else{
                       return back()->with('errors', 'oops! something went wrong');
                    }
        }else{
           return back()->with('errors', 'Files! something went wrong');
        }


    }
    /* Add Listings */

    /* Update System Settings */
    public function system_update(Request $req){

        $system_name['description'] = $req->system_name;
        $data= DB::table('system_settings')->where('type','=','system_name')->update($system_name);

        $email['description'] = $req->email;
        $data= DB::table('system_settings')->where('type','=','email')->update($email);

        $phone['description'] = $req->phone;
        $data= DB::table('system_settings')->where('type','=','phone')->update($phone);

        $city['description'] = $req->city;
        $data= DB::table('system_settings')->where('type','=','city')->update($city);

        $address['description'] = $req->address;
        $data= DB::table('system_settings')->where('type','=','address')->update($address);

        $smtp_host['description'] = $req->smtp_host;
        $data= DB::table('system_settings')->where('type','=','smtp_host')->update($smtp_host);

        $smtp_port['description'] = $req->smtp_port;
        $data= DB::table('system_settings')->where('type','=','smtp_port')->update($smtp_port);

        $smtp_username['description'] = $req->smtp_username;
        $data= DB::table('system_settings')->where('type','=','smtp_username')->update($smtp_username);

        $smtp_password['description'] = $req->smtp_password;
        $data= DB::table('system_settings')->where('type','=','smtp_username')->update($smtp_password);

        $currency['description'] = $req->currency;
        $data= DB::table('system_settings')->where('type','=','currency')->update($currency);

        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $ext = $file->extension();
                $path = public_path('uploads/system/');
                $prefix = 'system-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                     $insert_image['description'] = $img_name;
                    DB::table('system_settings')->where('type','=','system_image')->update($insert_image);

                }
            }
        }
        return back()->with('success', 'Data Updated successfully');
    }
    /* Update System Settings */

    /* Account Settings */
    public function account_settings(){
        $data= DB::table('users_system')->where('users_system_id',session('id'))->get();

            
        return view('admin.new_account_settings',compact('data'));
        // return view('admin.new_users.account_settings',compact('data'));
    }
    /* Account Settings */

    /* Account UPDATE Settings */
    public function profile_update(Request $req,$id){

       $insert=array();
        $insert['city'] = $req->city;
        $insert['address'] = $req->address;
        $insert['first_name'] = $req->first_name;
        //$insert['gender'] = $req->gender;
        $insert['email'] = $req->email;
        $insert['password'] = $req->password;
        $insert['mobile'] = $req->mobile;

        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $img_name=$file->getClientOriginalName();
                $path = public_path('uploads/users/');
                if ($file->move($path, $img_name)) {
                    $insert['user_image'] = $img_name;
                }
            }
        }

        $a = DB::table('users_system')->where('users_system_id','=',$id)->update($insert);
        if ($a) {
            return back()->with('success', 'Data Updated successfully');
        } else {
             return back()->with('errors', 'oops! something went wrong');
        }
    }
    /* Account UPDATE Settings */

    //Account settings

    // public function account_setting(){
    //     $admin = users_system::all()
    //     return view('admin.account_setting',compact('users_system'));
    // }

    public function dash_board(){
        

        $users = DB::table('users')->count();
       
        $categories = DB::table('categories')->count();
        
        $event_posts = DB::table('event_posts')->count();
         
        $businesses = DB::table('businesses')->count();
         
        $tickets = DB::table('tickets')->count();

        return view('admin.dash_board',compact('users', 'event_posts', 'tickets', 'categories', 'businesses'));
    }

    public function new_reports(Request $request){
        if ($request->session()->has('id')) {
            $eventReports = db::table('event_reports')
            ->orderBy('report_id', 'desc')
            ->get();

            return view('admin.new_reports', compact('eventReports'));
        }
        else return view('admin.new_login');
    }

    public function respond_report(Request $request){
        if ($request->session()->has('id')) {
            $user = DB::table('event_posts')->select('users_id')->where('event_post_id', $request->event_post_id)->first();
            $user_data = DB::table('users')->select('email')->where('users_id', $user->users_id)->first();

            // send email
            $email = $user_data->email;
            Mail::to($email)->send(new ReportMail($request->email_subject, $request->email_message));
            // send email

            return redirect('/new_reports');

            /*$emailDetails = array(
                'to_email' => 'bscs-17-15@outlook.com',
                'subject' => $request->email_subject,
                //'from_email' => $fromEmail,

            );
            $message_content = $request->email_message;

            Mail::send(array(), array(), function ($message) use ($emailDetails, $message_content) {
                $message->to($emailDetails['to_email'])
                  ->subject($emailDetails['subject'])
                  //->from($emailDetails['from_email'])
                  ->setBody($message_content, 'text/html');
              });*/
        }
        else return view('admin.new_login');
    }

    public function new_refund_requests(Request $request){
        if ($request->session()->has('id')) {
            $refundRequests = db::table('refund_requests')
            ->orderBy('refund_request_id', 'desc')
            ->get();

            return view('admin.new_refund_requests', compact('refundRequests'));
        }
        else return view('admin.new_login');
    }
}


