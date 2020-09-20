<?php
require_once "dbconnect.php";
require_once  "dbUtils.php";

$email = filter_var($_POST['e'],FILTER_SANITIZE_EMAIL);
$token = $_POST['t'];

     //elegxos tou token an einai swsto
if(checkToken($mysqli,$token,$email)){
     //elegxos an to pedio newPassword sthn vash einai 0 an einai tote einai epivevewsh email
     //an den einai tote einai epivevewsh allaghs kwdikou
    mysqli_autocommit($mysqli,FALSE); 
 if(getValueFromDB($mysqli,$email,"newPassword")=="0"){
    if(updateValueInDB($mysqli,$email,"confirmed",1,"i")){
        mysqli_commit($mysqli);
        echo "success";
    }
    else{
        mysqli_rollback($mysqli);
        echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";
    }
 }
 else{
    if(updateValueInDB($mysqli,$email,"password",getValueFromDB($mysqli,$email,"newPassword"),"s")){
        if(updateValueInDB($mysqli,$email,"newPassword","0","s")){  //vazw to newPassword=0 pali gia na borei na zitisei o xristis xana allagh kwdikou ama xriastei
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
           echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";
    }
    
 }
}
else{
    echo"Κάτι πήγε στραβά και δεν μπορέσαμε να επιβεβαιώσουμε το email σας,δοκιμάστε ξανά αργότερα";   
}


  //elegxos an to token tou xristi sthn vash einai idio me to token pou hrthe apo to POST
function checkToken($mysqli,$token,$email){
    $sqlSelect ="SELECT token AS token FROM users WHERE email=?";
    if($stmt = $mysqli->prepare($sqlSelect)){
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row["token"]== $token){
            return true;
        }
        else return false;
    }
}

?>