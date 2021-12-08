<?php
use \Illuminate\Support\Facades\File;
function authSession($force=false){
    $session = new \App\Models\User;
    if($force){
        $user = \Auth::user()->getRoleNames();
        \Session::put('auth_user',$user);
        $session = \Session::get('auth_user');
        return $session;
    }
    if(\Session::has('auth_user')){
        $session = \Session::get('auth_user');
    }else{
        $user = \Auth::user();
        \Session::put('auth_user',$user);
        $session = \Session::get('auth_user');
    }
    return $session;
}

function comman_message_response( $message, $status_code = 200)
{	
	return response()->json( [ 'message' => $message ], $status_code );
}

function comman_custom_response( $response, $status_code = 200 )
{
    return response()->json($response,$status_code);
}

function comman_list_response( $data )
{
    return response()->json(['data' => $data]);
}


function checkMenuRoleAndPermission($menu)
{
    if (\Auth::check()) {
        if ($menu->data('role') == null && auth()->user()->hasRole('admin')) {
            return true;
        }

        if($menu->data('permission') == null && $menu->data('role') == null) {
            return true;
        }

        if($menu->data('role') != null) {
            if(auth()->user()->hasAnyRole(explode(',', $menu->data('role')))) {
                return true;
            }
        }

        if($menu->data('permission') != null) {
            if(auth()->user()->can($menu->data('permission')) ) {
                return true;
            }
        }
    }

    return false;
}


function checkRolePermission($role,$permission){
    try{
        if($role->hasPermissionTo($permission)){
            return true;
        }
        return false;
    }catch (Exception $e){
        return false;
    }
}

function demoUserPermission(){
    if(\Auth::user()->hasAnyRole(['demo_admin'])){
        return true;
    }else{
        return false;
    }
}

function getSingleMedia($model, $collection = 'profile_image', $skip=true   )
{
    if (!\Auth::check() && $skip) {
        return asset('images/user/user.png');
    }
    $media = null;
    if ($model !== null) {
        $media = $model->getFirstMedia($collection);
    }

    if (getFileExistsCheck($media)) {
        return $media->getFullUrl();
    }else{

        switch ($collection) {
            case 'image_icon':
                $media = asset('images/user/user.png');
                break;
            case 'profile_image':
                $media = asset('images/user/user.png');
                break;
            case 'service_attachment':
                $media = asset('images/default.png');
                break;
            case 'site_logo':
                $media = asset('images/logo.png');
                break;
            case 'site_favicon':
                $media = asset('images/favicon.png');
                break;

            default:
                $media = asset('images/default.png');
                break;
        }
        return $media;
    }
}

function getFileExistsCheck($media)
{
    $mediaCondition = false;

    if($media) {
        if($media->disk == 'public') {
            $mediaCondition = file_exists($media->getPath());
        } else {
            $mediaCondition = \Storage::disk($media->disk)->exists($media->getPath());
        }
    }

    return $mediaCondition;
}

function storeMediaFile($model,$file,$name)
{
    if($file) {
        $model->clearMediaCollection($name);
        if (is_array($file)){
            foreach ($file as $key => $value){
                $model->addMedia($value)->toMediaCollection($name);
            }
        }else{
            $model->addMedia($file)->toMediaCollection($name);
        }
    }

    return true;
}

function getAttachments($attchments)
{
    $files = [];
    if (count($attchments) > 0) {
        foreach ($attchments as $attchment) {
            if (getFileExistsCheck($attchment)) {
                array_push($files, $attchment->getFullUrl());
            }
        }
    }

    return $files;
}

function getMediaFileExit($model, $collection = 'profile_image')
{
    if($model==null){
        return asset('images/user/user.png');;
    }

    $media = $model->getFirstMedia($collection);

    return getFileExistsCheck($media);
}

function activeRoute($route, $isClass = false): string
{
    $requestUrl = request()->is($route);

    if($isClass) {
        return $requestUrl ? $isClass : '';
    } else {
        return $requestUrl ? 'active' : '';
    }
}

