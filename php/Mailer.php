<?php
//apostoli tou email me token gia epivevewsh logariasmou me thn voitheia tou PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
require_once "dbconnect.php";
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/Exception.php';
require_once '../PHPMailer/SMTP.php';


function sendEmail($email,$token,$msg){
    $mail = new PHPMailer;
     $mail->SMTPDebug = 4;                                        // Enable debug output
     $mail->isSMTP();                                               // Set mailer to use SMTP
     $mail->Host = 'smtp.teithe.gr';                             // Specify main and backup SMTP servers
    // $mail->SMTPAuth = true;                                        // Enable SMTP authentication
    // $mail->Username = 'YourPetGr@gmail.com';                      // SMTP username
   //  $mail->Password = 'YPselida777';                              // SMTP password
  //   $mail->SMTPSecure = 'tls';                                     // Enable TLS encryption, `ssl` also accepted
     $mail->Port = 25;                                             // TCP port to connect to
     $mail->setFrom('noc@teithe.gr', 'YourPet'); 
     $mail->addAddress($email);                                      // Add a recipient
  //   $mail->addReplyTo('YourPetGr@gmail.com');
     $mail->isHTML(true);                                            // Set email format to HTML
     $mail->Subject = 'YourPet email verification';
     $mail->Body    =  $msg.":
                       <a href='https://nireas.it.teithe.gr/yourpet/?p=login&t=".$token."&e=".$email."'>Πατήστε εδώ</a>";
     $mail->AltBody = 'press on this link alt';
     if(!$mail->send()) {
         return false;
     } else {
         return true;
     }
}

//methodos gia thn dimiourgia token 
function generateToken($addStr){
    $str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890*!";
    $str .= substr(str_shuffle($str),0,20);
    return substr(str_shuffle($str),0,20);
}

?>