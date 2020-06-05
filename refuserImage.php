<?php

    include_once("connect.php");
    $params = json_decode(file_get_contents("php://input"), true);
    $id = $params["id_image"];
    $sql=$pdo->prepare("DELETE FROM imagecapture WHERE id_image = :nid ; ");
    $sql->execute(['nid' => $id]);
    

    $response = new stdClass();
    if(count($sql->fetch()) > 0) {
        $response->success=true;
       
    }
    else{
            
        $response->success=false;
       
    }
/*foreach($array as &$item){
    $item["image"]=base64_encode($item["image"]);
}*/
    $response = json_encode($response);
    echo $response;



?>    