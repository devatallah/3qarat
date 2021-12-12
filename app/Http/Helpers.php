<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function locale()
{
    return Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
}

function rtl_assets()
{
    if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
        return '-rtl';
    }
    return '';
}

function locales()
{
    $arr = [];
    foreach (LaravelLocalization::getSupportedLocales() as $key => $value) {
        $arr[$key] = __('' . $value['name']);
    }
    return $arr;
}

function languages()
{
    if (app()->getLocale() == 'en') {
        return ['ar' => 'arabic', 'en' => 'english'];
    } else {
        return ['ar' => 'العربية', 'en' => 'النجليزية'];

    }
}

function youtubeVideoId($url)
{
    $pattern =
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x';
    $result = preg_match($pattern, $url, $matches);
    if ($result) {
        return $matches[1];
    }
    return false;

}

function mainResponse($status, $msg, $items, $validator, $code = 200, $pages = null)
{
    $item_with_paginate = $items;
    if (gettype($items) == 'array') {
        if (count($items)) {
            $item_with_paginate = $items[array_key_first($items)];
        }
    }

    if (isset(json_decode(json_encode($item_with_paginate, true), true)['data'])) {
        $pagination = json_decode(json_encode($item_with_paginate, true), true);
        $new_items = $pagination['data'];
        $pages = [
            "current_page" => $pagination['current_page'],
            "first_page_url" => $pagination['first_page_url'],
            "from" => $pagination['from'],
            "last_page" => $pagination['last_page'],
            "last_page_url" => $pagination['last_page_url'],
            "next_page_url" => $pagination['next_page_url'],
            "path" => $pagination['path'],
            "per_page" => $pagination['per_page'],
            "prev_page_url" => $pagination['prev_page_url'],
            "to" => $pagination['to'],
            "total" => $pagination['total'],
        ];
    } else {
        $pages = [
            "current_page" => 0,
            "first_page_url" => '',
            "from" => 0,
            "last_page" => 0,
            "last_page_url" => '',
            "next_page_url" => null,
            "path" => '',
            "per_page" => 0,
            "prev_page_url" => null,
            "to" => 0,
            "total" => 0,
        ];
    }

    if (gettype($items) == 'array') {
        if (count($items)) {
            $new_items = [];
            foreach ($items as $key => $item) {
                if (isset(json_decode(json_encode($item, true), true)['data'])) {
                    $pagination = json_decode(json_encode($item, true), true);
                    $new_items[$key] = $pagination['data'];
                } else {
                    $new_items[$key] = $item;
                }

                $items = $new_items;
            }
        }
    } else {
        if (isset(json_decode(json_encode($item_with_paginate, true), true)['data'])) {
            $pagination = json_decode(json_encode($item_with_paginate, true), true);
            $items = $pagination['data'];
        }
    }

//    $items = $new_items;

    $aryErrors = [];
    foreach ($validator as $key => $value) {
        $aryErrors[] = ['field_name' => $key, 'messages' => $value];
    }
    /*    $aryErrors = array_map(function ($i) {
            return $i[0];
        }, $validator);*/

    $newData = ['status' => $status, 'message' => __($msg), 'items' => $items, 'pages' => $pages, 'errors' => $aryErrors];

    return response()->json($newData);
}

function latlng($ip = '213.6.137.2')
{
    $url = 'http://ip-api.com/json/' . $ip;
    $headers = ['Accept' => 'application/json'];
    $http = new \GuzzleHttp\Client();
    $response = $http->get($url, [
        'headers' => $headers,
        'form_params' => [],
    ]);
    $data = json_decode((string)$response->getBody(), true);
    return ['lat' => $data['lat'], 'lng' => $data['lon']];
    /*    if (array_key_exists('countryCode', $data)) {
            //do your code
        }
        return 'no data';*/
}

function check_number($mobile)
{
    $persian = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
    $num = range(9, 0);
    $mobile = str_replace(' ', '', $mobile);
    $mobile = str_replace($persian, $num, $mobile);
    $mobile = substr($mobile, -9);

    if (preg_match("/^[5][0-9]{8}$/", $mobile)) {
        $mobile = '966' . $mobile;

        return $mobile;
    } else {
        return FALSE;
    }
}

function getGeneratedCode($model, $column)
{
    $model = ucfirst($model);
    $model_name = 'App\Models\\'.$model;
    do {
        $code = substr(str_shuffle('1234567890'), 0, 5);
    } while ($model_name::query()->where($column, $code)->count() > 0);
    return $code;
}


function send_sms($mobile, $message)
{
    $mobiles = [];
    foreach ($mobile as $item) {
        $mobiles[] = check_number($item);
    }
    $mobiles = implode(',', $mobiles);
    if ($mobile) {
        $message = urlencode($message);
//        $url = "http://www.jawalsms.net/httpSmsProvider.aspx?username=wasilcom&password=987654321&mobile=$mobiles&unicode=E&message=$message&sender=WasilCom";
        $url = "shttps://www.hisms.ws/api.php?send_sms&username=966541412144&password=As120120&numbers=$mobiles&sender=FOORSA-AD&message=$message";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLE_HTTP_NOT_FOUND, 1);
        $LastData = curl_exec($ch);
        curl_close($ch);
        return $LastData;
    }
}


