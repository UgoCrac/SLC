<?php

namespace App\Controller;

use App\Entity\Photos;
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

class PhotosController extends AbstractController
{

    // CRUD Photos

    #[Route('/api/photos', name: 'photos', methods: ['GET'])]
    public function getAllPhotos(PhotosRepository $photosRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPhotos = $photosRepository->findAll();
        // Récuperer toutes les photos

        $jsonPhotosList = $serializer->serialize($listPhotos, 'json');

        return new JsonResponse($jsonPhotosList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/photos/{id}', name: 'detailPhoto', methods: ['GET'])]
    public function getPhoto(Photos $photo, SerializerInterface $serializer): JsonResponse
    {
        $jsonPhoto = $serializer->serialize($photo, 'json');

        return new JsonResponse($jsonPhoto, Response::HTTP_OK, [], true);
    }

    #[Route('/api/photos', name: "createPhoto", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour ajouter des photos')]
    public function createPhoto(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $photo = $serializer->deserialize($request->getContent(), Photos::class, 'json');

        $errors = $validator->validate($photo);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }
        $em->persist($photo);
        $em->flush();

        $jsonPhoto = $serializer->serialize($photo, 'json');

        $location = $urlGenerator->generate(
            'detailPhoto',
            ["id" => $photo->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($jsonPhoto, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/photos/{id}', name: "deletePhoto", methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer des photos')]
    public function deletePhoto(Photos $photo, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($photo);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/photos/{id}', name: 'updatePhoto', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour modifier des photos')]
    public function updatePhoto(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Photos $currentPhoto, ValidatorInterface $validator): JsonResponse
    {
        $updatedPhoto = $serializer->deserialize($request->getContent(), Photos::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentPhoto]);

        $errors = $validator->validate($updatedPhoto);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }
        $em->persist($updatedPhoto);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
