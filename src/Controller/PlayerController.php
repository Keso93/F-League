<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\Image;
use App\Manager\BaseManagerInterface;
use App\Manager\ClubManager;
use App\Manager\LocationManager;
use App\Manager\PerformanceManager;
use App\Manager\PlayerManager;
use App\Manager\PositionManager;
use App\Repository\PlayerRepository;
use App\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PlayerController extends AbstractController
{

    private $playerManager;
    /**
     * @var PerformanceManager
     */
    private $performanceManager;
    /**
     * @var LocationManager
     */
    private $locationManager;
    /**
     * @var ClubManager
     */
    private $clubManager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(PlayerManager $playerManager, PerformanceManager $performanceManager, LocationManager $locationManager, ClubManager $clubManager, Security $security)
    {
        $this->playerManager = $playerManager;
        $this->performanceManager = $performanceManager;
        $this->locationManager = $locationManager;
        $this->clubManager = $clubManager;
        $this->security = $security;
    }

    /**
     * @Route("/player", name="player_list")
     * @isGranted("ROLE_ADMIN", message="Access Denied")
     */
    public function index()
    {
        return $this->showAll('player/index.html.twig');

    }

    /**
     * @Route("/addplayer", name="addplayer")
     * @isGranted("ROLE_ADMIN")
     */
    public function addPlayer()
    {
        $positions =  Player::getPositions();
        $locations = $this->locationManager->findAll();
        $clubs = $this->clubManager->findAll();

        return $this->render('player/addPlayer.html.twig', array('positions' => $positions, 'clubs' => $clubs, 'locations' => $locations));
    }

    /**
     * @Route("/json/addplayer", name="json_add_player")
     */
    public function jsonAddPlayer(Player $playerLFConverter)
    {
        if ($playerLFConverter->getImage()){
            $playerLFConverter->getImage()->saveToFile();
            $result = $this->playerManager->persist($playerLFConverter);
        } else {
            $result = false;
        }
//        $playerLFConverter->getImage() ? Image::saveToFile($playerLFConverter->getImage()) : $result = false;
//        $result = $this->playerManager->persist($playerLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

//    /**
//     * @Route("/player/edit/{id}", name="edit_player", methods={"GET", "POST"})
//     */
//    public function find(PlayerManager $playerManager, $id)
//    {
//        return $this->editShow($id, 'player/editPlayer.html.twig');
//    }
//
    /**
     * @Route("/player/edit/{id}", name="edit_player", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function findPlayerCustom(ArrayCollection $editPlayerConverter)
    {
        return $this->render('player/editPlayer.html.twig', $editPlayerConverter->toArray());
    }
//    /**
//     * @Route("/player/showperformances/{id}", name="players_performances", methods={"GET", "POST"})
//     * /**
//     * @isGranted("ROLE_PLAYER")
//     */
//    public function findPlayersPerformances($id)
//    {
//        $performances = $this->performanceManager->findPerformanceByPlayerId($id);
//
//        return $this->render('player/show-performance.html.twig', array('performances' => $performances));
//    }


    /**
     * @Route("/player/player-performances", name="players_performances", methods={"GET", "POST"})
     * /**
     * @isGranted("ROLE_PLAYER")
     */
    public function findPlayersPerformances()
    {
        $performances = $this->performanceManager->findPerformanceByPlayerId($this->security->getUser()->getId());

        return $this->render('player/show-performance.html.twig', array('performances' => $performances));
    }


    /**
     * @Route("/json/player/update", name="json_edit_player", methods={"GET", "POST"})
     */
    public function jsonEditPlayer(Player $playerLFConverter)
    {
        if ($playerLFConverter->getImage()){
            $playerLFConverter->getImage()->saveToFile();
            $result = $this->playerManager->merge($playerLFConverter);
        } else {
            $result = false;
        }

        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }


    /**
     * @Route("/json/player/find", name="json_find_player", methods={"GET", "POST"})
     */
    public function jsonFindPlayer(Player $playerLFConverter)
    {
        $result = $this->playerManager->findPlayer($playerLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("/json/player/delete" , name="json_delete_player", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */

    public function delete(Player $playerLFConverter)
    {
        try {
            $this->playerManager->delete($playerLFConverter);

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
        return $this->playerManager;
    }
}
