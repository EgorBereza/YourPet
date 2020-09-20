<?php
if(!isset($_SESSION)) { 
    session_start(); 
}
if(isset($_SESSION["userID"])){
    require_once "dbconnect.php";
    $card_id=filter_var($_POST['card_id'],FILTER_SANITIZE_STRING);
 
    $sqlSelect ="SELECT * FROM cards WHERE card_id=? AND user_id=?";  //gia elegxo oti i kartelas ontos anoikei ston xristi
    if($stmt = $mysqli->prepare($sqlSelect)){
        $stmt->bind_param("ii",$card_id,$_SESSION["userID"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if(!empty($row["card_id"])){
            mysqli_autocommit($mysqli,FALSE);
            if(deleteUsersCard($mysqli,$row["animal_id"],$row["contact_id"],$row["card_id"])){
                if(deltePhotos($card_id)){
                    mysqli_commit($mysqli);
                    echo "success";
                }
                else{
                    mysqli_rollback($mysqli);
                    echo "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"; 
                }    
            }
            else{
                    mysqli_rollback($mysqli);
                    echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"; 
            }
        }
        else{
                    echo "Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"; 
        }
    }
    else{
                    echo"Κάτι πήγε στραβά,δοκιμάστε ξανά αργότερα"; 
    }
  }
  else{
                   echo"Κάτι πήγε στραβά,βεβαιωθείτε ότι είστε συνδεδεμένος"; 
  }

//diagrafei tis grammes ths kartelas apo thn vash
function deleteUsersCard($mysqli,$animal_id,$contact_id,$card_id){
    $cardDeleted=deleteRow($mysqli,"DELETE FROM cards WHERE card_id=?",$card_id);
    $animalDeleted=deleteRow($mysqli,"DELETE FROM animals WHERE animal_id=?",$animal_id);
    $contactDeleted=deleteRow($mysqli,"DELETE FROM contact_info WHERE contact_id=?",$contact_id);
    if( $animalDeleted && $contactDeleted && $cardDeleted) return true;
    else return false;
}

//diagrafei mia grammh apo vash 
function deleteRow($mysqli,$sqlDelete,$id){
    if($stmt = $mysqli->prepare($sqlDelete)){
        $stmt->bind_param("i",$id);
        if($stmt->execute()) return true;
        else return false;
    }
}

//diagrafei foto kai fakelo ths kartelas
function deltePhotos($card_id){
    if(array_map('unlink', glob("../usersImages/".$card_id."/*.*")) && rmdir("../usersImages/".$card_id)) return true;
    else  return false;
}


?>
