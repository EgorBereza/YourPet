<?php


require_once  "Mailer.php";

if(isset($_POST['password'],$_POST['cPassword'],$_POST['name'],$_POST['surname'],$_POST['email'])){

$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$cPassword = filter_var($_POST['cPassword'], FILTER_SANITIZE_STRING);
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if(empty($password) || empty($name) || empty($surname ) || empty($email)){
    echo "Παρακαλώ συμπληρώστε όλα τα πεδία";
}
else if(strlen($password)<1){  //////NEEDS TO CHANGE BACK TO 8
    echo "Ο κωδικός πρέπει να αποτελείται τουλάχιστον από 8 χαρακτήρες";
}
else if($password != $cPassword){
    echo "Ο κωδικός είναι διαφορετικός απο την επιβεβαίωση κωδικού";
}
else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo "Δεν είναι έγκυρο το email";
}
else{
    //elegxos gia uparxi xristi me idio email sthn vash
    if(sameEmail($mysqli,$email)){
       echo "Υπάρχει ήδη λογαριασμός με αυτό το email";
    }
    else{
     mysqli_autocommit($mysqli,FALSE);
     $sqlInsert="INSERT INTO `users` (`password`,`name`,`surname`,`email`,`confirmed`,`token`,`newPassword`) VALUES (?,?,?,?,0,?,'0')"; 
     //dimiourgia tuxaiou token gia epivevewsh email tou xristi
     $token=generateToken($surname);
     //hash tou kwdikou 
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     if($stmt = $mysqli->prepare($sqlInsert)){
        $stmt->bind_param("sssss",$hashed_password,$name,$surname,$email,$token);
        $stmt->execute();
        $msg = "Για να επιβεβαιώσετε το email σας";
        //apostoli tou email epivevewsis
        if(sendEmail($email,$token,$msg)) { 
        mysqli_commit($mysqli);
        echo "success";
        }else {
        echo "Δεν είναι έγκυρο το email";
        mysqli_rollback($mysqli);
        }
    }
     mysqli_close($mysqli);
    }
}
}

//methodos gia to an uparxei idi xristis me auto to email
function sameEmail($mysqli,$email){
    $sqlSelect ="SELECT COUNT(*) AS emailExist FROM users WHERE email=?";
    if($stmt = $mysqli->prepare($sqlSelect)){
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      if($row["emailExist"]==1){
        return true;
      }
      else return false;
    }
}


?>