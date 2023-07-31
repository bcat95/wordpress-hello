<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "/home/mentor.chatgptvietnam.org/public_html/vendor/autoload.php";
require_once "/home/mentor.chatgptvietnam.org/public_html/include/conn.php";
require_once "/home/mentor.chatgptvietnam.org/public_html/include/mentor_func.php";

// config
$m_config = [];
$m_config['Host'] = 'email-smtp.us-west-2.amazonaws.com';
$m_config['Username'] = 'AKIAUCQYKLABJM5XYFUG';
$m_config['Password'] = 'BACLL/kPbjY32gHgJ9SgmGyTBLn/DF5xjxeDsF4O/LHK';

function mail_send($mail_key, $mail_setting = array(), $mail_content = array()){
    global $m_config;
    $mail = new PHPMailer(true);

    // setFrom
    if (isset($mail_setting['setFrom'])) $setFrom = $mail_setting['setFrom']; else $setFrom = [];
    if (!isset($setFrom['name']) || $setFrom['name'] == "") $setFrom['name'] = 'info@chatgptvietnam.org';
    if (!isset($setFrom['title']) || $setFrom['title'] == "") $setFrom['title'] = 'chatgptvietnam.org';

    // addAddress
    if (isset($mail_setting['addAddress'])) $addAddress = $mail_setting['addAddress']; else $addAddress = [];
    if (!isset($addAddress['name']) || $addAddress['name'] == "") { echo response('errors','Email received is incorrect or missing');return false; }
    if (!isset($addAddress['title']) || $addAddress['title'] == "") { echo response('errors','Email name received incorrect or missing');return false; }

    // addReplyTo
    if (isset($mail_setting['addReplyTo']) && $mail_setting['addReplyTo']) $addReplyTo = $mail_setting['addReplyTo']; else $addReplyTo = [];
    if (is_array($addReplyTo)){
        if (!isset($addReplyTo['name']) || $addReplyTo['name'] == "") $addReplyTo['name'] = 'hotro@chatgptvietnam.org';
        if (!isset($addReplyTo['title']) || $addReplyTo['title'] == "") $addReplyTo['title'] = 'chatgptvietnam.org';
    }

    // addCC
    if (isset($mail_setting['addCC']) && $mail_setting['addCC']) $addCC = $mail_setting['addCC']; else $addCC = [];

    // addBCC
    if (isset($mail_setting['addBCC']) && $mail_setting['addBCC']) $addBCC = $mail_setting['addBCC']; else $addBCC = [];

    if (!isset($mail_content['Subject']) || $mail_content['Subject'] == "") { echo response('errors','Email subject missing');return false; }
    else $Subject = $mail_content['Subject'];

    if (!isset($mail_content['Body']) || $mail_content['Body'] == "") { echo response('errors','Email Body missing');return false; }
    else $Body = $mail_content['Body'];
    
    if (!isset($mail_content['AltBody']) || $mail_content['AltBody'] == "") $AltBody = "";
    else $AltBody = $mail_content['AltBody'];

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $m_config['Host'];                      // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $m_config['Username'];                  // SMTP username
        $mail->Password   = $m_config['Password'];                  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($setFrom['name'], $setFrom['title']);
        $mail->addAddress($addAddress['name'], $addAddress['title']);
        if (is_array($addReplyTo)) $mail->addReplyTo($addReplyTo['name'], $addReplyTo['title']);
        if (is_array($addCC) && sizeof($addCC) > 0)  foreach ($addCC as $key => $value) $mail->addCC($value);
        if (is_array($addBCC) && sizeof($addBCC) > 0) foreach ($addBCC as $key => $value) $mail->addBCC($value);

        if (isset($mail_setting['unsubscribe']) && $mail_setting['unsubscribe'] != ""){
            $mail->AddCustomHeader('List-Unsubscribe', $mail_setting['unsubscribe']);
        }

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $Subject;
        $mail->Body    = $Body;
        $mail->AltBody = $AltBody;
        $mail->Encoding = 'base64';
        $mail->send();
        // echo response('success','Message has been sent');
        mail_log($mail_key,'mail_send','1',$mail_setting,$mail_content);
        return true;
    } catch (Exception $e) {
        // echo response('errors','Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
        mail_log($mail_key,'mail_send','0',$mail_setting,$mail_content);
        return false;
    }
}


