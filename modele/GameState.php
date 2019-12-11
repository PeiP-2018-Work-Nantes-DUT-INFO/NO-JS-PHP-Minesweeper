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
     * @var int
     */
    private $joueurId;

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
     * @param int $joueurId identifiant du joueur
     */
    public function __construct($joueurId)
    {
        $mines = $this->genererMines();
        $this->etatCaseJeu = array_fill(0, NBR_LIGNES, array_fill(0, NBR_COLONNES, new CaseMetier(false, 0, false)));
        for ($i = 0; $i < NBR_LIGNES; $i++) {
            for ($j = 0; $j < NBR_COLONNES; $j++) {
                $this->etatCaseJeu[$i][$j] = new CaseMetier(
                    false,
                    $this->compterMines($mines, $i, $j),
                    isset($mines[$i][$j])
                );
            }
        }
        $this->joueurId = $joueurId;
        $this->caseRestantes = NBR_COLONNES * NBR_LIGNES;
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
                    if (!$this->etatCaseJeu[$i][$j]->estUneMine()) {
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
     * @param bool[][] $mines tableau des mines
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     * @return integer le nombre de mines adjacentes.
     */
    private function compterMines($mines, $x, $y): int
    {
        $nbrMines = 0;
        for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
            for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                if (isset($mines[$i]) && $mines[$i] == $j) {
                    $nbrMines++;
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
