<?php

require_once 'controleurAuthentification.php';
require_once 'controleurMessage.php';

class Routeur
{

    private $ctrlAuthentification;
    private $ctrlMessage;

    public function __construct()
    {
        $this->ctrlAuthentification = new ControleurAuthentification();
        $this->ctrlMessage = new ControleurMessage();
    }

    // Traite une requête entrante
    public function routerRequete()
    {
        if (isset($_SESSION['pseudo'])) {
            if (isset($_POST['message'])) {
                $this->ctrlMessage->envoyerMessage($_SESSION['pseudo'], $_POST['message']);
            } else if (isset($_GET['deconnexion'])) {
                $this->ctrlAuthentification->logout();
            } else {
                $this->ctrlMessage->afficherInterfaceMessages($_SESSION['pseudo']);
            }
        } else if (isset($_POST['username'])) {
            $this->ctrlAuthentification->login($_POST['username'], $_POST['password']);
        } else {
            $this->ctrlAuthentification->accueil();
        }

    }

}
