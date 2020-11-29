<?php


namespace App;

use App\action\CreateEntity;
use App\action\DeleteEntity;
use App\action\GetAllEntities;
use App\action\GetEntity;
use App\action\UpdateEntity;
use App\Entity\Client;
use JsonException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RessourceController
 * @package App
 */
class RessourceController extends Controller
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array<string,string> $args
     * @return \Slim\Http\Response
     */
    public function getAll(Request $request, Response $response, array $args): Response
    {
        /** @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /** @var \App\Entity\Client $client */
        $client = $request->getAttribute('client');
        if (!is_a($client, Client::class)) {
            return $render->error(400, 'Client non renseignee');
        }
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$client->hasRessource($ressource)) {
            return $render->error(404, 'Ressource inconnue');
        }
        $pdo = $this->container->get(\PDO::class);
        $getAll = new GetAllEntities($pdo);
        $getAll->hydrateClientAndRessource($client, $ressource);
        $entities = $getAll();
        return $render->success($entities);
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array<string,string> $args
     * @return \Slim\Http\Response
     */
    public function getOne(Request $request, Response $response, array $args): Response
    {
        /** @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /** @var \InstanceResolver\ResolverClass $resolve */
        $resolve = $this->container->get('resolve');
        /** @var \App\Entity\Client $client */
        $client = $request->getAttribute('client');
        if (!is_a($client, Client::class)) {
            return $render->error(400, 'Client non renseignee');
        }
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$client->hasRessource($ressource) || empty($args['ref'] ?? '')) {
            return $render->error(404, 'Ressource inconnue');
        }
        try {
            /** @var \App\action\GetEntity $getOne */
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateClientAndRessource($client, $ressource);
            $entity = $getOne($args['ref']);
            return $render->success($entity);
        } catch (\Exception $e) {
            /** @noinspection ForgottenDebugOutputInspection */
            error_log(json_encode([
                'origin' => __FILE__,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace()
            ], JSON_THROW_ON_ERROR));
            return $render->error(500, 'Erreur interne');
        } catch (JsonException $e) {
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array<string,string> $args
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
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$client->hasRessource($ressource)) {
            return $render->error(404, 'Ressource inconnue');
        }
        $data = $this->getBodyRequest($request);
        if (!is_string($data) || !$this->valideJson($data)) {
            return $render->error(400, 'Les donnée a enregistrer ne sont pas valide.');
        }
        try {
            /* @var \App\action\CreateEntity $create */
            $create = $resolve(CreateEntity::class);
            $create->hydrateClientAndRessource($client, $ressource);
            /* @var \App\action\GetEntity $getOne */
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateClientAndRessource($client, $ressource);
            $entity = $getOne($create($data));
            return $render->success($entity);
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

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array<string,string> $args
     * @return \Slim\Http\Response
     */
    public function update(Request $request, Response $response, array $args): Response
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
        $ressource = $args['ressource'] ?? null;
        $ref = $args['ref'] ?? '';
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$client->hasRessource($ressource) || empty($args['ref'] ?? '')) {
            return $render->error(404, 'Ressource inconnue');
        }
        $data = $this->getBodyRequest($request);
        if (!is_string($data) || !$this->valideJson($data)) {
            return $render->error(400, 'Les données à enregistrer ne sont pas valides.');
        }
        $dataWithId = json_decode($data, false);
        if (isset($dataWithId->id)) {
            if ($ref !== $dataWithId->id) {
                return $render->error(400, 'Les données à enregistrer ne sont pas valides.');
            }
            unset($dataWithId->id);
        }
        $json = json_encode($dataWithId);
        try {
            /* @var \App\action\UpdateEntity $update */
            $update = $resolve(UpdateEntity::class);
            $update->hydrateClientAndRessource($client, $ressource);
            /* @var \App\action\GetEntity $getOne */
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateClientAndRessource($client, $ressource);
            $entity = $getOne($ref);
            if (null === $entity) {
                return $render->error(404, 'Ressource inconnue');
            }
            $entity = $getOne($update($ref, $json));
            return $render->success($entity);
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

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array<string,string> $args
     * @return \Slim\Http\Response
     */
    public function delete(Request $request, Response $response, array $args): Response
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
        $ressource = $args['ressource'] ?? null;
        $ref = $args['ref'] ?? '';
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$client->hasRessource($ressource) || empty($args['ref'] ?? '')) {
            return $render->error(404, 'Ressource inconnue');
        }
        try {
            /* @var \App\action\DeleteEntity $delete */
            $delete = $resolve(DeleteEntity::class);
            $delete->hydrateClientAndRessource($client, $ressource);
            /* @var \App\action\GetEntity $getOne */
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateClientAndRessource($client, $ressource);
            $entity = $getOne($ref);
            if (null === $entity) {
                return $render->error(404, 'Ressource inconnue');
            }
            $delete($ref);
            return $render->success();
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
