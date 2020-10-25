<?php

// Gia localhost

$user='yourpet';
$pass='yourpet2019';
$host='localhost';
$db = 'yourpet_db';


$mysqli = new mysqli($host, $user, $pass, $db);
mysqli_set_charset($mysqli,"utf8");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" .           
    $mysqli->connect_errno . ") " . $mysqli->connect_error;
    
 } 
 

/*
// Me socket gia users


$user='root';
$pass='*******';
$host="";
$db = 'yourpet_db';
$socket="/home/student/it/2015/it154506/mysql/run/mysql.sock";


$mysqli = new mysqli($host, $user, $pass, $db,null,$socket);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . 
    $mysqli->connect_errno . ") " . $mysqli->connect_error;
  
}
*/  


?>


