<?php

require_once PATH_VUE."/jeu.php";

class VueResultat
{
    
    public function afficherVueResultat($winners, ...$args)
    {
        $vueJeu = new VueJeu();
        call_user_func_array(array($vueJeu, "afficherVueJeu"), $args);
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
}