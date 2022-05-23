<?php


namespace API\Middleware;


use API\Core\Utils\Logger;
use API\Interfaces\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorMiddleware implements MiddlewareInterface
{

    public function __construct(RouterInterface $router, string $authorService, array $ignoredMethods)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Logger::log('AuthorMiddleware PIPED');
        return $handler->handle($request);
    }
}