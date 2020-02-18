<?php


namespace App\Manager;


use App\Entity\Position;
use App\Repository\PositionRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class PositionManager implements BaseManagerInterface
{
    private $positionRepository;
    /**
     * @var EntityValidator
     */
    private $validator;

    public function __construct(PositionRepository $positionRepository, EntityValidator $validator)
    {
        $this->positionRepository = $positionRepository;
        $this->validator = $validator;
    }

    public function findAll()
    {
        return $this->positionRepository->findAllPositions();
    }

    public function find($id)
    {
        return $this->positionRepository->findPositionById($id);
    }

    public function persist($data)
    {
        $position = new Position();
        $position->setName($data->name);

        return ($result = $this->validator->validate($position)) === true ? $this->positionRepository->savePosition($position) : $result;
    }

    public function merge($data)
    {
        $id = $data->id;
        $position = $this->positionRepository->findPositionById($id);
        $position->setName($data->name);
        return ($result = $this->validator->validate($position)) === true ? $this->positionRepository->savePosition($position) : $result;
    }

    public function delete($id)
    {
//        $this->positionRepository->deletePosition($this->positionRepository->findPositionById($id));
    }

    public function savePosition(Position $position){
        VarDumper::dump($this->validator->validate($position));exit;
        return ($result = $this->validator->validate($position)) === true ? $this->positionRepository->savePosition($position) : $result;
    }

    public function editPosition(Position $position)
    {
        return ($result = $this->validator->validate($position)) === true ? $this->positionRepository->mergePosition($position) : $result;
    }

    public function deletePosition(Position $position)
    {
        $this->positionRepository->deletePosition($position);
    }

}