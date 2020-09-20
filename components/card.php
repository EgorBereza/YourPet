<?php
//component pou emfanizei mia sugkekrimenh kartela zwou 
if(!isset($_SESSION)) { 
    session_start(); 
}
$_SESSION["currentPage"]='card';

if(file_exists("../php/dbconnect.php")){
  require_once "../php/dbconnect.php";
}


//edw thelei elegxo gia 2 diaforetika path gt otan kaleite apo ajax vriskete se fakelo components 
//enw otan ginei require sto index vriskete pleon exw apo fakelo components (ekei pou einai to index)
//i methodos elegxei an i fotografia einai jpeg i png (i allos tupos ama prosthesw kai alla)
function checkImageType($path){
  if(file_exists("../".$path."jpeg") || file_exists($path."jpeg") ) return "jpeg";
  //else if(file_exists(path."png")) return "png";  ama prosthesw kai alla image types
  else return "png";
}


//elegxos ama uparxei get parameter 'c' pou exei mesa card_id ths kartelas pou tha emfanistei
//an exei tote ginete ena select meso prepaired statment sthn vash gia ths plirofories pou xreiazontai
if(isset($_GET['c'])){
  $cardId = filter_var($_GET['c'], FILTER_SANITIZE_STRING);
  $sqlSelect ="SELECT * FROM cards JOIN animals ON cards.animal_id = animals.animal_id JOIN contact_info ON" + 
              " cards.contact_id = contact_info.contact_id WHERE card_id=?";
  if($stmt = $mysqli->prepare($sqlSelect)){
   $stmt->bind_param("i", $cardId);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();
    if(!empty($row["card_id"])){  
      //array me tis 3 fotografies ths kartelas kai meta elegxos ti tupou einai (jpeg/png)
      $images = array("usersImages/".$cardId."/1.","usersImages/".$cardId."/2.","usersImages/".$cardId."/3.");
      for($i=0;$i<sizeof($images);$i++){
        $images[$i]=$images[$i].checkImageType($images[$i]);
      }
      //elegxos ama einai kartela tou xristi gia na emfanisw ta koumbia diagrafi/epexergasia
      $usersCard=false;
      if(isset($_SESSION["userID"])){
         if($row["user_id"]==$_SESSION["userID"]){
            $usersCard=true;
         }
      }

//<div class="row mb-4">
      //echo katalilo html mazi me plirofories apo select/images
      echo'
      <main >
        <div id="myCarousel" class="carousel slide" data-ride="carousel ">
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>

      <div class="carousel-inner ">
          <div class="carousel-item active">
           <img src="'.$images[0].'" alt="photos/default.jpg" class="carousel-image">
          </div>

          <div class="carousel-item">
           <img src="'.$images[1].'" alt="photos/default.jpg" class="carousel-image">
          </div>

         <div class="carousel-item">
          <img src="'.$images[2].'" alt="photos/default.jpg" class="carousel-image">
         </div>
      </div>

          <a class="carousel-control-prev " href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>

          <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon " aria-hidden="true"></span>
            <span class="sr-only ">Next</span>
          </a>
        </div>
        <div id="alert-placeholderCard"></div>
        <div class="info-card mt-5 p-2 mb-4">
';

echo'

  <div id="card-btns" class=" row mb-2 btn-group btn-block mt-1"> 
  <div class="col-12 col-md-6 col-lg-3 mb-3  ">
  <button type="button" id="btn-copy-card" class=" btn btn-sm btn-dark btn-block">Αντιγραφή συνδέσμου</button>
  </div>
  
  
  ';
 
if($usersCard){
  echo'
     
       <div class="col-md-3 col-lg-3  mb-3">
        <button type="button" id="btn-delete-card" class="btn btn-sm btn-dark btn-block">διαγραφή</button>
        </div>
        <div class="col-md-3 col-lg-3  mb-3">
        <button type="button" id="btn-edit-card" class="btn btn-sm btn-dark btn-block">επεξεργασία</button>
       </div>
     
  ';
}

echo'</div>';

 echo'
  <div class="mb-2"><h5>Στοιχεία Αγγελίας</h5> </div>
  <div class="mb-5 grey-border">
        <div class="row ">
          <div class="col">
              <div class="row mb-2">

                  <div class="col-xl-5  col-12 mb-3"> <p class="word-br ml-2">Πόλη:'.$row["city"].'</p></div>
                
                  <div class="col-xl-4  col-12 mb-3"> <p class="word-br ml-2">Ζώο:'.$row["type"].'</p></div>
                
                  <div class="col-xl-2  col-12 mb-3"> <p class="word-br ml-2">Μέγεθος:'.$row["size"].'</p></div>

                  </div>
              </div> 
          </div>

          <div class="row">
            <div class="col">
                <div class="row mb-2">
              
                    <div class="col-xl-5  col-12 mb-3"> <p class="word-br ml-2">Ράτσα:'.$row["race"].'</p></div>
                   
                    <div class="col-xl-4   col-12 mb-3"> <p class="word-br ml-2">Ηλικία:'.$row["age"].'</p></div>

                    <div class=" col-xl-2  col-12 mb-3"> <p class="word-br ml-2">Φύλο:'.$row["gender"].'</p></div>
                  
                    </div>
                </div> 
            </div>

            <div class="row ">
              <div class="col">
                  <div class="row mb-2  ">

                      <div class="col-12 mb-3">  <p class="word-br ml-2">Κατάσταση:'.$row["purpose"].'</p></div>

                      </div>
                  </div> 
              </div>

          </div>

          <div class="mb-2"><h5>Στοιχεία Επικοινωνίας</h5> </div>
          <div class="mb-5 grey-border">
              <div class="row ">
                  <div class="col">
                      <div class="row  mb-1 justify-content-between ">
    
                          <div class=" col-xl-4  col-12 mb-3">  <p class="word-br ml-2">Όνομα:'.$row["name"].'</p></div>
        
                          <div class=" col-xl-7  col-12 mb-3">  <p class="word-br ml-2">Επώνυμο:'.$row["surname"].'</p></div>
          
                          </div>
                      </div> 
                  </div>

              <div class="row ">
                  <div class="col">
                      <div class="row  mb-1 justify-content-between ">
    
                          <div class=" col-xl-4   col-12 mb-3">  <p class="word-br ml-2 labels">Τηλέφωνο Επικοινωνίας:'.$row["phone"].'</p></div>
        
                          <div class=" col-xl-7  col-12 mb-3">  <p class="word-br ml-2">Email:'.$row["email"].'</p></div>
          
                          </div>
                      </div> 
                    </div>
          </div>

          <div class="mb-2"><h5>Επιπλέον πληροφορίες</h5> </div>
          <div class="word-br p-2 grey-border">
            <p>'.$row["description"].'</p>
          </div>
        </div>
</main>
';
  }
  else{
    echo "<div class='alert alert-danger alert-dismissible fade show myalert'>
           <button type='button' class='close' data-dismiss='alert'>&times;</button>
           Κάτι πήγε στραβά,πιθανόν η καρτέλα που ψάχνετε έχει διαγραφεί.Δοκιμάστε να κάνετε νέα αναζήτηση</div>";
 }
}
 else{
  echo "<div class='alert alert-danger alert-dismissible fade show myalert'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα</div>";
 }
}
else{
  echo "<div class='alert alert-danger alert-dismissible fade show myalert'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  Κάτι πήγε στραβά,πιθανόν η καρτέλα που ψάχνετε έχει διαγραφεί.Δοκιμάστε να κάνετε νέα αναζήτηση</div>";
}

?>
