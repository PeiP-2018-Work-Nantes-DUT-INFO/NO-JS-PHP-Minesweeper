<?php
namespace Minesweeper\Modele\Exceptions;

use Exception;

// Classe generale de definition d'exception

class MonException extends Exception
{
    private $chaine;
    public function __construct($chaine)
    {
        parent::__construct($chaine);
        $this->chaine=$chaine;
    }

    public function afficher()
    {
        return $this->chaine;
    }
}
