<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Entity\DataUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/user/me', name: 'my_data', methods: [ 'GET' ])]
    public function getMyData(
        SerializerInterface $serializer
    ): Response
    {
        /** @var AuthUser $user */
        $user = $this->getUser();
        $json = $serializer->serialize($user->getDataUser(), 'json', [ 'groups' => [ 'data' ] ]);

        return new Response(
            $json,
            Response::HTTP_OK,
            [ 'Content-Type' => 'application/json']
        );
    }

    #[Route('/api/user/save', name: 'save_user', methods: [ 'POST' ])]
    public function saveMyData(
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): Response
    {
        /** @var AuthUser $user */
        $user = $this->getUser();
        
        /** @var DataUser $dataUser */
        $dataUser = $user->getDataUser();

        try {
            // Deserialize only the provided fields into the existing object
            $serializer->deserialize(
                $request->getContent(),
                DataUser::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $dataUser,
                    AbstractNormalizer::IGNORED_ATTRIBUTES => [
                        'id'
                    ],
                ]
            );
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'error' => 'Invalid JSON.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($dataUser);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
