<?php
require_once "dbconnect.php";
require_once  "Mailer.php";
require_once  "dbUtils.php";
if($_POST['email']==="" || $_POST['newPassword']===""){
      echo "Παρακαλώ συμπληρώστε όλα τα πεδία ";  
}

else{
 $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
 $newPassword = filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING);
 mysqli_autocommit($mysqli,FALSE);
 $hashed_password = password_hash($newPassword , PASSWORD_DEFAULT);
 //an pedio newPassword == 0 sumainei oti akoma den exei zitisei allagh kwdikou o xristis
 $newPasswordSet=getValueFromDB($mysqli,$email,"newPassword");
 if($newPasswordSet!=NULL){
  if($newPasswordSet=="0"){ 
    //vazw neo kwdiko sthn vash (se allo keli apo ton kanoniko kwdiko mexri thn epivevewsi tou)
  if(updateValueInDB($mysqli,$email,"newPassword",$hashed_password,"s") ){  
    //dimiourgia tuxaiou token apo grammata/arithmous/sumvola+epwnimo tou xristi
    $token=generateToken(getValueFromDB($mysqli,$email,"surname"));
     //vazw token sthn vash
    updateValueInDB($mysqli,$email,"token",$token,"s");
    $msg="Λάβαμε αίτηση για αλλαγή του κωδικού πρόσβασης σας,αν δεν ζητήσατε την αλλαγή εσείς αγνοήστε αυτό το email.Αλλιώς για να επιβεβαιώσετε την αλλαγή του κωδικού";
   //apostoli email me token gia epivevewsh
    if(sendEmail($email,$token,$msg)){  
        mysqli_commit($mysqli);
        echo "success";
    }
    else{
        mysqli_rollback($mysqli);
        echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";   
    }
    
   }
   else{
       mysqli_rollback($mysqli);
       echo "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";
   }
 }
 else{
       echo "Έχετε ήδη ζητήσει αλλαγή κωδικού,ελέγξτε το email σας για να επιβεβαιώσετε την αλλαγή";
 }
}else{
       echo "Λάθος διεύθυνση email ";
}
mysqli_close($mysqli);
}






?>