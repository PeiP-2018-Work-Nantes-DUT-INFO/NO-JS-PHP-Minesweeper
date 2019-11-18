<?php
require_once PATH_METIER."/Message.php";


// Classe generale de definition d'exception
class MonException extends Exception{
	private $chaine;
	public function __construct($chaine){
		$this->chaine=$chaine;
	}

	public function afficher(){
		return $this->chaine;
	}

}


// Exception relative à un probleme de connexion
class ConnexionException extends MonException{
}

// Exception relative à un probleme d'accès à une table
class TableAccesException extends MonException{
}


// Classe qui gère les accès à la base de données

class Modele{
	private $connexion;

// Constructeur de la classe

	public function __construct(){
		try{  


			$chaine="mysql:host=".HOST.";dbname=".BD;
			$this->connexion = new PDO($chaine,LOGIN,PASSWORD);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			$exception=new ConnexionException("problème de connexion à la base");
			throw $exception;
		}
	}




// A développer
// méthode qui permet de se deconnecter de la base
	public function deconnexion(){
		$this->connexion=null;
	}


//A développer
// utiliser une requête classique
// méthode qui permet de récupérer les pseudos dans la table pseudo
// post-condition:
//retourne un tableau à une dimension qui contient les pseudos.
// si un problème est rencontré, une exception de type TableAccesException est levée

	public function getPseudos(){
		try{  

			$statement=$this->connexion->query("SELECT pseudo from pseudonyme;");

			while($ligne=$statement->fetch()){
				$result[]=$ligne['pseudo'];
			}
			return($result);
		}
		catch(PDOException $e){
			throw new TableAccesException("problème avec la table pseudonyme");
		}  
	}



//A développer
// utiliser une requête préparée
//vérifie qu'un pseudo existe dans la table pseudonyme
// post-condition retourne vrai si le pseudo existe sinon faux
// si un problème est rencontré, une exception de type TableAccesException est levée
	public function exists($pseudo){
		try{  
			$statement = $this->connexion->prepare("select id from pseudonyme where pseudo=?;");
			$statement->bindParam(1, $pseudoParam);
			$pseudoParam=$pseudo;
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if ($result["id"]!=NUll){
				return true;
			}
			else{
				return false;
			}
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table pseudonyme");
		}
	}



//A développer
// utiliser uen requête préparée
// ajoute un message sur le salon => pseudonyme + message
// precondition: le pseudo existe dans la table pseudonyme
// post-condition: le message est ajouté dans la table salon
// si un problème est rencontré, une exception de type TableAccesException est levée

	public function majSalon($pseudo,$message){
		try{ 

			$statement = $this->connexion->prepare("select id from pseudonyme where pseudo=?;");
			$statement->bindParam(1, $pseudoParam);
			$pseudoParam=$pseudo;
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC); 
			$statement = $this->connexion->prepare("INSERT INTO salon (idpseudo, message) VALUES (?,?);");
			$statement->bindParam(1, $result['id']);
			$statement->bindParam(2, $message);
			$statement->execute();

		}
		catch(PDOException $e){
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

	public function get10RecentMessage(){

		try{	
			$statement=$this->connexion->query("SELECT pseudonyme.pseudo ,salon.message FROM salon, pseudonyme where salon.idpseudo=pseudonyme.id ORDER BY salon.id DESC LIMIT 0, 10;");

			return($statement->fetchAll(PDO::FETCH_CLASS, "Message"));

		} 
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table salon");
		}
	}


}

?>