<?php
session_start();
require_once 'vendor/autoload.php';
use Minesweeper\Controleur\Routeur;

$routeur=new Routeur();
$routeur->routerRequete();