function fcmNotification($token, $title, $content, $body, $type, $device)
{
    $msg = [
        'title' => $title,
        'content' => $content,
        'body' => $body,
        'type' => $type,
        'icon' => 'myicon',
        'sound' => 'mySound',
    ];
    if ($device == 'ios') {
        $fields = [
            'registration_ids' => $token,
            'notification' => $msg,
        ];
    } else {
        $fields = [
            'registration_ids' => $token,
            'data' => $msg,
        ];
    }

    $headers = [
        'Authorization: key=' . 'AAAAI85-oRA:APA91bEYUoDX8hHcq3WNTouV-2ZskfBLylCh0jdTgZ6G_yNjkfzbtJrXNR-3F8eVGYNGsMJ_QDfLPW1hp0jroLd5fR2X_WLm08rXSHHQLb4AEHXZHMZzU-F_dbl7lDFNaPC1rzGBKghL',
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function places(Request $request)
{
    $GOOGLE_API_KEY = 'AIzaSyD1Tzy-KKqhc0kbpBrzonlBYTrynKNyolc';
    $rules = [
        'id' => 'nullable|exists:categories,id',
        'lat' => 'required',
        'lng' => 'required',
        'pagetoken' => 'nullable|string',
        'name' => 'nullable|string|max:255',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
    }


    $base_url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
    $base_url .= "location=" . $request->lat . "," . $request->lng;
    $base_url .= "&radius=5000";
    if ($request->id) {
        $category = Category::query()->find($request->id);
        if ($category) {
            $base_url .= "&type=" . $category->place_type;
        }
    }
    $base_url .= "&sensor=true";
    $base_url .= "&key=" . $GOOGLE_API_KEY;

    if ($request->pagetoken)
        $base_url .= "&pagetoken=" . $request->pagetoken;

    if ($request->opennow)
        if ($request->opennow == 1)
            $base_url .= "&opennow";

    if ($request->name)
        $base_url .= "&name=" . $request->name;

    $response = file_get_contents($base_url);

    $main_response = json_decode($response);
    $response = json_decode(json_encode($main_response, true), true)['results'];
    $has_more = @json_decode(json_encode($main_response, true), true)['next_page_token'] == null ? false : true;
    $next_page_token = @json_decode(json_encode($main_response, true), true)['next_page_token'];
    $places = [];

    foreach ($response as $i => $res) {
        $places[$i]['id'] = @$res['id'] ?? '';
        $places[$i]['place_id'] = $res['place_id'];
        $places[$i]['name'] = $res['name'];
        $places[$i]['is_open'] = @$res['opening_hours']['open_now'] == null ? false : @$res['opening_hours']['open_now'];

        $places[$i]['rating'] = @$res['rating'] == null ? 0 : @$res['rating'];
        $places[$i]['user_ratings_total'] = @$res['user_ratings_total'] == null ? 0 : @$res['user_ratings_total'];
        $places[$i]['address'] = @$res['vicinity'] == null ? "" : @$res['vicinity'];

        $places[$i]['image'] = "";
        $posts = @$res['photos'] == null ? [] : @$res['photos'];
        foreach ($posts as $photo) {
            $image_url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=1836&photoreference=";
            $image_url .= $photo['photo_reference'];
            $image_url .= "&key=" . $GOOGLE_API_KEY;
            $places[$i]['image'] = $image_url;
            break;
        }


        $location = $res['geometry']['location'];
        $places[$i]['lat'] = $location['lat'];
        $places[$i]['lng'] = $location['lng'];

        $location1 = [
            "lat" => $request->lat,
            "lng" => $request->lng
        ];

        $location2 = [
            "lat" => $places[$i]['lat'],
            "lng" => $places[$i]['lng']
        ];
        $places[$i]['distance'] = getDistance($request->lat, $request->lng, $places[$i]['lat'], $places[$i]['lng']);//$geocoder->getDistanceBetween($location1, $location2);
    }
    return mainResponse(true, __('ok'), compact('places', 'has_more', 'next_page_token'), [], 200);
}

function getDistance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return round($miles * 1.609344, 2);

}

function qoyodApi($end_point, $method, $lat2, $lon2)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.qoyod.com/api/2.0/$end_point",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
	"account": {
		"name_en": "Albilad Bank Account",
		"name_ar": "حساب بنك البلاد",
		"code": "412",
		"description": "Account description",
		"recieve_payments": "true",
		"type": "Bank"
	}
}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: insert-your-api-key-here'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    return round($miles * 1.609344, 2);

}




