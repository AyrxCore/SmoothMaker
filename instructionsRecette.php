<?php

include "bdd.php";

$id = $_GET["id"];

$requete = $bdd->prepare("
     SELECT * 
     FROM recipe
     WHERE id= :id
     ");

$requete->execute([
    "id" => $id
]);
$recipe = $requete->fetch();

$user = new UserSession;

if($user->isAuthenticated()) $idUser = $user->getUserId();

if($_POST)
{
    if($user->isAuthenticated()) 
    {
        $id = $_POST["id"];
        $etat = $_POST["action"];

        if($etat == "add")
        {
            $requete = $bdd->prepare("
            INSERT INTO favorite (idUser, idRecipe)
            VALUES ($idUser,$id)
            ");

            $result = $requete->execute();
        }
        else
        {
            $requete = $bdd->prepare("
            DELETE FROM favorite 
            WHERE idRecipe= :idRecipe AND idUser= :idUser   
            ");

            $result = $requete->execute([
                "idRecipe" => $id, 
                "idUser" => $idUser
            ]);
        }
        echo json_encode(["result" => $result, "id" => $id, "etat" => $etat]);
    }
    else 
    {
        echo json_encode(["result" => false, "redirect" => true]);
    }
}
else
{
    if ($user->isAuthenticated())
    {
        $requete = $bdd->prepare("
        SELECT idRecipe
        FROM favorite
        WHERE idUser =:idUser AND idRecipe = :idRecipe
        ");
        $requete->execute([
            "idUser" => $idUser,
            "idRecipe" => $id
            ]);

        $verif = $requete->fetch();

        if($verif == false)
        {
            $message = "<a class='fav' src='img/icons/emptyheart.png' data-fav='". $id ."'><i class='far fa-heart mr-3'></i> Ajouter cette recette à vos favoris</a>";
        }
        else
        {
            $message = "<a class='fav' src='img/icons/fullheart.png' data-fav='". $id ."'><i class='fas fa-heart mr-3'></i> Retirer cette recette de vos favoris</a>";
        }
    }
    else
    {
        $requete = $bdd->prepare("
        SELECT
        *,
        'false' AS InFavorite
        FROM recipe
        ");
        $requete->execute();
    }
    $myRecipe = $requete->fetch();

    $page = "instructionsRecette";
    include "layout.phtml";
}
