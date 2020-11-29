<?php

use App\Middleware\ClientMiddleware;
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

    $container[ClientMiddleware::class] = static function (ContainerInterface $c) {
        $clientFile = __DIR__ . '/../ressources/client.json';
        if (is_file($clientFile)) {
            $clients = json_decode(
                file_get_contents($clientFile),
                true
            );
            if (is_array($clients) && (json_last_error() === JSON_ERROR_NONE)) {
                return new ClientMiddleware($c, $clients);
            } else {
                error_log("Le fichier [$clientFile] n'est pas un JSON valide");
            }
        } else {
            error_log("Le fichier [$clientFile] n'existe pas");
        }
        return new ClientMiddleware($c, []);
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
