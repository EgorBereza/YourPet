<?php
//component gia inteface thns dimiourgias/epexergasias kartelas
if(!isset($_SESSION)){ 
    session_start(); 
}


//elegxos ama einai sundemenos o xristis an oxi tote allagh selidas se login
if(!isset($_SESSION["userID"])){
  $_SESSION["currentPage"]='login';
}
else{
  $_SESSION["currentPage"]='create';
}

//auta xreiazontai gia otan i selida kalite gia thn epexergasia mias kartelas kai prepei kapoia pedia na einai sumbliromena apo thn vash
  $imageDisplayed='notDisplayed';
  $placeholaderDisplayed='Displayed';
  $name="";
  $surname="";
  $phone="";
  $email="";
  $desc="";
  $images = array('photos/Spinner.gif','photos/Spinner.gif','photos/Spinner.gif');
  

//edw thelei elegxo gia 2 diaforetika path gt otan kaleite apo ajax vriskete se fakelo components 
//enw otan ginei require sto index vriskete pleon exw apo fakelo components (ekei pou einai to index)
//i methodos elegxei an i fotografia einai jpeg i png (i allos tupos ama prosthesw kai alla)
function checkImageType($path){
  if(file_exists("../".$path."jpeg") || file_exists($path."jpeg") ) return "jpeg";
  //else if(file_exists(path."png")) return "png";  ama prosthesw kai alla image types
  else return "png";
}
$pageEdit=false;
//elegxos an uparxei get parameter c pou exei card_id.An uparxei tote i selida kalite gia epexergasia ths kartelas alliws gia dimiourgia mias neas
if(isset($_GET['c'])){
  if(file_exists("../php/dbconnect.php")){
    require_once "../php/dbconnect.php";
  }  
  $pageEdit=true;
  $cardId = filter_var($_GET['c'], FILTER_SANITIZE_STRING);
  //to select edw einai kai gia elegxo oti i kartela pros epexergasia anikei ontos ston sundemeno xristi
  $sqlSelect ="SELECT * FROM cards  JOIN contact_info ON cards.contact_id = contact_info.contact_id WHERE card_id=? AND user_id=?";
  if($stmt = $mysqli->prepare($sqlSelect)){
    $stmt->bind_param("ii",$cardId,$_SESSION["userID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    //an i grammi einai adeia tote i kartela auth den anikei ston xristi opote tha emfanistei to interface gia thn dimiourgia neas
    if(!empty($row["card_id"])){
      //afou perasei o elegxos tote pernoun times oi katw metavlites pou tha sumblirwsoun tis formes sto create me plirofories tis kartelas pou tha epexergastei
      
          $imageDisplayed='Displayed';
          $placeholaderDisplayed='notDisplayed';
          $name=$row["name"];
          $surname=$row["surname"];
          $phone=$row["phone"];
          $email=$row["email"];
          $desc=$row["description"];
          
          $images = array("usersImages/".$cardId."/1.","usersImages/".$cardId."/2.","usersImages/".$cardId."/3.");
          for($i=0;$i<sizeof($images);$i++){
            $images[$i]=$images[$i].checkImageType($images[$i]);
          }
    }
  }
}


//
//katw to html kommati ths selida me php tags opou xreizontai na boun oi plirofories apo vasi
?>

 <!-- Create-->
<main> 
<?php   require_once "spinner.php"; ?>
    <div id="alert-placeholderCreate"></div>
       <div class="form-info p-2 mb-3 "> 
          <form id='searchForm'>
           
            <div class="row">
                <div class="col">
                    <div class="row  mb-4 mt-3">
    
                        <div class="col-xl-1 col-lg-1  col-sm-2">Πόλη:</div>
                        <div class="col-lg-4 col-sm-9 "> 
                            <div class="autoComplete">
                                <label for="city" class="sr-only">Πόλη</label>
                                <input type="text"  id="city" class="mb-2 form-control autoInput" placeholder="Πόλη"  autocomplete="off" required autofocus>
                                <div class="lista" id='cityLista'> </div>
                            </div>
                         </div>
    
                        <div class="col-xl-1 col-lg-1  col-sm-2"><label for="type">Ζώο:</label></div>
                        <div class="col-lg-2 col-sm-9 mb-2"> <select id="type" class="custom-select d-block w-100 " >
                                <option value="Σκύλος">Σκύλος</option>
                                <option value="Γάτα">Γάτα</option>
                                                        </select>
                         </div>
    
                       <div class="col-xl-1 col-lg-2 col-sm-2"><label for="size">Μέγεθος:</label></div>
                       <div class="col-lg-2 col-sm-9 "> <select id="size"  class="custom-select d-block w-100 " >
                                <option value="Μικρό">Μικρό</option>
                                <option value="Μεσαίο">Μεσαίο</option>
                                <option value="Μεγάλο">Μεγάλο</option>
                                                       </select>
                         </div>
                      </div>
                  </div>
                </div>
    
    
                <div class="row">
                    <div class="col">
                       <div class="row  mb-3">
    
                       <div class="col-xl-1 col-lg-1  col-sm-2">Ράτσα:</div>
                       <div class="col-lg-4  col-sm-9">
                          <div class="autoComplete">
                               <label for="race" class="sr-only">Ράτσα</label>
                               <input type="text"  id="race" class="mb-2 form-control autoInput" placeholder="Ράτσα"  autocomplete="off"  >
                               <div class="lista" id='raceLista'> </div>
                          </div>
                        </div>
    

                        <div class="col-xl-1 col-lg-1 col-sm-2"><label for="age">Ηλικία:</label></div>
                        <div class="col-lg-2 col-sm-9 mb-2"> <select  id="age" class="custom-select d-block w-100 " >  
                               <option value="0-2 μηνών">0-2 μηνών</option>
                               <option value="2-6 μηνών">2-6 μηνών</option>
                               <option value="6-11 μηνών">6-11 μηνών</option>
                               <option value="1 έτους">1 έτους</option>
                               <option value="2 έτων">2 έτων</option>
                               <option value="3 έτων">3 έτων</option>
                               <option value="4 έτων">4 έτων</option>
                               <option value="5 έτων">5 έτων</option>
                               <option value="6 έτων">6 έτων</option>
                               <option value="7 έτων">7 έτων</option> 
                               <option value="8 έτων">8 έτων</option>
                               <option value="9 έτων">9 έτων</option>
                               <option value="10 έτων">10 έτων</option>
                               <option value="11 έτων">11 έτων</option>
                               <option value="12 έτων">12 έτων</option>
                               <option value="13 έτων">13 έτων</option>
                               <option value="14 έτων">14 έτων</option>
                               <option value="15 έτων">15 έτων</option>
                               <option value="16+ έτων">16+ έτων</option>
                                                        </select>
                        </div>


                        <div class="col-xl-1 col-lg-2  col-sm-2"><label for="gender">Φύλο:</label></div>
                        <div class="col-lg-2 col-sm-9 "> <select id="gender" class="custom-select d-block w-100 " >
                               <option value="Αρσενικό">Αρσενικό</option>
                               <option value="Θηλυκό">Θηλυκό</option>
                                                        </select>
                         </div>
    
    
                    </div>
                </div>
              </div>
    
              <div class="row">
                <div class="col">
                   <div class="row  mb-3">
    
                      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"> <div class="custom-control custom-checkbox">
                                                               <input type="checkbox" class="custom-control-input" id="adopt">
                                                               <label class="custom-control-label" for="adopt">Υιοθεσία</label>
                                                               </div> 
                      </div>
    
                      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"> 
                          <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="found">
                          <label class="custom-control-label" for="found">Βρέθηκε</label>
                          </div> 
                      </div>
    
                      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"> 
                          <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="searched">
                          <label class="custom-control-label" for="searched">Αναζητείται</label>
                          </div> 
                      </div>
    
                    
                    </div>
                  </div>
                </div>

                <div class="mt-5"><h5>Στοιχεία Επικοινωνίας:</h5> </div>
                <div class="row mt-3 ">
                    <div class="col">
                        <div class="row  mb-1  ">
      
                            <div class=" col-xl-3 col-lg-4 col-md-5  col-12 mb-3">     <p class="p-0 ">Όνομα:</p></div>
                            <div class=" col-xl-3 col-lg-8 col-md-7   col-12 mb-3 ">   <input id="name" type="text" value="<?php echo $name ?>" class="form-control form-control-sm"></div>
          
                            <div class=" col-xl-1 col-lg-4 col-md-5  col-12 mb-3">     <p class="p-0">Επώνυμο:</p></div>
                            <div class=" col-xl-3 col-lg-8 col-md-7   col-12 mb-3 ">   <input id="surname" type="text" value="<?php echo $surname ?>" class="form-control form-control-sm"></div>
            
                            </div>
                        </div> 
                    </div>
  
  
  
                <div class="row ">
                    <div class="col">
                        <div class="row  mb-1  ">
      
                            <div class=" col-xl-3 col-lg-4 col-md-5   col-12 mb-3">    <p class="p-0">Τηλέφωνο Επικοινωνίας:</p></div>
                            <div class=" col-xl-3 col-lg-8 col-md-7   col-12 mb-3 ">   <input id="phone" type="text" value="<?php echo $phone ?>" class="form-control form-control-sm"></div>
          
                            <div class=" col-xl-1 col-lg-4 col-md-5  col-12 mb-3">      <p class=" p-0">Email:</span></p></div>
                            <div class=" col-xl-3 col-lg-8 col-md-7   col-12 mb-3 ">   <input id="email" type="text" value="<?php echo $email ?>" class="form-control form-control-sm"></div>
            
                            </div>
                        </div> 
                    </div>

                   

 
   

                    <div class="form-group mt-5">
                        <label for="comment" class="text-size">Επιπλέον Πληροφορίες:</label>
                        <textarea class="form-control mt-2" rows="5" id="comment"><?php echo $desc ?></textarea>
                      </div>

                   
                   <div class="mt-5"><h5>Φωτογραφίες:</h5> </div>
                      <div id="uploadPhotos" class="album py-5 bg-light">
                         <input id="fileChooser" type="file" accept='image/*'><br> 
                        <div class="container">
                          <div class="row">
                         
                          <div class="col-md-4">
                              <div class="card mb-4 shadow-sm ">
                             <svg id="placeholder1" class="bd-placeholder-img card-img-top  <?php echo $placeholaderDisplayed ?>" width="100%" height="225"  xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/></svg>                            
                             <img alt="Card image cap" id="img1" class="card-img-top img-fluid  <?php echo $imageDisplayed ?>" src="<?php echo $images[0] ?>" />  <!--  edw default andi gia tinda px-->
                                  <div class="d-flex justify-content-center ">
                                    <div class="btn-group btn-block mt-1">
                                      <button type="button" id="btn1" class="btn-uploadPhoto btn btn-bg btn-outline-secondary">Επιλέξτε φωτογραφία</button>
                                    </div>
                                  </div>
                               </div>
                          </div>

                          <div class="col-md-4">
                            <div class="card mb-4 shadow-sm ">
                              <svg  id="placeholder2" class="bd-placeholder-img card-img-top <?php echo $placeholaderDisplayed ?>" width="100%" height="225"  xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/></svg>
                              <img alt="Card image cap" id="img2" class="card-img-top img-fluid <?php echo $imageDisplayed ?>" src="<?php echo $images[1] ?>" /> 
                              <div class="d-flex justify-content-center ">
                                  <div class="btn-group btn-block mt-1">
                                    <button type="button" id="btn2" class="btn-uploadPhoto btn btn-bg btn-outline-secondary">Επιλέξτε φωτογραφία</button>
                                  </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">
                          <div class="card mb-4 shadow-sm ">
                            <svg  id="placeholder3" class="bd-placeholder-img card-img-top <?php echo $placeholaderDisplayed ?>" width="100%" height="225"  xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/></svg>
                            <img alt="Card image cap" id="img3" class="card-img-top img-fluid <?php echo $imageDisplayed ?>" src="<?php echo $images[2] ?>" /> 
                            <div class="d-flex justify-content-center ">
                                <div class="btn-group btn-block mt-1">
                                  <button type="button" id="btn3" class="btn-uploadPhoto btn btn-bg btn-outline-secondary">Επιλέξτε φωτογραφία</button>
                                </div>
                              </div>
                           </div>
                      </div>
   
                       </div>
                     </div>
                    </div>
                    <button id="btn-create" class="btn btn-lg btn-dark btn-block not-Pressed" type="submit"><?php if($pageEdit) echo"Αποθήκευση"; else echo"Δημιουργία";?></button>
              </form>
            
            </div>
</main>
