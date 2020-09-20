<?php
/*
if(!isset($_SESSION)){ 
    session_start(); 
}

 SELECT * FROM cards JOIN animals ON cards.animal_id = animals.animal_id 

 WHERE animals.type='Σκύλος' OR animals.type='Γάτα' 
 ORDER BY cards.card_id DESC LIMIT 5;


SELECT * FROM cards ORDER BY creation_date DESC
*/
require_once "dbconnect.php";
$limit = filter_var($_POST['limit'],FILTER_SANITIZE_NUMBER_INT);

$sqlSelect="SELECT * FROM cards JOIN animals  ON  cards.animal_id = animals.animal_id WHERE animals.type='Σκύλος' OR animals.type='Γάτα' ORDER BY cards.card_id DESC LIMIT ?";
if($stmt = $mysqli->prepare($sqlSelect)){
    $stmt->bind_param("i",$limit );
    $stmt->execute();

    $result = $stmt->get_result();
    $jasonarray = array();
    $jasonarray = $result->fetch_all(MYSQLI_ASSOC);

     //Prosthetw sto result tou select kai to imageType (jpeg/png) gia thn eikona ths kartelas sthn emfanisi apotelesmatwn 
     for($i=0;$i<sizeof($jasonarray);$i++){
        $jasonarray[$i]['imageType']=checkImageType("usersImages/". $jasonarray[$i]['card_id']."/1.");
    }
    print json_encode($jasonarray);
}
else{
    print json_encode(array('error' => "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"));
}


//vriskei to tupo ths prwtis eikonas pou tha emfanizete sthn kartela
function checkImageType($path){
    if(file_exists("../".$path."jpeg") ) return "jpeg";
    //else if(file_exists(path."png")) return "png";  ama prosthesw kai alla image types
    else return "png";
  }

?>