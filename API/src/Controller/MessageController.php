<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Repository\MessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageController extends AbstractController
{
    #[Route('/api/messages', name: 'message', methods: ['GET'])]
    public function getAllMessages(MessagesRepository $messagesRepository, SerializerInterface $serializer): JsonResponse
    {
        $listMessages = $messagesRepository->findAll();

        $jsonMessagesList = $serializer->serialize($listMessages, 'json', ['groups' => 'getMessages']);

        return new JsonResponse($jsonMessagesList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/messages/{id}', name: 'detailMessage', methods: ['GET'])]
    public function getMessage(Messages $message, SerializerInterface $serializer): JsonResponse
    {
        $jsonMessage = $serializer->serialize($message, 'json', ['groups' => 'getMessages']);

        return new JsonResponse($jsonMessage, Response::HTTP_OK, [], true);
    }

    #[Route('/api/messages/{id}', name: 'deleteMessage', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un message')]
    public function deleteMessage(Messages $message, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($message);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route("/api/messages", name: "createMessage", methods: ['POST'])]
    public function createClient(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $message = $serializer->deserialize($request->getContent(), Messages::class, 'json');
        $message->setDate(new \DateTime());

        $errors = $validator->validate($message);

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', ['groups' => 'getMessages']);

        $location = $urlGenerator->generate('detailMessage', ["id" => $message->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMessage, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
