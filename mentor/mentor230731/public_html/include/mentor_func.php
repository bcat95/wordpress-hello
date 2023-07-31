<?php
function echo_isset($var, $default = "") {
    if (isset($var)) echo $var; else echo $default;
}

function issetor($var, $default = false) {
    return isset($var) ? $var : $default;
}

function user_fid(){
    if (isset($_SESSION["user_fid"])) {
        return $_SESSION["user_fid"];
    } else return null;
}

function set_link($key,$value){
    $query = $_GET;
    $query[$key] = $value;
    $query_result = http_build_query($query);
    return $query_result;
}

function get_table_auto_id($table_name){
    global $connect;
    $query = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'sanc_admin_sancontent' AND TABLE_NAME = '$table_name'";
    $rows = mysqli_fetch_assoc(mysqli_query($connect,$query));
    $id_auto = (int)$rows['AUTO_INCREMENT'];
    return $id_auto;
}
// ************************************ /.database ************************************ //

function us_id(){
    if (isset($_SESSION["us_id"])) return $_SESSION["us_id"];
    else if (isset($_COOKIE["us_id"])) return $_COOKIE["us_id"];
    else {
        $us_id = new_us_id();
        return $us_id;
    }
}

function set_us_id($us_id) {
    header("Set-Cookie: us_id=".$us_id."; path=/; domain=.chatgptvietnam.com; Secure; SameSite=None;Max-Age=".(86400*365*10));
    return $us_id;
}

function new_us_id(){
    $us_id = md5(time().'_'.rand(1,1000));
    $us_id = set_us_id($us_id);
    return $us_id;
}

function get_client_ip(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function response($type,$message){
    $response = [];
    $response[$type]['message'] = $message;
    return json_encode($response);
}

function compress_htmlcode($codedata) {
    $searchdata = array(
        '/\>[^\S ]+/s', // remove whitespaces after tags
        '/[^\S ]+\</s', // remove whitespaces before tags
        '/(\s)+/s' // remove multiple whitespace sequences
    );
    $replacedata = array('>','<','\\1');
    $codedata = preg_replace($searchdata, $replacedata, $codedata);
    return $codedata;
}

function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    }
    return $inp; 
}

function search($array, $key, $value){
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }
    return $results;
}

function time_elapsed_string($datetime, $full = false) {
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'vừa xong';
}

function time_elapsed_string_2($datetime, $full = false) {
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' nữa' : 'sắp';
}

function utf8convert($string){
    if (!$string) {
        return false;
    }

    $string = strtolower($string);
    $utf8 = [
        'a' => 'á|à|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ|Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    ];
    foreach ($utf8 as $ascii => $uni) {
        $string = preg_replace("/($uni)/i", $ascii, $string);
    }

    return $string;
}

function utf8tourl($text){
    $text = strtolower(utf8convert($text));
    $text = str_replace("ß", "ss", $text);
    $text = str_replace("%", "", $text);
    $text = preg_replace("/[^_a-zA-Z0-9 -] /", "", $text);
    $text = str_replace(['%20', ' '], '-', $text);
    $text = str_replace("----", "-", $text);
    $text = str_replace("---", "-", $text);
    $text = str_replace("--", "-", $text);
    return $text;
}

function _curl($url,$post="",$usecookie = false,$_sock = false,$timeout = false) { 
    $ch = curl_init();
    if($post) {
        curl_setopt($ch, CURLOPT_POST ,1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
    }
    if($timeout){
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout);
    }
    if($_sock){
        curl_setopt($ch, CURLOPT_PROXY, $_sock);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36'); 
    if ($usecookie) { 
        curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie); 
        curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);    
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    //curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    $result=curl_exec($ch);
    curl_close ($ch); 
    return $result; 
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function shopee_curl($url,$post="",$usecookie = false,$_sock = false,$timeout = false) {
    $if_none_match = shopee_if_none_match($url);
    if (!$if_none_match) return false;

    $ch = curl_init();
    if($post) {
        curl_setopt($ch, CURLOPT_POST ,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if($timeout){
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout);
    }
    if($_sock){
        curl_setopt($ch, CURLOPT_PROXY, $_sock);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36'); 
    if ($usecookie) { 
        curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie); 
        curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);    
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'cookie: SPC_U=0;',
        'if-none-match-: '.$if_none_match,
        'Referer: https://shopee.vn'
    ));
    $result=curl_exec ($ch); 
    curl_close ($ch); 
    return $result; 
}

function shopee_if_none_match($url){
  $if_none_match = 0;
  $url_query = parse_url($url, PHP_URL_QUERY);
  $if_none_match = '55b03-'.md5('55b03'.md5($url_query).'55b03');
  return $if_none_match;
}

function detect_is_bot_sa() {
    if (isset($_GET['speed']) && $_GET['speed'] == 1) return true;
    $bots = array("spider", "coccoc", "bot", "Scooter", "URL_Spider_SQL", "Googlebot", "Firefly", "WebBug", "WebFindBot", "crawler",  "appie", "msnbot", "InfoSeek", "FAST", "Spade", "NationalDirectory","chrome-lighthouse");
    if (isset($_SERVER['HTTP_USER_AGENT'])) $agent = strtolower($_SERVER['HTTP_USER_AGENT']); else return true;
    foreach($bots as $bot) {
        if(stripos($agent,$bot)!==false) {return true;}
    }
    return false;
}

function detect_is_bot() {
    if (isset($_GET['speed']) && $_GET['speed'] == 1) return true;
    $bots = array("spider", "coccoc", "bot", "Scooter", "URL_Spider_SQL", "Googlebot", "Firefly", "WebBug", "WebFindBot", "crawler",  "appie", "msnbot", "InfoSeek", "FAST", "Spade", "NationalDirectory","chrome-lighthouse");
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach($bots as $bot) {
        if(stripos($agent,$bot)!==false) {return true;}
    }
    return false;
}

function weekDay($weekday){
    $weekday = strtolower($weekday);
    switch ($weekday) {
        case 'mon':
            return 'Thứ hai';
            break;
        case 'tue':
            return 'Thứ ba';
            break;
        case 'wed':
            return 'Thứ tư';
            break;
        case 'thu':
            return 'Thứ năm';
            break;
        case 'fri':
            return 'Thứ sáu';
            break;
        case 'sat':
            return 'Thứ bảy';
            break;
        default:
            return 'Chủ nhật';
            break;
    }
}
?>