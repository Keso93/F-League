<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Position|null find($id, $lockMode = null, $lockVersion = null)
 * @method Position|null findOneBy(array $criteria, array $orderBy = null)
 * @method Position[]    findAll()
 * @method Position[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionRepository extends AbstractRepository
{
    const ALIAS = "a";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

    public function savePosition(Position $position)
    {
        $this->_em->persist($position);
        $this->_em->flush();

        return $position;
    }

    public function mergePosition(Position $position)
    {
        $this->_em->merge($position);
        $this->_em->flush();

        return $position;
    }
    public function deletePosition(Position $position)
    {
        $pos = $this->_em->merge($position);
        $this->_em->remove($pos);
        $this->_em->flush();
    }

    public function findAllPositions(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findPositionById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    // /**
    //  * @return Position[] Returns an array of Position objects
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
    public function findOneBySomeField($value): ?Position
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
