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
     * @param int[] $chiffresTimer les centaines dizaines unite du timer
     * @param boolean $animer si le timer doit s'animer
     * @param boolean $son si le son doit être joué
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
        $difficulty,
        $chiffresTimer,
        $animer,
        $son
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
            $difficulty,
            $chiffresTimer,
            $animer,
            $son
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
     * @param int[] $chiffresTimer les centaines dizaines unite du timer
     * @param boolean $animer si le timer doit s'animer
     * @param boolean $son si le son doit être joué
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
        $difficulty,
        $chiffresTimer,
        $animer,
        $son
    ) {
        $this->sonWave($son, $animer, $gagne, $perdu);
        if ($perdu) {
            $this->headerMinesweeper(
                $centaine,
                $dizaine,
                $unite,
                'dead',
                $flagMode,
                $difficulty,
                $chiffresTimer,
                $animer,
                $son
            );
        } elseif ($gagne) {
            $this->headerMinesweeper(
                $centaine,
                $dizaine,
                $unite,
                'boss',
                $flagMode,
                $difficulty,
                $chiffresTimer,
                $animer,
                $son
            );
        } else {
            $this->headerMinesweeper(
                $centaine,
                $dizaine,
                $unite,
                'smile',
                $flagMode,
                $difficulty,
                $chiffresTimer,
                $animer,
                $son
            );
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
     * Ajoute les balises audio
     *
     * @param boolean $active
     * @param boolean $estCommencé
     * @param boolean $estGagne
     * @param boolean $estPerdu
     * @return void
     */
    public function sonWave($active, $estCommencé, $estGagne, $estPerdu)
    {
        if (!$active) {
            return;
        } ?>
        <?php if ($estCommencé) :?>
<audio loop autoplay>
    <source src="assets/sounds/beep.ogg" type="audio/ogg">
</audio>
        <?php endif?>

        <?php if ($estGagne) :?>
<audio autoplay>
    <source src="assets/sounds/win.ogg" type="audio/ogg">
</audio>
        <?php endif?>

        <?php if ($estPerdu) :?>
<audio autoplay>
    <source src="assets/sounds/game_over.ogg" type="audio/ogg">
</audio>
        <?php endif?>
        <?php
    }
    public function genererStyleTimer($chiffresTimer)
    {
        ?>
<style>
.minesweeper .game_window .head .clock-group div.centaine.animate {
    -webkit-animation: counter 1000s infinite steps(10) forwards, partcountercentaines <?=1000 - (($chiffresTimer[0]+1)%10) * 100?>s steps(<?=10 - (($chiffresTimer[0]+1)%10)?>), partcountercentainesfirststage <?=100-$chiffresTimer[1]*10 - $chiffresTimer[2]?>s steps(1);
    animation: counter 1000s infinite steps(10) forwards, partcountercentaines <?=1000- (($chiffresTimer[0]+1)%10) * 100?>s steps(<?=10 - (($chiffresTimer[0]+1)%10)?>), partcountercentainesfirststage <?=100-$chiffresTimer[1]*10 - $chiffresTimer[2]?>s steps(1);
    animation-delay: <?=1000 - $chiffresTimer[0] * 100 - $chiffresTimer[1] * 10 - $chiffresTimer[2]?>s, <?=100-$chiffresTimer[1]*10 - $chiffresTimer[2]?>s, 0s;
}

.minesweeper .game_window .head .clock-group div.dizaine.animate {
    -webkit-animation: counter 100s infinite steps(1) forwards, partcounterdizaines <?=100 - (($chiffresTimer[1]+1)%10) * 10?>s steps(<?=10 - (($chiffresTimer[1]+1)%10)?>), partcounterdizainesfirststage <?=10-$chiffresTimer[2]?>s steps(1);
    animation: counter 100s infinite steps(10) forwards, partcounterdizaines <?=100 - (($chiffresTimer[1]+1)%10) * 10?>s steps(<?=10 - (($chiffresTimer[1]+1)%10)?>), partcounterdizainesfirststage <?=10-$chiffresTimer[2]?>s steps(1);
    animation-delay: <?=100 - $chiffresTimer[1] * 10 - $chiffresTimer[2]?>s, <?=10 - $chiffresTimer[2]?>s, 0s;
}

.minesweeper .game_window .head .clock-group div.unite.animate {
    -webkit-animation: counter 10s infinite steps(10) forwards, partcounterunites <?=10-$chiffresTimer[2]?>s steps(<?=10-$chiffresTimer[2]?>);
    animation: counter 10s infinite steps(10) forwards, partcounterunites <?=10-$chiffresTimer[2]?>s steps(<?=10-$chiffresTimer[2]?>);
    animation-delay: <?=10 - $chiffresTimer[2]?>s, 0s;

}

@keyframes partcounterunites {
    0% {
        -webkit-transform: translateY(-<?=$chiffresTimer[2] * 10?>%);
        transform: translateY(-<?=$chiffresTimer[2] * 10?>%);
    }

    100% {
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%);
    }
}

@keyframes partcounterdizaines {
    0% {
        -webkit-transform: translateY(-<?=(($chiffresTimer[1]+1)%10) * 10?>%);
        transform: translateY(-<?=(($chiffresTimer[1]+1)%10) * 10?>%);
    }

    100% {
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%);
    }
}

@keyframes partcounterdizainesfirststage {
    0% {
        -webkit-transform: translateY(-<?=$chiffresTimer[1] * 10?>%);
        transform: translateY(-<?=$chiffresTimer[1] * 10?>%);
    }

    100% {
        -webkit-transform: translateY(-<?=(($chiffresTimer[1]+1)%10) * 10?>%);
        transform: translateY(-<?=(($chiffresTimer[1]+1)%10) * 10?>%);
    }
}

@keyframes partcountercentaines {
    0% {
        -webkit-transform: translateY(-<?=(($chiffresTimer[0]+1)%10) * 10?>%);
        transform: translateY(-<?=(($chiffresTimer[0]+1)%10) * 10?>%);
    }

    100% {
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%);
    }
}

