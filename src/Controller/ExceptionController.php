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

class ExceptionController extends AbstractController
{
    /**
     * @Route("/exception", name="exception")
     */
    public function index()
    {

        return $this->render('exception/exception.html.twig', []);
    }

    protected function getManager(): BaseManagerInterface
    {
        // TODO: Implement getManager() method.
    }
}