<?php


namespace API\Middleware;


use API\Core\Utils\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PipedMiddleware2 implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
//        Logger::log('PipedMiddleware2');
        return $handler->handle($request);
    }

}