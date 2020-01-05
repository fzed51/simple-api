<?php

use App\Middleware\OwnerMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = static function (ContainerInterface $c) {
        return new \App\Renderer\ApiRenderer($c->get('response'));
    };

    $container[OwnerMiddleware::class] = static function (ContainerInterface $c) {
        $owners = json_decode(
            file_get_contents(__DIR__ . '/../ressources/owner.json'),
            true
        );
        return new OwnerMiddleware($c, $owners);
    };

    $container[\PDO::class] = static function (ContainerInterface $c) {
        $settings = $c->get('settings');
        switch ($settings['db']['provider']) {
            case 'mysql':
                return \Helper\PDOFactory::mysql(
                    $settings['db']['host'],
                    $settings['db']['name'],
                    $settings['db']['user'],
                    $settings['db']['pass']
                );
            default:
                throw new \RuntimeException(
                    "le provider de base de donnée '{$settings['db']['provider']}' n'est pas pris en compte"
                );
        }
    };


};
