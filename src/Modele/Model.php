<?php
namespace Minesweeper\Modele;

use PDO;
use PDOException;
use Minesweeper\Modele\Exceptions\TableAccesException;
use Minesweeper\Modele\Exceptions\ConnexionException;

/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

// Classe qui gère les accès à la base de données

class Modele
{
    private $connexion;

    // Constructeur de la classe

    public function __construct()
    {
        try {
            $chaine="mysql:host=".HOST.";dbname=".BD;
            $this->connexion = new PDO($chaine, LOGIN, PASSWORD);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $exception=new ConnexionException("Problème de connexion à la base");
            throw $exception;
        }
    }


    /**
     * Méthode qui permet de se deconnecter de la base
     */
    public function deconnexion()
    {
        $this->connexion=null;
    }

    /**
     * Méthode qui permet de vérifier si l'utilisateur est valide
     *
     * @param string $pseudo Login
     * @param string $password Mot de passe
     * @return boolean Vrai si le couple pseudo/mot de passe est valide.
     */
    public function verifierPassword($pseudo, $password)
    {
        try {
            $statement = $this->connexion->prepare("SELECT motDePasse from joueurs where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $hash = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($hash["motDePasse"]!=null) {
                return password_verify($password, $hash["motDePasse"]);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table joueurs");
        }
    }


    /**
     * Permet de s'avoir si un pseudo existe dans la table joueurs
     *
     * @param string $pseudo le pseudo du compte
     * @return boolean vrai si le pseudo existe
     */
    public function existsInJoueurs($pseudo)
    {
        try {
            $statement = $this->connexion->prepare("SELECT pseudo from joueurs where pseudo = ?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result=$statement->fetch(PDO::FETCH_ASSOC);

            return $result != null;
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table joueurs");
        }
    }


    /**
     * Permet de s'avoir si un pseudo existe dans la table parties
     *
     * @param string $pseudo le pseudo du compte
     * @return boolean vrai si le pseudo existe
     */
    public function existsInParties($pseudo)
    {
        try {
            $statement = $this->connexion->prepare("SELECT pseudo from parties where pseudo = ?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result=$statement->fetch(PDO::FETCH_ASSOC);

            return ($result["pseudo"] != null);
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'ajouter un joueur dans la table des parties
     *
     * @param string $pseudo identifiant du joueur
     */
    public function addPartie($pseudo)
    {
        try {
            $statement = $this->connexion->prepare("INSERT INTO parties VALUES (?,0,0,NULL);");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'incrémenter les parties jouées d'un joueur
     *
     * @param string $pseudo identifiant du joueur
     */
    public function incrPartieJouees($pseudo)
    {
        try {
            $statement = $this->connexion->prepare("SELECT * FROM parties where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $plays = $result['nbPartiesJouees'] +1;

            $statement = $this->connexion->prepare("UPDATE parties SET nbPartiesJouees = ? WHERE pseudo = ?;");
            $statement->bindParam(1, $plays);
            $statement->bindParam(2, $pseudo);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'incrémenter les parties gagnées d'un joueur
     *
     * @param string $pseudo identifiant du joueur
     * @param int $temps temps du joueur
     */
    public function incrPartieGagnees($pseudo, $temps)
    {
        try {
            $statement = $this->connexion->prepare("SELECT * FROM parties where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $wins = $result['nbPartiesGagnees'] +1;
            $result['bestTime'] = $result['bestTime'] == null ? 9999 : $result['bestTime'];
            $temps = min($temps, $result['bestTime']);

            $statement = $this->connexion->prepare("UPDATE parties SET nbPartiesGagnees = ?, bestTime = ? WHERE pseudo = ?;");
            $statement->bindParam(1, $wins);
            $statement->bindParam(2, $temps);
            $statement->bindParam(3, $pseudo);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'obtenir un tableau des 3 meilleurs joueurs
     *
     * @return array ['pseudo', 'nbPartiesJouees', 'nbPartiesGagnees']
     */
    public function get3MeilleursDemineurs()
    {
        try {
            $statement=$this->connexion->query("SELECT *, (nbPartiesGagnees / nbPartiesJouees) AS quotient FROM parties ORDER BY quotient DESC LIMIT 0, 3;");
            return($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'obtenir les données d'un joueur
     *
     * @return array ['pseudo', 'nbPartiesJouees', 'nbPartiesGagnees']
     */
    public function getDataDemineur($pseudo)
    {
        try {
            $statement=$this->connexion->prepare("SELECT * FROM parties WHERE pseudo = ?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            return($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet de vider la table 'parties'
     */
    public function resetParties()
    {
        try {
            $statement=$this->connexion->query("DELETE FROM parties;");
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }
}
