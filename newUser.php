<?php

include "bdd.php";

if(isset($_POST["creation"]))
{
    $mail = htmlspecialchars($_POST['email']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $mdp = password_hash(htmlspecialchars($_POST['mdp']), PASSWORD_BCRYPT);

    $requete = $bdd->prepare("
    INSERT INTO user 
    SET email=?, firstName=?, lastName=?, password=?
        ");
    $resultat = $requete->execute([$mail, $prenom, $nom, $mdp]);

    header('Location: nosRecettes.php');
    exit;
}

$page = "newUser";
include "layout.phtml";
