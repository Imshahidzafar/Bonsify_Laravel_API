<?php

namespace App\Helpers;

use Datetime;
use DB;


class Helper
{

    public static function decode_image($img , $unique_id, $path_url, $prefix, $random, $postfix){

        $data = base64_decode($img);
        $file_name = $prefix.$unique_id.$random.$postfix.'.jpeg';
        $file = $path_url.$file_name;
        $success = file_put_contents($file, $data);
        return $file_name;

    }
    public static function convert_conncash($conncash){
        $detail =   DB::table('system_settings')->where('type','one_dollar_conncash_rate')->first();
        $convertedDollars = $conncash / $detail->description;
        return $convertedDollars;
    }
    public static function convert_dollars($dollars){
        $detail =   DB::table('system_settings')->where('type','one_dollar_conncash_rate')->first();
        $convertedConncash = $dollars * $detail->description;
        return $convertedConncash;
    }

    public static function sendpushnotification($oneSignalDeviceId, $message, $type)
    {
        $content = array(
            "en" => $message,
        );

        $fields = array(
//            'app_id'    =>  env('ONE_SIGNAL_APP_ID'),
            'app_id'    =>  "3502caf3-e7b3-4352-a53e-a1e13ebe2cd0",
            'include_player_ids' => [$oneSignalDeviceId],
            'data' => array("type" => $type),
            'contents' => $content
        );

        $fields = json_encode($fields);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$fields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type:  application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
//        echo $response;

        return $response;
    }







    public static function get_day_difference($timestamp){
        $today = new DateTime(date('Y-m-d H:i:s'));
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




    public static function get_days_difference($timestamp){
        $today = new DateTime(date('Y-m-d H:i:s'));
        $thatDay = new DateTime($timestamp);
        $dt = $today->diff($thatDay);
        return $dt->d;
    }


    public  static function miles($lat1, $lon1, $lat2, $lon2, $unit) {
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




    public  static function calculate_miles($lat1, $lon1, $lat2, $lon2, $unit) {
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
                if ($miles < 1){
//                    convert miles into feet
                    $miles = $miles * 5280;

                    $smiles = $miles/100;
                    $distance = number_format($smiles, 0, '.', ',');

                    $distance = $distance * 100;
                    $distance = $distance . " Feet Away";
                }else{
                    $distance = number_format($miles, 0, '.', ',');
                    $distance = $distance . " Miles Away";
                }

                return $distance;
            }
        }
    }


    public static function  thousandsCurrencyFormat($num) {

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


    public static function  generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function time_elapsed_string($datetime, $full = false) {
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

    public static function user_time_elapsed_string($datetime,$user_timezone) {
        date_default_timezone_set($user_timezone);
        $now = new DateTime;
        $ago = new DateTime($datetime);

//        return response(["noe"=>$now,"ago"=>$ago]);

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
        $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


    //--- Create stripe account
    public static function create_stripe_account($email){
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
    public static function transfer_stripe_payment($amount,$stripe_account_id){

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
    public static function generate_stripe_account_link($account_id){
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




}
