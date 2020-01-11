<?php
require_once PATH_VUE."/jeu.php";
require_once PATH_VUE."/resultat.php";
require_once PATH_MODELE."/GameState.php";
require_once PATH_MODELE."/modele.php";


class ControleurJeu
{
    private $vueJeu;
    private $vueResultat;
    private $modele;

    /**
     *
     */
    public function __construct()
    {
        $this->vueJeu = new VueJeu();
        $this->vueResultat = new VueResultat();
        $this->modele =  new Modele();
    }


    /**
     *
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
            $this->getSessionDifficultee()
        );
    }
    
    /**
     *
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
                if (!$this->modele->existsInParties($pseudo)) {
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

    public function changerDifficultee($difficultee)
    {
        switch ($difficultee) {
            case "1":
                $_SESSION['difficultee'] = 1;
                break;
            case "2":
                $_SESSION['difficultee'] = 2;
                break;
            default:
                $_SESSION['difficultee'] = 0;
        }
        $this->nouveauJeu();
    }

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
     *
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
            $this->getSessionDifficultee()
        );
    }
    private function getSessionDifficultee()
    {
        if (isset($_SESSION['difficultee'])) {
            return $_SESSION['difficultee'];
        } else {
            $_SESSION['difficultee'] = 0;
            return 0;
        }
    }
    private function getDifficultee()
    {
        switch ($this->getSessionDifficultee()) {
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
     *
     */
    public function nouveauJeu()
    {
        header("Refresh: 30; URL=index.php?credits");
        $pseudo = $_SESSION['pseudo'];
        [$nbrColonnes, $nbrLignes, $nbrMines] = $this->getDifficultee();
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

        $this->vueResultat->afficherVueResultat(
            $this->modele->get3MeilleursDemineurs(),
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
            $this->getSessionDifficultee()
        );
    }

    public function afficherCredits($id)
    {
        $pseudo = $_SESSION['pseudo'];
        $id = intval($id);
        if ($id < count(CREDITS)) {
            header("Refresh: 5; URL=index.php?credits=".($id+1));
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
