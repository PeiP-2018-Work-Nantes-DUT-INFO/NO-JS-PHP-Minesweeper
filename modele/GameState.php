<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

require_once 'Case.php';


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
     * Si a vrai, le jeu est perdu
     *
     * @var boolean
     */
    private $estPerdu;
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
     * @return boolean vrai si le mouvement fait perdre
     */
    public function jouer($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y)) {
            $this->caseRestantes--;
            $this->etatCaseJeu[$x][$y]->jouer();
            if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0) {
                $this->jouerCaseAdjacentes($x, $y);
            }
            if ($this->testerCase($x, $y)) {
                $this->etatCaseJeu[$x][$y]->surbriller();
                $this->revelerMines();
                $this->estPerdu = true;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Révèle toutes les mines en fin de jeu
     *
     * @return void
     */
    private function revelerMines()
    {
        foreach ($this->etatCaseJeu as $ligne) {
            foreach ($ligne as $case) {
                if ($case->estUneMine()) {
                    $case->jouer();
                }
            }
        }
    }
    /**
     * Permet de jouer les cases adjacentes d'une case donnée.
     *
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     * @return void
     */
    private function jouerCaseAdjacentes($x, $y)
    {
        if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0 && !$this->etatCaseJeu[$x][$y]->estUneMine()) {
            $casesAJouer = array_fill(0, NBR_LIGNES, []);
            for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                    if (isset($this->etatCaseJeu[$i][$j]) && !$this->etatCaseJeu[$i][$j]->estUneMine()) {
                        $casesAJouer[$i][$j] =true;
                    }
                }
            }
            foreach ($casesAJouer as $x => $ligne) {
                foreach (array_keys($ligne) as $y) {
                    $this->jouer($x, $y);
                }
            }
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
     * @return boolean vrai si la case n'a pas déjà été jouée, qu'elle est valide et que le jeu n'est pas perdu
     */
    public function mouvementPossible($x, $y): bool
    {
        return isset($this->etatCaseJeu[$x][$y]) && !$this->etatCaseJeu[$x][$y]->estJouee() &&!$this->estPerdu;
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

    /**
     * Permet de savoir si le jeu est perdu
     *
     * @return boolean Vrai si le jeu est perdu
     */
    public function estPerdu()
    {
        return $this->estPerdu;
    }

    /**
     * Permet de savoir si le jeu a été commencé
     *
     * @return boolean vrai si au moins une case a été jouée
     */
    public function estCommence()
    {
        return $this->caseRestantes != NBR_COLONNES * NBR_LIGNES;
    }
}
