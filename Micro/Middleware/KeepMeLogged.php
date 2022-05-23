<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 22/02/2019
 * Time: 13:23
 */

namespace Micro\Middleware;

use GuzzleHttp\Psr7\Response;
use Micro\Contracts\RepositoryInterface;
use Micro\Controllers\AuthorController;
use Micro\Core\Session\Session;
use Micro\Core\Utils\AppCrypt;
use Micro\Core\Utils\Logger;
use Micro\Models\AppUser;
use Micro\Repository\AuthModelRepository;
use Psr\Http\Message\ServerRequestInterface;

class KeepMeLogged
{
    /**
     * @var AuthModelRepository
     */
    private $conn;

    /**
     * KeepMeLogged constructor.
     * @param RepositoryInterface $conn
     */
    public function __construct(RepositoryInterface $conn)
    {
        $this->conn = $conn;
    }

    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        if (Session::get('loggedIn')) {
            return $next($request, $response);
        }
        if (!isset($request->getCookieParams()['micro'])) {
            return $next($request, $response);
        }

        $cookie = $request->getCookieParams()['micro'];
        list($identifier, $token) = explode(':', $cookie);

        Logger::log("middleware: FOUND COOKIE MICRO");

        $user = new AppUser();
        $user->setIdentifier($identifier)
            ->setToken($token);

        if ($user = $this->conn->validateAuthCookie($user)) {
            $this->conn->validate($user);
            $user->setId(Session::loggedUserID());
            $user->setToken(AppCrypt::generateToken());
            $user->setTimeout(time() + 60 * 60 * 24 * 7);
            $user = $this->conn->updateUserToken($user);
            setcookie('micro', "{$user->getIdentifier()}:{$user->getToken()}", time() + 60 * 60 * 24 * 7);
            $this->conn->updateLastLogged($user->getId());
            $this->setFlashCookie([
                'type' => 'success',
                'title' => 'Logged',
                'message' => 'Your login was made by KeepMeLogged Cookie ',
            ]);

            return (new Response())
                ->withStatus(200)
                ->withHeader('Location', APP_ASSET_BASE);
        }


        return $next($request, $response);
    }

    public function setFlashCookie($cookie)
    {
        $appCookie = json_encode([
            'type' => $cookie['type'],
            'title' => rawurlencode($cookie['title']),
            'message' => rawurlencode($cookie['message']),
        ]);
        setcookie(
            'microFlash',
            "{$appCookie}",
            time() + 3,
            '/'
        );

    }

}
