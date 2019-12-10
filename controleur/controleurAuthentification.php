<?php
require_once PATH_VUE."/authentification.php";
require_once PATH_MODELE."/modele.php";

class ControleurAuthentification{

	private $vue;
	private $modele;

	function __construct(){
		$this->vue = new Authentification();
		$this->modele = new Modele();
	}

	function accueil(){
		$this->vue->connexion(null);
	}

	function login($login, $password) {
		if($this->modele->exists($login)) {
			if ($this->modele->verifierPassword($login, $password)) {
				$_SESSION['pseudo']=$login;
				header('Location: index.php', false, 301);
			} else {
				$this->vue->connexion('Le mot de passe est incorrect');
			}
		} else {
			$this->vue->connexion('Le pseudo n\'est pas dans la base de données');
		}
	}

	function logout() {
		session_destroy();
		header('Location: index.php', false, 301);
	}




}