<?php
    include_once("connect.php");
   
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $nom = $_POST["nom"];
        $tel = $_POST["tel"];
        $site = $_POST["site"];
        $adresse = $_POST["adresse"];
        $codePostal = $_POST['codePostal'];
        $ville = $_POST['ville'];
        $pays = $_POST['pays'];
        $image = $_POST['image'];

    $stmt = $pdo->prepare("INSERT INTO adresse ( adresse , codePostal,ville,pays) VALUES (:adresse , :cp ,:ville ,:pays)");
        $stmt->execute(['adresse' => $adresse, 'cp' => $codePostal,'ville' => $ville,'pays' => $pays]);
    
        $id=$pdo->lastInsertId() ;
        if($id != 0){
            $stmt = $pdo->prepare("INSERT INTO pharmacie ( nom_pharmacie , numTel, image, site , id_adresse ) VALUES (:nnom , :ntel  ,:nimage,:nsite , :nid)");
            $stmt->execute(['nnom' => $nom,'ntel' => $tel , 'nimage' => $image , 'nsite' => $site,'nid' =>$id]);
        }  
        else{
            echo "erreur ";
        }

        $response = new stdClass();
        if($id != 0) {
            $response->success=true;
            $response->nomPharma=$nom;
            $response->tel=$tel;
            $response->site=$site;
            $response->adress=$adresse;
            $response->cp=$codePostal;
            $response->ville=$ville;
            $response->pays=$pays;
            $response->image=$image;
        } else{
           
            $response->success=false;
            $response->message="User not exist";
        } 

        $response = json_encode($response);
        echo $response;
      
    }





?>