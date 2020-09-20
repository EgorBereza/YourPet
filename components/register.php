<?php
if(!isset($_SESSION)){ 
    session_start(); 
}
//component gia to inteface ths eggrafis
$_SESSION["currentPage"]='register';
?>

<!-- register-->
<div id = "alert-placeholderRegister">
 <div  class="alert alert-info alert-dismissible fade show myalert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <a id="alert-register" href="#" >Έχετε ήδη λογαριασμό;</a>     
 </div>
 </div>
 <div id="alert-placeholderRegister2"></div>
 <?php   require_once "spinner.php"; ?>
 <main class="text-center">
            <form class="form-signin">
              <i class="fas fa-paw fa-10x"></i>  
              <h1 class="h3 mb-3 font-weight-normal">Παρακαλώ εισάγετε τα στοιχεία σας</h1>
        
              <label for="inputName" class="sr-only">Name</label>
              <input type="text" id="inputName" class="form-control" placeholder="Όνομα" required autofocus>
             
              <label for="inputSurname" class="sr-only">Surname</label>
              <input type="text" id="inputSurname" class="form-control" placeholder="Επώνυμο" required>
        
              <label for="inputEmail" class="sr-only">Email address</label>
              <input type="email" id="inputEmail" class="form-control" placeholder="Email" required >
            
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" class="form-control mb-0" placeholder="Κωδικός" required>
              
              <label for="confirmPassword" class="sr-only ">Confirm Password</label>
              <input type="password" id="confirmPassword" class="form-control" placeholder="Επιβεβαίωση Κωδικού" required>
        
              <button id="register-submit" class="not-Pressed btn btn-lg btn-primary btn-block" type="submit">Εγγραφή</button> 
            </form>
  </main>