<?php
/**
 * @version 1.0.0
 * @license MIT
 * @author Simon <simon.sassi@etu.univ-nantes.fr> & Eliott <eliott.dubois@etu.univ-nantes.fr>
 */

require_once PATH_VUE."/jeu.php";
require_once PATH_VUE."/resultat.php";
require_once PATH_MODELE."/GameState.php";
require_once PATH_MODELE."/modele.php";

class ControleurJeu
{
    private $vueJeu;
    private $vueResultat;
    private $modele;

    public function __construct()
    {
        $this->vueJeu = new VueJeu();
        $this->vueResultat = new VueResultat();
        $this->modele =  new Modele();
    }


    /**
     * Permet de charger la vue du jeu
     */
    public function afficherJeu()
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $gamePerdu = $game->estPerdu();
        $gameGagne = $game->aGagne();
        $etatCases = $game->obtenirEtatJeu();
        $pseudo = $_SESSION['pseudo'];
        $unite = (int)($game->drapeauxRestants()%10);
        $dizaine = (int)($game->drapeauxRestants()-$unite)%100/10;
        $centaine = (int)($game->drapeauxRestants()/100);
        if ($game->drapeauxRestants() < 0) {
            $centaine = "-";
            $unite = -$unite;
            $dizaine = -$dizaine;
        }

