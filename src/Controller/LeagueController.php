<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\League;
use App\Manager\BaseManagerInterface;
use App\Manager\LeagueManager;
use App\Repository\LeagueRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @isGranted("ROLE_ADMIN", message="Access Denied")
 */
class LeagueController extends AbstractController
{

    private $leagueManager;

    public function __construct(LeagueManager $leagueManager)
    {
        $this->leagueManager = $leagueManager;
    }

    /**
     * @Route("/league", name="league_list")
     */
    public function index()
    {
        return $this->showAll('league/index.html.twig');
    }

    /**
     * @Route("/add-league", name="addleague")
     * @isGranted("ROLE_ADMIN")
     */
    public function addLeague()
    {
        return $this->render('league/addLeague.html.twig', []);
    }

    /**
     * @Route("/json/addleague", name="json_add_league")
     */
    public function jsonAddLeague(League $leagueLFConverter)
    {
        $result = $this->leagueManager->saveLeague($leagueLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));

    }

    /**
     * @Route("/league/edit/{id}", name="edit_league", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function find($id)
    {
        return $this->editShow($id,'league/editLeague.html.twig');
    }

    /**
     * @Route("/league/update", name="json_edit_league", methods={"GET", "POST"})
     */
    public function editLoc(League $leagueLFConverter)
    {
        return $this->jsonEdit($leagueLFConverter);
    }

    /**
     * @Route("/league/delete", name="json_delete_league", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(League $leagueLFConverter)
    {
        return $this->jsonDelete($leagueLFConverter);
    }

    protected function getManager(): BaseManagerInterface
    {
        return $this->leagueManager;
    }
}
