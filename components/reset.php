<?php
//component gia to inteface ths allagis tou kwdikou
if(!isset($_SESSION)){ 
    session_start(); 
}
$_SESSION["currentPage"]='reset';
?>

 <!-- reset-->
 <main class="text-center">
   <div id="alert-placeholderReset"></div>
    <form class="form-signin">
      <i class="fas fa-paw fa-10x"></i>  
      <h1 class="h3 mb-3 font-weight-normal">Παρακαλώ επιλέξτε νέο κωδικό</h1>

      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
      
      <label for="inputNewpassword" class="sr-only">Password</label>
      <input type="password" id="inputNewpassword" class="form-control" placeholder="Νέος Κωδικός" required>
    
      <button id="btn-reset" class="btn btn-lg btn-primary btn-block" type="submit">Επαναφορά</button>
     
    </form>
    </main>