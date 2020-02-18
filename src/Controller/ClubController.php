<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\Club;
use App\Entity\User;
use App\Manager\AbstractManager;
use App\Manager\BaseManagerInterface;
use App\Manager\ClubManager;
use App\Manager\GameManager;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 */
class ClubController extends AbstractController
{

    private $clubManager;
    /**
     * @var GameManager
     */
    private $gameManager;

    public function __construct(ClubManager $clubManager, GameManager $gameManager)
    {
        $this->clubManager = $clubManager;
        $this->gameManager = $gameManager;
    }

    /**
     * @Route("/club", name="club_list")
     */
    public function index()
    {

        return $this->showAll('club/index.html.twig');
//        $data = $this->clubManager->findClubs();
    }

    /**
     * @Route("/", name="ranking", methods={"GET", "POST"})
     */
    public function ranking()
    {
        $ranking = $this->clubManager->showRanking();
        return $this->render('club/ranking.html.twig', array('rankings' => $ranking));
    }


    /**
     * @Route("/addclub", name="addclub")
     */
    public function addClub()
    {
        return $this->render('club/addClub.html.twig', []);
    }

//    /**
//     * @Route("/json/addclub", name="json_add_club")
//     */
//    public function jsonAddClub(Request $request)
//    {
//        return $this->jsonAdd($request);
//    }

    /**
     * @Route("/json/addclub", name="json_add_club")
     */
    public function jsonAddClub(Club $clubLFConverter)
    {
        $result = $this->clubManager->persist($clubLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("/club/edit/{id}", name="edit_club", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function find($id, Request $request)
    {
        return $this->editShow($id, 'club/editClub.html.twig');
    }

    /**
     * @Route("/json/club/update", name="json_edit_club", methods={"GET", "POST"})
     */
    public function editClub(Club $clubLFConverter)
    {
        $result = $this->clubManager->merge($clubLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("/json/game/show_form", name="json_show_form", methods={"GET", "POST"})
     */
    public function showForm(Club $clubLFConverter)
    {
        $data = $this->gameManager->showForm($clubLFConverter);
        $html = $this->renderView('game/form.html.twig', ['games' => $data]);

        return new JsonResponse(['html' => $html]);
    }

    /**
     * @Route("json/club/delete", name="json_delete_club")
     * Method({"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Club $clubLFConverter)
    {

//        return $this->jsonDelete($clubLFConverter);

        try {
            $this->clubManager->delete($clubLFConverter);
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
        return $this->clubManager;
    }
}
