<?php

require_once "dbconnect.php";
if(!isset($_SESSION)) { 
    session_start(); 
}

if(isset($_POST['password'],$_POST['email'])){
$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$sqlSelect ="SELECT * FROM users WHERE email=?";
if($stmt = $mysqli->prepare($sqlSelect)){
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
  if(!empty($row["user_id"])){
      if($row["confirmed"]==1){
        if(password_verify($password,$row["password"])){
            $_SESSION["userID"]=$row["user_id"];
            $_SESSION["name"]=$row["name"];
            $_SESSION["surname"]=$row["surname"];
            echo "success Είστε συνδεδεμένος ως ".$_SESSION["name"]." ".$_SESSION["surname"];
        }
        else{
            echo "Λάθος κωδικός";
        }
      }
      else{
        echo "confirm"; 
      }
  }
  else{
       echo "Λάθος διεύθυνση email";
  }
 }
}



?>