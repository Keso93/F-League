<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\Club;
use App\Entity\Game;
use App\Manager\BaseManagerInterface;
use App\Manager\ClubManager;
use App\Manager\GameManager;
use App\Manager\LeagueManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_ADMIN", message="Access Denied")
 */
class GameController extends AbstractController
{
    private $gameManager;
    /**
     * @var ClubManager
     */
    private $clubManager;
    /**
     * @var LeagueManager
     */
    private $leagueManager;

    public function __construct(GameManager $gameManager, ClubManager $clubManager, LeagueManager $leagueManager)
    {
        $this->gameManager = $gameManager;
        $this->clubManager = $clubManager;
        $this->leagueManager = $leagueManager;
    }

    /**
     * @Route("/game", name="game_list")
     */
    public function index()
    {
        return $this->showAll('game/index.html.twig');
    }

    /**
     * @Route("/addgame", name="addgame")
     * @isGranted("ROLE_ADMIN")
     */
    public function addGame()
    {
        $clubs = $this->clubManager->findAll();
        $leagues = $this->leagueManager->findAll();

        return $this->render('game/addGame.html.twig', array('clubs' => $clubs, 'leagues' => $leagues));
    }

    /**
     * @Route("/json/addgame", name="json_add_game")
     */
    public function jsonAddGame(Game $gameLFConverter)
    {

        $result = $this->gameManager->persist($gameLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }


    /**
     * @Route("/game/edit/{id}", name="edit_game", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function findCustomGame($id)
    {
        $clubs = $this->clubManager->findAll();
        $game = $this->gameManager->find($id);
        $leagues = $this->leagueManager->findAll();

        return $this->render('game/editGame.html.twig', array('game' => $game, 'leagues' => $leagues, 'clubs' => $clubs));
    }

    /**
     * @Route("/json/game/update", name="json_edit_game", methods={"GET", "POST"})
     */
    public function editLoc(Game $gameLFConverter)
    {
        $result = $this->gameManager->merge($gameLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }


    /**
     * @Route("json/game/delete", name="json_delete_game")
     * Method({"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Game $gameLFConverter)
    {
        try {
            $this->gameManager->delete($gameLFConverter);

            $response = [
                'code' => 200,
                'status' => 'deleted',
            ];
        } catch (\Exception $exception){
            $response = [
                'code' => 500,
                'status' => 'error',
            ];
        }
        return new JsonResponse($response);
    }

    protected function getManager(): BaseManagerInterface
    {
        return $this->gameManager;
    }
}
