<?php require_once("../public/configuration.php");
	$cnct=new cnct_class();$cnct->cnct();?>
<?php require_once("../models/index/index.php"); 	
	$index=new index(); ?>
<?php
/*
$message=$_POST['message'];
$index->send_one ($message, $_POST['subject'], $_POST['name'], $_POST['email'], 'hind.walieddine@live.com');
*/

$sendto   = "hind.walieddine@live.com";
$username = $_POST['name'];
$usermail = $_POST['email'];
$usersubject = $_POST['subject'];
$content  = nl2br($_POST['message']);

$subject  = "New Feedback Message From Claray Website";
$headers  = "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=UTF-8 \r\n";

$msg  = "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>New User Feedback</h2>\r\n";
$msg .= "<p><strong>name:</strong>".$username."</p>\r\n";
$msg .= "<p><strong>subject:</strong>".$usersubject."</p>\r\n";
$msg .= "<p><strong>Sent by:</strong> ".$usermail."</p>\r\n";
$msg .= "<p><strong>Message:</strong><br/> ".$content."</p>\r\n";
$msg .= "</body></html>";

$index->send_one ($msg, $subject, $_POST['name'], $_POST['email'], $sendto);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>