<?php
if(!isset($_SESSION)){ 
    session_start(); 
}
//euresi kartelwn tou sindemenou xristi

if(isset($_SESSION["userID"])){
    require_once "dbconnect.php";
    $sqlSelect = "SELECT * FROM animals JOIN cards ON animals.animal_id = cards.animal_id WHERE cards.user_id=?";
    if($stmt = $mysqli->prepare($sqlSelect)){
        $stmt->bind_param("i", $_SESSION["userID"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $jasonarray = array();
        $jasonarray = $result->fetch_all(MYSQLI_ASSOC);
        
        //Prosthetw to imageType (jpeg/png) gia thn eikona ths kartelas sthn emfanisi apotelesmatwn 
        for($i=0;$i<sizeof($jasonarray);$i++){
           $jasonarray[$i]['imageType']=checkImageType("usersImages/". $jasonarray[$i]['card_id']."/1.");
        }
        print json_encode($jasonarray);
    }
    else{
        print json_encode(array('error' => "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"));
    }
}
else{
    print json_encode(array('error' => "Παρακαλώ συνδεθείτε..."));
}


function checkImageType($path){
    if(file_exists("../".$path."jpeg") ) return "jpeg";
    //else if(file_exists(path."png")) return "png";  ama prosthesw kai alla image types
    else return "png";
  }



?>