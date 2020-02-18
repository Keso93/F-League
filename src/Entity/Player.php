<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player extends User implements BaseEntityInterface
{


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(callback="getPositions")
     */
    private $position;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="players")
     */
    private $location;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="players")
     */
    private $club;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $surname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", inversedBy="players", cascade={"persist", "remove", "merge"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Positive
     */
    private $JMBG;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $birthDate;

    /**
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="player", cascade={"remove"})
     */
    protected $performances;

    public function __construct()
    {
        $this->performances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getJMBG(): ?string
    {
        return $this->JMBG;
    }

    public function setJMBG(string $JMBG): self
    {
        $this->JMBG = $JMBG;

        return $this;
    }

    public function getBirthDate():?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public static function getPositions()
    {
        return ['CB', 'CMF', 'LB', 'CF'];
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getFullName(){
        return sprintf("%s %s", $this->name, $this->surname);
    }
}