@keyframes partcountercentainesfirststage {
    0% {
        -webkit-transform: translateY(-<?=$chiffresTimer[0] * 10?>%);
        transform: translateY(-<?=$chiffresTimer[0] * 10?>%);
    }

    100% {
        -webkit-transform: translateY(-<?=(($chiffresTimer[0]+1)%10) * 10?>%);
        transform: translateY(-<?=(($chiffresTimer[0]+1)%10) * 10?>%);
    }
}
</style>
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
     * @param int[] $chiffresTimer les centaines dizaines unite du timer
     * @param boolean $animer si le timer doit s'animer
     * @param boolean $son si le son est activé
     */
    public function headerMinesweeper(
        $centaine,
        $dizaine,
        $unite,
        $smiley,
        $flagMode,
        $difficulty,
        $chiffresTimer,
        $animer,
        $son
    ) {
        ?>
<div class="popup minesweeper">
        <?php $this->genererStyleTimer($chiffresTimer) ?>
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
                    <li><a href="index.php?reset">
                            <div class="w18"></div>
                            <p>New</p>
                        </a></li>
                    <li class="separator"></li>
                    <li><a href="<?= $difficulty !== 0 ? 'index.php?difficulty=default': '#'?>">
                            <div class="w18 <?= $difficulty === 0? 'checked': ''?>"></div>
                            <p>Beginner</p>
                        </a></li>
                    <li><a href="<?= $difficulty !== 1 ? 'index.php?difficulty=1': '#'?>">
                            <div class="w18 <?= $difficulty === 1? 'checked': ''?>"></div>
                            <p>Intermediate</p>
                        </a></li>
                    <li><a href="<?= $difficulty !== 2 ? 'index.php?difficulty=2': '#'?>">
                            <div class="w18 <?= $difficulty === 2? 'checked': ''?>"></div>
                            <p>Expert</p>
                        </a></li>
                    <li class="separator"></li>
                    <li><a href="#">
                            <div class="w18"></div>
                            <p>Marks (?)</p>
                        </a></li>
                    <li><a href="#">
                            <div class="w18 checked"></div>
                            <p>Color</p>
                        </a></li>
                    <li><a href="index.php?sound">
                            <div class="w18 <?= $son ? 'checked': ''?>"></div>
                            <p>Sound</p>
                        </a></li>
                    <li class="separator"></li>
                    <li><a href="index.php?scores">
                            <div class="w18"></div>
                            <p>Best Players...</p>
                        </a></li>
                    <li class="separator"></li>
                    <li><a href="index.php?deconnexion">
                            <div class="w18"></div>
                            <p>Exit</p>
                        </a></li>
                </ul>
            </li>
            <li id="help"><u>H</u>elp
                <ul class="dropdown-menu">
                    <li><a href="https://github.com/PeiP-2018-Work-Nantes-DUT-INFO/NO-JS-PHP-Minesweeper">
                            <div class="w18"></div>
                            <p>Github</p>
                        </a></li>
                    <li><a href="https://github.com/search/advanced?q=">
                            <div class="w18"></div>
                            <p>Search for Help on ...</p>
                        </a></li>
                    <li><a href="https://github.com/PeiP-2018-Work-Nantes-DUT-INFO/NO-JS-PHP-Minesweeper">
                            <div class="w18"></div>
                            <p>Using Help</p>
                        </a></li>
                    <li class="separator"></li>
                    <li><a href="index.php?credits">
                            <div class="w18"></div>
                            <p>Credits</p>
                        </a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="content" id="game_container">
        <div class="game_window">
            <div class="head box-shadow">
                <div class="display-bomb" style="background-image: url(assets/img/display.png);">
                    <div class="bomb centaine" style="background-image: url(assets/img/display<?= $centaine ?>.png);">
                    </div>
                    <div class="bomb dizaine" style="background-image: url(assets/img/display<?= $dizaine ?>.png);">
                    </div>
                    <div class="bomb unite" style="background-image: url(assets/img/display<?= $unite ?>.png);">
                    </div>
                </div>
                <div class="n-decouvert" id="<?= $smiley ?>">
                    <a href="index.php?reset" draggable="false"></a>
                </div>
                <div class="clock-group">
                    <div class="w41">
                        <div class="<?= $flagMode ? '' : 'n-'?>decouvert" id="flag">
                            <a href="<?= $flagMode ? 'index.php' : 'index.php?flag-mode'?>" draggable="false"></a>
                        </div>
                        <div class="display-bomb" style="background-image: url(assets/img/display.png);">
                            <div class="bomb centaine <?= $animer ?  'animate' : ''?>"
                                style="top:-<?= $chiffresTimer[0] * 0?>px;">
                            </div>
                            <div class="bomb dizaine <?= $animer ?  'animate' : ''?>"
                                style="top: -<?= $chiffresTimer[1] * 0?>px;">
                            </div>
                            <div class="bomb unite <?= $animer ?  'animate' : ''?>"
                                style="top: -<?= $chiffresTimer[2] * 0?>px;">
                            </div>
                        </div>
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
                            <a href="index.php?<?=$flagMode ? 'flag-mode&' : ''?>x=<?= $colonne ?>&y=<?= $ligne ?>"
                                draggable="false"></a>
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