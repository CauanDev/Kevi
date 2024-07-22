<?php

namespace Http\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        
        $verifyHeader = $request->getHeaderLine('Authorization');
        
        if ($verifyHeader !== $_ENV['AUTHORIZATION_KEY_API']) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'You Should Not Pass','teste'=>$verifyHeader]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
