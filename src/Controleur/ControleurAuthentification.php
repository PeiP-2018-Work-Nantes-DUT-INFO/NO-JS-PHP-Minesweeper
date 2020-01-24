<?php
namespace Minesweeper\Controleur;

use Minesweeper\Vue\VueAuthentification;

use Minesweeper\Modele\Modele;

/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */


class ControleurAuthentification
{
    private $vue;
    private $modele;

    public function __construct()
    {
        $this->vue = new VueAuthentification();
        $this->modele = new Modele();
    }


    /**
     * Permet de charger la vue de connexion
     */
    public function accueil()
    {
        $this->vue->connexion(null);
    }


    /**
     * Permet de connecter un utilisateur
     * @param string $login l'identifiant
     * @param string $password le mot de passe
     */
    public function login($login, $password)
    {
        if ($this->modele->existsInJoueurs($login)) {
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


    /**
     * Permet de déconnecter un utilisateur
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php', false, 301);
    }
}
