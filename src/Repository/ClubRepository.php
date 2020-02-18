<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;


/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends AbstractRepository
{
    const ALIAS = "a";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function saveClub(Club $club)
    {
        $this->_em->persist($club);
        $this->_em->flush();


        return $club;
    }

    public function mergeClub(Club $club)
    {
        $this->_em->merge($club);
        $this->_em->flush();

        return $club;
    }

    public function deleteClub(Club $detachedClub)
    {
        $club = $this->_em->merge($detachedClub);
        $this->_em->remove($club);
        $this->_em->flush();
    }

    public function findAllClubs(){

        return $this->createQueryBuilder(self::ALIAS)->getQuery()->getResult();
    }

    public function findClubById($id){

        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    public function showRanking(){
        $sql = "SELECT club.id, club.name, IFNULL(final_table.points, 0) as points, IFNULL(( wins + loss +draws ), 0) as played, IFNULL(final_table.wins, 0) as wins, IFNULL(final_table.draws, 0) as draws, IFNULL(final_table.loss, 0) as loss, IFNULL(final_table.gf, 0) as gf, IFNULL(final_table.ga, 0) as ga, IFNULL((gf - ga), 0) as gd FROM `club` LEFT JOIN(
    SELECT
        id,
        NAME,
        SUM(points) as points,
        SUM(wins) as wins,
        SUM(loss) as loss,
        SUM(draws) as draws,
        SUM(gf) as gf,
        SUM(ga) as ga
    FROM
        (
        SELECT
            club.id,
            club.name,
            SUM(
                CASE WHEN home_goals > guest_goals THEN 3 WHEN home_goals = guest_goals THEN 1 ELSE 0
            END
            ) AS points,
            SUM(
                    CASE WHEN home_goals > guest_goals THEN 1 ELSE 0
                END
            ) AS wins,
            SUM(
                    CASE WHEN home_goals < guest_goals THEN 1 ELSE 0
                END
            ) AS loss,
            SUM(
                    CASE WHEN home_goals = guest_goals THEN 1 ELSE 0
                END
            ) AS draws,
            SUM(home_goals) as gf,
            SUM(guest_goals) as ga
        FROM
            `game`
        LEFT JOIN club ON game.home_club_id = club.id
        GROUP BY
            home_club_id
        UNION ALL
        SELECT
            club.id,
            club.name,
            SUM(
                CASE WHEN guest_goals > home_goals THEN 3 WHEN home_goals = guest_goals THEN 1 ELSE 0
            END
        ) AS points,
            SUM(
                CASE WHEN guest_goals > home_goals THEN 1 ELSE 0
            END
        ) AS wins,
            SUM(
                CASE WHEN guest_goals < home_goals THEN 1 ELSE 0
            END
        ) AS loss,
            SUM(
                CASE WHEN guest_goals = home_goals THEN 1 ELSE 0
            END
        ) AS draws,
            SUM(guest_goals) as gf,
            SUM(home_goals) as ga
        FROM
            `game`
        LEFT JOIN club ON game.guest_club_id = club.id
        GROUP BY
            guest_club_id
    ) all_games
    GROUP BY
        id) as final_table
        ON club.id = final_table.id ORDER BY final_table.points DESC";

        $statement = $this->_em->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }


    // /**
    //  * @return Club[] Returns an array of Club objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Club
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
//    SELECT CASE WHEN home_club_id = 1 THEN SUM(CASE WHEN home_goals > guest_goals THEN 3 WHEN home_goals = guest_goals
//      THEN 1 ELSE 0 END) WHEN guest_club_id = 1 THEN SUM(CASE WHEN guest_goals > home_goals THEN 3
//      WHEN home_goals = guest_goals THEN 1 ELSE 0 END)END as points, SUM(CASE WHEN home_club_id =1 THEN 1
//      WHEN guest_club_id= 1 THEN 1 ELSE 0 END) as games FROM `game`
}
