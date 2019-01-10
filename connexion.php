<?php

include "bdd.php";

if($_POST)
{
    $email = htmlspecialchars($_POST["email"]);
    $mdp = htmlspecialchars($_POST['mdp']);

    $requete = $bdd->prepare("
        SELECT password 
        FROM user 
        WHERE email= :email
        ");

    $requete->execute([
        "email" => $email
    ]);
    $verifMdp = $requete->fetch();
    
    if(password_verify($mdp, $verifMdp["password"]))
    {
        $requete = $bdd->prepare("
            SELECT id, firstName, lastName, email, password 
            FROM user 
            WHERE email= :email AND password= :password
            ");

        $requete->execute([
            "email" => $email, 
            "password" => $verifMdp["password"]
        ]);
        $user = $requete->fetch();

        if(empty($user))
        {
            $result = false;
        }
        else
        {
            $result = true;
            $newSession = new UserSession();
            $newSession->create(
                $user["id"],
                $user["firstName"],
                $user["lastName"],
                $user["email"]
            );
        }
        echo json_encode(["result" => $result]);
    }
    else
    {
        $result = false;
        echo json_encode(["result" => $result]);
    }
}
else
{
    $page = "connexion";
    include "layout.phtml";
}
