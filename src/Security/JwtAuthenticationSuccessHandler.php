<?php

namespace App\Security;

use App\Entity\AuthUser;
use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtAuthenticationSuccessHandler extends AuthenticationSuccessHandler
{
    private $em;
    private UserProviderInterface $userProvider;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $em,
        UserProviderInterface $userProvider,
        iterable $cookieProviders = [],
        bool $removeTokenFromBodyWhenCookiesUsed = true,
    )
    {
        parent::__construct(
            $jwtManager,
            $dispatcher,
            $cookieProviders,
            $removeTokenFromBodyWhenCookiesUsed
        );

        $this->em = $em;
        $this->userProvider = $userProvider;
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
    ): JsonResponse
    {
        /** @var AuthUser $user */
        $user = $token->getUser();

        $response = parent::onAuthenticationSuccess($request, $token);

        $data = json_decode($response->getContent(), true);
        $jwtToken = $data['token'];
        
        $jsonResponse = new JsonResponse(null, Response::HTTP_NO_CONTENT);
    
        $session = new Session();
        $session->setAuthUser($user);
        $session->setIpAddress($request->getClientIp());
        $session->setUserAgent($request->headers->get('User-Agent'));
        $user->addSession($session);
        
        $this->em->persist($user);
        $this->em->flush();

        // TODO: add dynamic values for secure & domain
        // name, value, expiration, path, domain, secure, httpOnly, raw, sameSite
        $atCookie = new Cookie(
            'at', $jwtToken, time() + 3600, '/', null, true, true, false, 'lax'
        );

        $rtCookie = new Cookie(
            'rt', $session->getRefreshToken(), time() + 86400, '/', null, true, true, false, 'lax'
        );

        $jsonResponse->headers->setCookie($atCookie);
        $jsonResponse->headers->setCookie($rtCookie);
        
        return $jsonResponse;
    }
}