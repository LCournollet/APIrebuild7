<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends AbstractController
{
    #[Route('/api/players', name: 'player', methods: ['GET'])]
    public function getPlayerList(PlayerRepository $playerRepository, SerializerInterface $serializer): JsonResponse
    {
        $playerList = $playerRepository->findAll();

        $jsonPlayerList = $serializer->serialize($playerList, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonPlayerList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/players/{id}', name: 'detailPlayer', methods: ['GET'])]
    public function getDetailPlayer(Player $player, SerializerInterface $serializer)
    {
        $jsonPlayer = $serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonPlayer, Response::HTTP_OK, [], true);
    }
}
