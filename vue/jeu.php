<?php

require_once PATH_VUE.'/bandeau/bandeauJeu.php';
require_once PATH_VUE.'/bandeau/piedDePageJeu.php';

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
     * @param \CaseMetier[][] $etatCases un tableau a 2 dimensions de Case
     */
    public function afficherVueJeu($pseudo, $centaine, $dizaine, $unite, $perdu, $gagne, $etatCases)
    {
        headerPageJeu($pseudo);
        $this->afficherPopupJeu($centaine, $dizaine, $unite, $perdu, $gagne, $etatCases);
        //$this->close_gameTable();
        $this->afficherWinBar();
        footerPageJeu();
    }

    public function afficherPopupJeu($centaine, $dizaine, $unite, $perdu, $gagne, $etatCases)
    {
        if ($perdu) {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'dead');
        } elseif ($gagne) {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'boss');
        } else {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'smile');
        }
        $this->openGameTable();
        for ($ligne=0; $ligne < count($etatCases); $ligne++) {
            $this->openTableLine();
            for ($colonne=0; $colonne < count($etatCases[$ligne]); $colonne++) {
                $case = $etatCases[$colonne][$ligne];
                $nb_mines = $case->getMinesAdjacentes();
                if ($case->estUneMine() && $case->estJouee()) {
                    if ($case->getSurbrillance()) {
                        $this->discoveredCase(' entourer', '*');
                    } else {
                        $this->discoveredCase('', '*');
                    }
                } elseif ($case->estJouee() || ($gagne && !$case->estUneMine())) {
                    if ($nb_mines == 0) {
                        $this->discoveredCase(strval($nb_mines), '');
                    } else {
                        $this->discoveredCase(strval($nb_mines), strval($nb_mines));
                    }
                } else {
                    if (!$perdu && !$gagne) {
                        $this->hiddenCase($ligne, $colonne);
                    } else {
                        $this->noClickable();
                    }
                }
            }
            $this->closeTableLine();
        } ?>
        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php
    }


   


    /**
     * Permet d'ouvrir et d'afficher le haut du jeu 'Minesweeper'
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param string $smiley la tete du smiley a afficher
     */
    public function headerMinesweeper($centaine, $dizaine, $unite, $smiley)
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
            <div class="content" id="game_container">
                <div class="game_window">
                    <div class="head box-shadow">
                        <div class="display-bomb" style="background-image: url(assets/img/display.png);">
                            <div class="bomb centaine"
                                style="background-image: url(assets/img/display<?= $centaine ?>.png);">
                            </div>
                            <div class="bomb dizaine"
                                style="background-image: url(assets/img/display<?= $dizaine ?>.png);">
                            </div>
                            <div class="bomb unite"
                                style="background-image: url(assets/img/display<?= $unite ?>.png);">
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
    public function openGameTable()
    {
        ?>
            <div class="game box-shadow">
                <table>
        <?php
    }


    /**
     * Permet d'ouvrir une ligne de la table de jeu
     */
    public function openTableLine()
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
     * @param string $class nombre de mines
     * @param string $display le nombre de mine a afficher : ' ' si $class == 0, $class si $class > 0, '*' si est une mine
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
    public function closeTableLine()
    {
        ?>
            </tr>
        <?php
    }


    public function afficherWinBar()
    {
        ?>
            <div class="winbar">
                <div class="start_stuff">
                    <div class="start">
                        <img class="logo" src="assets/img/startButton.png" alt="startButton" draggable="false">
                    </div>
                    <div class="process_gameMS">
                        <img class="logo" src="assets/img/mine_logo.png" alt="mine_logo" draggable="false">
                        <div class="title">Minesweeper</div>
                    </div>
                </div>
                <div class="end_stuff">
                    <img src="assets/img/sound.png" alt="speaker" draggable="false">
                    <img src="assets/img/usb.png" alt="usb" draggable="false">
                    <img src="assets/img/shield.png" alt="shield" draggable="false">
                    <div class="date">
                    <?php
                        $utc1 = 3600;
                        echo gmdate('g:i A', time()+($utc1));
                        ?>
                    </div>
                </div>
            </div>
        <?php
    }
}
?>