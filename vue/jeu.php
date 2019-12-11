<?php

class Jeu
{

    public function header()
    {
        ?>
            <head>
                <link rel="stylesheet" href="assets/game.css">
                <meta charset="UTF-8">
                <title>Minesweeper</title>
            </head>
            <body>
                <ul class="nav nav-menu">
                    <li>Pseudo</li>
                    <li><a href="index.php?deconnexion">Deconnexion</a></li>
                </ul>
        <?php
    }

    public function view($pseudo, $gameState)
    {
        ?>
            <html>
                <?php $this->header(); ?>
                <div class="popup minesweeper">
                    <div class="header">Minesweeper</div>
                    <div class="nav-bar">
                        <ul class="nav nav-btn">
                            <li id="game"><u>G</u>ame</li>
                            <li id="help"><u>H</u>elp</li>
                        </ul>
                    </div>
                    <div class="content">
                        <div class="game_window">
                            <div class="head box-shadow">
                                <div class="display-bomb" style="background-image: url(assets/display.png);">
                                <div class="bomb centaine" style="background-image: url(assets/display<?= $c = (int)($gameState->drapeauxRestants()/100) ?>.png);"></div>
                                    <div class="bomb dizaine" style="background-image: url(assets/display<?= $d = (int)($gameState->drapeauxRestants()/10)-($c*10) ?>.png);"></div>
                                    <div class="bomb unite" style="background-image: url(assets/display<?= (int)($gameState->drapeauxRestants())-($c*100)-($d*10) ?>.png);"></div>
                                </div>
                                <div class="n-decouvert"  id="smile">
                                    <a href="#" draggable="false"></a>
                                </div>
                                <div class="w41">
                                    <div class="n-decouvert"  id="flag">
                                        <a href="#" draggable="false"></a>
                                    </div>
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
                                                            if ($case->estJouee()) {
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
                </div>
            </html>
        <?php
    }

    public function endGame($winners)
    {
        ?>
            <div class="won popup">
                <div class="header">Best Mine Sweepers</div>
                <div class="content">
                    <div class="scores">
                        <?php
                            foreach ($winners as $win) {
                                ?>
                                    <p class="pseudo"></p>
                                    <p class="wins"></p>
                                    <p class="plays"></p>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="buttons">

                    </div>
                </div>
            </div>
        <?php
    }
}
?>