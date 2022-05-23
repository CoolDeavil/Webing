<?php


namespace API\Middleware;


use API\Core\Utils\GetIPAddress;
use API\Core\Utils\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SecureRouteMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ip = GetIPAddress::getIP();
        Logger::log('Private Route was Accessed by : '.$ip);
        return $handler->handle($request);
    }
}