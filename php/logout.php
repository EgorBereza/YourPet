<?php
if(!isset($_SESSION)) { 
    session_start(); 
}

$_SESSION["userID"]=null;
$_SESSION["name"]=null;
$_SESSION["surname"]=null;
echo "success";

?>