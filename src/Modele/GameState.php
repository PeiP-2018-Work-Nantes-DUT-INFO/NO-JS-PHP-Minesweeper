<?php
namespace Minesweeper\Modele;

use DateTime;

/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */


/**
 * Class qui contient l'état de jeu d'une partie
 */
class GameState
{
    /**
     * Contient l'état des cases
     *
     * @var CaseMetier[][]
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
     * Nombre de colonnes du jeu
     * @var int
     */
    private $nbrColonnes;

    /**
     * Nombre de lignes du jeu
     * @var int
     */
    private $nbrLignes;

    /**
     * Nombre de mines du jeu
     *
     * @var int
     */
    private $nbrMines;

    /**
     * Date de démarrage de la partie
     *
     * @var DateTime
     */
    private $dateDemarrage;

    /**
     * Date de fin du jeu
     *
     * @var DateTime
     */
    private $dateFin;


    /**
     * Initialise le jeu et génère les mines.
     *
     * @param string $pseudoJoueur identifiant du joueur
     * @param int $nbrColonnes Nombre de colonnes du jeu
     * @param int $nbrLignes Nombre de lignes du jeu
     * @param int $nbrMines Nombre de mines du jeu
     */
    public function __construct($pseudoJoueur, $nbrColonnes, $nbrLignes, $nbrMines)
    {
        $this->nbrColonnes = $nbrColonnes;
        $this->nbrLignes = $nbrLignes;
        $this->nbrMines = $nbrMines;
        $this->etatCaseJeu = array_fill(0, $nbrColonnes, array_fill(0, $this->nbrLignes, new CaseMetier(false)));
        $this->pseudoJoueur = $pseudoJoueur;
        $this->caseRestantes = $nbrColonnes * $nbrLignes;
    }


    /**
     * Permet de genérer les mines
     *
     * @param int $xPlayed Position en x de la première case jouée
     * @param int $yPlayed Position en y de la première case jouée
     * @return bool[][] un tableau d'entier associant à un entier (le numéro de ligne) un autre entier (le numéro de colonne de la ligne)
     */
    private function genererMines($xPlayed, $yPlayed)
    {
        $mines = array_fill(0, $this->nbrColonnes, []);
        $i = 0;
        while ($i < $this->nbrMines) {
            $y = rand(0, $this->nbrLignes - 1);
            $x = rand(0, $this->nbrColonnes - 1);
            if ($x !== $xPlayed && $y !== $yPlayed && !isset($mines[$x][$y])) {
                $mines[$x][$y] = true;
                $i++;
            }
        }
        return $mines;
    }

    /**
     * Initialise les cases et les mines
     * Ne place pas de mine sur la première case jouée
     *
     * @param int $x Coordonée en x de la première case jouée
     * @param int $y Coordonée en y de la première case jouée
     * @return void
     */
    private function initialiserCases($x, $y)
    {
        $this->dateDemarrage = new DateTime();
        $mines = $this->genererMines($x, $y);
        for ($i = 0; $i < $this->nbrColonnes; $i++) {
            for ($j = 0; $j < $this->nbrLignes; $j++) {
                $this->etatCaseJeu[$i][$j] = new CaseMetier(
                    isset($mines[$i][$j])
                );
            }
        }
        $this->compterMines($mines);
    }

    /**
     * Permet de jouer une case
     *
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     * @return boolean vrai si le mouvement fait perdre
     */
    public function jouer($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y) && !$this->etatCaseJeu[$x][$y]->aDrapeau()) {
            if (!$this->estCommence()) {
                $this->initialiserCases(intval($x), intval($y));
            }
            $this->etatCaseJeu[$x][$y]->jouer();
            $this->caseRestantes--;
            if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0) {
                $this->jouerCaseAdjacentes($x, $y);
            }
            if ($this->testerCase($x, $y)) {
                $this->etatCaseJeu[$x][$y]->surbriller();
                $this->revelerMines();
                $this->estPerdu = true;
                $this->dateFin = new DateTime();
                return true;
            } else {
                if ($this->aGagne()) {
                    $this->dateFin = new DateTime();
                }
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
        $casesAReveler= [[$x, $y]];
        $casesAJouer = array_fill(0, $this->nbrColonnes, []);
        while (count($casesAReveler) > 0) {
            [$x, $y] = array_pop($casesAReveler);
            if ($this->etatCaseJeu[$x][$y]->getMinesAdjacentes() === 0 && !$this->etatCaseJeu[$x][$y]->estUneMine()) {
                for ($i = $x - 1, $cptI = 0; $cptI < 3; $i++, $cptI++) {
                    for ($j = $y - 1, $cptJ = 0; $cptJ < 3; $j++, $cptJ++) {
                        if (isset($this->etatCaseJeu[$i][$j]) && !$this->etatCaseJeu[$i][$j]->estUneMine() && !isset($casesAJouer[$i][$j])) {
                            if ($this->etatCaseJeu[$i][$j]->getMinesAdjacentes() === 0) {
                                $casesAReveler[] = [$i, $j];
                            }
                            $casesAJouer[$i][$j] = true;
                        }
                    }
                }
            }
        }
        foreach ($casesAJouer as $x => $ligne) {
            foreach (array_keys($ligne) as $y) {
                if (!$this->etatCaseJeu[$x][$y]->estJouee()) {
                    $this->caseRestantes--;
                }
                $this->etatCaseJeu[$x][$y]->jouer();
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
        return $this->caseRestantes == $this->nbrMines;
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
        return $this->nbrMines - $this->drapeauxPosees;
    }


    /**
     * Place un drapeau sur une case
     * Si une case a déjà le drapeau, il est retiré,
     * Si elle n'en a pas, le drapeau est posé
     *
     * @param int $x numéro de la colonne
     * @param int $y numéro de la ligne
     * @return boolean vrai si le drapeau a été pausé
     */
    public function placerDrapeau($x, $y): bool
    {
        if ($this->mouvementPossible($x, $y)) {
            $aDrapeau = $this->etatCaseJeu[$x][$y]->aDrapeau();
            $this->etatCaseJeu[$x][$y]->setDrapeau(!$aDrapeau);
            if ($aDrapeau) {
                $this->drapeauxPosees--;
                return false;
            } else {
                $this->drapeauxPosees++;
                return true;
            }
        } else {
            return false;
        }
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
     * @return CaseMetier[][]
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
        return $this->caseRestantes != $this->nbrLignes * $this->nbrColonnes;
    }


    /**
     * Permet d'obtenir le nombre de lignes du jeu actuel
     */
    public function getNbrLignes()
    {
        return $this->nbrLignes;
    }


    /**
     * Permet d'obtenir le nombre de colonnes du jeu actuel
     */
    public function getNbrColonnes()
    {
        return $this->nbrColonnes;
    }


    /**
     * Permet d'obtenir le nombre de mines du jeu actuel
     */
    public function getNbrMines()
    {
        return $this->nbrMines;
    }
    
    /**
     * Temps en secondes depuis le démarrage de la partie
     *
     * @return int
     */
    public function getSecondes()
    {
        if ($this->estPerdu() || $this->aGagne()) {
            return $this->dateFin->getTimestamp() - $this->dateDemarrage->getTimestamp();
            ;
        } elseif ($this->dateDemarrage != null) {
            return (new DateTime())->getTimestamp() - $this->dateDemarrage->getTimestamp();
        } else {
            return 0;
        }
    }
}
