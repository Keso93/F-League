<?php


namespace App\Manager;


use App\Entity\Club;
use App\Entity\Game;
use App\Entity\Performance;
use App\Entity\League;
use App\Entity\Player;
use App\Repository\PerformanceRepository;
use App\Utils\Validation\EntityValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class PerformanceManager implements BaseManagerInterface
{
    private $performanceRepository;
    /**
     * @var EntityValidator
     */
    private $validator;

    public function __construct(PerformanceRepository $performanceRepository, EntityValidator $validator)
    {
        $this->performanceRepository = $performanceRepository;
        $this->validator = $validator;
    }

    public function findAll()
    {
        return $this->performanceRepository->findAllPerformances();
    }

    public function find($id)
    {
        return $this->performanceRepository->findPerformanceById($id);
    }

    public function findPerformanceByPlayerID($id)
    {
        return $this->performanceRepository->findPerformanceByPlayerId($id);
    }

    public function findPerformanceByPlayer(Player $player, $currentPage)
    {
        $performances =  $this->performanceRepository->findPerformanceByPlayer($player, $currentPage);
        $totalPerformancesReturned = $performances->getIterator()->count();

        $limit = 3;
        $maxPages = ceil($performances->count() / $limit);
        $thisPage = $currentPage;


        $graph =  $this->performanceRepository->findPerformanceByPlayerGraph($player);

        return ['performances' => $performances, 'graph' => $graph, 'maxPages' => $maxPages, 'thisPage'=> $thisPage];
    }
    public function findPerformanceByPlayerPag(Request $request)
    {
        $data = json_decode($request->getContent());
        $page = $data->page;
        $id = $data->id;

        $performances =  $this->performanceRepository->findPerformanceByPlayerPag($id, $page);
        $totalPerformancesReturned = $performances->getIterator()->count();

        $limit = 3;
        $maxPages = ceil($performances->count() / $limit);
        $thisPage = $page;


        return ['performances' => $performances, 'maxPages' => $maxPages, 'thisPage'=> $thisPage];
    }
//
//    public function persist($data)
//    {
//        $performance = new Performance();
//        $performance = $this->populatePerformance($data, $performance);
//
//        return ($result = $this->validator->validate($performance)) === true ? $this->performanceRepository->savePerformance($performance) : $result;
//    }

    public function multiplePersist(ArrayCollection $performances)
    {
        foreach ($performances as $performance) {
            if (!$this->validator->validate($performance) === true){
                return false;
            }
        }
        return $this->performanceRepository->multiplePersist($performances);
    }

    public function merge($performance)
    {
        return $this->performanceRepository->merge($performance);

    }

    public function delete($performance)
    {
        $this->performanceRepository->delete($performance);
    }

    private function populatePerformance($data, Performance $performance){
        $performance->setGame($this->performanceRepository->getReference(Game::class, $data->game));
        $performance->setPlayer($this->performanceRepository->getReference(Player::class, $data->player));
        $performance->setPlayerRating($data->rating);

        return $performance;
    }

    public function persist($entity)
    {
        // TODO: Implement persist() method.
    }
}