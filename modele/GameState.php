<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

require_once 'Case.php';

 /**
  * Nombre de colonnes du demineur
  */
define("NBR_COLONNES", 8);

/**
 * Nombre de lignes du demineur
 */
define("NBR_LIGNES", 8);

/**
 * Nombre de mines contenues dans le demineur
 */
define("NBR_MINES", 10);

/**
 * Class content l'état de jeu
 */
class GameState
{
    /**
     * Contient l'état des cases
     *
     * @var \CaseMetier[][]
     */
    private $etatCaseJeu;

    /**
     * Identifiant du joueur en train de jouer
     *
     * @var string
     */
    private $pseudoJoueur;

    /**
     * Nombre de case restantes
     * @var int
     */
    private $caseRestantes;

    /**
     * Represente le nombre de drapeau poses
     *
     * @var int
     */
    private $drapeauxPosees;

    /**
     * Initialise le jeu et génère les mines.
     * @param string $pseudoJoueur identifiant du joueur
     */
    public function __construct($pseudoJoueur)
    {
        $mines = $this->genererMines();
        $this->etatCaseJeu = array_fill(0, NBR_LIGNES, array_fill(0, NBR_COLONNES, new CaseMetier(false)));
        for ($i = 0; $i < NBR_LIGNES; $i++) {
            for ($j = 0; $j < NBR_COLONNES; $j++) {
                $this->etatCaseJeu[$i][$j] = new CaseMetier(
                    isset($mines[$i][$j])
                );
            }
        }
        $this->pseudoJoueur = $pseudoJoueur;
        $this->caseRestantes = NBR_COLONNES * NBR_LIGNES;
        $this->compterMines($mines);
    }

    /**
     * Permet de genérer les mines
     * @return bool[][] un tableau d'entier associant à un entier (le numéro de ligne) un autre entier (le numéro de colonne de la ligne)
     */
    private function genererMines()
    {
        $mines = array_fill(0, NBR_LIGNES, []);
        $i = 0;
        while ($i < NBR_MINES) {
            $x = rand(0, 7);
            $y = rand(0, 7);
            if (!isset($mines[$x][$y])) {
                $mines[$x][$y] = true;
                $i++;
            }
        }
        return $mines;
    }

    /**
     * Permet de jouer
     *
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     * @return boolean vrai si le mouvement est possible, faux sinon
     */
    public function jouer($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y)) {
            $this->caseRestantes--;
            $this->etatCaseJeu[$x][$y]->jouer();
            if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0) {
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
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     * @return void
     * @throws Exception Si la case à jouer à une mine adjacente.
     */
    private function jouerCaseAdjacentes($x, $y)
    {
        if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0) {
            $casesAJouer = [];
            for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                    if (isset($this->etatCaseJeu[$i][$j]) && !$this->etatCaseJeu[$i][$j]->estUneMine()) {
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
     * Incrémente les cases adjacentes aux mines
     * @param bool[][] $mines tableau des mines
     * @returnvoid
     */
    private function compterMines($mines)
    {
        $nbrMines = 0;
        foreach ($mines as $x => $arr) {
            foreach (array_keys($arr) as $y) {
                for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                    for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                        if (isset($this->etatCaseJeu[$i][$j]) && !$this->etatCaseJeu[$i][$j]->estUneMine()) {
                            $this->etatCaseJeu[$i][$j]->incrementerCompteurMine();
                        }
                    }
                }
            }
        }

        return $nbrMines;
    }
    
    /**
     * Permet de tester si un mouvement est possible
     *
     * @param int $x numéro de la colonne
     * @param int $y numéro de la ligne
     * @return boolean vrai si la case n'a pas déjà été jouée
     */
    public function mouvementPossible($x, $y): bool
    {
        return !$this->etatCaseJeu[$x][$y]->estJouee();
    }

    /**
     * Permet de tester si un mouvement fait perdre le jeu
     *
     * @param int $x numéro de la colonne
     * @param int $y numéro de la ligne
     * @return boolean vrai si le mouvement joué contient une mine
     */
    public function testerCase($x, $y): bool
    {
        if ($this->etatCaseJeu[$x][$y]->estUneMine()) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Obtient le nombre de drapeau restants
     * Ce chiffre peut être négatif
     *
     * @return integer
     */
    public function drapeauxRestants() : int
    {
        return NBR_MINES - $this->drapeauxPosees;
    }

    /**
     * Pose un drapeau sur une case
     *
     * @param int $x numéro de la colonne
     * @param int $y numéro de la ligne
     * @return boolean vrai si le drapeau a été pausé
     */
    public function poserDrapeau($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y) && !$this->etatCaseJeu[$x][$y]->aDrapeau()) {
            $this->etatCaseJeu[$x][$y]->setDrapeau(true);
            $this->drapeauxPosees++;
        } else {
            return false;
        }
    }

    /**
     * Retire un drapeau d'une case
     *
     * @param int $x numéro de la colonne
     * @param int $y numéro de la ligne
     * @return boolean vrai si le drapeau a été retiré
     */
    public function retirerDrapeau($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y) && $this->etatCaseJeu[$x][$y]->aDrapeau()) {
            $this->etatCaseJeu[$x][$y]->setDrapeau(false);
            $this->drapeauxPosees--;
        } else {
            return false;
        }
    }

    /**
     * Obtient l'etat du jeu
     *
     * @return \CaseMetier[][]
     */
    public function obtenirEtatJeu(): array
    {
        return $this->etatCaseJeu;
    }
}
