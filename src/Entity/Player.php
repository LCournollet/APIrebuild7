<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPlayers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPlayers"])]
    private ?string $playerName = null;

    #[ORM\Column]
    #[Groups(["getPlayers"])]
    private ?int $playerAge = null;

    #[ORM\Column]
    #[Groups(["getPlayers"])]
    private ?int $playerPrice = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPlayers"])]
    private ?string $playerPicture = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[Groups(["getPlayers"])]
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