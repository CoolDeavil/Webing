<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 21/03/2018
 * Time: 13:45
 */
$appText = [



    'MICRO1' => "Domicile",
    'MICRO2' => "Sur",
    'MICRO3' => "Demos",


    'TEST' => 'Trial',



    'BANNER_TITTLE' => 'Un titre aléatoire',
    'PRESENTATION_TITTLE' => 'Les concepts derrière...',
    'PRESENTATION_SUBTITLE' => 'Quelques idées de base sur le Framework',

    'PRESENTATION' => "La simplicité et la facilité d'utilisation sont les principales idées soulignées sur ce framework. En commençant par une dépendance minimale des packages arborescents, guzzlehttp/psr7 psr/http-message http-interop/response-sender
Le code est basé sur le modèle MVC de Model View Controller et sur une architecture ancienne mais efficace. Tous les contrôleurs implémentent les méthodes Psr\Http\Message\MessageInterface. Autoriser l'utilisation dans le pipeline de tout autre middleware implémentant cette norme
Toutes les classes principales sont basées sur des interfaces, comme RenderInterface ou RouterInterface, ce qui permet de changer très facilement la logique derrière, comme par exemple remplacer la classe de routeur personnalisée qui est livrée avec le framework par une autre comme par exemple coffeecode/router ou l'injection de dépendance par en utilisant un Container (*) plus avancé comme PHP-DI 6 ou même un composant Symphony comme DependencyInjection. Pour le système de rendu, changez-le, si vous pouvez en trouver un meilleur que Twig, le moteur de rendu que j'ai choisi pour être utilisé par microPHP, et maintenant le modèle de rendu officiel pour Symphony.
La simplicité du fichier index.php reflète toute l'idée derrière le framework.
(*) La classe DIContainer est en fait un simple tableau de valeurs de clés avec quelques méthodes pour obtenir et définir les valeurs, j'ai appelé une boîte, microPHP est sur une boîte ! Le concept principal du framework est l'injection de dépendances à travers le conteneur à partir duquel le répartiteur demandera tous les contrôleurs correspondants au routeur.",

    'LOG_IN' => "LogIn",
    'REGISTER' => "Register",
    'DASHBOARD' => "Profile",
    'LOG_OUT' => "LogOut",

    ## Dates
    'YEAR' => 'Année',
    'MONTH'=> 'Mois',
    'DAY'=> 'journée',
    'HOUR'=> 'Heure',
    'MINUTE'=> 'Minute',
    'SECOND'=> 'Seconde',


    ## Dates
    'YEARS' => 'Années',
    'MONTHS'=> 'Mois',
    'DAYS'=> 'Journées',
    'HOURS'=> 'Heures',
    'MINUTES'=> 'Minutes',
    'SECONDS'=> 'Secondes',


    'BEFORE'=> 'Before',
    'STARTED'=> 'Started',
    'ENDED'=> 'Ended',

];
