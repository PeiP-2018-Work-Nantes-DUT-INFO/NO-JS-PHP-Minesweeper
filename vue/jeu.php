<?php

class Jeu
{

    public function view($pseudo, $gameState)
    {
        ?>
            <html>
                <div class="content">
                    <div class="game_window">
                        <div class="head box-shadow">
                            <div class="n-decouvert">
                                <a href="#" draggable="false"></a>
                            </div>
                        </div>
                        <div class="game box-shadow">
                            <table>
                                <?php
                                    $etatCases = $gameState->obtenirEtatJeu();
                                    for ($ligne=0; $ligne < NBR_LIGNES; $ligne++) { 
                                        ?>
                                            <tr>
                                                <?php
                                                    for ($colonne=0; $colonne < NBR_COLONNES; $colonne++) {
                                                        $case = $etatCases[$colonne][$ligne];
                                                        $nb_mines = $case->getMinesAdjacentes();
                                                        if ($nb_mines) {
                                                            ?><td class="decouvert mine-<?= $nb_mines ?>"><?= $nb_mines ?></td><?php
                                                        } else {
                                                            ?><td class="n-decouvert"><a href="index.php?x=<?= $colonne ?>&y=<?= $ligne ?>" draggable="false"></a></td><?php
                                                        }
                                                    }
                                                ?>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </html>
        <?php
    }

    public function header()
    {
        ?>
            <head>
                <link rel="stylesheet" href="assets/game.css">
                <link rel="stylesheet" href="assets/webfontkit/stylesheet.css">
                <meta charset="UTF-8">
                <title>Minesweeper</title>
            </head>
            <body>
                <ul>
                    <li>Pseudo</li>
                    <li><a href="index.php?deconnexion">Déconnexion</a></li>
                </ul>
        <?php
    }
}
?>