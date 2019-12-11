<?php

class VueAuthentification
{
    /**
     * Affiche la vue de connexion
     * @var mixed $err permet d'identifier si il y a erreur ou non
     */
    public function connexion($err)
    {
        header("Content-type: text/html; charset=utf-8"); ?>
        <html>
            <head>
                <link rel="stylesheet" href="assets/login.css">
                <link rel="icon" href="favicon.gif" type="image/gif">
            </head>
        <body>

            <div class="logon popup">
                <div class="header">Log On to Windows</div>
                <div class="winxp-bg"></div>

                <div class="content">
                    <form method="post" action="index.php">
                        <div class="field">
                            <label for="username">User name:</label>
                            <input type="text" name="username" id="username">
                        </div>
                        <div class="field">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password">
                        </div>
                        <div class="buttons">
                            <input type="submit" name="submit" id="submit" value="OK">
                            <input type="reset" name="cancel" value="Cancel">
                            <button>Options >></button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($err) {$this->loadError();} ?>
        </body>
        </html>
    <?php
    }

    /**
     * Affiche une erreur de connexion sur la vue appelante
     */
    public function loadError() {
        ?>
            <div class="error popup">
                <div class="header">Logon Message</div>
                <div class="content">
                    <img src="assets/warning_icon.png" alt="warning_icon">
                    <p>The system could not log you on. Make sure your User name is correct, then type your password again. Letters in passwords must be typed using the correct case.</p>
                </div>
            </div>
        <?php
    }
}
?>