<?php

header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 
// /usr/bin/php -q /home/riokupon.com/public_html/api/vn/mail/camp/birthday.php > /dev/null 2>&1
// 

$currentHour = date('H',time()+60*60*7);
if ($currentHour < 7 || $currentHour > 8) {
    exit();
}

require_once "/home/riokupon.com/public_html/api/vn/mail/mail-func.php";

$mail_camp = [];
$mail_camp['camp_name'] = "birthday";
$mail_camp['camp_title'] = "CHÚC MỪNG SINH NHẬT";
$mail_camp['camp_send_to'] = "all";
$mail_camp['camp_send_date'] = "230516";

if (isset($_GET['token']) && $_GET['token'] != '') {
    $token = base64_decode(trim($_GET['token']));
    $sender_id = substr($token, 0, -10);
    $user = rio_get_sender($sender_id);
    if (!$user) {
        echo response('error','Người dùng không tồn tại');
        exit;
    }
    
    $check_send_user_mail_camp = check_send_user_mail_camp($user,$mail_camp);
    if ($check_send_user_mail_camp) {
        echo response('error','Email đã được gửi đi trước đó.');
        exit;
    }

    $users_send_mail_camp = [];
    $users_send_mail_camp[] = $user;
    send_users_mail_camp($users_send_mail_camp,$mail_camp);
    exit();
}

// main
$users_send_mail_camp = t_get_users_send_mail_camp($mail_camp,1);
if (isset($_GET['f5']) && $_GET['f5'] > 0 && $users_send_mail_camp) echo '<meta http-equiv="refresh" content="'.$_GET['f5'].'">';
if ($users_send_mail_camp) send_users_mail_camp($users_send_mail_camp,$mail_camp);
else {
    echo '<h3>'.date('d/m/Y H:i:s',time()+60*60*7).'</h3>';
    echo '<h3>DONE. Tại lại sau 3600s nữa</h3>';
    echo '<meta http-equiv="refresh" content="3600">';
}

function t_get_users_send_mail_camp($mail_camp,$limit=1){
    global $connect;
    $query = "
        SELECT t1.*,t3.name,t3.nickname,t3.email FROM fbchat_user_mission t1
        INNER JOIN fbchat_user t3 ON t3.sender_id = t1.sender_id AND (t3.email != '' AND t3.email IS NOT NULL) AND t3.status = 1
        WHERE 1
        AND t1.mission_id = 'birthday' AND t1.mail_send = 0
        ORDER BY id DESC
        LIMIT $limit
    ";
    // echo $query;
    // Truy vấn cần 0.0006 giây.
    $result = mysqli_query($connect,$query);
    if ($result){
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    } else $rows = false;

    return $rows;
}

function check_send_user_mail_camp($user,$mail_camp){
    global $connect;
    $query = "SELECT t1.* FROM rio_users_mail t1
        WHERE 1
        AND mail_key IN (SELECT mail_key FROM rio_mail_log t2 WHERE t2.mail_key = t1.mail_key)
        AND t1.us_id = '".$user['sender_id']."'
        AND t1.mail_name = 'mail_camp' AND t1.value = '".$mail_camp['camp_name']."' AND t1.status = 1
        LIMIT 1";
    // echo $query;exit();
    $result = mysqli_query($connect,$query);
    if ($result && $row=mysqli_fetch_assoc($result)){
        return true;
    } else {
        return false;
    }
}

