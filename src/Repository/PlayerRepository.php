<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends AbstractRepository
{
    const ALIAS = "a";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function savePlayer(Player $player)
    {
        $this->_em->persist($player);
        $this->_em->flush();

        return $player;
    }

    public function mergePlayer(Player $player)
    {
        $this->_em->merge($player);
        $this->_em->flush();

        return $player;
    }

    public function remove(Player $detachedPlayer)
    {
        $player = $this->_em->merge($detachedPlayer);
        $this->_em->remove($player);
        $this->_em->flush();
    }

    public function findAllPlayers(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findPlayerById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findPlayer(Player $player){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $player->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findBestPlayer(){

        $sql = "SELECT player.id FROM `player` LEFT JOIN performance on 
                player.id = performance.player_id GROUP BY player.id ORDER BY AVG(performance.player_rating) DESC LIMIT 1";

        $statement = $this->_em->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetch();
    }

    public function findPlayersByTeamsId($domacinId, $gostId){
        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.club = :domacin')
            ->orWhere(self::ALIAS.'.club = :gost')
            ->setParameter('domacin', $domacinId)
            ->setParameter('gost', $gostId)
            ->orderBy(self::ALIAS.'.club')
            ->getQuery()
            ->getResult();
    }


//    public function findPlayersByTeams($domacinId, $gostId, $game)
//    {
//            $sql = sprintf("SELECT p.name, p.surname, p.id, club.name as club FROM player as p LEFT JOIN club on p.club_id = club.id
//                                    LEFT JOIN performance on performance.player_id = p.id
//                                    LEFT JOIN game on performance.game_id = game.id
//                                    WHERE NOT EXISTS( SELECT * FROM performance where performance.player_id = p.id
//                                    AND performance.game_id = %s) AND (club.id = %s OR club.id = %s)", $domacinId, $gostId, $game);
//
//        $statement = $this->_em->getConnection()->prepare($sql);
//        $statement->execute();
//
//        return $statement->fetchAll();
//    }

    // /**
    //  * @return Player[] Returns an array of Player objects
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
    public function findOneBySomeField($value): ?Player
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
