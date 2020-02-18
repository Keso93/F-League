<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\Validation as ClubAssert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ClubAssert\ContainsSameClubs
 */
class Game implements BaseEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="games", cascade={"persist"})
     */
    private $league;

    /**
     * @ORM\Column(type="date")
     */
    private $gameDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="games", cascade={"persist"})
     */
    private $homeClub;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="games", cascade={"persist"})
     */
    private $guestClub;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $homeGoals;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $guestGoals;

    /**
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="game", cascade={"remove"})
     */
    protected $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }


    public function setId($id){
        $this->id = $id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setHomeGoals($homeGoals){
        $this->homeGoals = $homeGoals;
        return $this;
    }

    public function getHomeGoals(){
        return $this->homeGoals;
    }

    public function setGuestGoals($guestGoals){
        $this->guestGoals = $guestGoals;
        return $this->guestGoals;
    }

    public function getGuestGoals(){
        return $this->guestGoals;
    }

    public function getGameDate(): ?\DateTimeInterface
    {
        return $this->gameDate;
    }

    public function setGameDate(\DateTimeInterface $gameDate): self
    {
        $this->gameDate = $gameDate;

        return $this;
    }

    public function getHomeClub(): ?Club
    {
        return $this->homeClub;
    }

    public function setHomeClub(?Club $homeClub): self
    {
        $this->homeClub = $homeClub;

        return $this;
    }

    public function getGuestClub(): ?Club
    {
        return $this->guestClub;
    }

    public function setGuestClub(?Club $guestClub): self
    {
        $this->guestClub = $guestClub;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }
}
