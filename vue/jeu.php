<?php

class VueJeu
{
    /**
     * Permet d'afficher la vue du jeu
     * @param string $pseudo identifiant du joueur
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param boolean $perdu vrai si le jeu est perdu
     * @param boolean $gagne vrai si le jeu est gagné
     * @param Case[][] $etatCases un tableau a 2 dimensions de Case
     */
    public function afficherVueJeu($pseudo, $centaine, $dizaine, $unite, $perdu, $gagne, $etatCases)
    {
        $this->header_page($pseudo);
        if ($perdu) {
            $this->header_minesweeper($centaine, $dizaine, $unite, 'dead');
        } else if ($gagne) {
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
                } else if ($case->estJouee() || ($gagne && !$case->estUneMine())) {
                    if ($nb_mines == 0) {
                        $this->discoveredCase($nb_mines, '');
                    } else {
                        $this->discoveredCase($nb_mines, $nb_mines);
                    }
                } else {
                    if (!$perdu && !$gagne) {
                        $this->hiddenCase($ligne, $colonne);
                    } else {
                        $this->noClickable();
                    }
                }
            }
            $this->close_tableLine();
        }
        //$this->close_gameTable();
        $this->footer_page();
    }


    /**
     * Permet de charger la balise head ainsi que le haut de la page
     * @param string $pseudo identifiant du joueur
     */
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


    /**
     * Permet d'ouvrir et d'afficher le haut du jeu 'Minesweeper'
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param string $smiley la tete du smiley a afficher
     */
    public function header_minesweeper($centaine, $dizaine, $unite, $smiley)
    {
        ?>
        <div class="popup minesweeper">
            <div class="header">
                <div class="title">Minesweeper</div>
                <div class="buttons">
                    <!--<div class="btn hide-btn"></div>
                    <div class="btn resize-btn disable"></div>-->
                    <div class="btn close-btn"><a href="#"></a></div>
                </div>
                </div>
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


    /**
     * Permet d'ouvrir la table de jeu
     */
    public function open_gameTable()
    {
        ?>
            <div class="game box-shadow">
                <table>
        <?php
    }


    /**
     * Permet d'ouvrir une ligne de la table de jeu
     */
    public function open_tableLine()
    {
        ?>
            <tr>
        <?php
    }


    /**
     * Permet d'afficher une case non-decouverte
     * @param int $ligne le numéro de la ligne
     * @param int $colonne le numéro de la colonne
     */
    public function hiddenCase($ligne, $colonne)
    {
        ?>
            <td class="n-decouvert">
                <a href="index.php?x=<?= $colonne ?>&y=<?= $ligne ?>" draggable="false"></a>
            </td>
        <?php
    }


    /**
     * Permet d'afficher une case découverte
     * @param int $class le nombre de mine
     * @param int $display le nombre de mine a afficher : ' ' si $class == 0, $class si $class > 0, '*' si est une mine
     */
    public function discoveredCase($class, $display)
    {
        ?> <td class="decouvert mine-<?= $class ?>"> <?= $display ?> </td> <?php
    }


    /**
     * Permet d'afficher une case non-découverte non-cliquable
     */
    public function noClickable()
    {
        ?> <td class="n-decouvert"></td><?php
    }


    /**
     * Permet de fermer une ligne de la table de jeu
     */
    public function close_tableLine()
    {
        ?>
            </tr>
        <?php
    }


    /**
     * Permet de fermer la table de jeu, le jeu 'Minesweeper' et la page de jeu
     */
    public function footer_page()
    {
        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
        <?php
    }
}
?>