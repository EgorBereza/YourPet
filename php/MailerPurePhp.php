<?php
require_once "dbconnect.php";

function sendEmail($email,$token,$from){
    $msg = "First line of text\nSecond line of text";
    $msg = wordwrap($msg,70);

// send email
    mail("berezayehor@gmail.com","test",$msg);

}


?>