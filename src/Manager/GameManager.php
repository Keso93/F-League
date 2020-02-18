<?php


namespace App\Manager;


use App\Entity\Club;
use App\Entity\Game;
use App\Entity\League;
use App\Repository\GameRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\VarDumper\VarDumper;

class GameManager implements BaseManagerInterface
{
    private $gameRepository;
    /**
     * @var EntityValidator
     */
    private $validator;

    public function __construct(GameRepository $gameRepository, EntityValidator $validator)
    {
        $this->gameRepository = $gameRepository;
        $this->validator = $validator;
    }

    public function findAll()
    {
        return $this->gameRepository->findAllGames();
    }
    public function findGames()
    {
        return $this->gameRepository->findGames();
    }

    public function find($id)
    {
        return $this->gameRepository->findGameById($id);
    }

    public function persist($game)
    {
//        $game = $this->createReferences($game);

        return ($result = $this->validator->validate($game)) === true ? $this->gameRepository->saveGame($game) : $result;

//        $game = new Game();
//        $game = $this->populateGame($data, $game);
//
//        return ($result = $this->validator->validate($game)) === true ? $this->gameRepository->saveGame($game) : $result;
    }

    public function merge($game)
    {
        return ($result = $this->validator->validate($game)) === true ? $this->gameRepository->mergeGame($game) : $result;
    }

    public function delete($game)
    {
        $this->gameRepository->deleteGame($game);
    }

    public function showForm($club){
        return $this->gameRepository->showForm($club);
    }

    private function populateGame($data, Game $game){
        $game->setHomeClub($this->gameRepository->getReference(Club::class, $data->domacin));
        $game->setGuestClub($this->gameRepository->getReference(Club::class, $data->gost));
        $game->setHomeGoals($data->domacinGolovi);
        $game->setGuestGoals($data->gostGolovi);
        $game->setLeague($this->gameRepository->getReference(League::class, $data->liga));
        $game->setGameDate(new \DateTime($data->date));

        return $game;
    }

    public function createReferences(Game $game)
    {
        $game->setHomeClub($this->gameRepository->getReference(Club::class, $game->getHomeClub()->getId()));
        $game->setGuestClub($this->gameRepository->getReference(Club::class, $game->getGuestClub()->getId()));
        $game->setLeague($this->gameRepository->getReference(League::class, $game->getLeague()->getId()));

        return $game;
    }
}