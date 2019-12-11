<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_MODELE."/GameState.php";
require_once PATH_MODELE."/modele.php";


class ControleurJeu
{
    private $vue;
    private $modele;

    public function __construct()
    {
        $this->vue = new Jeu();
        $this->modele =  new Modele();
    }

    public function afficherJeu()
    {
        $game = unserialize($_SESSION['game']);
        $this->vue->view($_SESSION['pseudo'], $game);
    }
    
    public function jouer($x, $y)
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $game->jouer($x, $y);
        $this->vue->view($_SESSION['pseudo'], $game);
        $_SESSION['game'] = serialize($game);
    }
    
    public function nouveauJeu()
    {
        $pseudo = $_SESSION['pseudo'];
        $game = new GameState($pseudo);
        $_SESSION['game'] = serialize($game);
        $this->vue->view($pseudo, $game);
    }
}
