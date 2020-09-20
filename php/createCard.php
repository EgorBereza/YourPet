<?php
//dimiourgia kartelas (Insert stous pinakes cards,animals,contact_info)

//data:image/png;base64,
//data:application/pdf
//data:text/plain;base64


if(!isset($_SESSION)){ 
    session_start(); 
}


require_once "dbconnect.php";
//data sanitization kai kataliloi elegxoi egkurotitas
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
$phone = filter_var($_POST['phone'],FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
$race = filter_var($_POST['race'], FILTER_SANITIZE_STRING);

$adopt = filter_var($_POST['adopt'], FILTER_SANITIZE_STRING);
$found = filter_var($_POST['found'], FILTER_SANITIZE_STRING);
$searched = filter_var($_POST['searched'], FILTER_SANITIZE_STRING);

$type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
$size = filter_var($_POST['size'], FILTER_SANITIZE_STRING);
$age = filter_var($_POST['age'], FILTER_SANITIZE_STRING);
$gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);

$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

if(empty($name) || empty($surname ) || empty($email) || empty($phone) || empty($city) || empty($race) || 
   empty($type) || empty($size) || empty($age) || empty($gender) || !isset($adopt)   || !isset($found) || !isset($searched)){
   echo "Παρακαλώ συμπληρώστε όλα τα απαραίτητα πεδία";
}
else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
   echo "Δεν είναι έγκυρο το email";
}
else if( ((int)$adopt + (int)$found + (int)$searched) !=1 ){
   echo "Παρακαλώ επιλέξτε ένα από:Υιοθεσία,Βρέθηκε,Αναζητείται";
}
else if(empty($_POST['img1']) || empty($_POST['img2']) || empty($_POST['img3']) || $_POST['img1']=="photos/Spinner.gif"  || $_POST['img2']=="photos/Spinner.gif"  ||
     $_POST['img3']=="photos/Spinner.gif"){
   echo "Παρακαλώ επιλέξτε 3 φωτογραφίες";
}
else{
   $cardId=false;  //an uparxei card_id parametros einai epexergasia uparxousas kartelas
   if(isset($_POST['card_id'])){
   $cardId=filter_var($_POST['card_id'],FILTER_SANITIZE_STRING);
   }
   mysqli_autocommit($mysqli,FALSE);  //vgazw to autocommit gia na borw na kanw rollback an ginei lathos 
   $purpose=Getpurpose($adopt,$found);
    
   //an $cardId==false tote kanw create neas kartelas alliws epexergasia uparxousas
   //dimiourgia neas kartelas
   if($cardId==false){
    
   //katw 3 prepaied statments gia ta 3 insert sthn vash
   $animalInsert="INSERT INTO `animals` (`type`,`city`,`race`,`gender`,`size`,`age`) VALUES (?,?,?,?,?,?)"; 
   $contactInsert="INSERT INTO `contact_info` (`name`,`surname`,`phone`,`email`) VALUES (?,?,?,?)"; 
   $cardInsert="INSERT INTO `cards` (`user_id`,`animal_id`,`creation_date`,`purpose`,`contact_id`,`description`) VALUES (?,?,?,?,?,?)"; 

   if($stmt = $mysqli->prepare($animalInsert)){
      $stmt->bind_param("ssssss",$type,$city,$race,$gender,$size,$age);
      $stmt->execute();
      $animalID=$mysqli->insert_id;
      if($stmt = $mysqli->prepare($contactInsert)){
        $stmt->bind_param("ssss",$name,$surname,$phone,$email);
        $stmt->execute();
        $contactID=$mysqli->insert_id;
        if($stmt = $mysqli->prepare($cardInsert)){
           date_default_timezone_set('Europe/Athens');
           $date=date("Y/m/d H:i:s");
           $stmt->bind_param("iissis",$_SESSION["userID"],$animalID,$date,$purpose,$contactID,$comment);
           if($stmt->execute()){
            $cardId=$mysqli->insert_id;
            mkdir("../usersImages/".$cardId);
            //elegxos/anevasma fotografiwn
            $UploadOk=uploadImages(array($_POST['img1'],$_POST['img2'],$_POST['img3']),array(1,2,3),"../usersImages/".$cardId."/",$cardId);
            if($UploadOk){  
               mysqli_commit($mysqli);  //commit mono an ola pigan kala
               echo "success";
            }
            else{
               mysqli_rollback($mysqli);     //rollback an kati paei strava
                 rmdir("../usersImages/".$cardId);
                 echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"; 
            }
          
           }
           else{
            mysqli_rollback($mysqli);
            echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
           }
          
         }
         else{
            mysqli_rollback($mysqli);
            echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
         }
      }
   }

   }
   //Update gia epexergasia kartelas
   else{
      //me to select elegxo oti i kartela pros epexergasia ontos anikei ston sundemeno xristi kai pernw kai stoixeia pou xriazomai gia na kanw update
      $sqlSelect ="SELECT * FROM cards WHERE card_id=? AND user_id=?";
      if($stmt = $mysqli->prepare($sqlSelect)){
          $stmt->bind_param("ii",$cardId,$_SESSION["userID"]);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          if(!empty($row["card_id"])){

            $animalUpdate = "UPDATE animals SET type=?,city=?,race=?,gender=?,size=?,age=?  WHERE animal_id=?";
            $contactUpdate = "UPDATE contact_info SET name=?,surname=?,phone=?,email=? WHERE contact_id=?";
            $cardUpdate = "UPDATE cards SET creation_date=?,purpose=?,description=? WHERE card_id=?";

            if($stmt = $mysqli->prepare($animalUpdate)){
               $stmt->bind_param("ssssssi",$type,$city,$race,$gender,$size,$age,$row["animal_id"]);
               $stmt->execute();
               if($stmt = $mysqli->prepare($contactUpdate)){
                  $stmt->bind_param("ssssi",$name,$surname,$phone,$email,$row["contact_id"]);
                  $stmt->execute();
                  if($stmt = $mysqli->prepare($cardUpdate )){
                     date_default_timezone_set('Europe/Athens');
                     $date=date("Y/m/d H:i:s");
                     $stmt->bind_param("sssi",$date,$purpose,$comment,$row["card_id"]);
                     if($stmt->execute()){
                        //edw ginete elegxos/antikatastasi paliwn fotografiwn 
                        $UploadOk=CheckForOldImages($_POST['img1'],$_POST['img2'],$_POST['img3'],"../usersImages/".$cardId."/",$cardId);
                        if($UploadOk){
                           mysqli_commit($mysqli);
                           echo "success update";
                        }
                        else{
                           mysqli_rollback($mysqli);
                           echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
                        }
                     
                     }
                     else{
                        mysqli_rollback($mysqli);
                        echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
                     }
                  }
                  else{
                     mysqli_rollback($mysqli);
                     echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
                  }
               }
               else{
                  mysqli_rollback($mysqli);
                  echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
               }
            }
          }
         }
         else{
            echo "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα";  
         }
   }
   mysqli_close($mysqli);
}