function send_users_mail_camp($users_send_mail_camp,$mail_camp){
    if ($users_send_mail_camp && sizeof($users_send_mail_camp) > 0){
        foreach ($users_send_mail_camp as $key => $user) {

            if (isset($user['us_id']) && $user['us_id'] != '') $user_uid = $user['us_id'];
            if (isset($user['sender_id']) && $user['sender_id'] != '') $user_uid = $user['sender_id'];
            $mail_key = time()."_".$user_uid;

            if (isset($user['email']) && $user['email'] != ""){

                $htm_mail = mail_template_camp($mail_key,$user,$mail_camp);

                if ($htm_mail && $htm_mail != ""){
                    $setting = [];
                    
                    $setFrom = [];
                    $setFrom['name'] = 'info@riokupon.com';
                    $setting['setFrom'] = $setFrom;

                    $setting['addAddress'] = [];
                    $setting['addAddress']['name'] = $user['email'];

                    if (isset($user['name'])) $setting['addAddress']['title'] = $user['name'];
                    else if (isset($user['nickname'])) $setting['addAddress']['title'] = $user['nickname'];
                    else $setting['addAddress']['title'] = "Thành viên Riokupon";

                    // $setting['unsubscribe'] = '<mailto:quangcao@riokupon.com>,<"https://riokupon.com/vn/pages/unsubscribe?type=mail_camp&camp_name='.$mail_camp['camp_name'].'&rel=mail_'.$mail_key.'">';

                    $content = [];
                    $content['Subject'] = $mail_camp['camp_title'];
                    $content['Body'] = $htm_mail;

                    var_dump($setting);
                    echo '<br/><br/>';
                    var_dump($content);
                    // return;
                    
                    add_rio_users_mail($mail_key,$user_uid,'mail_camp',$mail_camp['camp_name'],'1');
                    update_fbchat_user_mission_mail_send($user['id'],1);
                    mail_send($mail_key, $setting, $content);

                    echo response('success','Email đã được gửi đi!');
                    // echo 'true';
                    exit();
                }
            }

        }
    }
}


function mail_template_camp($mail_key,$user,$mail_camp){

    if (isset($user['name'])) $user['user_name'] = $user['name'];
    else if (isset($user['nickname'])) $user['user_name'] = $user['nickname'];
    else if (isset($user['phone'])) $user['user_name'] = $user['phone'];
    else if (isset($user['email'])) $user['user_name'] = $user['email'];
    else $user['user_name'] = "Bạn";

    if (isset($user['us_id']) && $user['us_id'] != '') $user_uid = $user['us_id'];
    if (isset($user['sender_id']) && $user['sender_id'] != '') $user_uid = $user['sender_id'];

    $htm_mail = '';
    ob_start();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riokupon</title>
    <style type="text/css">
    body{font-family:Helvetica,Arial,sans-serif;font-size:15px}
    p{margin:0;padding:0}
    table{border-collapse:collapse}
    h1,h2,h3,h4,h5,h6{display:block;margin:0;padding:0}
    img,a img{border:0;height:auto;outline:none;text-decoration:none}
    img{-ms-interpolation-mode:bicubic}
    a[href^="tel"],a[href^="sms"]{color:inherit;cursor:default;text-decoration:none}
    p,a,li,td,body,table,blockquote{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
    a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
    .templateContainer{max-width:600px!important}
    #bodyTable{margin:0 auto 24px;padding:0;background:#fff;border-radius:4px}
    </style>
</head>
<body style="height: 100%; margin: 0px; padding: 0px; width: 100%; background: rgb(247, 249, 251); zoom: 1;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="620" id="bodyTable" style="margin:0 auto 24px;padding:0;background:#fff;border-radius:4px">
        <tbody>
            <tr><td bgcolor="#F7F9FB" height="20px"></td></tr>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table width="500px">
                                        <tbody>
                                            <tr>
                                                <td style="padding-top: 32px;font-size: 15px;line-height:24px;height:24px;color: rgba(51,51,51,1);font-weight:bold;" align="left">
                                                    Chào <?=$user['user_name']?>,
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="500px">
                                        <tbody>
                                            <tr>
                                                <td style="padding-top:12px;padding-bottom:12px;font-size:14px;color:rgba(70,77,98,1);line-height:22px;" align="left">
                                                    <p style="padding-top:12px;">Riokupon chúc bạn một ngày sinh nhật ngập tràn niềm vui và hạnh phúc bên gia đình cùng những người thân yêu. Cảm ơn bạn vì đã đồng hành cùng Riokupon trong suốt chặng đường vừa qua, hy vọng chúng ta sẽ có thêm thật nhiều trải nghiệm đáng nhớ trong thời gian sắp đến. Happy birthday to you!</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="500px">
                                        <tbody>
                                            <tr>
                                                <td style="padding-top:16px;padding-bottom:40px;height:22px;font-size:14px;font-weight:500;color:rgba(51,51,51,1);line-height:22px;" align="left">
                                                    <p style="margin: 0;">Riokupon.com</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <img src="https://riokupon.com/api/vn/mail/open?uid=<?=$user_uid?>&mkey=<?=$mail_key?>" alt="" width="1" height="1" border="0" style="height:1px !important;width:1px !important;border-width:0 !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:0 !important;margin-left:0 !important;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;"/>
</body>
</html>

<?php
    $htm_mail = ob_get_clean();
    return $htm_mail;
}
