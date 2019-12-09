<?php

class Jeu
{

    public function interfaceMessage($pseudo, $messages)
    {
        header("Content-type: text/html; charset=utf-8");
        ?>

        <html>
        <body>
            <?php foreach ($messages as $msg): ?>
            <p><b><?=$msg->pseudo?></b>: <?=$msg->message?> </p>
            <?php endforeach;?>
        </body>
        </html>
    <?php
    }
}
?>