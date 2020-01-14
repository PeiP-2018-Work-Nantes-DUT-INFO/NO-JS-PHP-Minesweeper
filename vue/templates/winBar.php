<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

/**
 * Permet d'afficher la barre windows
 * 
 * @param string $pseudo identifiant du joueur
 */
function afficherWinBar($pseudo)
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
                <div class="text">
                    <div class="pseudo"><?= $pseudo ?></div>
                    <div class="date">
                    <?php
                        $utc1 = 3600;
                        echo gmdate('g:i A', time()+($utc1));
                    ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
}