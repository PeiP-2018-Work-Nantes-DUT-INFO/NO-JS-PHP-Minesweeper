<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr>
 */
define("NBR_COLONNES", 8);
define("NBR_LIGNES", 8);
define("NBR_MINES", 10);

/**
 * Class content l'état de jeu
 */
class GameState
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $etatCaseJeu;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $mines;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $joueurId;

    /**
     * Undocumented variable
     * @var [type]
     */
    private $caseRestantes;

    /**
     * Initialise le jeu et génère les mines.
     * @param [int] $joueurId identifiant du joueur
     */
    public function __construct($joueurId)
    {
        $this->etatCaseJeu = array_fill(0, NBR_COLONNES, array_fill(0, NBR_LIGNES, true));
        $this->mines = [];
        $this->genererMines();
        $this->joueurId = $joueurId;
        $this->caseRestantes = NBR_COLONNES * NBR_LIGNES;
        
    }
    
    public function compterToutesLesMines() {
        for($i = 0; $i < NBR_COLONNES; $i++) {
            for($j = 0; $j < NBR_LIGNES; $j++) {
                
            }
        }
    }
    /**
     * Permet de genérer les mines
     * @return void
     */
    private function genererMines()
    {
        $i = 0;
        while ($i < NBR_MINES) {
            $x = rand(0, 7);
            $y = rand(0, 7);
            if (!$this->mines[$x] == $y) {
                $this->mines[$x] = $y;
                $i++;
            }
        }
    }

    /**
     * Permet de jouer
     *
     * @param [int] $x numéro de la colonne de la case
     * @param [int] $y numéro de la ligne de la case
     * @return boolean vrai si le mouvement est possible, faux sinon
     */
    public function jouer($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y)) {
            $this->caseRestantes--;
            $this->etatCaseJeu[$x][$y] = false;
            if ($this->compterMines($x, $y) == 0) {
                $this->jouerCaseAdjacentes($x, $y);
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Permet de jouer les cases adjacentes d'une case donnée.
     *
     * @param [type] $x numéro de la colonne de la case
     * @param [type] $y numéro de la ligne de la case
     * @return void
     * @throws Exception Si la case à jouer à une mine adjacente.
     */
    private function jouerCaseAdjacentes($x, $y)
    {
        if ($this->compterMines($x, $y) == 0) {
            $casesAJouer = [];
            for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                    if (isset($this->mines[$i][j])) {
                        $casesAJouer[$i] =$j;
                    }
                }
            }
            foreach ($casesAJouer as $x => $y) {
                $this->jouer($x, $y);
            }
        } else {
            throw new Exception("Impossible de jouer les cases adjacentes");
        }
    }

    /**
     * Permet de tester, **dans l'état actuel**, si le joueur à gagner
     *
     * @return boolean vrai si le joueur à gagner
     */
    public function aGagne() : bool
    {
        return $this->caseRestantes == NBR_MINES;
    }

    /**
     * Compte les mines adjacentes à la case
     *
     * @param [int] $x numéro de la colonne de la case
     * @param [int] $y numéro de la ligne de la case
     * @return integer le nombre de mines adjacentes.
     * @throws Exception si la case donnée est une mine
     */
    private function compterMines($x, $y): int
    {
        $nbrMines = 0;
        if ($this->mouvementPossible($x, $y)) {
            for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                    if (isset($this->mines[$i]) && $this->mines[$i] == $j) {
                        $nbrMines++;
                    }
                }
            }
            return $nbrMines;
        } else {
            throw new Exception("La case testée pour le comptage de mine est une mine");
        }
    }
    /**
     * Permet de tester si un mouvement est possible
     *
     * @param [type] $x numéro de la colonne
     * @param [type] $y numéro de la ligne
     * @return boolean vrai si la case n'a pas déjà été jouée
     */
    public function mouvementPossible($x, $y): bool
    {
        return $this->etatCaseJeu[$x][$y];
    }

    /**
     * Permet de tester si un mouvement fait perdre le jeu
     *
     * @param [type] $x numéro de la colonne
     * @param [type] $y numéro de la ligne
     * @return boolean vrai si le mouvement joué contient une mine
     */
    public function testerCase($x, $y): bool
    {
        if ($this->mines[$x] == $y) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenirEtatJeu(): array {
        foreach()
        return [];
    }
}
