<?php

include_once("connect.php");

$params = json_decode(file_get_contents("php://input"), true);
$action = $params["action"];


switch($action) 
{
    case "ajouter":

       $nomPharma = $params["nomPharmacie"];
        $date = date('yy-m-d h:i:s');
        ajouterGarde($pdo,$nomPharma,$date);

    break;
}


function ajouterGarde($pdo, $nomPharma, $date) {
    $stmt = $pdo->prepare("SELECT id_pharmacie FROM pharmacie where nom_pharmacie like :nNomPharma");
       $stmt->execute(['nNomPharma' => $nomPharma]);
       $id = 0;

       if ($row = $stmt->fetch()) {
           $id = $row["id_pharmacie"]; 
       }    
       echo $id;

       if(count($stmt->fetch())>0)
       {
           $sql = $pdo->prepare("INSERT INTO garde ( date ,id_pharmacie) VALUES (:ndate , :nidpharma)");
           $sql->execute(['ndate' => $date, 'nidpharma' => $id]);
        }
         $response = new stdClass();
        if($id != 0) {
            $response->success=true;
            $response->date=$date;
            $response->id=$id;
        } else{
            $response->success=false;
            $response->message="erreur";
        } 
        $response = json_encode($response);
        echo $response;   
}  

?>

