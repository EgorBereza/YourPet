<?php
//component gia to inteface tou login


if(!isset($_SESSION)) { 
    session_start(); 
}
$_SESSION["currentPage"]='login';

//edw elegxos an prokeite gia thn epivevewsh email i aplos login kai emfanisi katalilou alert
//an uparxoun t(token) kai e(email) get parematroi tote einai gia epivevewsh 
//(profanos sthn sunexeia upaarxei kai allos elegxos an kapoios aplos valei parametrous sto url kai patisei sto link tou alert pou tha vgei)
if(isset($_GET['t']) && isset($_GET['e']) ){
  echo'
  <div id = "alert-placeholderLogin">
  <div id="test" class="alert alert-primary show myalert">
        <a id="alert-confirm" href="#" >Για να επιβεβαιώσετε την διεύθυνση email σας πατήστε εδώ</a>     
  </div>
  </div>
  ';
}
else{
  echo'
   <div id = "alert-placeholderLogin">
   <div class="alert alert-info alert-dismissible fade show myalert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <a id="alert-reset" href="#" >Ξεχάσατε τον κωδικό πρόσβασης;</a>     
   </div> 
   </div>
   ';
}
  ?>
   <?php   require_once "spinner.php"; ?>
  <main  class="text-center main-sigin">
        <form class="form-signin">
        <i class="fas fa-paw fa-10x"></i>  
          <h1 class="h3 mb-3 font-weight-normal">Παρακαλώ συνδεθείτε</h1>
          <label for="inputEmail" class="sr-only">Email</label>
          <input type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
          <label for="inputPassword" class="sr-only">Κωδικός</label>
          <input type="password" id="inputPassword" class="form-control" placeholder="Κωδικός" required>
          <button id="buttonLogin" class="btn btn-lg btn-primary btn-block" type="submit">Σύνδεση</button>
         </form>
  </main>

