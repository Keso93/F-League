<?php

namespace App\Controller;

use App\Entity\BaseEntityInterface;
use App\Manager\BaseManagerInterface;
use App\Manager\LocationManager;
use App\Repository\LocationRepository;
use App\Entity\Location;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\VarDumper\VarDumper;
USE Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @isGranted("ROLE_ADMIN", message="Access Denied")
 */
class LocationController extends AbstractController
{

    private $locationManager;

    public function __construct(LocationManager $locationManager)
    {
        $this->locationManager = $locationManager;
    }

    /**
     * @Route("/location", name="location_list")
     */
    public function index()
    {
        return $this->showAll('location/index.html.twig');

//        $locations = $locationManager->showAllLocations();
//
//        return $this->render('location/index.html.twig', array('locations' => $locations));
    }

    /**
     * @Route("/addlocation", name="addlocation")
     * @isGranted("ROLE_ADMIN")
     */
    public function addLocation()
    {
        return $this->render('location/addLocation.html.twig', []);
    }

    /**
     * @Route("/json/addlocation", name="json_add_location")
     */
    public function jsonAddLocation(Location $locationLFConverter)
    {

        $result = $this->locationManager->persist($locationLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));

//        return $this->jsonAdd($request);

//        $data = json_decode($request->getContent());
//
//
//        $location = $locationManager->saveLocation($data);
//
//        return new JsonResponse($this->formatResponse($location));

    }

    /**
     * @Route("/location/edit/{id}", name="edit_location", methods={"GET", "POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function find($id)
    {
        return $this->editShow($id, 'location/editLocation.html.twig');

//        $location = $locationManager->findLocation($id);
//
//        return $this->render('location/editLocation.html.twig', array('location' => $location));
    }

    /**
     * @Route("/json/location/update", name="json_edit_location", methods={"GET", "POST"})
     */
    public function editLoc(Location $locationLFConverter)
    {
        $result = $this->locationManager->merge($locationLFConverter);
        return new JsonResponse($result instanceof BaseEntityInterface ? $this->formatResponse($result) : $this->formatCustomResponse($result));
//        return $this->jsonEdit($request);
    }

    /**
     * @Route("/json/location/delete" , name="json_delete_location", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */

    public function delete(Location $locationLFConverter)
    {
        try {
            $this->locationManager->delete($locationLFConverter);

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
//        return $this->jsonDelete($request);
    }

    protected function getManager(): BaseManagerInterface
    {
        return $this->locationManager;
    }
}
