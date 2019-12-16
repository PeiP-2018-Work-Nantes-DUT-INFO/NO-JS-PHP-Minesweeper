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

    /**
     * Si est a vraie, indique que l'utilisateur vient de cliquer sur la case
     * Cela est utilisé pour colorer en rouge une mine lorsque la partie est perdu
     * Selon Mr Berdujgin, concerne l'affichage
     * @var boolean
     */
    private $estEnSurbrillance;

    public function __construct($estMine)
    {
        $this->jouee = false;
        $this->nombreMinesAdjancentes = 0;
        $this->estMine = $estMine;
        $this->aDrapeau = false;
    }

    public function jouer()
    {
        $this->jouee = true;
    }
    public function incrementerCompteurMine()
    {
        $this->nombreMinesAdjancentes++;
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

    public function surbriller()
    {
        $this->estEnSurbrillance = true;
    }

    public function getSurbrillance()
    {
        return $this->estEnSurbrillance;
    }

    /**
     * Permet de savoir si la mine doit être affichée "barrée".
     * Dans le minesweeper original, lorsque le jeu est perdu,
     * Dans le jeu original, lorsque le joueur clique sur une mine,
     * toutes les case portant un drapeau, qui ne sont pas une mine,
     * et qui sont adjacentes à une mine, sont révélées avec un icone "mine barré"
     * indiquant que le joueur s'est trompé
     *
     * @return boolean
     */
    public function estMineBarree(): bool
    {
        return $this->getMinesAdjacentes() > 0 && !$this->estUneMine() && $this->aDrapeau();
    }
}
