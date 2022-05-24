<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 21/03/2018
 * Time: 13:45
 */
$appText = [


    'MICRO1' => "Home",
    'MICRO2' => "About",
    'MICRO3' => "Demos",

    'TEST' => 'Trial',



    'PRESENTATION_TITTLE' => 'The Concepts Behind...',
    'PRESENTATION_SUBTITLE' => 'Some base ideas about the Framework',
    'BANNER_TITTLE' => 'Some Random Title',





    'PRESENTATION' => 'Simplicity and ease of use are the main ideas underlined on this framework. Starting with a minimal dependency of just tree packages, guzzlehttp/psr7 psr/http-message http-interop/response-sender
The code is based on the Model View Controller pattern MVC, and old but effective architecture. All controllers implement the Psr\Http\Message\MessageInterface Methods. Allowing the use in the pipeline of any other middleware implementing this standard
All main classes are based on Interfaces, like the RenderInterface or RouterInterface, making very easy to change the logic behind, like for example replacing the custom router class that ships with the framework with some other like for example coffeecode/router or the dependency injection by using a more advanced Container (*) like PHP-DI 6 or even a Symphony component like the DependencyInjection. For the render system, change it, if you can find a better one than Twig, the renderer that I chose to be used by microPHP, and now the official template render for Symphony.
The simplicity of the index.php file reflects the all idea behind the framework.
(*) The DIContainer Class is in fact a simple key value array with some methods to get and set the values, I called a box, microPHP is on a box! The main concept of the framework is dependency injection thru the container from witch the dispatcher will request all controller matched by the router.If you need a real Dependency Injection Container use one of the mentioned above or chose from many available on packagist',


    'LOG_IN' => "LogIn",
    'REGISTER' => "Register",
    'DASHBOARD' => "Profile",
    'LOG_OUT' => "LogOut",




    // Author Model


    ## Dates
    'YEAR' => 'Year',
    'MONTH'=> 'Month',
    'DAY'=> 'Day',
    'HOUR'=> 'Hour',
    'MINUTE'=> 'Minute',
    'SECOND'=> 'Second',


    ## Dates
    'YEARS' => 'Years',
    'MONTHS'=> 'Months',
    'DAYS'=> 'Days',
    'HOURS'=> 'Hours',
    'MINUTES'=> 'Minutes',
    'SECONDS'=> 'Seconds',


    'BEFORE'=> 'Before',
    'STARTED'=> 'Started',
    'ENDED'=> 'Ended',

];

