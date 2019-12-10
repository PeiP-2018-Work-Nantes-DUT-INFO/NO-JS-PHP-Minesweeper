<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_MODELE."/GameState.php";

class ControleurJeu{

	private $vue;
	private $modele;

	function __construct(){
		$this->vue = new Jeu();
		$this->modele = new GameState(1);
	} 

	function afficherJeu($pseudo) {
        $this->vue->view($pseudo, $this->modele);
	}

}