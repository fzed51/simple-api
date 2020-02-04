<?php

use App\Middleware\OwnerMiddleware;
use App\Renderer\ApiRenderer;
use Helper\PDOFactory;
use InstanceResolver\ResolverClass;
use Psr\Container\ContainerInterface;
use Slim\App;

return static function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = static function (ContainerInterface $c) {
        return new ApiRenderer($c->get('response'));
    };

    $container[OwnerMiddleware::class] = static function (ContainerInterface $c) {
        $ownerFile = __DIR__ . '/../ressources/owner.json';
        if (is_file($ownerFile)) {
            $owners = json_decode(
                file_get_contents($ownerFile),
                true
            );
            if (is_array($owners) && (json_last_error() === JSON_ERROR_NONE)) {
                return new OwnerMiddleware($c, $owners);
            } else {
                error_log("Le fichier [$ownerFile] n'est pas un JSON valide");
            }
        } else {
            error_log("Le fichier [$ownerFile] n'existe pas");
        }
        return new OwnerMiddleware($c, []);
    };

    $container[PDO::class] = static function (ContainerInterface $c) {
        $settings = $c->get('settings');
        PDOFactory::$case = PDO::CASE_LOWER;
        PDOFactory::$fetchMode = PDO::FETCH_ASSOC;
        switch ($settings['db']['provider']) {
            case 'mysql':
                return PDOFactory::mysql(
                    $settings['db']['host'],
                    $settings['db']['name'],
                    $settings['db']['user'],
                    $settings['db']['pass']
                );
            default:
                throw new RuntimeException(
                    "le provider de base de donnée '{$settings['db']['provider']}' n'est pas pris en compte"
                );
        }
    };

    $container['resolve'] = function (ContainerInterface $c) {
        return new ResolverClass($c);
    };

    return $container;
};
