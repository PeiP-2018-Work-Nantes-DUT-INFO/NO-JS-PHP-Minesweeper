<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

require_once PATH_VUE."/jeu.php";
require_once PATH_VUE.'/templates/headerPage.php';
require_once PATH_VUE.'/templates/footerPage.php';
require_once PATH_VUE.'/templates/winBar.php';

/**
 * Doit étendre VueJeu
 */
class VueResultat extends VueJeu
{
    /**
     * Permet d'afficher la vue des résultats
     * 
     * @param mixed[] $winners un tableau d'objets représentants des gagnants
     * @param string $pseudo identifiant du joueur
     * @param int $centaine la centaine de drapeaux restant
     * @param int $dizaine la dizaine de drapeaux restant
     * @param int $unite l'unité de drapeaux restant
     * @param boolean $perdu vrai si le jeu est perdu
     * @param boolean $gagne vrai si le jeu est gagné
     * @param \CaseMetier[][] $etatCases un tableau a 2 dimensions de Case
     * @param bool $flagMode si le on est en mode placement de drapeaux
     * @param int $nbrLignes nombre de lignes
     * @param int $nbrColonnes nombre de colonnes
     * @param int $difficulty la difficultée du niveau
     */
    public function afficherVueResultat($winners, $pseudo, ...$args)
    {
        headerPageJeu();
        call_user_func_array(array($this, "afficherPopupJeu"), $args);
        ?>
            <div class="popup won">
                <div class="header">
                    <div class="title">Best Mine Sweepers</div>
                    <div class="buttons">
                        <div class="btn info-btn"></div>
                        <div class="btn close-btn"><a href="index.php"></a></div>
                    </div>
                </div>
                <div class="content">
                    <div class="titles">
                        <p>Username</p>
                        <p>Wins</p>
                        <p>Plays</p>
                    </div>
                    <div class="scores">
                        <?php
                        $current_player = array_pop($winners);
                        foreach ($winners as $win) {
                            ?>
                            <div class="winner">
                                <p class="pseudo"><?= $win['pseudo'] ?></p>
                                <p class="wins"><?= $win['nbPartiesGagnees'] ?></p>
                                <p class="plays"><?= $win['nbPartiesJouees'] ?></p>
                            </div>
                            <?php
                        } ?>
                        <div class="winner">
                            <p class="pseudo">...</p>
                            <p class="wins">...</p>
                            <p class="plays">...</p>
                        </div>
                        <div class="winner">
                            <p class="pseudo"><?= $current_player['pseudo'] ?></p>
                            <p class="wins"><?= $current_player['nbPartiesGagnees'] ?></p>
                            <p class="plays"><?= $current_player['nbPartiesJouees'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <form method="post" action="index.php">
                        <button name="reset-scores" value="true"><u>R</u>eset Scores</button>
                    </form>
                    <form method="get">
                        <button formaction="?">OK</button>
                    </form>
                </div>
            </div>
        <?php
        afficherWinBar($pseudo);
        footerPageJeu();
    }
}