<?php
namespace Minesweeper\Vue;

/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */


use Minesweeper\Modele\CaseMetier;

class VueJeu
{
    /**
     * Permet d'afficher la vue du jeu
     *
     * @param string $pseudo identifiant du joueur
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param boolean $perdu vrai si le jeu est perdu
     * @param boolean $gagne vrai si le jeu est gagné
     * @param CaseMetier[][] $etatCases un tableau a 2 dimensions de Case
     * @param bool $flagMode si le on est en mode placement de drapeaux
     * @param int $nbrLignes nombre de lignes
     * @param int $nbrColonnes nombre de colonnes
     * @param int $difficulty la difficultée du niveau
     */
    public function afficherVueJeu(
        $pseudo,
        $centaine,
        $dizaine,
        $unite,
        $perdu,
        $gagne,
        $etatCases,
        $flagMode,
        $nbrLignes,
        $nbrColonnes,
        $difficulty
    ) {
        headerPageJeu();
        $this->afficherPopupJeu(
            $centaine,
            $dizaine,
            $unite,
            $perdu,
            $gagne,
            $etatCases,
            $flagMode,
            $nbrLignes,
            $nbrColonnes,
            $difficulty
        );
        afficherWinBar($pseudo);
        footerPageJeu();
    }


    /**
     * Permet d'afficher la fenêtre/div contenant le jeu
     *
     * @param mixed $centaine
     * @param mixed $dizaine
     * @param mixed $unite
     * @param mixed $perdu
     * @param mixed $gagne
     * @param CaseMetier[][] $etatCases
     * @param bool $flagMode
     * @param int $nbrLignes
     * @param int $nbrColonnes
     * @param int $difficulty la difficultée du niveau
     * @return void
     */
    public function afficherPopupJeu(
        $centaine,
        $dizaine,
        $unite,
        $perdu,
        $gagne,
        $etatCases,
        $flagMode,
        $nbrLignes,
        $nbrColonnes,
        $difficulty
    ) {
        if ($perdu) {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'dead', $flagMode, $difficulty);
        } elseif ($gagne) {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'boss', $flagMode, $difficulty);
        } else {
            $this->headerMinesweeper($centaine, $dizaine, $unite, 'smile', $flagMode, $difficulty);
        }
        $this->openGameTable();
        for ($ligne=0; $ligne < $nbrLignes; $ligne++) {
            $this->openTableLine();
            for ($colonne=0; $colonne < $nbrColonnes; $colonne++) {
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
                    $class = $case->aDrapeau() ? 'flag' : '';
                    if (!$perdu && !$gagne && (!$case->aDrapeau() || $flagMode)) {
                        $this->hiddenCase($ligne, $colonne, $flagMode, $class);
                    } else {
                        $this->noClickable($class);
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
     *
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param string $smiley la tete du smiley a afficher
     * @param int $difficulty la diffultée du niveau
     * @param boolean $flagMode
     */
    public function headerMinesweeper($centaine, $dizaine, $unite, $smiley, $flagMode, $difficulty)
    {
        ?>
        <div class="popup minesweeper">
            <div class="header">
                <div class="title">Minesweeper</div>
                <div class="buttons">
                    <div class="btn hide-btn disable"></div>
                    <div class="btn resize-btn disable"></div>
                    <div class="btn close-btn"><a href="index.php?deconnexion"></a></div>
                </div>
                </div>
            <div class="nav-bar">
                <ul class="nav nav-btn">
                    <li id="game"><u>G</u>ame
                        <ul class="dropdown-menu">
                            <li><a href="index.php?reset"><div class="w18"></div><p>New</p></a></li>
                            <li class="separator"></li>
                            <li><a href="<?= $difficulty !== 0 ? 'index.php?difficulty=default': '#'?>"><div class="w18 <?= $difficulty === 0? 'checked': ''?>"></div><p>Beginner</p></a></li>
                            <li><a href="<?= $difficulty !== 1 ? 'index.php?difficulty=1': '#'?>"><div class="w18 <?= $difficulty === 1? 'checked': ''?>"></div><p>Intermediate</p></a></li>
                            <li><a href="<?= $difficulty !== 2 ? 'index.php?difficulty=2': '#'?>"><div class="w18 <?= $difficulty === 2? 'checked': ''?>"></div><p>Expert</p></a></li>
                            <li class="separator"></li>
                            <li><a href="#"><div class="w18"></div><p>Marks (?)</p></a></li>
                            <li><a href="#"><div class="w18 checked"></div><p>Color</p></a></li>
                            <li><a href="#"><div class="w18"></div><p>Sound</p></a></li>
                            <li class="separator"></li>
                            <li><a href="index.php?scores"><div class="w18"></div><p>Best Players...</p></a></li>
                            <li class="separator"></li>
                            <li><a href="index.php?deconnexion"><div class="w18"></div><p>Exit</p></a></li>
                        </ul>
                    </li>
                    <li id="help"><u>H</u>elp
                        <ul class="dropdown-menu">
                            <li><a href="#"><div class="w18"></div><p>Content</p></a></li>
                            <li><a href="#"><div class="w18"></div><p>Search for Help on ...</p></a></li>
                            <li><a href="#"><div class="w18"></div><p>Using Help</p></a></li>
                            <li class="separator"></li>
                            <li><a href="index.php?credits"><div class="w18"></div><p>Credits</p></a></li>
                        </ul>
                    </li>
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
                            <div class="<?= $flagMode ? '' : 'n-'?>decouvert" id="flag">
                                <a href="<?= $flagMode ? 'index.php' : 'index.php?flag-mode'?>" draggable="false"></a>
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
     *
     * @param int $ligne le numéro de la ligne
     * @param int $colonne le numéro de la colonne
     * @param bool $flagMode
     * @param string $class classe additionnelle de la case non découvert, utilisée pour les drapeaux
     */
    public function hiddenCase($ligne, $colonne, $flagMode, $class)
    {
        ?>
            <td class="n-decouvert <?= $class ?>">
                <a href="index.php?<?=$flagMode ? 'flag-mode&' : ''?>x=<?= $colonne ?>&y=<?= $ligne ?>" draggable="false"></a>
            </td>
        <?php
    }


    /**
     * Permet d'afficher une case découverte
     *
     * @param string $class nombre de mines
     * @param string $display le nombre de mine a afficher : ' ' si $class == 0, $class si $class > 0, '*' si est une mine
     */
    public function discoveredCase($class, $display)
    {
        ?> <td class="decouvert mine-<?= $class ?>"> <?= $display ?> </td> <?php
    }


    /**
     * Permet d'afficher une case non-découverte non-cliquable
     *
     * @param string $class classe additionnelle de la case non découvert, utilisée pour les drapeaux
     */
    public function noClickable($class)
    {
        ?> <td class="n-decouvert disabled-case <?= $class ?>"></td><?php
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
}
?>