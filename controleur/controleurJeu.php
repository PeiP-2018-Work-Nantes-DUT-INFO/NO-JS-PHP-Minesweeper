<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_VUE."/resultat.php";
require_once PATH_MODELE."/GameState.php";
require_once PATH_MODELE."/modele.php";


class ControleurJeu
{
    private $vueJeu;
    private $vueResultat;
    private $modele;

    /**
     *
     */
    public function __construct()
    {
        $this->vueJeu = new VueJeu();
        $this->vueResultat = new VueResultat();
        $this->modele =  new Modele();
    }


    /**
     *
     */
    public function afficherJeu()
    {
        $game = unserialize($_SESSION['game']);
        $gamePerdu = $game->estPerdu();
        $gameGagne = $game->aGagne();
        $etatCases = $game->obtenirEtatJeu();
        $pseudo = $_SESSION['pseudo'];
        $centaine = (int)($game->drapeauxRestants()/100);
        $dizaine = (int)($game->drapeauxRestants()/10)-($centaine*10);
        $unite = (int)($game->drapeauxRestants())-($centaine*100)-($dizaine*10);
        if ($game->drapeauxRestants() < 0) {
            $centaine = "-";
        }

        $this->vueJeu->afficherVueJeu($pseudo, $centaine, $dizaine, $unite, $gamePerdu, $gameGagne, $etatCases);
    }
    

    /**
     *
     */
    public function jouer($x, $y)
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $pseudo = $_SESSION['pseudo'];
        if (!$game->estCommence() && $game->mouvementPossible($x, $y)) {
            $this->modele->incrPartieJouees($pseudo);
        }
        if (!$game->aGagne() && !$game->estPerdu()) {
            $faitPerdre = $game->jouer($x, $y);
            $_SESSION['game'] = serialize($game);
            if ($game->aGagne() || $game->estPerdu()) {
                if ($game->aGagne()) {
                    $this->modele->incrPartieGagnees($pseudo);
                }
                header("Location: ?scores", false, 301);
            } else {
                $this->afficherJeu();
            }
        } else {
            $this->afficherJeu();
        }
    }
    

    /**
     *
     */
    public function nouveauJeu()
    {
        $pseudo = $_SESSION['pseudo'];
        $game = new GameState($pseudo);
        $_SESSION['game'] = serialize($game);
        if (!$this->modele->existsInParties($pseudo)) {
            $this->modele->addPartie($pseudo);
        }
        $this->afficherJeu();
    }


    /**
     * Permet de remettre les scores à zéro
     */
    public function resetScores()
    {
        $this->modele->resetParties();
        $this->afficherResultat();
    }

    public function afficherResultat()
    {
        $game = unserialize($_SESSION['game']);
        $gamePerdu = $game->estPerdu();
        $gameGagne = $game->aGagne();
        $etatCases = $game->obtenirEtatJeu();
        $pseudo = $_SESSION['pseudo'];
        $centaine = (int)($game->drapeauxRestants()/100);
        $dizaine = (int)($game->drapeauxRestants()/10)-($centaine*10);
        $unite = (int)($game->drapeauxRestants())-($centaine*100)-($dizaine*10);
        if ($game->drapeauxRestants() < 0) {
            $centaine = "-";
        }

        $this->vueResultat->afficherVueResultat($this->modele->get3MeilleursDemineurs(), $pseudo, $centaine, $dizaine, $unite, $gamePerdu, $gameGagne, $etatCases);
    }
}
