<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 21/03/2018
 * Time: 13:45
 */

$appText = [



    'MICRO1' => "Inicio",
    'MICRO2' => "Sobre",
    'MICRO3' => "Exemplos",


    'PRESENTATION_TITTLE' => 'Os conceitos por trás',
    'PRESENTATION_SUBTITLE' => 'Algumas ideias básicas sobre o Framework',
    'BANNER_TITTLE' => 'Algum título aleatório',


    'PRESENTATION' => 'Simplicidade e facilidade de uso são as principais ideias sublinhadas neste framework. Começando com uma dependência mínima de apenas pacotes de árvore, guzzlehttp/psr7 psr/http-message http-interop/response-sender
O código é baseado no padrão MVC do Model View Controller e na arquitetura antiga, mas eficaz. Todos os controladores implementam os métodos Psr\Http\Message\MessageInterface. Permitindo o uso no pipeline de qualquer outro middleware que implemente este padrão
Todas as classes principais são baseadas em Interfaces, como RenderInterface ou RouterInterface, tornando muito fácil mudar a lógica por trás, como por exemplo, substituir a classe de roteador personalizada que acompanha o framework por outra como, por exemplo, coffeecode/router ou a injeção de dependência por usando um Container (*) mais avançado como PHP-DI 6 ou até mesmo um componente Symphony como o DependencyInjection. Para o sistema de renderização, altere-o, caso encontre um melhor que o Twig, o renderizador que escolhi para ser usado pelo microPHP, e agora o modelo oficial de renderização do Symphony.
A simplicidade do arquivo index.php reflete toda a ideia por trás do framework.
(*) A classe DIContainer é na verdade um array de key value simples com alguns métodos para obter e definir os valores, chamei de box, microPHP está em uma caixa! O conceito principal do framework é a injeção de dependência através do container de onde o despachante irá requisitar todos os controllers combinados pelo roteador.',





    'LOG_IN' => "LogIn",
    'REGISTER' => "Registo",
    'DASHBOARD' => "Perfil",
    'LOG_OUT' => "Sair",


    'TEST' => 'Experimental',

    ## Dates
    'YEARS' => 'Anos',
    'MONTHS'=> 'Meses',
    'DAYS'=> 'Dias',
    'HOURS'=> 'Horas',
    'MINUTES'=> 'Minutos',
    'SECONDS'=> 'Segundos',

    'YEAR' => 'Ano',
    'MONTH'=> 'Mês',
    'DAY'=> 'Dia',
    'HOUR'=> 'Hora',
    'MINUTE'=> 'Minuto',
    'SECOND'=> 'Segundo',

    'BEFORE'=> 'atras',
    'STARTED'=> 'Iniciou',
    'ENDED'=> 'Terminou',


];
