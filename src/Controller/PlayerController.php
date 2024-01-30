<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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

    #[Route('/api/players/{id}', name: 'deletePlayer', methods: ['DELETE'])]
    public function deletePlayer(Player $player, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($player);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/players', name:"createPlayer", methods: ['POST'])]
    public function createPlayer(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, TeamRepository $teamRepository): JsonResponse 
    {
        $player = $serializer->deserialize($request->getContent(), Player::class, 'json');

           // Récupération de l'ensemble des données envoyées sous forme de tableau
           $content = $request->toArray();

           // Récupération de l'idTeam. S'il n'est pas défini, alors on met -1 par défaut.
           $idTeam = $content['idTeam'] ?? -1;
   
           $player->setTeam($teamRepository->find($idTeam));

        $em->persist($player);
        $em->flush();

        $jsonPlayer = $serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        
        $location = $urlGenerator->generate('detailPlayer', ['id' => $player->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPlayer, Response::HTTP_CREATED, ["Location" => $location], true);
   }

   #[Route('/api/players/{id}', name:"updatePlayer", methods:['PUT'])]

   public function updatePlayer(Request $request, SerializerInterface $serializer, Player $currentPlayer, EntityManagerInterface $em, TeamRepository $teamRepository): JsonResponse 
   {
       $updatedPlayer = $serializer->deserialize($request->getContent(), 
               Player::class, 
               'json', 
               [AbstractNormalizer::OBJECT_TO_POPULATE => $currentPlayer]);
       $content = $request->toArray();
       $idTeam = $content['idTeam'] ?? -1;
       $updatedPlayer->setTeam($teamRepository->find($idTeam));
       
       $em->persist($updatedPlayer);
       $em->flush();
       return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
