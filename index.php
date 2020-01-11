<?php
session_start();
require "config/config.php";
require "config/credits.php";
require PATH_CONTROLEUR."/routeur.php";

$routeur=new Routeur();
$routeur->routerRequete();
