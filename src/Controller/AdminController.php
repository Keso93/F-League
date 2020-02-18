<?php

namespace App\Controller;

use App\Manager\PerformanceManager;
use App\Manager\PlayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class AdminController
 * @isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{

    /**
     * @var PlayerManager
     */
    private $playerManager;
    /**
     * @var PerformanceManager
     */
    private $performanceManager;

    public function __construct(PlayerManager $playerManager, PerformanceManager $performanceManager)
    {
        $this->playerManager = $playerManager;
        $this->performanceManager = $performanceManager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $players = $this->playerManager->findAll();

        return $this->render('admin/index.html.twig', array('players' => $players));

    }



    /**
     * @Route("/json/admin-best-player", name="json_admin_best_player")
     */
    public function bestPlayer()
    {
        $bestPlayerId = $this->playerManager->findBestPlayer();

        $bestPlayer = $this->playerManager->find($bestPlayerId);

        $data = $this->performanceManager->findPerformanceByPlayer($bestPlayer, $currentPage = 1);


        $html = $this->renderView('admin/list-players-performances.html.twig', ['performances' => $data['performances'], 'maxPages'=>$data['maxPages'], 'thisPage'=>$data['thisPage']]);
        $playerHtml = $this->renderView('admin/player-profile.html.twig', ['player' => $bestPlayer]);


        return new JsonResponse(['html' => $html, 'graphData' => $data['graph'], 'playerHtml' => $playerHtml, 'playerName'=> $bestPlayer->getFullName()]);

    }

}
