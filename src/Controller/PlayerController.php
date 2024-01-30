<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    #[IsGranted('ROLE_ADMIN', message:"Vous devez disposer des droits pour pouvoir créer un joueur")]
    public function createPlayer(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, TeamRepository $teamRepository, ValidatorInterface $validator): JsonResponse 
    {
        $player = $serializer->deserialize($request->getContent(), Player::class, 'json');

        $errors = $validator->validate($player);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($player);
        $em->flush();

        $content = $request->toArray();
        $idTeam = $content['idTeam'] ?? -1;
   
        $player->setTeam($teamRepository->find($idTeam));

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
