<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\Performance;
use App\Entity\Player;
use App\Manager\BaseManagerInterface;
use App\Manager\ClubManager;
use App\Manager\GameManager;
use App\Manager\PerformanceManager;
use App\Manager\LeagueManager;
use App\Manager\PlayerManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PerformanceController extends AbstractController
{
    private $performanceManager;
    /**
     * @var PlayerManager
     */
    private $playerManager;
    /**
     * @var GameManager
     */
    private $gameManager;

    public function __construct(PerformanceManager $performanceManager, PlayerManager $playerManager, GameManager $gameManager)
    {
        $this->performanceManager = $performanceManager;
        $this->playerManager = $playerManager;
        $this->gameManager = $gameManager;
    }

    /**
     * @Route("/performance", name="performance_list")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */
    public function index()
    {
        return $this->showAll('performance/index.html.twig');
    }

    /**
     * @Route("/addperformance", name="addperformance")
     * @isGranted("ROLE_ADMIN")
     */
    public function addPerformance()
    {
        $games = $this->gameManager->findGames();
        $players = $this->playerManager->findAll();

        return $this->render('performance/addPerformance.html.twig', array('games' => $games));
    }

    /**
     * @Route("/json/showPlayersByTeams", name="json_performance_players")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */

    public function jsonShowPerformancePlayers(Request $request)
    {
        $players = $this->playerManager->findPlayersByClubs($request);
        $html = $this->renderView('performance/listPlayers.html.twig', ['players' => $players]);

        return new JsonResponse(['html' => $html]);
    }

    /**
     * @Route("/json/performancebyplayers", name="json_players_performances")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */

    public function jsonShowPerformanceByPlayer(Player $playerLFConverter, $currentPage = 1)
    {
        $player =$this->playerManager->findPlayer($playerLFConverter);
        $data = $this->performanceManager->findPerformanceByPlayer($playerLFConverter, $currentPage);

        $html = $this->renderView('admin/list-players-performances.html.twig', ['performances' => $data['performances'], 'maxPages'=>$data['maxPages'], 'thisPage'=>$data['thisPage']]);
        $playerHtml = $this->renderView('admin/player-profile.html.twig', ['player' => $player]);


        return new JsonResponse(['html' => $html, 'graphData' => $data['graph'], 'playerHtml' => $playerHtml, 'playerName'=> $player->getFullName()]);
    }

    /**
     * @Route("/json/performancebyplayersPag", name="json_players_performances_pag")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */

    public function jsonShowPerformanceByPlayerPag(Request $request)
    {
        $data = $this->performanceManager->findPerformanceByPlayerPag($request);


        $html = $this->renderView('admin/list-players-performances.html.twig', ['performances' => $data['performances'], 'maxPages'=>$data['maxPages'], 'thisPage'=>$data['thisPage']]);


        return new JsonResponse(['html' => $html]);
    }

    /**
     * @Route("/json/addperformance", name="json_add_performance")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */
    public function jsonAddPerformance(ArrayCollection $performanceLFConverter)
    {
        return new JsonResponse($this->boolResponse($this->performanceManager->multiplePersist($performanceLFConverter)));
    }
//
//    /**
//     * @Route("/performance/edit/{id}", name="edit_performance", methods={"GET", "POST"})
//     */
//    public function find($id)
//    {
//        return $this->editShow($id, 'performance/editPerformance.html.twig');
//    }

    /**
     * @Route("/performance/edit/{id}", name="edit_performance", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function findCustomPerformance($id)
    {
        $performance = $this->performanceManager->find($id);

        return $this->render('performance/editPerformance.html.twig', array('performance' => $performance));
    }

    /**
     * @Route("/json/performance/update", name="json_edit_performance", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */
    public function editLoc(Performance $performanceLFConverter)
    {
        $result = $this->performanceManager->merge($performanceLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("json/performance/delete", name="json_delete_performance")
     * Method({"DELETE"})
     * @isGranted("ROLE_ADMIN", message="No delete")
     */
    public function delete(Performance $performanceLFConverter)
    {
        try {
            $this->performanceManager->delete($performanceLFConverter);

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
        return $this->performanceManager;
    }
}
