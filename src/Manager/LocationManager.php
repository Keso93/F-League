<?php


namespace App\Manager;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\VarDumper;

class LocationManager implements BaseManagerInterface
{
    private $locationRepository;
    /**
     * @var EntityValidator
     */
    private $validator;

    public function __construct(LocationRepository $locationRepository, EntityValidator $validator)
    {
        $this->locationRepository = $locationRepository;
        $this->validator = $validator;
    }

    public function findAll()
    {
        return $this->locationRepository->findAllLocations();
    }

    public function find($id)
    {
        return $this->locationRepository->findLocationById($id);
    }

    public function persist($location)
    {
        return ($result = $this->validator->validate($location)) === true ? $this->locationRepository->persistLocation($location) : $result;

//        $location = new Location();
//        $location->setName($data->name);
//        $location->setPtt($data->ptt);
//
//        return ($result = $this->validator->validate($location)) === true ? $this->locationRepository->saveLocation($location) : $result;
    }

    public function merge($location)
    {
        return ($result = $this->validator->validate($location)) === true ? $this->locationRepository->mergeLocation($location) : $result;

//        $id = $data->id;
//        $location = $this->locationRepository->findLocationById($id);
//        $location->setName($data->name);
//        $location->setPtt($data->ptt);
//
//        return ($result = $this->validator->validate($location)) === true ? $this->locationRepository->saveLocation($location) : $result;
    }

    public function delete($location)
    {
        $this->locationRepository->deleteLocation($location);

//        $this->locationRepository->deleteLocation($this->locationRepository->findLocationById($id));
    }

}