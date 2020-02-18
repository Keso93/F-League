<?php


namespace App\Manager;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Club;
use App\Repository\PlayerRepository;
use App\Utils\Validation\EntityValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\VarDumper\VarDumper;


class PlayerManager implements BaseManagerInterface
{
    private $playerRepository;
    /**
     * @var EntityValidator
     */
    private $validator;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(PlayerRepository $playerRepository, EntityValidator $validator, SerializerInterface $serializer)
    {
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function findAll()
    {
        return $this->playerRepository->findAllPlayers();
    }

    public function find($id)
    {
        return $this->playerRepository->findPlayerById($id);
    }

    public function findBestPlayer(){
        $player = $this->playerRepository->findBestPlayer();
        return $player['id'];
    }

    public function findPlayer(Player $player)
    {
        return $this->playerRepository->findPlayer($player);
    }


    public function findPlayersByClubs(Request $request)
    {
        $data = json_decode($request->getContent());

        $domacinId = $data->domacin;
        $gostId = $data->gost;

        return $this->playerRepository->findPlayersByTeamsId($domacinId, $gostId);
    }


    public function persist($player)
    {

        return ($result = $this->validator->validate($player)) === true ? $this->playerRepository->savePlayer($player) : $result;
    }

    public function merge($player)
    {
        return ($result = $this->validator->validate($player)) === true ? $this->playerRepository->mergePlayer($player) : $result;
    }

    public function delete($player)
    {
        $this->playerRepository->remove($player);
    }

    private function populatePlayer($data, Player $player){
        $player->setName($data->name);
        $player->setSurname($data->surname);
        $player->setJMBG($data->jmbg);
        $player->setLocation($this->playerRepository->getReference(Location::class, $data->location));
        $player->setPosition($data->position);
        $player->setClub($this->playerRepository->getReference(Club::class, $data->club));
        $player->setBirthDate(new \DateTime($data->birth_date));

        return $player;
    }


}