        $this->vueJeu->afficherVueJeu(
            $pseudo,
            $centaine,
            $dizaine,
            $unite,
            $gamePerdu,
            $gameGagne,
            $etatCases,
            false,
            $game->getNbrLignes(),
            $game->getNbrColonnes(),
            $this->getSessionDifficulte()
        );
    }


    /**
     * Permet de jouer une case
     *
     * @param int $x la coordonnée x de la case à jouer
     * @param int $y la coordonnée y de la case à jouer
     */
    public function jouer($x, $y)
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $pseudo = $_SESSION['pseudo'];
        if (!$game->estCommence() && $game->mouvementPossible($x, $y)) {
            $this->modele->incrPartieJouees($pseudo);
        }
        if (!$game->aGagne() && !$game->estPerdu()) {
            $game->jouer($x, $y);
            $_SESSION['game'] = serialize($game);
            if ($game->aGagne() || $game->estPerdu()) {
                if (!$this->modele->existsInParties($pseudo)) { // si le jouer a reset son score pendant la partie, on recréé la partie à la fin.
                    $this->modele->addPartie($pseudo);
                    $this->modele->incrPartieJouees($pseudo);
                }
                if ($game->aGagne()) {
                    $this->modele->incrPartieGagnees($pseudo);
                }
                header("Location: ?scores", false, 301);
            } else {
                $this->afficherJeu();
            }
        } else {
            $this->afficherJeu();
        }
    }


    /**
     * Permet changer la difficulté du jeu en changeant la taille et le nombre de mines
     *
     * @param string $difficulte le niveau de difficulté : 0, 1 ou 2
     */
    public function changerDifficulte($difficulte)
    {
        switch ($difficulte) {
            case "1":
                $_SESSION['difficulte'] = 1;
                break;
            case "2":
                $_SESSION['difficulte'] = 2;
                break;
            default:
                $_SESSION['difficulte'] = 0;
        }
        $this->nouveauJeu();
    }


    /**
     * Permet de placer un drapeau sur une case
     *
     * @param int $x numéro de la colonne de la case
     * @param int $y numéro de la ligne de la case
     */
    public function placerDrapeau($x, $y)
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $game->placerDrapeau($x, $y);
        $_SESSION['game'] = serialize($game);
        $this->afficherJeuModeDrapeau();
    }


    /**
     * Permet de charger la vue du jeu en mode drapeau
     */
    public function afficherJeuModeDrapeau()
    {
        /**
         * @var GameState
         */
        $game = unserialize($_SESSION['game']);
        $gamePerdu = $game->estPerdu();
        $gameGagne = $game->aGagne();
        $etatCases = $game->obtenirEtatJeu();
        $pseudo = $_SESSION['pseudo'];
        $unite = (int)($game->drapeauxRestants()%10);
        $dizaine = (int)($game->drapeauxRestants()-$unite)%100/10;
        $centaine = (int)($game->drapeauxRestants()/100);
        if ($game->drapeauxRestants() < 0) {
            $centaine = "-";
            $unite = -$unite;
            $dizaine = -$dizaine;
        }

        $this->vueJeu->afficherVueJeu(
            $pseudo,
            $centaine,
            $dizaine,
            $unite,
            $gamePerdu,
            $gameGagne,
            $etatCases,
            true,
            $game->getNbrLignes(),
            $game->getNbrColonnes(),
            $this->getSessionDifficulte()
        );
    }


    /**
     * Permet d'obtenir le niveau de difficulté actuel du jeu
     */
    private function getSessionDifficulte()
    {
        if (isset($_SESSION['difficulte'])) {
            return $_SESSION['difficulte'];
        } else {
            $_SESSION['difficulte'] = 0;
            return 0;
        }
    }


    /**
     * Permet d'obtenir les caractéristiques du niveau de difficulté actuel du jeu : taille et nombre de mines
     */
    private function getDifficulte()
    {
        switch ($this->getSessionDifficulte()) {
            case 1: // Mode intermediaire
                return [16, 16, 40];
                break;
            case 2: // Mode expert
                return [30, 16, 99];
                    break;
            default:
                return [NBR_COLONNES, NBR_LIGNES, NBR_MINES];
        }
    }


    /**
     * Permet de démarrer une nouvelle partie
     */
    public function nouveauJeu()
    {
        header("Refresh: 30; URL=index.php?credits");
        $pseudo = $_SESSION['pseudo'];
        [$nbrColonnes, $nbrLignes, $nbrMines] = $this->getDifficulte();
        $game = new GameState($pseudo, $nbrColonnes, $nbrLignes, $nbrMines);
        $_SESSION['game'] = serialize($game);
        if (!$this->modele->existsInParties($pseudo)) {
            $this->modele->addPartie($pseudo);
        }
        $this->afficherJeu();
    }


    /**
     * Permet de remettre les scores à zéro
     */
    public function resetScores()
    {
        $this->modele->resetParties();
        $this->afficherResultat();
    }


    /**
     * Permet de charger la vue des résultats
     */
    public function afficherResultat()
    {
        $game = unserialize($_SESSION['game']);
        $gamePerdu = $game->estPerdu();
        $gameGagne = $game->aGagne();
        $etatCases = $game->obtenirEtatJeu();
        $pseudo = $_SESSION['pseudo'];
        $unite = (int)($game->drapeauxRestants()%10);
        $dizaine = (int)($game->drapeauxRestants()-$unite)%100/10;
        $centaine = (int)($game->drapeauxRestants()/100);
        if ($game->drapeauxRestants() < 0) {
            $centaine = "-";
            $unite = -$unite;
            $dizaine = -$dizaine;
        }

        $winners = $this->modele->get3MeilleursDemineurs();
        array_push($winners, $this->modele->getDataDemineur($pseudo));

        $this->vueResultat->afficherVueResultat(
            $winners,
            $pseudo,
            $centaine,
            $dizaine,
            $unite,
            $gamePerdu,
            $gameGagne,
            $etatCases,
            false,
            $game->getNbrLignes(),
            $game->getNbrColonnes(),
            $this->getSessionDifficulte()
        );
    }


    /**
     * Permet de charger les vues de crédits
     *
     * @param string $id le numéro de la page de crédits à charger
     */
    public function afficherCredits($id)
    {
        $pseudo = $_SESSION['pseudo'];
        $id = intval($id);
        if ($id < count(CREDITS)) {
            header("Refresh: 3; URL=index.php?credits=".($id+1));
            $mode = CREDITS[$id][0];
            $text = CREDITS[$id][1];
            $lines = explode("\n", $text);
            $nbrColonnes = max(array_map('strlen', $lines)) + 2;
            $nbrLignes = count($lines) + 2;
            $game = new GameState($pseudo, $nbrColonnes, $nbrLignes, 0);
            for ($x = 1, $chrPos = 0; $x <=  $nbrColonnes; $x++, $chrPos++) {
                for ($y = 1, $line=0; $y <= count($lines); $y++, $line++) {
                    $chr = substr($lines[$line], $chrPos, 1);
                    if ($chr && !preg_match('/\s/', $chr)) {
                        $game->jouer(0, 0);
                        if ($mode == 0) {
                            $game->obtenirEtatJeu()[$x][$y]->setMine(true);
                            $game->obtenirEtatJeu()[$x][$y]->surbriller();
                        } else {
                            for ($i = 0; $i <= $id % 9; $i++) {
                                $game->obtenirEtatJeu()[$x][$y]->incrementerCompteurMine();
                            }
                        }
                    }
                }
            }
            $_SESSION['game'] = serialize($game);
            $this->afficherJeu();
        } else {
            header("Location: ?reset", false, 301);
        }
    }
}
