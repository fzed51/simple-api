<?php


namespace App;


use App\action\GetAllEntities;
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
        if(!$owner->hasRessource($ressource)){
            return $render->error(404, 'Ressource inconnue');
        }
        $pdo = $this->container->get(\PDO::class);
        $getAll = new GetAllEntities($pdo);
        $getAll->hydrateOwnerAndRessource($owner, $ressource);
        $entities = $getAll();
        return $render->success($entities);
    }
}