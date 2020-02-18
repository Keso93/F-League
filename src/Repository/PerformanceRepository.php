<?php

namespace App\Repository;

use App\Entity\Performance;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method Performance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Performance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Performance[]    findAll()
 * @method Performance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerformanceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }

    const ALIAS = "a";

    protected function beginTransaction()
    {
        $this->_em->beginTransaction();
    }

    protected function commitTransaction()
    {
        $this->_em->commit();
    }

    protected function rollbackTransaction()
    {
        $this->_em->rollback();
    }

    public function multiplePersist(ArrayCollection $entities)
    {

        try {
            $this->beginTransaction();
            foreach ($entities as $entity){
                $this->_em->persist($entity);
            }
            $this->_em->flush();
            $this->commitTransaction();
        } catch (DBALException $e) {
            $this->rollbackTransaction();

            return false;
        } catch (\Exception $exception) {
            $this->rollbackTransaction();

            return false;
        }

        return true;
    }

    public function savePerformance(Performance $performance)
    {
        $this->_em->persist($performance);
        $this->_em->flush();

        return $performance;
    }
    public function merge(Performance $performance)
    {

        $this->_em->merge($performance);
        $this->_em->flush();

        return $performance;
    }

    public function delete(Performance $performance)
    {
        $this->_em->remove($performance);
        $this->_em->flush();
    }

    public function findAllPerformances(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findPerformanceById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    public function findPerformanceByPlayerId($id){
        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.player = :player_id')
            ->setParameter('player_id', $id)
            ->getQuery()->getResult();

    }

    public function findPerformanceByPlayer(Player $player, $currentPage = 1){
        $query = $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.player = :player_id')
            ->setParameter('player_id', $player->getId())
            ->getQuery();

        return $this->paginate($query, $currentPage);
    }
    public function findPerformanceByPlayerPag($id, $page){
        $query = $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.player = :player_id')
            ->setParameter('player_id', $id)
            ->getQuery();

        return $this->paginate($query, $page);
    }

    public function findPerformanceByPlayerGraph(Player $player)
    {
        $sql = sprintf("SELECT p.player_rating as y, CONCAT ((CASE WHEN hc.id = player.club_id THEN gc.name ELSE hc.name END), '(', game.game_date, ')') as x 
                                FROM performance as p LEFT JOIN game on p.game_id = game.id 
                                LEFT JOIN club hc on game.home_club_id = hc.id
                                LEFT JOIN club gc on game.guest_club_id = gc.id
                                LEFT JOIN player on p.player_id = player.id
                                WHERE p.player_id = %s ORDER BY game.game_date ASC", $player->getId());

        $statement = $this->_em->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function paginate($dql, $page = 1, $limit = 3)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit
        return $paginator;
    }

    // /**
    //  * @return Performance[] Returns an array of Performance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Performance
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