function saveBookingActivity($data)
{
    $admin = \App\Models\User::where('user_type','admin')->first();
    // date_default_timezone_set( $admin->time_zone ?? 'UTC');
    $data['datetime'] = date('Y-m-d H:i:s');
    $role = auth()->user()->user_type;
    switch ($data['activity_type'])
    {
        case "add_booking":
                $data['activity_message'] = __('messages.booking_added');
                $activity_data = [
                    'service_id' => $data['booking']->service_id,
                    'service_name' => isset($data['booking']->service) ? $data['booking']->service->name : '',
                    'customer_id' => $data['booking']->customer_id,
                    'customer_name' => isset($data['booking']->customer) ? $data['booking']->customer->display_name : '',
                    'provider_id' => $data['booking']->provider_id,
                    'provider_name' => isset($data['booking']->provider) ? $data['booking']->provider->display_name : '',
                ];
                $sendTo = ['admin' , 'provider'];
            break;
        case "assigned_booking":
                $assigned_handyman = handymanNames($data['booking']->handymanAdded);
                $data['activity_message'] = __('messages.booking_assigned',['name' => $assigned_handyman]);
                $activity_data = [
                    'handyman_id' => $data['booking']->handymanAdded->pluck('handyman_id'),
                    'handyman_name' => $data['booking']->handymanAdded,
                ];
                $sendTo = ['handyman'];
                break;

        case "transfer_booking":
                $assigned_handyman = handymanNames($data['booking']->handymanAdded);

                $data['activity_message'] = __('messages.booking_transfer',['name' => $assigned_handyman]);
                $activity_data = [
                    'handyman_id' => $data['booking']->handymanAdded->pluck('handyman_id'),
                    'handyman_name' => $data['booking']->handymanAdded,
                ];
                $sendTo = ['handyman'];
            break;
            
        case "update_booking_status":

                $status = \App\Models\BookingStatus::bookingStatus($data['booking']->status);
                $old_status = \App\Models\BookingStatus::bookingStatus($data['booking']->old_status);
                $data['activity_message'] = __('messages.booking_status_update',[ 'from' => $old_status , 'to' => $status ]);
                $activity_data = [
                    'reason' => $data['booking']->reason,
                    'status' => $data['booking']->status,
                    'status_label' => $status,
                    'old_status' => $data['booking']->old_status,
                    'old_status_label' => $old_status,
                ];

                $sendTo = removeArrayValue(['admin', 'provider' , 'handyman' , 'user'],$role);
            break;
        case "cancel_booking":
            $status = \App\Models\BookingStatus::bookingStatus($data['booking']->status);
            $old_status = \App\Models\BookingStatus::bookingStatus($data['booking']->old_status);
            
            $data['activity_message'] = __('messages.cancel_booking');
            $activity_data = [
                'reason' => $data['booking']->reason,
                'status' => $data['booking']->status,
                'status_label' => \App\Models\BookingStatus::bookingStatus($data['booking']->status),
            ];
            $sendTo = removeArrayValue(['admin', 'provider' , 'handyman' , 'user'],$role);
        break;
        case "payment_message_status" :
            $data['activity_message'] = __('messages.payment_message',['status' => $data['payment_status'] ]);
            
            $activity_data = [
                'activity_type' => 'payment_message_status',
                'payment_status'=> $data['payment_status'],
                'booking_id' => $data['booking_id'],
            ];
            $sendTo = ['user'];
        break;
        
        default :
            $activity_data = [];
            break;
    }
    $data['activity_data'] = json_encode($activity_data);
    
    \App\Models\BookingActivity::create($data);
    $notification_data = [
        'id'   => $data['booking']->id,
        'type' => $data['activity_type'],
        'subject' => $data['activity_type'],
        'message' => $data['activity_message'],
    ];
    foreach($sendTo as $to){
        switch ($to)
        {
            case 'admin':
                $user = \App\Models\User::getUserByKeyValue('user_type','admin');
                break;
            case 'provider':
                $user = \App\Models\User::getUserByKeyValue( 'id', $data['booking']->provider_id );
                break;
            case 'handyman':
                $handymans = $data['booking']->handymanAdded->pluck('handyman_id');
                foreach($handymans as $id)
                {
                    $user = \App\Models\User::getUserByKeyValue( 'id', $id );
                    $user->notify(new \App\Notifications\BookingNotification($notification_data));
                    $user->notify(new App\Notifications\CommonNotification($notification_data['type'],$notification_data));
                }
                break;
            case 'user':
                    $user = \App\Models\User::getUserByKeyValue( 'id', $data['booking']->customer_id );
                break;
        }
        if($to != 'handyman' ) {
            $user->notify(new App\Notifications\CommonNotification($data['activity_type'],$notification_data ));
            $user->notify(new \App\Notifications\BookingNotification($notification_data));
        }
    }

}

