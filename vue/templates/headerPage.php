<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

/**
 * Permet de charger la balise head ainsi que le haut de la page
 * 
 * @param string $pseudo identifiant du joueur
 */
function headerPageJeu()
{
    ?>
        <head>
            <link rel="stylesheet" href="assets/css/main.css">
            <link rel="stylesheet" href="assets/css/nav.css">
            <link rel="stylesheet" href="assets/css/game.css">
            <link rel="stylesheet" href="assets/css/winBar.css">
            <link rel="stylesheet" href="assets/css/window.css">
            <meta charset="UTF-8">
            <title>Minesweeper</title>
        </head>

        <body class="game-page">
            <main>
        <?php
}