function Getpurpose($adopt,$found){
   if($adopt) return "Υιοθεσία";
   else if($found) return "Βρέθηκε";
   else return "Αναζητείται";
}


function decodeImage($img){
   
 $type = findImageType($img);

 //prepei na ginei gia swsto base64 decoading
 $img = str_replace('data:image/'.$type.';base64,', '', $img);
 $img = str_replace(' ', '+', $img);
 $img=base64_decode($img);
 
 if($img!=false){
   $imgArray = array($img,$type);
   return $imgArray;
 }

//methedos gia elegxo an to arxeio pou epilextike einai ontos eikona
//kai oxi kapoio kakovoulo arxeio (gia paradeigma arxeio me kwdika)
//dimiourgw prosorino image apo auto
//meta to diagrafw
//an ola petixoun simainei einai kanoniki eikona se swsti morfi
//apofasisa na to vgalw gt kathisterei polu to ajax call me auto 
//kai imagecreatefromstring sthn 203 gia megales eikones dimiourgei memory leak gia kapoio logo (den vrhka giati)
/*
 if($img!=false){
   echo " 202 ".memory_get_usage();
   $imgTemp=imagecreatefromstring($img);
   if($imgTemp!=false){
    if($type=="jpeg"){
      imagejpeg($imgTemp, 'myimage.'.$type);
    }
    else{
      imagepng($imgTemp, 'myimage.'.$type);
    }
    
    if(unlink('myimage.'.$type)){
      $imgArray = array($img,$type);
      unset($imgTemp);  //apeleutherwsi apo thn minimi 
      return $imgArray;
    }
    else  return false;
   }
   else return false;
}
else return false;
*/
}

