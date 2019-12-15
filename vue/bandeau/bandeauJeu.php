<?php
/**
 * Permet de charger la balise head ainsi que le haut de la page
 * @param string $pseudo identifiant du joueur
 */
function headerPageJeu($pseudo)
{
    ?>
        <head>
            <link rel="stylesheet" href="assets/main.css">
            <link rel="stylesheet" href="assets/nav.css">
            <link rel="stylesheet" href="assets/game.css">
            <link rel="stylesheet" href="assets/window.css">
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
