<?php

require_once 'controleurAuthentification.php';
require_once 'controleurJeu.php';

class Routeur
{

    private $ctrlAuthentification;
    private $ctrlJeu;

    public function __construct()
    {
        $this->ctrlAuthentification = new ControleurAuthentification();
        $this->ctrlJeu = new ControleurJeu();
    }

    // Traite une requête entrante
    public function routerRequete()
    {
        if (isset($_SESSION['pseudo'])) {
            if (isset($_GET['x']) && isset($_GET['y'])) {
                $this->ctrlJeu->jouer($x, $y);
            } else {
                $this->ctrlJeu->afficherJeu($_SESSION['pseudo']);
            }
        } else if (isset($_POST['username'])) {
            $this->ctrlAuthentification->login($_POST['username'], $_POST['password']);
        } else {
            $this->ctrlAuthentification->accueil();
        }

    }

}
