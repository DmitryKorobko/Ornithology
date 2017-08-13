<?php
$mail_to = "russell@randrwebdesign.co.uk";
$mail_to = "bo@fatbirder.net";
$mail_from = stripslashes($_POST['feedback_email']);
$mail_subject = "Feedback from fatbirder.com";
$mail_body = "";
$mail_body .= "Website Feedback:<br>";
$mail_body .= "<br>Name = " . stripslashes($_POST['feedback_name']);
$mail_body .= "<br>Email = " . stripslashes($_POST['feedback_email']);
$mail_body .= "<br>Feedback = " . stripslashes($_POST['feedback']);
if (($_POST['feedback_name'] == "") || ($_POST['feedback_email'] == "")){
	$error = 1;
}
if ($_POST['country']!=""){
	$error = 99;
}
//create mail footer message
$mail_footer = "<br><br>fatbirder.com";
//concatenate mail_body and mail_footer
$mail_body = $mail_body . $mail_footer;
$mail_body = ereg_replace( "['\"\]", "", $mail_body);
$text_body = strip_tags($mail_body);
if ($error < 1){
	include_once("../phpmail/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->From     = $mail_from;
	$mail->FromName = $mail_from;
	$mail->Subject = $mail_subject;
	$mail->Body    = $mail_body;
	$mail->AltBody = $text_body;
	$mail->AddAddress($mail_to);
	// send mail
	if($mail->Send()){
		$error = 0;
	}else	{
		$error = 2;
	}
}

?>