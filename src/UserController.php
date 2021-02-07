<?php

namespace App;

use App\action\CreateUser;
use App\action\GetSession;
use App\Entity\Client;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UserController
 * @package App
 */
class UserController extends Controller
{
    /**
     * méthode du controleur pour créer un utilisateur
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array $args
     * @return \Slim\Http\Response
     * @throws \ReflectionException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        /* @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /* @var \InstanceResolver\ResolverClass $resolve */
        $resolve = $this->container->get('resolve');
        /* @var \App\Entity\Client $client */
        $client = $request->getAttribute('client');
        if (!is_a($client, Client::class)) {
            return $render->error(400, 'Client non renseignee');
        }
        $data = $this->getBodyRequest($request);
        try {
            /** @var \App\action\CreateUser $create */
            $create = $resolve(CreateUser::class);
            $create->hydrateClient($client);
            $refUser = $create($data);
            /** @var \App\action\GetSession $getSession */
            $getSession = $resolve(GetSession::class);
            $getSession->hydrateClient($client);
            $session = $getSession($refUser);
            return $render->success($session);
        } catch (\Exception $e) {
            error_log(json_encode([
                'origin' => __FILE__,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace()
            ]));
            return $render->error(500, 'Erreur interne');
        }
    }
}
