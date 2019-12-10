<?php
require_once PATH_METIER . "/Message.php";

class Vue
{

    public function connexion($err)
    {
        header("Content-type: text/html; charset=utf-8");
        ?>
<html>

<body>

    <br />
    <br />
    <b><?=$err ? $err : ''?></b>
    <form method="post" action="index.php">
        Entrer votre pseudo <input type="text" name="pseudo" />
        </br>
        </br>
        <input type="submit" name="soumettre" value="envoyer" />
    </form>
    <br />
    <br />
    <?php
}
    public function interfaceMessage($pseudo, $messages)
    {
        header("Content-type: text/html; charset=utf-8");
        ?>
    <html>

    <body>

        <a href="index.php?deconnexion">Deconnexion</a>
        <h1>Bienvenue sur le salon, <?=$pseudo?></h1>
        <?php foreach ($messages as $msg): ?>
        <p><b><?=$msg->pseudo?></b>: <?=$msg->message?> </p>
        <?php endforeach;?>
        <form method="post" action="index.php">
            Entrer votre message <input type="text" autofocus name="message" />
            <input type="submit" name="soumettre" value="envoyer" />
        </form>
        <button href="#"
            onclick="var loc = window.location; window.location = loc.protocol + '//' + loc.host + loc.pathname + loc.search;">Recharger</button>
        <script>
        document.querySelectorAll('input').item(0).setAttribute("value", JSON.parse(localStorage.getItem('value')).c ||
            '');
        setCaretPosition(document.querySelectorAll('input').item(0), JSON.parse(localStorage.getItem('value')).s)
        setInterval(() => {
            localStorage.setItem('value', JSON.stringify({
                c: document.querySelectorAll('input').item(0).value,
                s: doGetCaretPosition(document.querySelectorAll('input').item(0))
            }));
            var loc = window.location;
            window.location = loc.protocol + '//' + loc.host + loc.pathname +
                loc.search;
        }, 1000)

        function setCaretPosition(ctrl, pos) {
            if (ctrl.setSelectionRange) {
                ctrl.focus();
                ctrl.setSelectionRange(pos, pos);
            } else if (ctrl.createTextRange) {
                var range = ctrl.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        }

        function doGetCaretPosition(ctrl) {
            var CaretPos = 0;

            if (ctrl.selectionStart || ctrl.selectionStart == 0) { // Standard.
                CaretPos = ctrl.selectionStart;
            } else if (document.selection) { // Legacy IE
                ctrl.focus();
                var Sel = document.selection.createRange();
                Sel.moveStart('character', -ctrl.value.length);
                CaretPos = Sel.text.length;
            }

            return (CaretPos);
        }
        </script>
        <?php
}
}