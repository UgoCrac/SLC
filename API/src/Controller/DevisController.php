<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Repository\ClientsRepository;
use App\Repository\DevisMateriauxRepository;
use App\Repository\DevisRepository;
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

class DevisController extends AbstractController
{
    #[Route('/api/devis', name: 'devis', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour voir les devis')]
    public function getAllDevis(DevisRepository $devisRepository, SerializerInterface $serializer): JsonResponse
    {
        $listDevis = $devisRepository->findAll();

        $jsonDevisList = $serializer->serialize($listDevis, 'json', ['groups' => ["getDevis", "getClientDevis", "getDevisMateriaux"]]);
        // Ici le "getClientDevis" me permet de recuperer les infos necessaire du client associé au devis sinon 
        // je recuperer un tableau vide dans client

        return new JsonResponse($jsonDevisList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/devis/{id}', name: 'detailDevis', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour voir un devis')]
    public function getDevis(Devis $devis, SerializerInterface $serializer): JsonResponse
    {
        $jsonDevis = $serializer->serialize($devis, 'json', ['groups' => ["getDevis", "getClientDevis", "getDevisMateriaux"]]);

        return new JsonResponse($jsonDevis, Response::HTTP_OK, [], true);
    }

    #[Route("/api/devis", name: "createDevis", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un devis')]
    public function createDevis(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ClientsRepository $clientsRepository, ValidatorInterface $validator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Récuperer l'ID du client à partir de la requête JSON
        $clientId = $requestData['client'];

        // Recuperer le client dans la bdd
        $client = $clientsRepository->find($clientId);

        if (!$client) {
            return new JsonResponse(['message' => 'Client not found'], 404);
        }

        // Je crée un nouveau devis en associant le client existant
        $devis = new Devis();
        $devis->setAdresse($requestData['adresse']);
        $devis->setDate(new \DateTime($requestData['date']));
        $devis->setClient($client);

        $errors = $validator->validate($devis);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($devis);
        $em->flush();

        $jsonDevis = $serializer->serialize($devis, 'json', ['groups' => ['getDevis', 'getClientDevis']]);

        $location = $urlGenerator->generate('detailClient', ["id" => $devis->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonDevis, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/devis/{id}', name: 'deleteDevis', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un devis')]
    public function deleteDevis(Devis $devis, EntityManagerInterface $em, DevisMateriauxRepository $devisMateriauxRepository): JsonResponse
    {

        $devisId = $devis->getId();
        $devisIdTrue = $devisMateriauxRepository->findBy(['devis' => $devisId]);

        if ($devisIdTrue) {
            foreach ($devisIdTrue as $d) {
                $em->remove($d);
            }
        }

        $em->remove($devis);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        // On renvoie null car l'objet $devis a été supprimé et on verifie si on a bien le code HTTP_NO_CONTENT
    }

    #[Route('/api/devis/{id}', name: "updateDevis", methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour modifier un devis')]
    public function updateDevis(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Devis $currentDevis, ValidatorInterface $validator): JsonResponse
    {
        $updatedDevis = $serializer->deserialize($request->getContent(), Devis::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentDevis]);
        // $currentDevis est le devis dans la bdd avant la modif, ici avec la method OBJECT_TO_POPULATE on specifie qu'on va écrire et modifier cet element

        $errors = $validator->validate($updatedDevis);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($updatedDevis);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
