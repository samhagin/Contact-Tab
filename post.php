<?php
require_once("../../../wp-config.php");
require_once("contact-tab.php");
if(is_array($options)) extract($options);
$mobile = $number.'@'.$mobile;

// no direct access
if(!$_POST) die('Restricted access');

function ct_sendmail($admin_email, $cc_email, $mobile, $subject) {
$to = $admin_email.',';
$to .= $cc_email.',';
$to .= $mobile;
$subject= $subject;
$from = $admin_email;
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$body = "Name: {$_POST['ct_name']}\n".
"Email: {$_POST['ct_email']} \n\n".
"Subject: {$_POST['ct_subject']}\n\n".
"Message: \n ".
"{$_POST['ct_message']}\n\n".
"IP: $ip\n\n".
"Sent from: {$_POST['ct_pageurl']}\n";
$headers = "From: $admin_email \r\n";
$headers .= "Reply-To: {$_POST['ct_email']} \r\n";
//$headers .= "Bcc: $cc_email \r\n";
$headers .= "Cc: $cc_email \r\n";
if(!empty($_POST['ct_name']) || !empty($_POST['ct_email']) || !empty($_POST['ct_message']))
                wp_mail($to, $subject, $body,$headers);

}

function ct_autorespond ($admin_email, $am) {
$from = $admin_email;
$to = $_POST['ct_email'];
$subject = 'Thank you for contacting us';
$body = $am;
$headers = "From: $from \r\n";
$headers .= "Reply-To: $from \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
mail ($to, $subject, $body, $headers );
}

if ($captcha == 'yes') {
ob_start();
session_start();
	if(strtolower($_POST['ct_code']) == strtolower($_SESSION['6_letters_code']))
	{
		
		
		echo '1'; //valid code
 		//send the email
		ct_sendmail($admin_email, $cc_email, $mobile, $subject);
		ct_save();
if ($auto == 'yes' && !empty($am)) {
		ct_autorespond($admin_email, $am);
}
		
	}
	else
	{
		echo '0'; // invalid code
	}
}

else {
if(!empty($_POST['ct_name']) || !empty($_POST['ct_email']) || !empty($_POST['ct_message'])){
echo '1';
//send the email
ct_sendmail($admin_email, $cc_email, $mobile, $subject);
ct_save();
if ($auto == 'yes' && !empty($am)) {
                ct_autorespond($admin_email, $am);
}

	}
}

//insert data into database
function ct_save() {
//honeypot for bots
if(empty($_POST['ct_null'])) {
global $wpdb, $wp_locale;
$email = esc_html($_POST['ct_email']);
$name  = esc_html($_POST['ct_name']);
$message = esc_html($_POST['ct_message']);
$ip = $_SERVER['REMOTE_ADDR'];
$subject = esc_html($_POST['ct_subject']);
$time = date('D M j G:i:s Y',current_time('timestamp')); 
$url = esc_html($_POST['ct_pageurl']);
$table_name = $wpdb->prefix . "contact_tab"; 

$wpdb->insert($table_name, array('time' => $time,
'name'=> $name,
'email' => $email,
'subject' => $subject,
'message' => $message,
'ip' => $ip,
'url' => $url,
'replied' => '0' ));
	}
}

	?>
