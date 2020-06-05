<?php

// include connect php
include_once("connect.php");
$params = json_decode(file_get_contents("php://input"), true);
$action = $params["action"];

switch($action) 
{
    case "register":
        $nom = $params["nom"];
        $prenom = $params["prenom"];
        $email = $params["email"];
        $username = $params["username"];
        $password = $params["password"];
        $type = $params["type"];
        $numTel = $params["numTel"];
      
      
        $resp=verifier($pdo,$username,$email);
        if($resp!=null){
            $resp = json_encode($resp);
            echo $resp;
        }
        else{
            register($pdo,$username, $password, $nom, $prenom, $email ,$type ,$numTel);
        }

   
    break;

    case "login":
        $username = $params["username"];
        $password = $params["password"];
        login($pdo, $username, $password);
        
    break;
}

function verifier($pdo,$username,$email){
    
    
    $stmt=$pdo->prepare("SELECT p.id FROM personne p, authentification a where a.id=p.id_auth and a.username=:nuser and p.email=:nemail");
    $stmt->execute(['nuser' => $username, 'nemail' =>$email]);

    if(count($stmt->fetchAll()) > 0){
        $resp = new stdClass();
        $resp->success=false;
        $resp->message="User exist dejat";

        return $resp;
    }
  return null;

}

function login($pdo, $username, $password) {

     $stmt = $pdo->prepare("SELECT p.id, p.nom,p.prenom, p.email, a.username, p.Tel FROM personne p, authentification a where a.id=p.id_auth and a.username=:usern and a.password=:passwd");
        $stmt->execute(['usern' => $username, 'passwd' => $password]);
        
        $id = 0;
        $nom = "";
        $prenom = "";
        $email = "";
        $numTel = "";
        $username = "";

        if ($row = $stmt->fetch()) {
            $id = $row["id"]; 
            $nom = $row["nom"];
            $prenom = $row["prenom"];
            $email = $row["email"];
            $numTel =  $row["Tel"];
            $username = $row["username"];
        } 
        $is_admin = false;
        $is_pharmacien = false;
        $is_simple_user = false;
   $type="";

        if($id != 0) {
            // rechercher dans la table Admin
            $stmt = $pdo->prepare("SELECT * FROM administrateur a where a.id_personne=:id_pers");
            $stmt->execute(['id_pers' => $id]);
            if ($row = $stmt->fetch()) {
                  $is_admin = true;
            }
            // rechercher dans la table ph
           if(!$is_admin) {
                $stmt = $pdo->prepare("SELECT * FROM pharmacien a where a.id_personne=:id_pers");
            $stmt->execute(['id_pers' => $id]);
            if ($row = $stmt->fetch()) {
                   $is_pharmacien = true; 
            }
             }   
            
            // rechercher dans la table user



            if(!$is_pharmacien) {
                $stmt = $pdo->prepare("SELECT * FROM simpleuser a where a.id_personne=:id_pers");
                $stmt->execute(['id_pers' => $id]);
                if ($row = $stmt->fetch()) {
                    $is_simple_user = true;
                }
            }
        }
      
        $response = new stdClass();
        if($id != 0) {
            $response->success=true;
            $response->us=$username;
            $response->nom=$nom;
            $response->prenom=$prenom;
            $response->email=$email;
            $response->tel=$numTel;
                  

            $response->type=($is_admin ? "admin" : ($is_pharmacien ? "ph" : "user"));
        } else{
           
            $response->success=false;
            $response->message="User not exist";
        } 

        $response = json_encode($response);
        echo $response;
}


        
function register($pdo,$username, $password, $nom, $prenom, $email ,$type ,$numTel ){
     $stmt = $pdo->prepare("INSERT INTO authentification ( username , password) VALUES (:usern , :passwd)");
    $stmt->execute(['usern' => $username, 'passwd' => $password]);

    $id=$pdo->lastInsertId() ;
  
  
    if($id != 0){
        $stmt = $pdo->prepare("INSERT INTO personne (  nom , prenom, email, Tel , id_auth ) VALUES (:nnom , :nprenom  ,:nemail,:ntel , :nid)");
        $stmt->execute(['nnom' => $nom,'nprenom' => $prenom , 'nemail' => $email , 'ntel' => $numTel,'nid' =>$id]);
        
        $idperson=$pdo->lastInsertId() ;


        if($type == "admin"){
            $stmt = $pdo->prepare("INSERT INTO administrateur ( id_personne ) VALUES (:person )");
            $stmt->execute(['person' => $idperson]);
        }
        else if($type == "Pharmacien"){
            $stmt = $pdo->prepare("INSERT INTO pharmacien ( id_personne ) VALUES (:person )");
            $stmt->execute(['person' => $idperson]);
         
        }
        else if($type == "simpleUser"){
            $stmt = $pdo->prepare("INSERT INTO simpleuser ( id_personne ) VALUES (:person )");
            $stmt->execute(['person' => $idperson]);
        }
        else{
            echo "erreur il faut choisir un user";
        }

    }

    $response = new stdClass();
        if($id != 0) {
            $response->success=true;
            $response->us=$username;
            $response->nom=$nom;
            $response->prenom=$prenom;
            $response->email=$email;
            $response->numtel=$numTel;
            $response->type=$type;

        } else{
           
            $response->success=false;
            $response->message="User not exist";
        } 

        $response = json_encode($response);
        echo $response;

}
