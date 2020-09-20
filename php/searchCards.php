<?php


if(!isset($_SESSION)){ 
    session_start(); 
}
require_once "dbconnect.php";

$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
$race = filter_var($_POST['race'], FILTER_SANITIZE_STRING);

$adopt = filter_var($_POST['adopt'], FILTER_SANITIZE_STRING);
$found = filter_var($_POST['found'], FILTER_SANITIZE_STRING);
$searched = filter_var($_POST['searched'], FILTER_SANITIZE_STRING);

$type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
$size = filter_var($_POST['size'], FILTER_SANITIZE_STRING);
$age = filter_var($_POST['age'], FILTER_SANITIZE_STRING);
$gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);



if(empty($type) || empty($size) || empty($age) || empty($gender) || !isset($adopt)   || !isset($found) || !isset($searched)){
    print json_encode(array('error' => "Παρακαλώ συμπληρώστε όλα τα απαραίτητα πεδία"));
}
else if( ((int)$adopt + (int)$found + (int)$searched) == 0 ){
    print json_encode(array('error' => "Παρακαλώ επιλέξτε τουλάχιστον ένα από:Υιοθεσία,Βρέθηκε,Αναζητείται"));
}
else{
   
   //array me plirofories gia select opou sto (1)Select querie ,(2)string me paremetrous gia prepaired statment ,(3)array me parametrous me swsti seira
    $selectInfo = array("sqlSelect"=>"SELECT * FROM animals JOIN cards ON animals.animal_id = cards.animal_id WHERE type=? AND ( ","paramsStr"=>"s","params" => array($type));
    $selectInfo = generateSelect($selectInfo,$city,$race,$size,$age,$gender,$adopt,$found,$searched); //dimiourgei dinamika katalilo select
    $paramsNumber= sizeof($selectInfo['params']);
  
if($stmt = $mysqli->prepare( $selectInfo['sqlSelect'])){
    //to switch kanei bind swstous parametrous kai to katalilo string sto prepaired statment
    //prepei na ginei ect gt to select dimiourgite dinamika (den xerw posous parametrous tha exei,oute poioi einai kai me ti seira)
    switch ($paramsNumber) {
        case 1:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0]);
            break;
        case 2:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1]);
            break;
        case 3:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2]);
            break;
        case 4:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3]);
            break;
        case 5:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3],$selectInfo['params'][4]);
            break;
        case 6:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3],$selectInfo['params'][4],
            $selectInfo['params'][5]); 
            break; 
        case 7:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3],$selectInfo['params'][4],
            $selectInfo['params'][5],$selectInfo['params'][6]); 
            break;
        case 8:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3],$selectInfo['params'][4],
            $selectInfo['params'][5],$selectInfo['params'][6],$selectInfo['params'][7]); 
            break;
        case 9:
            $stmt->bind_param($selectInfo['paramsStr'],$selectInfo['params'][0],$selectInfo['params'][1],$selectInfo['params'][2],$selectInfo['params'][3],$selectInfo['params'][4],
            $selectInfo['params'][5],$selectInfo['params'][6],$selectInfo['params'][7],$selectInfo['params'][8]); 
            break;

        default:
           print json_encode(array('error' => "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"));
    }
       
    $stmt->execute();

   
}  
   
  
    $result = $stmt->get_result();
    $jasonarray = array();
    $jasonarray = $result->fetch_all(MYSQLI_ASSOC);
    
    //Prosthetw sto result tou select kai to imageType (jpeg/png) gia thn eikona ths kartelas sthn emfanisi apotelesmatwn 
    for($i=0;$i<sizeof($jasonarray);$i++){
        $jasonarray[$i]['imageType']=checkImageType("usersImages/". $jasonarray[$i]['card_id']."/1.");
    }

    print json_encode($jasonarray);
    

   
 


}
//vriskei to tupo ths prwtis eikonas pou tha emfanizete sthn kartela
function checkImageType($path){
    if(file_exists("../".$path."jpeg") ) return "jpeg";
    //else if(file_exists(path."png")) return "png";  ama prosthesw kai alla image types
    else return "png";
  }
  



//methoos gia thn dimiourgia dinamika katalilou select querie string,string me parametrous kai parametrous pou xriazontai se swsti seira
function generateSelect($selectInfo,$city,$race,$size,$age,$gender,$adopt,$found,$searched){
//to i exei arithmo tou parametrou pou benei (xekinaei apo 1 gt sto 0 benei panda parametros 'type'(Gata/Skulos) anexartita apo to upoloipo select)
//me to i vriskw kai posa apo 'purpose'(Υιοθεσία,Βρέθηκε,Αναζητείται) tha xrisimopoithoun sthn select (einai apo 1 mexri 3) opote borei na xriazete OR an einai panw apo 1
    $i=1; 
    if($adopt==1){
        $selectInfo['sqlSelect'] .= "purpose=? ";

        $selectInfo['paramsStr'] .= "s";
        $adoptValue ="Υιοθεσία";
        $selectInfo['params'][$i]=$adoptValue;
        $i++;
    }
    if($found==1){
        if($i==1) $selectInfo['sqlSelect'] .= "purpose=? ";
        else      $selectInfo['sqlSelect'] .= " OR purpose=? ";  
        
        $selectInfo['paramsStr'] .= "s";
        $foundValue ="Βρέθηκε";
        $selectInfo['params'][$i]=$foundValue;
        $i++;
    }
    if($searched==1){
        if($i==1) $selectInfo['sqlSelect'] .= "purpose=? ";
        else      $selectInfo['sqlSelect'] .= " OR purpose=? ";  

        $selectInfo['paramsStr'] .= "s";
        $searchedValue ="Αναζητείται";
        $selectInfo['params'][$i]=$searchedValue;
        $i++;
    }
    $selectInfo['sqlSelect'] .=")";

    if($city != ""){
     $selectInfo['sqlSelect'] .= " AND city=? ";
     $selectInfo['paramsStr'] .= "s";
     $selectInfo['params'][$i]=$city;
     $i++;
    }
    if($race != ""){
     $selectInfo['sqlSelect'] .="AND race=? ";
     $selectInfo['paramsStr'] .= "s";
     $selectInfo['params'][$i]=$race;
     $i++;
    }
    if($size != "all"){
     $selectInfo['sqlSelect'] .= "AND size=? ";
     $selectInfo['paramsStr'] .= "s";
     $selectInfo['params'][$i]=$size;
     $i++;
    }
    if($age !="all"){
     $selectInfo['sqlSelect'] .= "AND age=? ";
     $selectInfo['paramsStr'] .= "s";
     $selectInfo['params'][$i]=$age;
     $i++;
    }
    if($gender !="all"){
     $selectInfo['sqlSelect'] .= "AND gender=? ";
     $selectInfo['paramsStr'] .= "s";
     $selectInfo['params'][$i]=$gender;
     $i++;
    }
    return $selectInfo;
}



?>