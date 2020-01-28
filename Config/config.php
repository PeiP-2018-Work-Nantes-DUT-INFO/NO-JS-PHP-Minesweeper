<?php

// les chemins vers les différents répertoires liés au modèle MVC

/**
 * chemin complet sur le serveur de la racine du site, il est
 * supposé que config.php est dans un sous-repertoire de la racine du site
 */
define("HOME_SITE", __DIR__."/..");

// définition des chemins vers les divers répertoires liés au modèle MVC
define("PATH_VUE", HOME_SITE."/vue");
define("PATH_CONTROLEUR", HOME_SITE."/controleur");
define("PATH_MODELE", HOME_SITE."/modele");
define("PATH_METIER", HOME_SITE."/metier");


// données pour la connexion au sgbd

// remplacer les X par vos identifiants de connexion à mysql

define("HOST", "localhost");
define("BD", "E189580Q_php");
define("LOGIN", "root");
define("PASSWORD", "password");

 /**
  * Nombre de colonnes du demineur
  */
  define("NBR_COLONNES", 9);

  /**
   * Nombre de lignes du demineur
   */
  define("NBR_LIGNES", 9);
  
  /**
   * Nombre de mines contenues dans le demineur
   */
  define("NBR_MINES", 10);

  define("UTC", 3600);