<?php
$email = htmlentities($_POST["email"]);
$message = htmlentities($_POST["message"]);
$user = strstr($email, "@", true);
$sujet = "Communication clients";

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email))
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}

$boundary = "-----=".md5(rand());

$header = "From: \"$user\"<$email>".$passage_ligne;
$header .= "Reply-to: \"SmoothMaker\" <smoothmaker@laposte.net>".$passage_ligne;
$header .= "MIME-Version: 1.0".$passage_ligne;
$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

$contenu = $passage_ligne."--".$boundary.$passage_ligne;
$contenu.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$contenu.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$contenu.= $passage_ligne.$message.$passage_ligne;

$contenu.= $passage_ligne."--".$boundary.$passage_ligne;

mail($email,$sujet,$contenu,$header);

if(mail($email,$sujet,$contenu,$header) == false)
{
    $result = false;
}
else
{
    $result = true;
}

echo json_encode(["result" => $result]);
