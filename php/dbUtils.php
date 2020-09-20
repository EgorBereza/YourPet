<?php
//SELECT newPassword FROM `users` where email='berezayehor@gmail.com'
//select gia mia timi sthn vash (pinakas users)
function getValueFromDB($mysqli,$email,$value){
    $sqlSelect ="SELECT ".$value." FROM users WHERE email=?";
    if($stmt = $mysqli->prepare($sqlSelect)){
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $result = $stmt->get_result();
       $row = $result->fetch_assoc();
       if(($row[$value]!=NULL)) return $row[$value];
       else return false;
      
    }
   }
   
//update mias grammhs sthn vash (pinakas users)
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
?>