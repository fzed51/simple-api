<?php

use App\Middleware\OwnerMiddleware;
use App\Renderer\ApiRenderer;
use Helper\PDOFactory;
use Psr\Container\ContainerInterface;
use Slim\App;

return static function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = static function (ContainerInterface $c) {
        return new ApiRenderer($c->get('response'));
    };

    $container[OwnerMiddleware::class] = static function (ContainerInterface $c) {
        $owners = json_decode(
            file_get_contents(__DIR__ . '/../ressources/owner.json'),
            true
        );
        return new OwnerMiddleware($c, $owners);
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

    return $container;
};