function mail_log($mail_key,$action,$status,$mail_setting,$mail_content){
    global $connect;

    if (isset($mail_setting['addAddress'])) $addAddress = $mail_setting['addAddress'];
    if (isset($addAddress) && isset($addAddress['name'])) $user = get_user_by_email($addAddress['name']);
    
    // $us_id = '';
    // if (isset($user['us_id']) && $user['us_id'] != '') $user_uid = $user['us_id'];
    // if (isset($user['sender_id']) && $user['sender_id'] != '') $user_uid = $user['sender_id'];
    $t_arr = explode("_", $mail_key);
    $us_id = $t_arr[1];

    $mail_setting = addslashes(json_encode($mail_setting, JSON_UNESCAPED_UNICODE));
    $mail_content = addslashes(json_encode($mail_content, JSON_UNESCAPED_UNICODE));
    $time_created = time();

    $query = "INSERT INTO rio_mail_log (mail_key,us_id,action,status,setting,content,time_created) VALUES ('".$mail_key."','".$us_id."','".$action."','".$status."','".$mail_setting."','".$mail_content."','".$time_created."')";
    $connect->query($query);
}

function get_users_send_mail_camp($mail_camp,$limit=1){
    global $connect;
    $camp_name = $mail_camp['camp_name'];
    $LIMIT = $limit;
    $query = "
        SELECT * FROM mentor_user t1
        WHERE t1.email != '' AND t1.email_verified = 1
        AND NOT EXISTS (
            SELECT * FROM mentor_users_mail t2
            WHERE t2.us_id = t1.us_id AND t2.mail_name = 'mail_camp' AND t2.value = '".$camp_name."'
            LIMIT $LIMIT
        )
        ORDER BY t1.id ASC
        LIMIT $LIMIT
    ";
    $result=mysqli_query($connect,$query);
    if ($result){
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    } else $rows = false;

    return $rows;
}

function add_mentor_users_mail($mail_key,$us_id,$mail_name,$value="",$status=0){
    global $connect;
    if ($value == "") $value = date('d-m-Y');
    if ($status == 0) $status = 0;
    $query = "
        INSERT INTO mentor_users_mail (mail_key,us_id,mail_name,value,status)
        VALUES ('".$mail_key."','".$us_id."','".$mail_name."','".$value."','".$status."')";
    // echo $query;
    if ($connect->query($query) === TRUE) {
        // echo response('success','Thêm mới rio_users_mail thành công');return true;
    } else {
        // echo response('errors','Thêm mới rio_users_mail không thành công');return false;
    }
}

function send_mail_wellcome($user){
    if (!$user) return false;
    // create mail_key

    if (isset($user['us_id']) && $user['us_id'] != '') $user_uid = $user['us_id'];
    if (isset($user['sender_id']) && $user['sender_id'] != '') $user_uid = $user['sender_id'];

    $mail_key = time()."_".$user_uid;

    if (isset($user['email']) && $user['email'] != ""){
        // mail wellcome
        
        $htm_mail = mail_template_wellcome($mail_key,$user);

        if ($htm_mail && $htm_mail != ""){
            $setting = [];
            
            $setFrom = [];
            $setFrom['name'] = 'info@chatgptvietnam.org';
            $setting['setFrom'] = $setFrom;

            $setting['addAddress'] = [];
            $setting['addAddress']['name'] = $user['email'];

            if (isset($user['display_name'])) $setting['addAddress']['title'] = $user['display_name'];
            else $setting['addAddress']['title'] = "Thành viên chatgptvietnam";

            $content = [];
            $content['Subject'] = "Chào mừng bạn ".$setting['addAddress']['title']." đến với chatgptvietnam.org";
            $content['Body'] = $htm_mail;

            // var_dump($setting);
            // echo '<br/><br/>';
            // var_dump($content);
            // return true;

            add_msht_users_mail($mail_key,$user_uid,'mail_wellcome',"",1);
            mail_send($mail_key, $setting, $content);
            return true;
        }
    }
}


