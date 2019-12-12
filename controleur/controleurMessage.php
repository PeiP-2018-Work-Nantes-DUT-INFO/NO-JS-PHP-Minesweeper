<?php
require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/modele.php";

class ControleurMessage
{

    private $vue;
    private $modele;

    function __construct()
    {
        $this->vue=new Vue();
        $this->modele = new Modele();
    }

    function afficherInterfaceMessages($pseudo)
    {
        $msgs = $this->modele->get10RecentMessage();
        $msgArray = [];
        foreach ($msgs as $messageContent) {
            $msgArray[] = $messageContent;
        }
        $this->vue->interfaceMessage($pseudo, $msgArray);
    }

    function envoyerMessage($pseudo, $message)
    {
        $this->modele->majSalon($pseudo, $message);
        $this->afficherInterfaceMessages($pseudo);
    }
}
