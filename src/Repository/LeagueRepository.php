<?php

namespace App\Repository;

use App\Entity\League;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method League|null find($id, $lockMode = null, $lockVersion = null)
 * @method League|null findOneBy(array $criteria, array $orderBy = null)
 * @method League[]    findAll()
 * @method League[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueRepository extends AbstractRepository
{
    const ALIAS = "a";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, League::class);
    }

    public function saveLeague(League $league)
    {
        $this->_em->persist($league);
        $this->_em->flush();

        return $league;
    }

    public function mergeLeague($league)
    {
        $this->_em->merge($league);
        $this->_em->flush();

        return $league;
    }

    public function deleteLeague(League $league)
    {
        $this->_em->remove($league);
        $this->_em->flush();
    }

    public function findAllLeagues(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findLeagueById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    // /**
    //  * @return League[] Returns an array of League objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?League
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
