<?php

return [
    \API\Middleware\CORSHandler::class,
    \API\Middleware\TrailingSlash::class,
    \API\Middleware\AuthorMiddleware::class,
];
