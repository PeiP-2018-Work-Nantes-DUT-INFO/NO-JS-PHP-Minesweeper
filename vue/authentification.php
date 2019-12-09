<?php

class Authentification
{
    public function demandePseudo($err)
    {
        header("Content-type: text/html; charset=utf-8"); ?>
        <html>
            <head>
                <link rel="stylesheet" href="assets/login.css">
            </head>
        <body>

            <div class="logon">
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


    public function loadError() {
        ?>
        <html>
        <body>
            <div class="error">
                <div class="header">Logon Message</div>
                <div class="content">
                    <img src="#" alt="warning_icon">
                    <p>The system could not log you on. Make sure your User name is correct, then type your password again. Letters in passwords must be typed using the correct case.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
?>