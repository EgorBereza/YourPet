<?php
if(!isset($_SESSION)){ 
    session_start(); 
}

if(isset($_SESSION["userID"])){
    echo "1";
    
}
else{
    echo "0";
}
?>