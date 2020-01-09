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
            if (isset($_GET['deconnexion'])) {
                $this->ctrlAuthentification->logout();
            } elseif (isset($_SESSION['game']) && !isset($_GET['reset'])) {
                if (isset($_GET['x']) && isset($_GET['y'])) {
                    $this->ctrlJeu->jouer($_GET['x'], $_GET['y']);
                } elseif (isset($_GET['reset-scores'])) {
                    $this->ctrlJeu->resetScores();
                } elseif (isset($_GET['scores'])) {
                    $this->ctrlJeu->afficherResultat();
                } else {
                    $this->ctrlJeu->afficherJeu();
                }
            } else {
                $this->ctrlJeu->nouveauJeu($_SESSION['pseudo']);
            }
        } elseif (isset($_POST['username'])) {
            $this->ctrlAuthentification->login($_POST['username'], $_POST['password']);
        } else {
            $this->ctrlAuthentification->accueil();
        }
    }
}
