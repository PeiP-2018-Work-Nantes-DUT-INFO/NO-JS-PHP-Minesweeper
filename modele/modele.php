<?php

// Classe generale de definition d'exception
class MonException extends Exception
{
    private $chaine;
    public function __construct($chaine)
    {
        parent::__construct($chaine);
        $this->chaine=$chaine;
    }

    public function afficher()
    {
        return $this->chaine;
    }
}


// Exception relative à un probleme de connexion
class ConnexionException extends MonException
{
}

// Exception relative à un probleme d'accès à une table
class TableAccesException extends MonException
{
}


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




    // A développer
    // méthode qui permet de se deconnecter de la base
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

    //A développer
    // utiliser une requête classique
    // méthode qui permet de récupérer les pseudos dans la table pseudo
    // post-condition:
    //retourne un tableau à une dimension qui contient les pseudos.
    // si un problème est rencontré, une exception de type TableAccesException est levée

    public function getPseudos()
    {
        try {
            $statement=$this->connexion->query("SELECT pseudo from joueurs;");

            while ($ligne=$statement->fetch()) {
                $result[]=$ligne['pseudo'];
            }
            return($result);
        } catch (PDOException $e) {
            throw new TableAccesException("problème avec la table pseudonyme");
        }
    }



    /**
     * Permet de s'avoir si un pseudo existe
     *
     * @param string $pseudo le pseudo du compte
     * @param string $table la table où effectuer la recherche
     * @return boolean vrai si le pseudo existe
     */
    public function exists($pseudo, $table)
    {
        try {
            $statement = $this->connexion->prepare("SELECT pseudo from $table where pseudo = ?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result=$statement->fetch(PDO::FETCH_ASSOC);

            return ($result["pseudo"] != null);
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table $table");
        }
    }



    //A développer
    // utiliser uen requête préparée
    // ajoute un message sur le salon => pseudonyme + message
    // precondition: le pseudo existe dans la table pseudonyme
    // post-condition: le message est ajouté dans la table salon
    // si un problème est rencontré, une exception de type TableAccesException est levée

    public function majSalon($pseudo, $message)
    {
        try {
            $statement = $this->connexion->prepare("select pseudo from joueurs where pseudo=?;");
            $statement->bindParam(1, $pseudoParam);
            $pseudoParam=$pseudo;
            $statement->execute();
            $result=$statement->fetch(PDO::FETCH_ASSOC);
            $statement = $this->connexion->prepare("INSERT INTO salon (idpseudo, message) VALUES (?,?);");
            $statement->bindParam(1, $result['id']);
            $statement->bindParam(2, $message);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("problème avec la table salon");
        }
    }






    //A développer
    //utiliser une requête classique
    // méthode qui permet de récupérer les 10 derniers messages émis sur le salon
    // post-condition:
    //retourne un tableau qui contient des objets de type Message (script de la classe Message dans le répertoire métier)
    // c'est en fait simplement le résultat de l'application de la méthode fetchAll() avec le bon paramètre
    // si un problème est rencontré, une exception de type TableAccesException est levée

    public function get10RecentMessage()
    {
        try {
            $statement=$this->connexion->query("SELECT pseudonyme.pseudo ,salon.message FROM salon, pseudonyme where salon.idpseudo=pseudonyme.id ORDER BY salon.id DESC LIMIT 0, 10;");

            return($statement->fetchAll(PDO::FETCH_CLASS, "Message"));
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("problème avec la table salon");
        }
    }


    /**
     * Permet d'ajouter un joueur dans la table des parties
     * @param string $pseudo identifiant du joueur
     */
    public function addPartie($pseudo)
    {
        try {
            $statement = $this->connexion->prepare("INSERT INTO parties VALUES (?,0,0);");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'incrémenter les parties jouées et gagnées d'un joueur
     * @param string $pseudo identifiant du joueur
     * @param boolean $gagne vrai si la partie est gagne
     */
    public function incrPartie($pseudo, $gagne)
    {
        try {
            
            $nb = 1;
            $statement = $this->connexion->prepare("SELECT * FROM parties where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $plays = $result['nbPartiesJouees']+1;
            $wins = $result['nbPartiesGagnees'];
            if ($gagne) {
                $wins = $wins +1;
            }

            $statement = $this->connexion->prepare("UPDATE parties SET nbPartiesJouees = ?, nbPartiesGagnees = ? WHERE pseudo = ?;");
            $statement->bindParam(1, $plays);
            $statement->bindParam(2, $wins);
            $statement->bindParam(3, $pseudo);
            $statement->execute();
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * Permet d'obtenir un tableau des 3 meilleurs joueurs
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
     * @return array ['pseudo', 'nbPartiesJouees', 'nbPartiesGagnees']
     */
    public function getDataDemineur($pseudo)
    {
        try {
            $statement=$this->connexion->query("SELECT * FROM parties WHERE pseudo = ?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            return($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            $this->deconnexion();
            throw new TableAccesException("Problème avec la table parties");
        }
    }


    /**
     * 
     * 
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
