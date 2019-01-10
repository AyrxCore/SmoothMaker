<?php

include "bdd.php";

$user = new UserSession;
if($user->isAuthenticated())
{
        $idUser = $user->getUserId();

        $requete = $bdd->prepare("
                SELECT recipeName, imgSrcRecipe, idRecipe
                FROM recipe INNER JOIN favorite
                ON recipe.id = favorite.idRecipe
                WHERE idUser=?
                ");

        $requete->execute([$idUser]);
        $myRecipes = $requete->fetchAll();

        $page = "recettesFav";
        include "layout.phtml";
} 
else 
{
        header("Location:connexion.php");
        exit();
}
