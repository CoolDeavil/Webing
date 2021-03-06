<?php

use API\Core\Router\MRoute;
use API\Validation\CheckIfRegisteredUser;
use API\Validation\CheckIfUniqueEmail;
use API\Core\App\{
    Dispatcher,
    Micro
};
use API\Controllers\{AuthorController, MicroController};
use API\Core\Database\Database;
use API\Core\Render\{
    TwigRenderer,
    TwigRendererExtensions,
};
use API\Core\Router\MRouter;
use API\Core\Utils\{FormBuilder, Logger, NavBuilder\NavBuilder, Translate, Validator};

;
use API\Interfaces\{
    ContainerInterface,
    RenderInterface,
    RouterInterface
};
use API\Middleware\{AuthorMiddleware,
    CORSHandler,
    LastIntent,
    TrailingSlash};


use API\Repository\{AuthModelRepository, GeneralRepository};

use GuzzleHttp\Psr7\ServerRequest;
use Twig\{
    Environment,
    Extension\DebugExtension,
    Loader\FilesystemLoader
};

return [
    // App System
    Micro::class => function(ContainerInterface $container){
        return new Micro($container);
    },
    Translate::class => function () {
        return Translate::getInstance();
    },
    Validator::class => function(ContainerInterface $ioc){
        return new Validator($ioc);
    },
    NavBuilder::class => function (ContainerInterface $container) {
        $router = $container->get(RouterInterface::class);
        $render = $container->get(RenderInterface::class);
        $translate = $container->get(Translate::class);
        return new NavBuilder($router, $render, $translate);
    },
    // Interfaces
    RouterInterface::class => function (ContainerInterface $container) {
        return MRouter::getInstance(ServerRequest::fromGlobals(),$container);
    },
    MRoute::class => function($args){
        extract($args);
        /** @var $route */
        /** @var $callable */
        /** @var $method */
        /** @var $name */
        return new MRoute($route, $callable, $method, $name);
    },
    RenderInterface::class => function (ContainerInterface $container) {
        $loader = new FilesystemLoader(APP_VIEWS);
        $twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new TwigRendererExtensions($container));
        return new TwigRenderer($loader, $twig,$container);
    },
    // Repos
    GeneralRepository::class => function(){
        return new GeneralRepository(Database::getInstance());
    },
    // Controllers
    MicroController::class => function ($args, ContainerInterface $ioc ){
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new MicroController(
            $router,
            $render,
            new \API\Core\Utils\Validator($ioc)
        );
    },
    AuthorController::class => function ($args, ContainerInterface $ioc ){
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new AuthorController(
            $router,
            $render,
            new Validator($ioc),
            new FormBuilder($render, $router),
            new AuthModelRepository(Database::getInstance())
        );
    },
    FormBuilder::class => function( ContainerInterface $ioc){
     return new FormBuilder(
            $ioc->get(RenderInterface::class),
            $ioc->get(RouterInterface::class)
        );
    },

    // Middleware
    Dispatcher::class => function(ContainerInterface $ioc){
        return new Dispatcher($ioc);
    },
    AuthorMiddleware::class => function(ContainerInterface $ioc){
        $authorService = 'authorService';
        $ignoredMethods = ['index','create'];
        $router = $ioc->get(RouterInterface::class);
        return new AuthorMiddleware($router, $authorService, $ignoredMethods);
    },
    'AuthorMiddleware' => function(ContainerInterface $ioc){
        return $ioc->get(AuthorMiddleware::class);
    },

    LastIntent::class => function () {
        $ignored = [
            '/error404',
        ];
        return new LastIntent($ignored);
    },
    CORSHandler::class => function(){
        return new CORSHandler();
    },
    TrailingSlash::class => function(){
        return new TrailingSlash();
    },

    // Validations
    'minLength' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\MinLength($translate);
    },
    'notEmpty' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\NotEmpty($translate);
    },
    'range' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\Range($translate);
    },
    'securePass' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\SecurePass($translate);
    },
    'checkEmail' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\ValidEMail($translate);
    },
    'noZeroSelection' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\NoZeroSelection($translate);
    },
    'checked' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new \API\Validation\Checked($translate);
    },
    'equalTo' => function (ContainerInterface $container) {
        return new \API\Validation\EqualTo( $container->get(Translate::class));
    },
    'checkIfUniqueEmail' => function(ContainerInterface $container){
        return new CheckIfUniqueEmail(
            $container->get(Translate::class),
            new AuthModelRepository(Database::getInstance()
            ));

    },
    'checkIfRegisteredUser' => function(ContainerInterface $container){
        return new CheckIfRegisteredUser(
            $container->get(Translate::class),
            new AuthModelRepository(Database::getInstance())
        );

    }
];

