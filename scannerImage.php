<?php
 include_once("connect.php");
 if($_SERVER['REQUEST_METHOD']=='POST'){
   $action = $_POST['action'];
   if($action == "scanner") {
      $ville = $_POST['ville'];
      $image = $_POST['image'];
   
      $stmt = $pdo->prepare("INSERT INTO imagecapture ( imageGarde , ville) VALUES (:imageGarde , :ville)");
      $stmt->execute(['imageGarde' => $image, 'ville' => $ville]);

      //verify if insterted
      // TODO

      $response = new stdClass();
      $response->success=true;

      $response = json_encode($response);
      echo $response;

   }


}
?>


