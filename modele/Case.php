<?php 
class CaseMetier
{
    public $jouee;
    public $nombreMinesAdjancentes;
    public $x;
    public $y;
    public function __construct($jouee, $nombreMinesAdjancentes, $x, $y)
    {
        $this->jouee = $jouee;
        $this->nombreMinesAdjancentes = $nombreMinesAdjancentes;
        $this->x = $x;
        $this->y = $y;
    }
}