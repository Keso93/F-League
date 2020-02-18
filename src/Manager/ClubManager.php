<?php
namespace App\Manager;

use App\Entity\BaseEntityInterface;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class ClubManager implements BaseManagerInterface
{

    private $clubRepository;
    /**
     * @var EntityValidator
     */
    private $validator;

    public function __construct(ClubRepository $clubRepository, EntityValidator $validator)
    {
        $this->clubRepository = $clubRepository;
        $this->validator = $validator;
    }


    public function findAll()
    {
        return $this->clubRepository->findAllClubs();
//        return $this->serializer->serialize($result, 'json');

    }

    public function find($id)
    {
        return $this->clubRepository->findClubById($id);
    }

    public function persist($club)
    {
        return ($result = $this->validator->validate($club)) === true ? $this->clubRepository->saveClub($club) : $result;
    }

    public function merge($club)
    {
        return ($result = $this->validator->validate($club)) === true ? $this->clubRepository->mergeClub($club) : $result;
    }

    public function delete($club)
    {
        $this->clubRepository->deleteClub($club);
    }

    public function showRanking(){
        return $this->clubRepository->showRanking();
    }

}