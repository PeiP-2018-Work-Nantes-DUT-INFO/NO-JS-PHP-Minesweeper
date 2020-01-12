<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

class VueAuthentification
{
    /**
     * Affiche la vue de connexion
     * 
     * @var mixed $err permet d'identifier si il y a erreur ou non
     */
    public function connexion($err)
    {
        header("Content-type: text/html; charset=utf-8"); ?>
        <html>
            <head>
                <link rel="stylesheet" href="assets/css/main.css">
                <link rel="stylesheet" href="assets/css/login.css">
                <link rel="stylesheet" href="assets/css/window.css">
                <link rel="icon" href="img/favicon.gif" type="image/gif">
            </head>
        <body class="log-page">

            <div class="popup logon">
                <div class="header">
                    <div class="title">Log On to Windows</div>
                    <div class="buttons">
                        <div class="btn hide-btn disable"></div>
                        <div class="btn resize-btn disable"></div>
                        <div class="btn close-btn"><a href="#"></a></div>
                    </div>
                </div>
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

            <?php if ($err) {
            $this->loadError();
        } ?>
        </body>
        </html>
        <?php
    }

    /**
     * Affiche une erreur de connexion sur la vue appelante
     */
    public function loadError()
    {
        ?>
            <div class="popup error">
            <div class="header">
                    <div class="title">Logon Message</div>
                    <div class="buttons">
                        <div class="btn info-btn"></div>
                        <div class="btn close-btn"><a href="index.php"></a></div>
                    </div>
                </div>
                <div class="content">
                    <img src="assets/img/warning_icon.png" alt="warning_icon">
                    <p>The system could not log you on. Make sure your User name is correct, then type your password again. Letters in passwords must be typed using the correct case.</p>
                </div>
                <div class="buttons">
                    <form action="index.php">
                        <button type="submit">OK</button>
                    </form>
                </div>
            </div>
        <?php
    }
}
?>