<?php
    // include connect php
    include_once("connect.php");
    $params = json_decode(file_get_contents("php://input"), true);

    $sql = "SELECT p.nom_pharmacie,p.numTel,p.site,a.adresse,g.date, p.image FROM pharmacie p,adresse a,garde g WHERE a.id_adresse=p.id_adresse AND p.id_pharmacie=g.id_pharmacie" ;
  
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $array=array();

    while ($row = $stmt->fetch()) {
      
       array_push($array,$row);
      
    } 
    $response = new stdClass();
    if(count($stmt->fetch()) > 0) {
        $response->success=true;
        $response = json_encode($array); 

    }
    else{
            
        $response->success=false;
        $response->message="User not exist";
    }
/*foreach($array as &$item){
    $item["image"]=base64_encode($item["image"]);
}*/
    $response = json_encode($array);
    echo $response;

?>