function settingSession($type='get'){
    if(\Session::get('setting_data') == ''){
        $type='set';
    }
    switch ($type){
        case "set" : 
            $settings = \App\Models\AppSetting::first();
            \Session::put('setting_data',$settings);
            break;
        default : 
            break;
    }
    return \Session::get('setting_data');
}

function envChanges($type,$value){
    $path = base_path('.env');

    $checkType = $type.'="';
    if(strpos($value,' ') || strpos(file_get_contents($path),$checkType) || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)){
        $value = '"'.$value.'"';
    }
    
    $value = str_replace('\\', '\\\\', $value);

    if (file_exists($path)) {
        $typeValue = env($type);
        
        if(strpos(env($type),' ') || strpos(file_get_contents($path),$checkType)){
            $typeValue = '"'.env($type).'"';
        }
        
        file_put_contents($path, str_replace(
            $type.'='.$typeValue, $type.'='.$value, file_get_contents($path)
        ));

        $onesignal = collect(config('constant.ONESIGNAL'))->keys();

        $checkArray = \Arr::collapse([$onesignal,['DEFAULT_LANGUAGE']]);
       
        
        if( in_array( $type ,$checkArray) ){
            if(env($type) === null){
                file_put_contents($path,"\n".$type.'='.$value ,FILE_APPEND);
            }
        }
    }
}

function getPriceFormat($price){
    if($price == null){
        return 0;
    }
    $currency_symbol = \App\Models\Setting::where('type','CURRENCY')->where('key','CURRENCY_COUNTRY_ID')->with('country')->first();
    $symbol = '$';
    if(!empty($currency_symbol))
    {
        $symbol = $currency_symbol->country->symbol;
    }
    $currency_position = \App\Models\Setting::where('type','CURRENCY')->where('key','CURRENCY_POSITION')->first();
    $position = 'left';
    if( !empty($currency_position) ){
        $position = $currency_position->value;
    }

    if ($position == 'left') {
        $price = $symbol."".$price;
    } else {
        $price = $price."".$symbol;
    }
    
    return $price;
}
function payment_status(){

    return [
        'pending' => __('messages.pending'),
        'paid' => __('messages.paid'),
        'failed' => __('messages.failed'),
        'refunded' => __('messages.refunded')
    ];
}

function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return 'GMT' . $sign . str_pad($hour, 2, '0', STR_PAD_LEFT)
        . ':' . str_pad($minutes, 2, '0');
}

function timeZoneList()
{
    $list = \DateTimeZone::listAbbreviations();
    $idents = \DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (!empty($zone['timezone_id']) and !in_array($zone['timezone_id'], $added) and in_array($zone['timezone_id'], $idents)) {

                $z = new \DateTimeZone($zone['timezone_id']);
                $c = new \DateTime(null, $z);
                $zone['time'] = $c->format('H:i a');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }

    array_multisort($offset, SORT_ASC, $data);
    $options = array();
    foreach ($data as $key => $row) {

        $options[$row['timezone_id']] = $row['time'] . ' - ' . formatOffset($row['offset'])  . ' ' . $row['timezone_id'];
    }

    return $options;
}

function stringLong($str = '', $type = 'title', $length = 0) //Add … if string is too long
{
    if ($length != 0) {
        return strlen($str) > $length ? substr($str, 0, $length) . "..." : $str;
    }
    if ($type == 'desc') {
        return strlen($str) > 150 ? substr($str, 0, 150) . "..." : $str;
    } elseif ($type == 'title') {
        return strlen($str) > 15 ? substr($str, 0, 25) . "..." : $str;
    } else {
        return $str;
    }

}
function dateAgoFormate($date,$type2='')
{
    if($date==null || $date=='0000-00-00 00:00:00'){
        return '-';
    }

    $diff_time1= \Carbon\Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    $datetime = new \DateTime($date);
    $la_time = new \DateTimeZone(\Auth::check() ? \Auth::user()->time_zone ?? 'UTC' : 'UTC');
    $datetime->setTimezone($la_time);
    $diff_date= $datetime->format('Y-m-d H:i:s');

    $diff_time= \Carbon\Carbon::parse($diff_date)->isoFormat('LLL');

    if($type2 != ''){
        return $diff_time;
    }

    return $diff_time1 .' on '.$diff_time;
}

