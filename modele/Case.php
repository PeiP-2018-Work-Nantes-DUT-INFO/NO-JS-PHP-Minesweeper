<?php
class CaseMetier
{
    /**
     * Si vrai, la case est jouée
     *
     * @var boolean
     */
    public $jouee;

    /**
     * Donne le nombre de mines adjacentes
     *
     * @var int
     */
    public $nombreMinesAdjancentes;

    /**
     * Si est à vraie, la case est une mine
     *
     * @var boolean
     */
    public $estMine;


    public function __construct($jouee, $nombreMinesAdjancentes, $estMine)
    {
        $this->jouee = $jouee;
        $this->nombreMinesAdjancentes = $nombreMinesAdjancentes;
        $this->estMine = $estMine;
    }

    public function jouer()
    {
        if ($this->estMine) {
            $this->jouee = true;
        }
    }

    public function getMinesAdjacentes() : int
    {
        return $this->nombreMinesAdjancentes;
    }

    public function estUneMine() : bool
    {
        return $this->estMine;
    }

    public function estJouee() : bool {
        return $this->jouee;
    }
}
