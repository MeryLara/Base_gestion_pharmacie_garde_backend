<?php
    // include connect php
    include_once("connect.php");
    $params = json_decode(file_get_contents("php://input"), true);
    $ville = $params["ville"];

     $stmt = $pdo->prepare("SELECT p.nom_pharmacie, concat (a.adresse ,', ', a.codePostal) as adresse FROM adresse a,pharmacie p WHERE a.id_adresse=p.id_adresse AND a.ville=:nville");
    $stmt->execute(['nville' => $ville]);

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
        $response->message="ville n exist pas";
    } 
    $response = json_encode($array);
    echo $response;

?>