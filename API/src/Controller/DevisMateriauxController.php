<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\DevisMateriaux;
use App\Entity\Materiaux;
use App\Repository\DevisMateriauxRepository;
use App\Repository\DevisRepository;
use App\Repository\MateriauxRepository;
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

class DevisMateriauxController extends AbstractController
{
    #[Route('/api/devis-materiaux', name: 'devis-materiaux', methods: ['GET'])]
    public function getAllDevisMateriaux(DevisMateriauxRepository $devisMateriauxRepository, SerializerInterface $serializer): JsonResponse
    {

        $listDevisMateriaux = $devisMateriauxRepository->findAll();

        $jsonDevisMateriauxList = $serializer->serialize($listDevisMateriaux, 'json', ['groups' => ["getDevis", "getClientDevis", "getDevisMateriaux"]]);
        // Ici le "getClientDevis" me permet de recuperer les infos necessaire du client associé au devis sinon je recuperer un tableau vide dans client

        return new JsonResponse($jsonDevisMateriauxList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/devis-materiaux/devis/{devis}', name: 'allDetailDevisMateriaux', methods: ['GET'])]
    public function getAllDetailDevisMateriaux(Devis $devis, DevisMateriaux $devisMateriaux, SerializerInterface $serializer): JsonResponse
    {
        // Récupérez le DevisMateriaux associé au Devis
        $devisMateriaux = $devis->getDevisMateriauxes();

        if (!$devisMateriaux) {
            return new JsonResponse(['message' => 'DevisMateriaux not found'], 404);
        }

        // Sérialisez l'objet DevisMateriaux en JSON
        $jsonDevisMateriaux = $serializer->serialize($devisMateriaux, 'json', ['groups' => ["getDevis", "getClientDevis", "getDevisMateriaux"]]);

        return new JsonResponse($jsonDevisMateriaux, Response::HTTP_OK, [], true);
    }

    #[Route('/api/devis-materiaux/{id}', name: 'detailDevisMateriaux', methods: ['GET'])]
    public function getDevisMateriaux(DevisMateriaux $devisMateriaux, SerializerInterface $serializer): JsonResponse
    {
        //On récupére uniquement la ligne avec l'id passé dans le lien grace a l'injection de dependance de symfony
        $jsonDevisMateriaux = $serializer->serialize($devisMateriaux, 'json', ['groups' => ["getDevis", "getClientDevis", "getDevisMateriaux"]]);

        return new JsonResponse($jsonDevisMateriaux, Response::HTTP_OK, [], true);
    }

    #[Route("/api/devis-materiaux", name: "createDevisMateriaux", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un devis')]
    public function createDevisMateriaux(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, MateriauxRepository $materiauxRepository, DevisRepository $devisRepository): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Récupérer l'ID du devis à partir de la requête JSON
        $devisId = $requestData['devis'];
        $materiauxId = $requestData['materiaux'];

        // Rechercher le devis dans la base de données
        $devis = $devisRepository->find($devisId);
        $materiaux = $materiauxRepository->find($materiauxId);

        if (!$devis || !$materiaux) {
            return new JsonResponse(['message' => 'Devis ou materiaux not found'], Response::HTTP_NOT_FOUND);
        }

        // Créer un nouvel objet DevisMateriaux
        $devisMateriaux = new DevisMateriaux();
        $devisMateriaux->setQuantite($requestData['quantite']);
        $devisMateriaux->setDevis($devis); // Associer le devis au DevisMateriaux
        $devisMateriaux->setMateriaux($materiaux); // Associez le matériau

        // Valider l'objet DevisMateriaux
        $errors = $validator->validate($devisMateriaux);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['message' => 'Erreur', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Enregistrer le DevisMateriaux en base de données
        $em->persist($devisMateriaux);
        $em->flush();

        $jsonDevisMateriaux = $serializer->serialize($devisMateriaux, 'json', ['groups' => ['getDevisMateriaux']]);

        // $location = $urlGenerator->generate('detailDevisMateriaux', ["id" => $devisMateriaux->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonDevisMateriaux, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/devis-materiaux/{id}', name: 'deleteDetailDevisMateriaux', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un devis')]
    public function deleteDetailDevisMateriaux(DevisMateriaux $devisMateriaux, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($devisMateriaux);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/devis-materiaux/devis/{devis}', name: 'deleteDevisMateriaux', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un devis')]
    public function deleteDevisMateriaux(Devis $devis, EntityManagerInterface $em): JsonResponse
    {
        $devisId = $devis->getId();

        // Récuperer toutes les lignes de la table devis_materiaux associées à $devisId
        $devisMateriauxList = $em->getRepository(DevisMateriaux::class)->findBy(['devis' => $devisId]);

        foreach ($devisMateriauxList as $devisMateriaux) {
            $em->remove($devisMateriaux);
        }

        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/devis-materiaux/{id}', name: "updateDevisMateriaux", methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour modifier un devis')]
    public function updateDevisMateriaux(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, DevisMateriaux $currentDevisMateriaux, Materiaux $materiaux, MateriauxRepository $materiauxRepository, ValidatorInterface $validator): JsonResponse
    {
        $updatedDevisMateriaux = $serializer->deserialize($request->getContent(), DevisMateriaux::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentDevisMateriaux]);

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['materiaux'])) {
            $newMateriaux = $materiauxRepository->find($requestData['materiaux']);
            $updatedDevisMateriaux->setMateriaux($newMateriaux);
        }

        $errors = $validator->validate($updatedDevisMateriaux);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }
        $em->persist($updatedDevisMateriaux);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
