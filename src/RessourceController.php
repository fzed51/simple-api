<?php


namespace App;


use App\action\CreateEntity;
use App\action\GetAllEntities;
use App\action\GetEntity;
use Slim\Http\Request;
use Slim\Http\Response;

class RessourceController extends Controller
{
    public function getAll(Request $request, Response $response, array $args)
    {
        /** @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /** @var \App\Owner $owner */
        $owner = $request->getAttribute('owner');
        if (!is_a($owner, Owner::class)) {
            return $render->error(400, 'Owner non renseignee');
        }
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$owner->hasRessource($ressource)) {
            return $render->error(404, 'Ressource inconnue');
        }
        $pdo = $this->container->get(\PDO::class);
        $getAll = new GetAllEntities($pdo);
        $getAll->hydrateOwnerAndRessource($owner, $ressource);
        $entities = $getAll();
        return $render->success($entities);
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array $args
     * @return \Slim\Http\Response
     */
    public function getOne(Request $request, Response $response, array $args)
    {
        /** @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /** @var \InstanceResolver\ResolverClass $resolve */
        $resolve = $this->container->get('resolve');
        /** @var \App\Owner $owner */
        $owner = $request->getAttribute('owner');
        if (!is_a($owner, Owner::class)) {
            return $render->error(400, 'Owner non renseignee');
        }
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        if (!$owner->hasRessource($ressource) || empty($args['ref'] ?? '')) {
            return $render->error(404, 'Ressource inconnue');
        }
        try {
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateOwnerAndRessource($owner, $ressource);
            $entity = $getOne($args['ref']);
            return $render->success($entity);
        } Catch (\Exception $e) {
            return $render->error(500, 'Erreur interne');
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array $args
     * @return \Slim\Http\Response
     */
    public function create(Request $request, Response $response, array $args)
    {
        /* @var \App\Renderer\ApiRenderer $render */
        $render = $this->container->get('renderer');
        /* @var \InstanceResolver\ResolverClass $resolve */
        $resolve = $this->container->get('resolve');
        /* @var \App\Owner $owner */
        $owner = $request->getAttribute('owner');
        if (!is_a($owner, Owner::class)) {
            return $render->error(400, 'Owner non renseignee');
        }
        $ressource = $args['ressource'] ?? null;
        if ($ressource === null) {
            return $render->error(400, 'Ressource non renseignee');
        }
        $data = $this->getBodyRequest($request);
        if (!is_string($data) || !$this->valideJson($data)) {
            return $render->error(400, 'Les donnée a enregistrer ne sont pas valide.');
        }
        try {
            /* @var \App\action\CreateEntity $create */
            $create = $resolve(CreateEntity::class);
            $create->hydrateOwnerAndRessource($owner, $ressource);
            /* @var \App\action\GetEntity $getOne */
            $getOne = $resolve(GetEntity::class);
            $getOne->hydrateOwnerAndRessource($owner, $ressource);
            $entity = $getOne($create($data));
            return $render->success($entity);
        } Catch (\Exception $e) {
            return $render->error(500, 'Erreur interne');
        }
    }
}