function timeAgoFormate($date)
{
    if($date==null){
        return '-';
    }

    // date_default_timezone_set('UTC');

    $diff_time= \Carbon\Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();

    return $diff_time;
}

function duration($start ='' , $end='' ,$type = '')
{
    $start = \Carbon\Carbon::parse($start);
    $end = \Carbon\Carbon::parse($end);
    
    if($type){
        $diff_in_minutes = $start->diffInMinutes($end);
        return $diff_in_minutes;
    }else{
        $diff = $start->diff($end);
        return $diff->format('%H:%I');
    }
}

function removeArrayValue($array = [],$find)
{
    foreach (array_keys($array, $find) as $key) {
        unset($array[$key]);
    }

    return array_values($array);
}

function handymanNames($collection)
{
    return $collection->mapWithKeys(function ($item) {
        return [$item->handyman_id => optional($item->handyman)->display_name];
    })->values()->implode(',');
}

function languagesArray($ids = [])
{
    $language = [ 
        [ 'title' => 'Abkhaz' , 'id' => 'ab'],
        [ 'title' => 'Afar' , 'id' => 'aa'],
        [ 'title' => 'Afrikaans' , 'id' => 'af'],
        [ 'title' => 'Akan' , 'id' => 'ak'],
        [ 'title' => 'Albanian' , 'id' => 'sq'],
        [ 'title' => 'Amharic' , 'id' => 'am'],
        [ 'title' => 'Arabic' , 'id' => 'ar'],
        [ 'title' => 'Aragonese' , 'id' => 'an'],
        [ 'title' => 'Armenian' , 'id' => 'hy'],
        [ 'title' => 'Assamese' , 'id' => 'as'],
        [ 'title' => 'Avaric' , 'id' => 'av'],
        [ 'title' => 'Avestan' , 'id' => 'ae'],
        [ 'title' => 'Aymara' , 'id' => 'ay'],
        [ 'title' => 'Azerbaijani' , 'id' => 'az'],
        [ 'title' => 'Bambara' , 'id' => 'bm'],
        [ 'title' => 'Bashkir' , 'id' => 'ba'],
        [ 'title' => 'Basque' , 'id' => 'eu'],
        [ 'title' => 'Belarusian' , 'id' => 'be'],
        [ 'title' => 'Bengali' , 'id' => 'bn'],
        [ 'title' => 'Bihari' , 'id' => 'bh'],
        [ 'title' => 'Bislama' , 'id' => 'bi'],
        [ 'title' => 'Bosnian' , 'id' => 'bs'],
        [ 'title' => 'Breton' , 'id' => 'br'],
        [ 'title' => 'Bulgarian' , 'id' => 'bg'],
        [ 'title' => 'Burmese' , 'id' => 'my'],
        [ 'title' => 'Catalan; Valencian' , 'id' => 'ca'],
        [ 'title' => 'Chamorro' , 'id' => 'ch'],
        [ 'title' => 'Chechen' , 'id' => 'ce'],
        [ 'title' => 'Chichewa; Chewa; Nyanja' , 'id' => 'ny'],
        [ 'title' => 'Chinese' , 'id' => 'zh'],
        [ 'title' => 'Chuvash' , 'id' => 'cv'],
        [ 'title' => 'Cornish' , 'id' => 'kw'],
        [ 'title' => 'Corsican' , 'id' => 'co'],
        [ 'title' => 'Cree' , 'id' => 'cr'],
        [ 'title' => 'Croatian' , 'id' => 'hr'],
        [ 'title' => 'Czech' , 'id' => 'cs'],
        [ 'title' => 'Danish' , 'id' => 'da'],
        [ 'title' => 'Divehi; Dhivehi; Maldivian;' , 'id' => 'dv'],
        [ 'title' => 'Dutch' , 'id' => 'nl'],
        [ 'title' => 'English' , 'id' => 'en'],
        [ 'title' => 'Esperanto' , 'id' => 'eo'],
        [ 'title' => 'Estonian' , 'id' => 'et'],
        [ 'title' => 'Ewe' , 'id' => 'ee'],
        [ 'title' => 'Faroese' , 'id' => 'fo'],
        [ 'title' => 'Fijian' , 'id' => 'fj'],
        [ 'title' => 'Finnish' , 'id' => 'fi'],
        [ 'title' => 'French' , 'id' => 'fr'],
        [ 'title' => 'Fula; Fulah; Pulaar; Pular' , 'id' => 'ff'],
        [ 'title' => 'Galician' , 'id' => 'gl'],
        [ 'title' => 'Georgian' , 'id' => 'ka'],
        [ 'title' => 'German' , 'id' => 'de'],
        [ 'title' => 'Greek, Modern' , 'id' => 'el'],
        [ 'title' => 'Guaraní' , 'id' => 'gn'],
        [ 'title' => 'Gujarati' , 'id' => 'gu'],
        [ 'title' => 'Haitian; Haitian Creole' , 'id' => 'ht'],
        [ 'title' => 'Hausa' , 'id' => 'ha'],
        [ 'title' => 'Hebrew (modern)' , 'id' => 'he'],
        [ 'title' => 'Herero' , 'id' => 'hz'],
        [ 'title' => 'Hindi' , 'id' => 'hi'],
        [ 'title' => 'Hiri Motu' , 'id' => 'ho'],
        [ 'title' => 'Hungarian' , 'id' => 'hu'],
        [ 'title' => 'Interlingua' , 'id' => 'ia'],
        [ 'title' => 'Indonesian' , 'id' => 'id'],
        [ 'title' => 'Interlingue' , 'id' => 'ie'],
        [ 'title' => 'Irish' , 'id' => 'ga'],
        [ 'title' => 'Igbo' , 'id' => 'ig'],
        [ 'title' => 'Inupiaq' , 'id' => 'ik'],
        [ 'title' => 'Ido' , 'id' => 'io'],
        [ 'title' => 'Icelandic' , 'id' => 'is'],
        [ 'title' => 'Italian' , 'id' => 'it'],
        [ 'title' => 'Inuktitut' , 'id' => 'iu'],
        [ 'title' => 'Japanese' , 'id' => 'ja'],
        [ 'title' => 'Javanese' , 'id' => 'jv'],
        [ 'title' => 'Kalaallisut, Greenlandic' , 'id' => 'kl'],
        [ 'title' => 'Kannada' , 'id' => 'kn'],
        [ 'title' => 'Kanuri' , 'id' => 'kr'],
        [ 'title' => 'Kashmiri' , 'id' => 'ks'],
        [ 'title' => 'Kazakh' , 'id' => 'kk'],
        [ 'title' => 'Khmer' , 'id' => 'km'],
        [ 'title' => 'Kikuyu, Gikuyu' , 'id' => 'ki'],
        [ 'title' => 'Kinyarwanda' , 'id' => 'rw'],
        [ 'title' => 'Kirghiz, Kyrgyz' , 'id' => 'ky'],
        [ 'title' => 'Komi' , 'id' => 'kv'],
        [ 'title' => 'Kongo' , 'id' => 'kg'],
        [ 'title' => 'Korean' , 'id' => 'ko'],
        [ 'title' => 'Kurdish' , 'id' => 'ku'],
        [ 'title' => 'Kwanyama, Kuanyama' , 'id' => 'kj'],
        [ 'title' => 'Latin' , 'id' => 'la'],
        [ 'title' => 'Luxembourgish, Letzeburgesch' , 'id' => 'lb'],
        [ 'title' => 'Luganda' , 'id' => 'lg'],
        [ 'title' => 'Limburgish, Limburgan, Limburger' , 'id' => 'li'],
        [ 'title' => 'Lingala' , 'id' => 'ln'],
        [ 'title' => 'Lao' , 'id' => 'lo'],
        [ 'title' => 'Lithuanian' , 'id' => 'lt'],
        [ 'title' => 'Luba-Katanga' , 'id' => 'lu'],
        [ 'title' => 'Latvian' , 'id' => 'lv'],
        [ 'title' => 'Manx' , 'id' => 'gv'],
        [ 'title' => 'Macedonian' , 'id' => 'mk'],
        [ 'title' => 'Malagasy' , 'id' => 'mg'],
        [ 'title' => 'Malay' , 'id' => 'ms'],
        [ 'title' => 'Malayalam' , 'id' => 'ml'],
        [ 'title' => 'Maltese' , 'id' => 'mt'],
        [ 'title' => 'Māori' , 'id' => 'mi'],
        [ 'title' => 'Marathi (Marāṭhī)' , 'id' => 'mr'],
        [ 'title' => 'Marshallese' , 'id' => 'mh'],
        [ 'title' => 'Mongolian' , 'id' => 'mn'],
        [ 'title' => 'Nauru' , 'id' => 'na'],
        [ 'title' => 'Navajo, Navaho' , 'id' => 'nv'],
        [ 'title' => 'Norwegian Bokmål' , 'id' => 'nb'],
        [ 'title' => 'North Ndebele' , 'id' => 'nd'],
        [ 'title' => 'Nepali' , 'id' => 'ne'],
        [ 'title' => 'Ndonga' , 'id' => 'ng'],
        [ 'title' => 'Norwegian Nynorsk' , 'id' => 'nn'],
        [ 'title' => 'Norwegian' , 'id' => 'no'],
        [ 'title' => 'Nuosu' , 'id' => 'ii'],
        [ 'title' => 'South Ndebele' , 'id' => 'nr'],
        [ 'title' => 'Occitan' , 'id' => 'oc'],
        [ 'title' => 'Ojibwe, Ojibwa' , 'id' => 'oj'],
        [ 'title' => 'Oromo' , 'id' => 'om'],
        [ 'title' => 'Oriya' , 'id' => 'or'],
        [ 'title' => 'Ossetian, Ossetic' , 'id' => 'os'],
        [ 'title' => 'Panjabi, Punjabi' , 'id' => 'pa'],
        [ 'title' => 'Pāli' , 'id' => 'pi'],
        [ 'title' => 'Persian' , 'id' => 'fa'],
        [ 'title' => 'Polish' , 'id' => 'pl'],
        [ 'title' => 'Pashto, Pushto' , 'id' => 'ps'],
        [ 'title' => 'Portuguese' , 'id' => 'pt'],
        [ 'title' => 'Quechua' , 'id' => 'qu'],
        [ 'title' => 'Romansh' , 'id' => 'rm'],
        [ 'title' => 'Kirundi' , 'id' => 'rn'],
        [ 'title' => 'Romanian, Moldavian, Moldovan' , 'id' => 'ro'],
        [ 'title' => 'Russian' , 'id' => 'ru'],
        [ 'title' => 'Sanskrit (Saṁskṛta)' , 'id' => 'sa'],
        [ 'title' => 'Sardinian' , 'id' => 'sc'],
        [ 'title' => 'Sindhi' , 'id' => 'sd'],
        [ 'title' => 'Northern Sami' , 'id' => 'se'],
        [ 'title' => 'Samoan' , 'id' => 'sm'],
        [ 'title' => 'Sango' , 'id' => 'sg'],
        [ 'title' => 'Serbian' , 'id' => 'sr'],
        [ 'title' => 'Scottish Gaelic; Gaelic' , 'id' => 'gd'],
        [ 'title' => 'Shona' , 'id' => 'sn'],
        [ 'title' => 'Sinhala, Sinhalese' , 'id' => 'si'],
        [ 'title' => 'Slovak' , 'id' => 'sk'],
        [ 'title' => 'Slovene' , 'id' => 'sl'],
        [ 'title' => 'Somali' , 'id' => 'so'],
        [ 'title' => 'Southern Sotho' , 'id' => 'st'],
        [ 'title' => 'Spanish; Castilian' , 'id' => 'es'],
        [ 'title' => 'Sundanese' , 'id' => 'su'],
        [ 'title' => 'Swahili' , 'id' => 'sw'],
        [ 'title' => 'Swati' , 'id' => 'ss'],
        [ 'title' => 'Swedish' , 'id' => 'sv'],
        [ 'title' => 'Tamil' , 'id' => 'ta'],
        [ 'title' => 'Telugu' , 'id' => 'te'],
        [ 'title' => 'Tajik' , 'id' => 'tg'],
        [ 'title' => 'Thai' , 'id' => 'th'],
        [ 'title' => 'Tigrinya' , 'id' => 'ti'],
        [ 'title' => 'Tibetan Standard, Tibetan, Central' , 'id' => 'bo'],
        [ 'title' => 'Turkmen' , 'id' => 'tk'],
        [ 'title' => 'Tagalog' , 'id' => 'tl'],
        [ 'title' => 'Tswana' , 'id' => 'tn'],
        [ 'title' => 'Tonga (Tonga Islands)' , 'id' => 'to'],
        [ 'title' => 'Turkish' , 'id' => 'tr'],
        [ 'title' => 'Tsonga' , 'id' => 'ts'],
        [ 'title' => 'Tatar' , 'id' => 'tt'],
        [ 'title' => 'Twi' , 'id' => 'tw'],
        [ 'title' => 'Tahitian' , 'id' => 'ty'],
        [ 'title' => 'Uighur, Uyghur' , 'id' => 'ug'],
        [ 'title' => 'Ukrainian' , 'id' => 'uk'],
        [ 'title' => 'Urdu' , 'id' => 'ur'],
        [ 'title' => 'Uzbek' , 'id' => 'uz'],
        [ 'title' => 'Venda' , 'id' => 've'],
        [ 'title' => 'Vietnamese' , 'id' => 'vi'],
        [ 'title' => 'Volapük' , 'id' => 'vo'],
        [ 'title' => 'Walloon' , 'id' => 'wa'],
        [ 'title' => 'Welsh' , 'id' => 'cy'],
        [ 'title' => 'Wolof' , 'id' => 'wo'],
        [ 'title' => 'Western Frisian' , 'id' => 'fy'],
        [ 'title' => 'Xhosa' , 'id' => 'xh'],
        [ 'title' => 'Yiddish' , 'id' => 'yi'],
        [ 'title' => 'Yoruba' , 'id' => 'yo'],
        [ 'title' => 'Zhuang, Chuang' , 'id' => 'za']
    ];
    if(!empty($ids))
    {
        $language = collect($language)->whereIn('id',$ids)->values();   
    }
    return $language;
}
function replaceJsonString($replace_from,$replace_to,$input)
{
    $result="";

    for($i=0;$i<strlen($input);$i++)
    {
        $result.= ($input[$i]==$replace_from)?$replace_to:$input[$i];
    }

    return $result;
}
function flattenToMultiDimensional(array $array, $delimiter = '.')
{
    $result = [];
    foreach ($array as $notations => $value) {
        // extract keys
        $keys = explode($delimiter, $notations);
        // reverse keys for assignments
        $keys = array_reverse($keys);

        // set initial value
        $lastVal = $value;
        foreach ($keys as $key) {
            // wrap value with key over each iteration
            $lastVal = [
                $key => $lastVal
            ];
        }
        
        // merge result
        $result = array_merge_recursive($result, $lastVal);
    }

    return $result;
}
function createLangFile($lang=''){
    $langDir = resource_path().'/lang/';
    $enDir = $langDir.'en';
    $currentLang = $langDir . $lang;
    if(!File::exists($currentLang)){
       File::makeDirectory($currentLang);
       File::copyDirectory($enDir,$currentLang);
    }
}

