<?php

require_once PATH_VUE."/jeu.php";
require_once PATH_VUE.'/bandeau/bandeauJeu.php';
require_once PATH_VUE.'/bandeau/piedDePageJeu.php';

/**
 * Doit étendre VueJeu
 */
class VueResultat extends VueJeu
{

    public function afficherVueResultat($winners, $pseudo, ...$args)
    {
        headerPageJeu($pseudo);
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
                        foreach ($winners as $win) {
                            ?>
                            <div class="winner">
                                <p class="pseudo"><?= $win['pseudo'] ?></p>
                                <p class="wins"><?= $win['nbPartiesGagnees'] ?></p>
                                <p class="plays"><?= $win['nbPartiesJouees'] ?></p>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
                <div class="buttons">
                        <button><u>R</u>eset Scrores</button>
                        <button formaction="index.php">OK</button>
                    </div>
            </div>
        <?php
        footerPageJeu();
    }
}