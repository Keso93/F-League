<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PerformanceRepository")
 */
class Performance implements BaseEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $playerRating;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="performances")
     */
    protected $player;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="performances")
     */
    protected $game;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


    public function getPlayerRating(): ?float
    {
        return $this->playerRating;
    }

    public function setPlayerRating(float $playerRating): self
    {
        $this->playerRating = $playerRating;

        return $this;
    }

    public function setGame(?Game $game){
        $this->game = $game;
    }

    public function getGame(){
        return $this->game;
    }

    public function setPlayer(?Player $player){
        $this->player = $player;
    }

    public function getPlayer(){
        return $this->player;
    }
}
