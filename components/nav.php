<?php
if(!isset($_SESSION)) { 
    session_start(); 
}
//component gia to inteface ths navigation

//oi metavlites einai gia thn emfanisi/apokripsi epilogwn apo to nav analoga an einai sundemenos o xristis i oxi
$displayLogout="notDisplayed";
$displayMyCards="notDisplayed";
$displayLogin="";
$displayRegister="";
$displayName=false;

if(isset($_SESSION["userID"])){ 
$displayLogout="";
$displayMyCards="";
$displayLogin="notDisplayed";
$displayRegister="notDisplayed";
$displayName=true;
}

?>


<header>
            <div class="collapse bg-dark" id="navbarHeader">
              <div class="container">
                <div class="row">
                  <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">Your Pet</h4>
                    <p class="text-muted" id="nav-msg" ><?php if($displayName) echo "Είστε συνδεδεμένος ως ".$_SESSION["name"]." ".$_SESSION["surname"]; ?></p>
                  </div>
                  <div id="nav-menu" class="col-sm-4 offset-md-1 py-4">
                    <ul class="list-unstyled">
                      <li><a id="nav-home" href="#" class="text-white nav-option">Αρχική</a></li>
                      <li><a id="nav-login" href="#" class="text-white nav-option <?php echo $displayLogin ?>">Σύνδεση</a></li>
                      <li><a id="nav-register" href="#" class="text-white nav-option <?php echo $displayRegister ?>">Εγγραφή</a></li>
                      <li><a id="nav-create" href="#" class="text-white nav-option">Δημιουργία Καρτελάς</a></li>
                      <li><a id="nav-myCards" href="#" class="text-white nav-option <?php echo $displayMyCards?>">Οι Καρτέλες Μου</a></li>
                      <li><a id="nav-logout" href="#" class="text-white nav-option <?php echo $displayLogout ?> ">Αποσύνδεση</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="navbar navbar-dark bg-dark shadow-sm">
              <div class="container d-flex justify-content-between">
                <div class="navbar-brand d-flex align-items-center">
                  <strong>YourPet</strong>
                  <i class="fas fa-dog mx-2"></i> 
                  <i class="fas fa-cat mx-1"></i> 
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
              </div>
            </div>
</header>