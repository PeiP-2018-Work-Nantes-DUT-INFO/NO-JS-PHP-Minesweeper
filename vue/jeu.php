<?php

class Jeu
{
    public function loadPage($pseudo, $centaine, $dizaine, $unite, $game, $etatCases)
    {
        $this->header_page($pseudo);
        if ($game->estPerdu()) {
            $this->header_minesweeper($centaine, $dizaine, $unite, 'dead');
        } else if ($game->aGagne()) {
            $this->header_minesweeper($centaine, $dizaine, $unite, 'boss');
        } else {
            $this->header_minesweeper($centaine, $dizaine, $unite, 'smile');
        }
        $this->open_gameTable();
        for ($ligne=0; $ligne < count($etatCases); $ligne++) {
            $this->open_tableLine();
            for ($colonne=0; $colonne < count($etatCases[$ligne]); $colonne++) {
                $case = $etatCases[$colonne][$ligne];
                $nb_mines = $case->getMinesAdjacentes();
                if ($case->estUneMine() && $case->estJouee()) {
                    if ($case->getSurbrillance()) {
                        $this->discoveredCase(' entourer', '*');
                    } else {
                        $this->discoveredCase('', '*');
                    }
                } else if ($case->estJouee() || ($game->aGagne() && !$case->estUneMine())) {
                    if ($nb_mines == 0) {
                        $this->discoveredCase($nb_mines, '');
                    } else {
                        $this->discoveredCase($nb_mines, $nb_mines);
                    }
                } else {
                    if (!$game->estPerdu()) {
                        $this->hiddenCase($ligne, $colonne);
                    } else {
                        $this->noClickable();
                    }
                }
            }
            $this->close_tableLine();
        }
        $this->close_gameTable();
        $this->footer_minesweeper();
    }

    public function header_page($pseudo)
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
            <ul class="nav nav-menu">
                <li><?= $pseudo ?></li>
                <li><a href="index.php?deconnexion">Deconnexion</a></li>
            </ul>
        <?php
    }


    public function header_minesweeper($centaine, $dizaine, $unite, $smiley)
    {
        ?>
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
                            <div class="bomb centaine"
                                style="background-image: url(assets/display<?= $centaine ?>.png);">
                            </div>
                            <div class="bomb dizaine"
                                style="background-image: url(assets/display<?= $dizaine ?>.png);">
                            </div>
                            <div class="bomb unite"
                                style="background-image: url(assets/display<?= $unite ?>.png);">
                            </div>
                        </div>
                        <div class="n-decouvert" id="<?= $smiley ?>">
                            <a href="index.php?reset" draggable="false"></a>
                        </div>
                        <div class="w41">
                            <div class="n-decouvert" id="flag">
                                <a href="#" draggable="false"></a>
                            </div>
                        </div>
                    </div>
        <?php
    }

    public function open_gameTable()
    {
        ?>
            <div class="game box-shadow">
                <table>
        <?php
    }


    public function open_tableLine()
    {
        ?>
            <tr>
        <?php
    }


    public function hiddenCase($ligne, $colonne)
    {
        ?>
            <td class="n-decouvert">
                <a href="index.php?x=<?= $colonne ?>&y=<?= $ligne ?>" draggable="false"></a>
            </td>
        <?php
    }


    public function discoveredCase($class, $display)
    {
        ?> <td class="decouvert mine-<?= $class ?>"> <?= $display ?> </td> <?php
    }


    public function noClickable()
    {
        ?> <td class="n-decouvert"></td><?php
    }


    public function close_tableLine()
    {
        ?>
            </tr>
        <?php
    }


    public function close_gameTable()
    {
        ?>
                </table>
            </div>
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
                        } ?>
                    </div>
                    <div class="buttons">

                    </div>
                </div>
            </div>
        <?php
    }


    public function footer_minesweeper()
    {
        ?>
                    </div>
                </div>
            </div>
        <?php
    }
}
?>