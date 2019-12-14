<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_MODELE."/GameState.php";
require_once PATH_MODELE."/modele.php";


class ControleurJeu
{
    private $vueJeu;
    private $modele;

    public function __construct()
    {
        $this->vueJeu = new VueJeu();
        $this->modele =  new Modele();
    }

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

        $this->vueJeu->afficherVueJeu($pseudo, $centaine, $dizaine, $unite, $gamePerdu, $gameGagne, $etatCases);        
    }
    
    public function jouer($x, $y)
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $game->jouer($x, $y);
        $_SESSION['game'] = serialize($game);
        $this->afficherJeu();
    }
    
    public function nouveauJeu()
    {
        $pseudo = $_SESSION['pseudo'];
        $game = new GameState($pseudo);
        $_SESSION['game'] = serialize($game);
        $this->afficherJeu();
    }

}
