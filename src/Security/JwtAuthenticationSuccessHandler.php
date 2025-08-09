<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class JwtAuthenticationSuccessHandler extends AuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {

        $response = parent::onAuthenticationSuccess($request, $token);

        $data = json_decode($response->getContent(), true);
        $jwtToken = $data['token'];
        
        $jsonResponse = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        
        // TODO: add dynamic values for secure & domain
        // name, value, expiration, path
        // domain, secure, httpOnly, raw, sameSite
        $cookie = new Cookie(
            'jwt_token', $jwtToken, time() + 3600, '/',
            null, true, true, false, 'lax'
        );
        
        $jsonResponse->headers->setCookie($cookie);
        
        return $jsonResponse;
    }
}