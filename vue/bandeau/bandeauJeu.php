<?php
/**
 * Permet de charger la balise head ainsi que le haut de la page
 * @param string $pseudo identifiant du joueur
 */
function headerPageJeu($pseudo)
{
    ?>
        <head>
            <link rel="stylesheet" href="assets/css/main.css">
            <link rel="stylesheet" href="assets/css/nav.css">
            <link rel="stylesheet" href="assets/css/game.css">
            <link rel="stylesheet" href="assets/css/window.css">
            <meta charset="UTF-8">
            <title>Minesweeper</title>
        </head>

        <body class="game-page">
            <nav>
                <ul class="nav nav-menu">
                    <li><?= $pseudo ?></li>
                    <li><a href="index.php?deconnexion">Deconnexion</a></li>
                </ul>
            </nav>
            <main>
        <?php
}
