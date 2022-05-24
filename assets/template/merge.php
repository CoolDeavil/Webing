<?php

use API\Core\Container\MicroDI;

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');

$modules1=[];
$modules2=[];
/**@var $bootstrap */
$ioc = MicroDI::getInstance($bootstrap);
$pipe_modules1 = include_once MODULES_PIPE;
$pipe_modules2 = include_once MODULES_PIPE2;

$params = [
    'router' => $ioc->get(\API\Interfaces\RouterInterface::class),
    'render' => $ioc->get(\API\Interfaces\RenderInterface::class),
];

foreach ($pipe_modules1 as $module) {
    $modules1[] = $ioc->get($module, $params);
}
foreach ($pipe_modules2 as $module) {
    $modules2[] = $ioc->get($module, $params);
}


$unique = array_unique( array_merge(
    $modules1,
    $modules2
), SORT_REGULAR );


dump($unique);