<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_MODELE."/GameState.php";

class ControleurJeu
{
    private $vue;
    private $modele;

    public function __construct()
    {
        $this->vue = new Jeu();
        $this->modele = new GameState(1);
    }

    public function afficherJeu($pseudo)
    {
        $this->vue->header();
        $this->vue->view($pseudo, $this->modele);
    }
    
    public function jouer($x, $y)
    {
        return null;
    }
    
    public function nouveauJeu()
    {
		$_SESSION['game'] = null;
    }
}