function mail_template_wellcome($mail_key,$user){

    if (isset($user['display_name'])) $user['user_name'] = $user['display_name'];
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
    <title>Mua Sắm Hoàn Tiền</title>
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
                                <td style="padding-top: 20px;" align="center">
                                    <table width="500px">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="middle">
                                                    <a href="https://muasamhoantien.com/">
                                                        <img width="132" height="48" src="https://muasamhoantien.com/assets/logo-msht.png" alt="MuaSamHoanTien.com" style="display: block;height: 48px;">
                                                    </a>
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
                                                <td style="padding-top: 32px;font-size: 16px;line-height:24px;height:24px;color: rgba(51,51,51,1);font-weight:bold;" align="left">
                                                    Chào mừng <?=$user['user_name']?> đã đến với Muasamhoantien.com
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
                                                    <p style="margin: 0">Muasamhoantien.com tự hào là nền tảng mua sắm hoàn tiền đối tác với hơn 100+ các trang thương mại điện tử hàng đầu Việt Nam như: Shopee, Tiki, Lazada, Sendo... và hàng ngàn thương hiệu.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:12px;padding-bottom:12px;font-size:14px;color:rgba(70,77,98,1);line-height:22px;" align="left">
                                                    <p>Mua sắm an toàn hơn với Review đánh giá, tiết kiệm hơn với mã giảm giá & thông minh hơn với Muasamhoantien.com - Ứng dụng hoàn tiền khi mua sắm trực tuyến - 1 click đăng ký sử dụng trọn đời.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:8px;font-size:14px;text-align: center; font-weight:500;color:#363642;line-height:22px">
                                                    <a href="https://muasamhoantien.com/app/shop?rel=mail_<?=$mail_key?>" style="padding: 8px 12px;border-radius: 5px;color: #fff;background-color: #f30;border-color: #f35;margin: 0px auto;display: inline-block;text-decoration: none"> Bắt đầu mua sắm</a>
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
                                                    <p style="margin: 0;">chatgptvietnam.org</p>
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
            <tr>
                <td bgcolor="#F7F9FB" align="center">
                    <table width="500px">
                        <tbody>
                            <tr>
                                <td style="padding-top:32px;font-size: 13px;line-height:22px;color:rgba(153,153,153,1);" align="left">
                                    <p style="margin: 0;">Nếu bạn có câu hỏi có thể liên hệ chúng tôi tại  
                                        <a title="Trung tâm trợ giúp" href="https://chatgptvietnam.org/pages/support?rel=mail_<?=$mail_key?>" target="_blank" style="letter-spacing: normal;text-align: center;text-decoration:none;color: #8DABDC;-ms-text-size-adjust: 100%;-webkit-text-size-adjust:100%;">Trung tâm trợ giúp.
                                        </a> Hoặc trả lời bên dưới.
                                    </p>
                                    <p style="margin-top: 0;">© <?=date('Y')?> chatgptvietnam.org</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <img src="https://chatgptvietnam.org/api/vn/mail/open?uid=<?=$user_uid?>&mkey=<?=$mail_key?>" alt="" width="1" height="1" border="0" style="height:1px !important;width:1px !important;border-width:0 !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:0 !important;margin-left:0 !important;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;"/>
</body>
</html>

<?php
    $htm_mail = ob_get_clean();
    return $htm_mail;
}


?>