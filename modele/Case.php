<?php
class CaseMetier
{
    /**
     * Si vrai, la case est jouée
     *
     * @var boolean
     */
    private $jouee;

    /**
     * Donne le nombre de mines adjacentes
     *
     * @var int
     */
    private $nombreMinesAdjancentes;

    /**
     * Si est vraie, la case est une mine
     *
     * @var boolean
     */
    public $estMine;

    /**
     * Si est vraie, la case à un drapeau
     *
     * @var boolean
     */
    private $aDrapeau;


    public function __construct($jouee, $nombreMinesAdjancentes, $estMine)
    {
        $this->jouee = $jouee;
        $this->nombreMinesAdjancentes = $nombreMinesAdjancentes;
        $this->estMine = $estMine;
        $this->aDrapeau = false;
    }

    public function jouer()
    {
        $this->jouee = true;
    }

    public function getMinesAdjacentes() : int
    {
        return $this->nombreMinesAdjancentes;
    }

    public function estUneMine() : bool
    {
        return $this->estMine;
    }

    public function estJouee() : bool
    {
        return $this->jouee;
    }
    
    public function setDrapeau($etat)
    {
        $this->aDrapeau = $etat;
    }

    public function aDrapeau() : bool
    {
        return $this->aDrapeau;
    }
}
