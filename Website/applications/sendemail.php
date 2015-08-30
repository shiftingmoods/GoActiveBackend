<?php require_once("../public/configuration.php");
	$cnct=new cnct_class();
	$cnct->cnct();
	echo "11";
?>
<?php require_once("../models/index/index.php"); 	
	$index=new index();
?>
<?php
/*
$message='<b>'.$_POST["type"].'</b><br><br>';
$message.=$_POST['message'];
$message = str_replace("
", "<br/>", $message);



$index->send_one ($message, $_POST['subject'], $_POST['name'], $_POST['email'], 'hind.walieddine@live.com');
*/
//header('Location:../index/index.php');


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
$msg .= "<p><strong>Message:</strong> ".$content."</p>\r\n";
$msg .= "</body></html>";

//$index->send_one ($message, $_POST['subject'], $_POST['name'], $_POST['email'], 'hind.walieddine@live.com');

echo 'msg '.$msg; echo '<br>';
echo 'sendto '.$sendto; echo '<br>';
echo 'username '.$username; echo '<br>';
echo 'usermail '.$usermail; echo '<br>';
echo 'usersubject '.$usersubject; echo '<br>';
echo 'content '.$content;  echo '<br>';
echo 'msg '.$msg; echo '<br>';

index->send_one ($msg, $usersubject, $username, $usermail, $sendto);
/*
if(@mail($sendto, $subject, $msg, $headers)) {
	echo "true";
	//header( 'index.php' );
} else {
	echo "false";
}
*/
header('Location:../index/index.php');
?>