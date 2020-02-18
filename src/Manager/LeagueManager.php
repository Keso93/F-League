<?php


namespace App\Manager;


use App\Entity\League;
use App\Repository\LeagueRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\Validator\ConstraintViolation;

class LeagueManager implements BaseManagerInterface
{
    private $leagueRepository;

    private $validator;

    public function __construct(LeagueRepository $leagueRepository, EntityValidator $validator)
    {
        $this->leagueRepository = $leagueRepository;
        $this->validator = $validator;
    }

    public function findAll()
    {
        return $this->leagueRepository->findAllLeagues();
    }

    public function find($id)
    {
        return $this->leagueRepository->findLeagueById($id);
    }

    public function saveLeague(League $league){
        return ($result = $this->validator->validate($league)) === true ? $this->leagueRepository->saveLeague($league) : $result;
    }

    public function persist($data)
    {
        $league = new League();
        $league->setLeagueName($data->name);

        return ($result = $this->validator->validate($league)) === true ? $this->leagueRepository->saveLeague($league) : $result;
    }

    public function merge($league)
    {
        return ($result = $this->validator->validate($league)) === true ? $this->leagueRepository->mergeLeague($league) : $result;
//
//        $id = $data->id;
//        $league = $this->leagueRepository->findLeagueById($id);
//        $league->setLeagueName($data->name);
//
//        return ($result = $this->validator->validate($league)) === true ? $this->leagueRepository->saveLeague($league) : $result;
    }

    public function delete($league)
    {
        $this->leagueRepository->deleteLeague($league);
    }
}