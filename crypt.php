<?php 

// cryptage de 'toto'
$toto=password_hash('toto',PASSWORD_DEFAULT);
echo $toto."<br/>";

// il faut que la fonction password_verify() connaisse la méthode de cryptage et le "sel" à utiliser. 
//Il faut que ce soit les mêmes que ce qui a été utilisé lors du cryptage.
// ces 2 informations sont stockées au début de la chaîne de caractères résultant du cryptage
//C'est pour cette raison que l'on passe $toto comme 2ème paramètre.
// voir documentation php

if (password_verify('toto', $toto)) {
    echo 'Le mot de passe est valide !';
} else {
    echo 'Le mot de passe est invalide.';
}

?>
