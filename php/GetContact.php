<?php 
require "ProcessData.php";
require "PHPMailer-master/PHPMailerAutoload.php";
require "PHPMailer-master/class.smtp.php";
require "constant.php";

if(!empty($_POST["name"])){$Name = $_POST["name"];}
if(!empty($_POST["email"])){$Email = $_POST["email"];}
if(!empty($_POST["phone"])){$Phone = $_POST["phone"];}
if(!empty($_POST["comment"])){$Comment = $_POST["comment"];}

$subject = "Contact from:" . $Name;
$body = "<p>Name:" . $Name . "</p><p>Email:" . $Email . "</p><p>Phone:" . $Phone . "</p><p>Comment:" . $Comment . "</p>";
$mailto = "nagaoreishi@gmail.com";
$msg = "Xin cảm ơn đã liên lạc với chúng tôi. Xin lưu ý, văn phòng của chúng tôi hoạt động từ 8h30am đến 5h30pm GMT+7.";
if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["comment"])){ 
 	SendMail($subject, $body, $mailto, $Email, $msg);
}else{
	echo "Something is wrong.";
}

?>