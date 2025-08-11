<?php

namespace App\Controller;

use App\Entity\AuthUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
}
