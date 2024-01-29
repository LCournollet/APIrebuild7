<?php

namespace App\Controller;

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

        $jsonPlayerList = $serializer->serialize($playerList, 'json');
        return new JsonResponse($jsonPlayerList, Response::HTTP_OK, [], true);
    }
}
