<?php /** @noinspection ForgottenDebugOutputInspection */

/** @noinspection PhpUnusedParameterInspection */

namespace App;

use App\action\CreateUser;
use App\action\GetUser;
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
     * @param string[] $args
     * @return \Slim\Http\Response
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

            $getUser = $resolve(GetUser::class);
            $getUser->hydrateClient($client);
            $user = $getUser($refUser);
            return $render->success($user);
        } catch (\Exception $e) {
            try {
                error_log(json_encode([
                    'origin' => __FILE__,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTrace()
                ], JSON_THROW_ON_ERROR));
            } catch (\JsonException $e) {
                error_log('json_encode => ' . $e->getMessage());
            }
            return $render->error(500, 'Erreur interne');
        }
    }
}
