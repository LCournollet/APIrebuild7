<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $playerName = null;

    #[ORM\Column]
    private ?int $playerAge = null;

    #[ORM\Column]
    private ?int $playerPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $playerPicture = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Team $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): static
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getPlayerAge(): ?int
    {
        return $this->playerAge;
    }

    public function setPlayerAge(int $playerAge): static
    {
        $this->playerAge = $playerAge;

        return $this;
    }

    public function getPlayerPrice(): ?int
    {
        return $this->playerPrice;
    }

    public function setPlayerPrice(int $playerPrice): static
    {
        $this->playerPrice = $playerPrice;

        return $this;
    }

    public function getPlayerPicture(): ?string
    {
        return $this->playerPicture;
    }

    public function setPlayerPicture(string $playerPicture): static
    {
        $this->playerPicture = $playerPicture;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }
}