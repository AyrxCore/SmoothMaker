"use strict";

$(document).ready(function()
{
    $(".menuMobile").on("click", function()
    {
        $(".navMobile").toggle();
        $(".menuMobile i").toggleClass("fas fa-times");
        $(".menuMobile i").toggleClass("fas fa-bars");
    });
    $(".create").on('submit', saveUser);
    $(".connect").on('submit',verifInfo);
    $("#recettes .fav").on('click', clickFav);
    $("#recettesFav .fav").on('click', menuModifFav);
    $(".no").on("click", function()
    {
        $(this)["0"].parentElement.hidden = true;
    });
    $(".yes").on('click', suppFav);
    $(".sendEmail").on("click", send);
});

function send(e)
{
    e.preventDefault();

    $(".alert").remove();

    var email = $("#email").val();
    var message = $("#message").val();

    if(verifMail(email) == false || verifMail(email) == undefined)
    {
        e.preventDefault();
        $(".failed").html('<div class="alert alert-danger" role="alert">Format du mail incorrect</div>');
    }
    else
    {
        $.ajax(
            {
            url: 'sendMail.php',
            method: 'post',
            dataType: 'json',
            data: {email: email, message: message},
            success: function(data)
                {
                    console.log(data)
                    if(data.result == false)
                    {
                        $(".failed").html("<div class='alert alert-danger' role='alert'>Erreur lors de l'envoi du message ! Veuillez réessayer.</div>") 
                    }
                    else
                    {
                        $(".failed").html("<div class='alert alert-success' role='alert'>Votre message a bien été envoyé !</div>") 
                    }
                }
            });
    }
}

function verifInfo(e)
{
    e.preventDefault();

    $(".alert").remove();
    var email = $("#email").val();
    var mdp = $("#password").val();

    verifMail(email);

    if(email.length > 0 && mdp.length > 0)
    {
        var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
        if(regex.test(email) == false)
        {
            $(".failed").html('<div class="alert alert-danger" role="alert">Le format de votre adresse mail est incorrect !</div>')
        }
        else
        {
            $.ajax(
                {
                url: 'connexion.php',
                method: 'post',
                dataType: 'json',
                data: {email: email, mdp: mdp},
                success: function(data){
                    if(data.result == true)
                    {
                        window.location.href = "recettesFav.php";
                    }
                    else
                    {
                        $(".failed").html("<div class='alert alert-danger' role='alert'>Utilisateur inconnu !</div>") 
                    }
                }
            });
        }
    }
    else
    {
        $(".failed").html('<div class="alert alert-danger" role="alert">Merci de remplir tous les champs</div>')
    }
}

function saveUser(e)
{
    var email = $("#email").val();
    if(verifMail(email) == false)
    {
        e.preventDefault();
        $(".failed").html('<div class="alert alert-danger" role="alert">Format du mail incorrect</div>')
    }
}

function verifMail(email)
{
    if(email.length > 0)
    {
        var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;

        if(regex.test(email) == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

function clickFav()
{
    var id = $(this).data("fav");
    if($(this).attr("src") == "img/icons/emptyheart.png")
    {
        $.ajax(
            {
            url: 'nosRecettes.php',
            method: 'post',
            dataType: 'json',
            data: {id: id, action: "add"},
            success: function(data)
            {
                if(data.result == true)
                {
                    $(this).attr("src", "img/icons/fullheart.png");
                }
                else if(data.result == false && data.redirect == true)
                {
                    window.location = "connexion.php"
                }
            }.bind(this)
        });
    }
    else
    {
        $.ajax({
            url: 'nosRecettes.php',
            method: 'post',
            dataType: 'json',
            data: {id: id, action: "remove"},
            success: function(data){
                if(data.result == true)
                {
                    $(this).attr("src", "img/icons/emptyheart.png");
                }
            }.bind(this)
        });
    }
}

function menuModifFav()
{
    if($(this).attr("src") == "img/icons/fullheart.png")
    {
        if($(this).attr("data-fav") == $(this)["0"].nextElementSibling.dataset.fav)
        {
            $(this)["0"].nextElementSibling.hidden = false;
        }
    }
    else
    {
        if($(this).attr("data-fav") == $(this)["0"].nextElementSibling.dataset.fav)
        {
            $(this)["0"].nextElementSibling.hidden = true;
        }
    }
}

function suppFav()
{
    var id = $(this).data("fav");
    $.ajax({
        url: 'nosRecettes.php',
        method: 'post',
        dataType: 'json',
        data: {id: id, action: "remove"},
        success: function(data){
            if(data.result == true)
            {
                location.reload(true);
            }
        }
    });
}
