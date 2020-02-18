<?php


namespace App\Repository;


use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, $class)
    {
        parent::__construct($registry, $class);
    }

    public function getReference(string $class, $id){
        return $this->_em->getReference($class, $id);
    }

}