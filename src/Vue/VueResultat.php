<?php
namespace Minesweeper\Vue;

/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

use Minesweeper\Vue\VueJeu;

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
     */
    public function afficherVueResultat($winners, $pseudo, ...$args)
    {
        headerPageJeu();
        call_user_func_array(array($this, "afficherPopupJeu"), $args); ?>
            <div class="popup won">
                <div class="header">
                    <div class="title">Best Mine Sweepers</div>
                    <div class="buttons">
                        <div class="btn info-btn disable"></div>
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
                            if ($win['pseudo'] == $current_player[0]['pseudo']) {
                                ?>
                                <div class="winner">
                                    <p class="pseudo"><b><?= $current_player[0]['pseudo'] ?></b></p>
                                    <p class="wins"><b><?= $current_player[0]['nbPartiesGagnees'] ?></b></p>
                                    <p class="plays"><b><?= $current_player[0]['nbPartiesJouees'] ?></b></p>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="winner">
                                    <p class="pseudo"><?= $win['pseudo'] ?></p>
                                    <p class="wins"><?= $win['nbPartiesGagnees'] ?></p>
                                    <p class="plays"><?= $win['nbPartiesJouees'] ?></p>
                                </div>
                                <?php
                            }
                            array_pop($win);
                        }
                        if (($current_player != null) && (!in_array($current_player[0]['pseudo'], array_column($winners, 'pseudo')))) {
                            ?>
                        <div class="winner">
                            <p class="pseudo">...</p>
                            <p class="wins">...</p>
                            <p class="plays">...</p>
                        </div>
                        <div class="winner">
                            <p class="pseudo"><b><?= $current_player[0]['pseudo'] ?></b></p>
                            <p class="wins"><b><?= $current_player[0]['nbPartiesGagnees'] ?></b></p>
                            <p class="plays"><b><?= $current_player[0]['nbPartiesJouees'] ?></b></p>
                        </div>
                            <?php
                        } ?>
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
