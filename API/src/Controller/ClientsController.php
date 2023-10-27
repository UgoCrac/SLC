<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientsController extends AbstractController
{

    // CRUD Clients

    #[Route('/api/clients', name: 'clients', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les clients')]
    public function getAllClients(ClientsRepository $clientsRepository, SerializerInterface $serializer): JsonResponse
    {
        $listClients = $clientsRepository->findAll();
        // Récuperer tous les clients

        $jsonClientsList = $serializer->serialize($listClients, 'json', ['groups' => "getClients"]);
        // On serialise ( convertir les objets recu en JSON) et le [groups] sert a récupérer uniquement les infos que je souhaite et pour éviter circular reference

        return new JsonResponse($jsonClientsList, Response::HTTP_OK, [], true);
        // On renvoie la réponse en JSON avec le code HTTP, le header

    }

    #[Route('/api/clients/{id}', name: 'detailClient', methods: ['GET'])]
    public function getClient(Clients $client, SerializerInterface $serializer): JsonResponse
    {
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => "getClients"]);

        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    #[Route('/api/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un client')]
    public function deleteClient(Clients $client, EntityManagerInterface $em): JsonResponse
    {
        $devis = $client->getDevis();
        // Recuperer et supprimer les devis associés au client
        if ($devis) {
            foreach ($devis as $d) {
                $em->remove($d);
            }
        }
        $em->remove($client);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        // On renvoie null car l'objet $client a été supprimé et on verifie si on a bien le code HTTP_NO_CONTENT
    }

    #[Route("/api/clients", name: "createClient", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un client')]
    public function createClient(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $client = $serializer->deserialize($request->getContent(), Clients::class, 'json');
        // On deserialize pour recuperer le JSON recu dans le body de la requete en Objet basé sur la classe Clients

        $errors = $validator->validate($client);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($client);
        // On envoie le client
        $em->flush();

        $jsonClient = $serializer->serialize($client, 'json', ['groups' => 'getClients']);

        $location = $urlGenerator->generate('detailClient', ["id" => $client->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonClient, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/clients/{id}', name: "updateClient", methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour mettre à jour un client')]
    public function updateClient(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Clients $currentClient, ValidatorInterface $validator): JsonResponse
    {
        $updatedClient = $serializer->deserialize($request->getContent(), Clients::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentClient]);
        // $currentClient est le client dans la bdd avant la modif, ici avec la method OBJECT_TO_POPULATE on specifie qu'on va écrire et modifier cet element

        $errors = $validator->validate($updatedClient);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($updatedClient);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