//anevazei thn eikona
 function uploadImage($img,$type,$name,$path){
  $filename=$path.$name.".".$type;
  file_put_contents($filename,$img);
}

//elegxos an einai eikona to arxeio
function isImage($img){
   if(substr($img,5,5)=="image") return true;
   else return false;
}


//vriskei tupo ths eikonas apo to base64 encoded string ths eikonas
function findImageType($img){
   $typeImage= explode("/",$img);
   return explode(";",$typeImage[1])[0];
}


//elegxei typo tis eikonas pou uparxei idi ston server
function checkImageType($path){
   if(file_exists($path.".jpeg")) return "jpeg";
   else if(file_exists($path.".png"))  return "png"; 
   else return false;
 }


//diagrafi paliwn fotografiwn
function deleteOldImages($newImagesPositions,$path){
       for($i=0;$i<sizeof($newImagesPositions);$i++){
         $type=checkImageType($path.$newImagesPositions[$i]);
         unlink($path.$newImagesPositions[$i].".".$type);
       }
}

//anevazei fotografies apo ton pinaka images me vash ta positions tous (1/2/3)
function uploadImages($images,$imagesPositions,$path){
   $decodedImages=array();
   for($i=0;$i<sizeof($images);$i++){
      if(!isImage($images[$i])) return false; //elegxos oti arxeio einai eikona
      else {
         array_push($decodedImages,decodeImage($images[$i]));
         $images[$i]=null; //apeleutherwsi ths eikonas apo thn minimi 
      }
   }
   unset($images);
   for($i=0;$i<sizeof($decodedImages);$i++){
        if($decodedImages[$i][0]==false)  return false;
   }
   for($i=0;$i<sizeof($decodedImages);$i++){
      uploadImage($decodedImages[$i][0],$decodedImages[$i][1],$imagesPositions[$i],$path);
      $decodedImages[$i]=null;  //apeleutherwsi ths eikonas apo thn minimi 
   }
   return true;
}

//elegxei poies eikones einai palies kai prepei na andikatastathoun me nees meta thn epexergasia kartelas
function CheckForOldImages($img1,$img2,$img3,$path,$cardId){
   $images = array($img1,$img2,$img3); 
   if(is_dir("../usersImages/".$cardId)){  //an uparxei fakelos sumenei oti uparxoun palies fotografies
      $newImages = array();
      $newImagesPositions = array();
      for($i=0;$i<sizeof($images);$i++){
             //an i fotografia den uparxei ston server sumenei oti einai nea alliws einai palia
             if(substr($images[$i],0,47) != 'https://nireas.it.teithe.gr/yourpet/usersImages'){ 
               array_push($newImages,$images[$i]);  //vazw se allo array nees fotografies kai tis theseis tous (1/2/3)
               array_push($newImagesPositions,($i+1));
             }
      }
      deleteOldImages($newImagesPositions,$path);   //diagrafw palies foto apo tis theseis stis opies tha boun nees 
      if(uploadImages($newImages,$newImagesPositions,$path)) return true;  //kalw uploadImages mono gia tis nees foto
      else return false;
      
   }
   else{
      mkdir("../usersImages/".$cardId);
      if(uploadImages($images,array(1,2,3),$path)) return true;
      else return false;
   }
   
}




?>