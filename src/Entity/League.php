<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeagueRepository")
 */
class League implements BaseEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * One league has many games. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Game", mappedBy="league")
     */
    private $game;

    public function __construct() {
        $this->game = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $leagueName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


    public function getLeagueName(): ?string
    {
        return $this->leagueName;
    }

    public function setLeagueName(string $leagueName): self
    {
        $this->leagueName = $leagueName;

        return $this;
    }
}