function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return sprintf($format, 0, 0);
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function getSettingKeyValue($type="",$key=""){
    $setting_data = \App\Models\Setting::where('type',$type)->where('key',$key)->first();
    if($setting_data != null)
    {
        return $setting_data->value;
    } else {

        
        switch ($key) {
            case 'DISTANCE_TYPE':
                return 'km';
                break;
            case 'DISTANCE_RADIOUS':
                return 50;
                break;
            default:
                break;
        }

    }
}

function countUnitvalue($unit){
   switch ($unit) {
       case 'mile':
           return 3956;
           break;
       default:
           return 6371;
           break;
   }
}

function imageExtention($media)
{
    $extention = null;
    if($media != null){
        $path_info = pathinfo($media);
        $extention = $path_info['extension'];
    }
    return $extention;
}

function verify_provider_document($provider_id)
{
    $documents = \App\Models\Documents::where('is_required',1)->where('status', 1)->withCount([
        'providerDocument',
        'providerDocument as is_verified_document' => function ($query) use($provider_id) {
            $query->where('is_verified', 1)->where('provider_id', $provider_id);
        }])
    ->get();
    
    $is_verified = $documents->where('is_verified_document', 1);

    if(count($documents) == count($is_verified))
    {
        return true;
    } else {
        return false;
    }
}