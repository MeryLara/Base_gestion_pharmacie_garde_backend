<?php
    // include connect php
    include_once("connect.php");
    $params = json_decode(file_get_contents("php://input"), true);
    $sql = "SELECT a.ville FROM adresse a" ;
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
        $response->message="ville n exist pas";
    } 
    $response = json_encode($array);
    echo $response;

?>