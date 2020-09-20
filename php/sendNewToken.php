<?php
require_once "dbconnect.php";
require_once  "Mailer.php";
require_once  "dbUtils.php";

//apostoli neou email epivevewshs otan to zitisei o xristis

if($_POST['email']==""){
      echo "empty email";  
}
else{
    mysqli_autocommit($mysqli,FALSE);
    $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $surname=getValueFromDB($mysqli,$email,'surname');
    if($surname!=false){
        $token=generateToken(); //dimiourgia neou token kai allagh tou sthn vash
        if(updateValueInDB($mysqli,$email,"token",$token,"s")){
            $msg = "Για να επιβεβαιώσετε το email σας";
            //apostoli tou email epivevewsis
            if(sendEmail($email,$token,$msg)){
               mysqli_commit($mysqli);  //commit mono an ola htan swsta
               echo "success";
            } else {
               echo "Δεν είναι έγκυρο το emai";
               mysqli_rollback($mysqli);
            } 
           }
           else{
            mysqli_rollback($mysqli);
           }
        mysqli_close($mysqli);
        }
}

/*

function getValueFromDB($mysqli,$email,$value){
    $sqlSelect ="SELECT ".$value." FROM users WHERE email=?";
    if($stmt = $mysqli->prepare($sqlSelect)){
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $result = $stmt->get_result();
       $row = $result->fetch_assoc();
       return $row[$value];
    }
   }
   
   function updateValueInDB($mysqli,$email,$valueName,$value,$type){
       $sqlUpdate = "UPDATE users SET ".$valueName."=? WHERE email=?";
       if($stmt = $mysqli->prepare($sqlUpdate)){
           $stmt->bind_param($type."s",$value,$email);
           if($stmt->execute()){
               return true;
           }
           else{
               return false;
           }
       }
       else{
           return false;
       }
   }

*/
?>