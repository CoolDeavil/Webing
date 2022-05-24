<?php

namespace API\Controllers;

use GuzzleHttp\Psr7\Response;
use API\Repository\GeneralRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RepositoryInterface;
use API\Interfaces\RouterInterface;
use API\Core\App\Controller;
use API\Core\Session\Session;
use API\Core\Utils\Validator;

/**
 * Class MicroController
 * @package Micro\Controllers
 */
class MicroController extends Controller
{
    public function __construct(
        RouterInterface $router, RenderInterface $render,
        Validator $validator,
    )
    {
        parent::__construct($router, $render, $validator);
        $this->render = $render;
        $this->router = $router;
        $this->validator = $validator;

        $this->router->post('/api/switchLang', [$this, 'switchLanguage'], 'Micro.switchLanguage');
        $this->router->get('/', [$this, 'index'], 'Micro.index');
        $this->router->get('/about', [$this, 'showAbout'], 'Micro.about');
        $this->router->get('/documents/:docPage', [$this, 'showWebPage'], 'Micro.showWebPage')->with('webView',LETTERS);

        $this->router->get('/api', [$this, 'handleRequest'], 'Micro.handleRequest');
        $this->router->get('/helpForCropper', [$this, 'cropHelper'], 'Micro.cropHelper');

    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return Response
     */


    public function index(ServerRequestInterface $request, ResponseInterface $response): Response
    {



        $bannerCount = 6;
        $template = "";
        for($i=0;$i<$bannerCount;$i++){
            $template .= $this->render->template('banner');
        }
        

        $view = (string)$this->render->render("concept",['banners' => $template ]);
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function cropHelper(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render("helpForCropper");
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function handleRequest(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $response->getBody()->write(json_encode([
            'method' => 'GET',
            'result' => 'OK'
        ]));
        /**@var $response  Response */
        return $response;
    }
    public function showAbout(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render("about");
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function showWebPage(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $docPage string $ */
        $view = (string)$this->render->render("Docs/".$docPage);
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function switchLanguage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $locales = [
            'pt' => 'pt_PT',
            'en' => 'en_GB',
            'fr' => 'fr_FR'
        ];
        Session::set('ACTIVE_LANG', $request->getParsedBody()['language']);
        Session::set('LOCALE', $locales[$request->getParsedBody()['language']]);
        //$this->setToastCookie('Language Changed to ' . strtoupper($request->getParsedBody()['language']), 'info', 5000);
        return (new Response())
            ->withStatus(200)
            ->withHeader('Location', Session::get('LAST_INTENT'));
    }


}
