<?php
require_once "php/dbconnect.php";
if(!isset($_SESSION)) { 
    session_start(); 
} 

if (isset($_GET["t"]) && isset($_GET["e"])){
  $_SESSION["currentPage"]="login";
}

//an den uparxei get parametros tote anoigei home page
if(!isset($_SESSION["currentPage"]) ){
  $_SESSION["currentPage"]="home";
}

//an uparxei tote ginete elegxos an einai swstos kai ana einai anoigei katalili selida
if(isset($_GET["p"])){
  if($_GET["p"]=="home" || $_GET["p"]=="login" || $_GET["p"]=="register"){
    $_SESSION["currentPage"]=$_GET["p"];
  }
  else if($_GET["p"]=="create" ){
      if(isset($_SESSION["userID"])){
        $_SESSION["currentPage"]=$_GET["p"];
      }
      else{
        $_SESSION["currentPage"]="home";
      }
  }
  
  //gia na anoixei selida mias kartelas xreiazetai kai parametros c pou exei to card_id ths kartelas
  if($_GET["p"]=="card" && isset($_GET["c"])){
    $_SESSION["currentPage"]="card";
  }  
 
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Bereza Egor">
    <title>YourPet</title>
    <link rel='icon' href='favicon.ico' type='image/x-icon'/ >
    <link rel="apple-touch-icon-precomposed" href="photos/dog-152-203766.png">

        
  <!--  <script src="https://kit.fontawesome.com/78c24acd1b.js"></script>  na dokimasw auto se kanoniko server -->

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom css -->
    <link href="css/full.css" rel="stylesheet">

      <!-- css  gia 'font awsome icons' -->
    <link href="icons/css/all.min.css" rel="stylesheet">

  </head>

  <body class="bg-light">
     <!-- NAVIGATION-->
     <?php require_once "components/nav.php";?>

    <!-- div gia thn selida pou kalite-->
    <div id="mymain" >
     <?php require_once "components/".$_SESSION['currentPage'].".php";?>
    </div>


  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
  <script src="https://getbootstrap.com/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

 

  <script src="javaScript/ajaxChangePage.js?newversion"></script>
  <script src="javaScript/autoComplete.js?newversion"></script>
  <script src="javaScript/ajaxCalls.js?newversion"></script>

   </body>
 </html>