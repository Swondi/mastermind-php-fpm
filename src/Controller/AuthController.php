<?php

namespace App\Controller;

use App\Entity\AuthUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/api/auth/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $data = json_decode($request->getContent());

        $user = new AuthUser();
        $user->setEmail($data->email);
        $user->setPassword($hasher->hashPassword($user, $data->password));

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'User registered successfully']);
    }

    #[Route('/api/auth/login', name: 'login', methods: ['POST'])]
    public function login() { } // Handled by the JwtHandler

    #[Route('/api/auth/logout', name: 'logout', methods: ['GET'])]
    public function logout(): Response
    {
        $response = $this->json(['message' => 'Logout successful']);
        
        $response->headers->clearCookie('jwt_token', '/');
        
        return $response;
    }

    #[Route('/api/debug/auth', name: 'debug_auth', methods: ['GET'])]
    public function debugAuth(Request $request): Response
    {
        $authHeader = $request->headers->get('Authorization');
        $cookieToken = $request->cookies->get('jwt_token');
        
        return $this->json([
            'authorization_header' => $authHeader,
            'cookie_token' => $cookieToken ? 'Present (length: ' . strlen($cookieToken) . ')' : 'Not present',
            'all_headers' => $request->headers->all(),
            'all_cookies' => $request->cookies->all(),
        ]);
    }
    
    #[Route('/api/get/data', name: 'test', methods: ['GET'])]
    public function test(): Response
    {
        /** @var AuthUser $user */
        $user = $this->getUser();

        return $this->json([
            'user' => $user->getEmail(),
        ]);
    }
}
