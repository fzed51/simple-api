<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleApi\Settings\LogSettings;
use SimpleApi\Settings\Settings;

return static function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([Settings::class => function () {
        return new Settings(
            new LogSettings(
                'simple-api',
                isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                Level::Debug
            )
        );
    }]);
};
