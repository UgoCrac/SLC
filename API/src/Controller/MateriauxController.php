<?php

namespace App\Controller;

use App\Entity\Materiaux;
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

class MateriauxController extends AbstractController
{
    #[Route('/api/materiaux', name: 'materiaux', methods: ['GET'])]
    public function getAllMateriaux(MateriauxRepository $materiauxRepository, SerializerInterface $serializer): JsonResponse
    {
        $listMateriaux = $materiauxRepository->findAll();
        // Récuperer tous les matériaux

        $jsonMateriauxList = $serializer->serialize($listMateriaux, 'json');
        // On serialise ( convertir les objets recu en JSON)

        return new JsonResponse($jsonMateriauxList, Response::HTTP_OK, [], true);
        // On renvoie la réponse en JSON avec le code HTTP, le header 
    }

    #[Route('/api/materiaux/{id}', name: 'detailMateriaux', methods: ['GET'])]
    public function getMateriaux(Materiaux $materiaux, SerializerInterface $serializer): JsonResponse
    {
        $jsonMateriaux = $serializer->serialize($materiaux, 'json');

        return new JsonResponse($jsonMateriaux, Response::HTTP_OK, [], true);
    }

    #[Route("/api/materiaux", name: "createMateriaux", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour ajouter des matériaux')]
    public function createMateriaux(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $materiaux = $serializer->deserialize($request->getContent(), Materiaux::class, 'json');
        // On deserialize pour recuperer le JSON recu dans le body de la requete en Objet basé sur la classe Materiaux

        $errors = $validator->validate($materiaux);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }
        $em->persist($materiaux);
        // On envoie le materiaux
        $em->flush();

        $jsonMateriaux = $serializer->serialize($materiaux, 'json');

        $location = $urlGenerator->generate('detailMateriaux', ["id" => $materiaux->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMateriaux, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/materiaux/{id}', name: 'deleteMateriaux', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer des matériaux')]
    public function deleteMateriaux(Materiaux $materiaux, EntityManagerInterface $em): JsonResponse
    {

        $em->remove($materiaux);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        // On renvoie null car l'objet $materiaux a été supprimé et on verifie si on a bien le code HTTP_NO_CONTENT
    }

    #[Route('/api/materiaux/{id}', name: "updateMateriaux", methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants modifier des matériaux')]
    public function updateMateriaux(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Materiaux $currentMateriaux, ValidatorInterface $validator): JsonResponse
    {
        $updatedMateriaux = $serializer->deserialize($request->getContent(), Materiaux::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMateriaux]);
        // $currentMateriaux est le materiaux dans la bdd avant la modif, ici avec la method OBJECT_TO_POPULATE on specifie qu'on va écrire et modifier cet element

        $errors = $validator->validate($updatedMateriaux);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }
        $em->persist($updatedMateriaux);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
