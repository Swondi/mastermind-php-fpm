<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Entity\Session;
use App\Repository\AuthUserRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $jwtManager,
        AuthUserRepository $authUserRepository
    ): Response
    {
        $data = json_decode($request->getContent());

        // TODO: handle bad requests in a custom request object

        if (null !== $authUserRepository->findByEmail($data->email)) {
            return $this->json([
                'error' => 'Email already in use'
            ], Response::HTTP_CONFLICT);
        }

        $user = new AuthUser();
        $user->setEmail($data->email);
        $user->setPassword($hasher->hashPassword($user, $data->password));

        
        $session = new Session();
        $session->setAuthUser($user);
        $session->setIpAddress($request->getClientIp());
        $session->setUserAgent($request->headers->get('User-Agent'));
        $user->addSession($session);
        
        $em->persist($user);
        $em->flush();
        
        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);

        $rtCookie = new Cookie(
            'rt', $session->getRefreshToken(), time() + 86400, '/',
            null, true, true, false, 'lax'
        );

        $atCookie = new Cookie(
            'at', $jwtManager->create($user), time() + 900, '/',
            null, true, true, false, 'lax'
        );
        
        $response->headers->setCookie($rtCookie);
        $response->headers->setCookie($atCookie);

        return $response;
    }

    #[Route('/api/auth/refresh', name: 'refresh', methods: ['GET'])]
    public function refresh(
        Request $request,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager,
        SessionRepository $sessionRepository
    ): Response {
        $rt = $request->cookies->get('rt');

        if (!$rt) {
            return $this->json(['error' => 'No refresh token provided'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var Session $session */
        $session = $sessionRepository->findByToken($rt);

        if (!$session) {
            return $this->json(['error' => 'Invalid refresh token'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var AuthUser $user */
        $user = $session->getAuthUser();

        $newSession = new Session();
        $newSession->setAuthUser($user);
        $newSession->setIpAddress($request->getClientIp());
        $newSession->setUserAgent($request->headers->get('User-Agent'));
        $user->addSession($newSession);
        $user->removeSession($session);

        $em->persist($user);
        $em->flush();

        $newRtCookie = new Cookie(
            'rt', $newSession->getRefreshToken(), time() + 86400, '/',
            null, false, true, false, 'lax'
        );

        $newAtCookie = new Cookie(
            'at', $jwtManager->create($user), time() + 900, '/',
            null, false, true, false, 'lax'
        );

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->setCookie($newRtCookie);
        $response->headers->setCookie($newAtCookie);

        return $response;
    }

    #[Route('/api/auth/login', name: 'login', methods: ['POST'])]
    public function login() { } // handled by the lexik jwt

    #[Route('/api/auth/logout', name: 'logout', methods: ['GET'])]
    public function logout(
        Request $request,
        SessionRepository $sessionRepository,
        EntityManagerInterface $em
    ): Response
    {
        $rt = $request->cookies->get('rt');

        if (!$rt) {
            return $this->json(['error' => 'No refresh token provided'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var Session $session */
        $session = $sessionRepository->findByToken($rt);

        if (!$session) {
            return $this->json(['error' => 'Invalid refresh token'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var AuthUser $user */
        $user = $session->getAuthUser();
        $user->removeSession($session);

        $em->persist($user);
        $em->flush();

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        
        $response->headers->clearCookie('at', '/');
        $response->headers->clearCookie('rt', '/');
        
        return $response;
    }

    #[Route('/api/auth/me', name: 'me', methods: ['GET'])]
    public function me(): Response
    {
        $isAuthenticated = !!$this->getUser();
        $projectDir = $this->getParameter('kernel.project_dir');
        $isFirstTime = file_exists($projectDir.'/.firsttime');

        return new JsonResponse([
            'isAuthenticated' => $isAuthenticated,
            'isFirstTime' => $isFirstTime,
        ]);
    }
}
