<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Entity\Position;
use App\Manager\BaseManagerInterface;
use App\Manager\PositionManager;
use App\Repository\PositionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @isGranted("ROLE_ADMIN", message="Access Denied")
 */
class PositionController extends AbstractController
{

    private $positionManager;

    public function __construct(PositionManager $positionManager)
    {
        $this->positionManager = $positionManager;
    }

    /**
     * @Route("/position", name="position_list")
     */
    public function index()
    {
        return $this->showAll('position/index.html.twig');
    }

    /**
     * @Route("/add-position", name="addposition")
     * @isGranted("ROLE_ADMIN")
     */
    public function addPosition()
    {
        return $this->render('position/addPosition.html.twig', []);
    }

    /**
     * @Route("/json/addposition", name="json_add_position")
     */
    public function jsonAddPosition(Position $positionLFConverter)
    {
        $result = $this->positionManager->savePosition($positionLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("/position/edit/{id}", name="edit_position", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     * @return Response
     */
    public function find($id)
    {
        return $this->editShow($id,'position/editPosition.html.twig');
    }

    /**
     * @Route("/position/update", name="json_edit_position", methods={"GET", "POST"})

     */
    public function editLoc(Position $positionLFConverter)
    {
        VarDumper::dump($positionLFConverter);exit;
        $result = $this->positionManager->editPosition($positionLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
    }

    /**
     * @Route("/position/delete", name="json_delete_position", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Position $positionLFConverter)
    {
//        return $this->deleteEntity($positionLFConverter);

        try {
            $this->positionManager->deletePosition($positionLFConverter);

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
        return $this->positionManager;
    }
}
