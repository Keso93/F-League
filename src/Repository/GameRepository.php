<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    const ALIAS = "a";

    public function saveGame(Game $game)
    {
        $this->_em->persist($game);
        $this->_em->flush();


        return $game;
    }

    public function mergeGame(Game $game)
    {
        $this->_em->merge($game);
        $this->_em->flush();

        return $game;
    }

    public function deleteGame(Game $detachedGame)
    {
        $game = $this->_em->merge($detachedGame);
        $this->_em->remove($game);
        $this->_em->flush();
    }

    public function findAllGames(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findGames(){
        $sql = "SELECT game.id, hc.name as homeClub, gc.name as guestClub, hc.id as homeClubId, gc.id as guestClubId FROM game LEFT JOIN performance ON performance.game_id = game.id 
                    LEFT JOIN club hc on game.home_club_id = hc.id
                    LEFT JOIN club gc on game.guest_club_id = gc.id
                    WHERE game.id NOT IN (SELECT game_id from performance)";

        $statement = $this->_em->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findGameById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    public function showForm($club)
    {
        $sql = sprintf('SELECT
                                    hc.name as home_club,
                                    gc.name as guest_club,
                                    game.home_goals,
                                    game.guest_goals,
                                    game_date,
                                    (CASE WHEN hc.id = %s THEN (CASE WHEN home_goals>guest_goals THEN "w" WHEN home_goals = guest_goals THEN "d" ELSE "l" END) 
                                    ELSE (CASE WHEN home_goals>guest_goals THEN "l" WHEN home_goals = guest_goals THEN "d" ELSE "w" END)END) as r
                                FROM
                                    game
                                LEFT JOIN club hc ON
                                    game.home_club_id = hc.id
                                LEFT JOIN club gc ON
                                    game.guest_club_id = gc.id
                                WHERE
                                    hc.id = %s OR gc.id = %s
                                ORDER BY
                                    game.game_date
                                DESC
                                LIMIT 5', $club->getId(), $club->getId(), $club->getId());

        $statement = $this->_em->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
