<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location implements BaseEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Positive
     * @Assert\Length(min="5", max="5", minMessage = "Location name must be {{ limit }} characters long", maxMessage = "Location name must be {{ limit }} characters long")
     */
    private $ptt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


    public function getPtt(): ?int
    {
        return $this->ptt;
    }

    public function setPtt(int $ptt): self
    {
        $this->ptt = $ptt;

        return $this;
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
}
