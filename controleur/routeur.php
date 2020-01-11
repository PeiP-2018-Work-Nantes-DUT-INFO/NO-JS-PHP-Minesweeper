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

    /**
     * Permet de traiter une requête entrante
     */
    public function routerRequete()
    {
        if (isset($_SESSION['pseudo'])) {
            if (isset($_GET['deconnexion'])) {
                $this->ctrlAuthentification->logout();
            } elseif (isset($_SESSION['game']) && !isset($_GET['reset']) && !isset($_GET['difficulty'])) {
                if (isset($_GET['x']) && isset($_GET['y']) && isset($_GET['flag-mode'])) {
                    $this->ctrlJeu->placerDrapeau($_GET['x'], $_GET['y']);
                } elseif (isset($_GET['flag-mode'])) {
                    $this->ctrlJeu->afficherJeuModeDrapeau();
                } elseif (isset($_GET['x']) && isset($_GET['y'])) {
                    $this->ctrlJeu->jouer($_GET['x'], $_GET['y']);
                } elseif (isset($_POST['reset-scores'])) {
                    $this->ctrlJeu->resetScores();
                } elseif (isset($_GET['scores'])) {
                    $this->ctrlJeu->afficherResultat();
                } elseif (isset($_GET['credits'])) {
                    $this->ctrlJeu->afficherCredits(filter_input(
                        INPUT_GET,
                        'credits',
                        FILTER_VALIDATE_INT,
                        array("options" => array(
                        "default" => 0,
                        "min_range" => 0))
                    ));
                } else {
                    $this->ctrlJeu->afficherJeu();
                }
            } else {
                if (isset($_GET['difficulty'])) {
                    $this->ctrlJeu->changerDifficulte($_GET['difficulty']);
                } else {
                    $this->ctrlJeu->nouveauJeu($_SESSION['pseudo']);
                }
            }
        } elseif (isset($_POST['username'])) {
            $this->ctrlAuthentification->login($_POST['username'], $_POST['password']);
        } else {
            $this->ctrlAuthentification->accueil();
        }
    }
}
