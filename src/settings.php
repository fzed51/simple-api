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
    }, LoggerInterface::class => function (ContainerInterface $c) {
        /** @var \SimpleApi\Settings\Settings $settings */
        $settings = $c->get(Settings::class);
        $handle = new Monolog\Handler\NullHandler();
        $process1 = new Monolog\Processor\UidProcessor(6);
        $process2 = new Monolog\Processor\WebProcessor();
        $process3 = new Monolog\Processor\IntrospectionProcessor();
        return new Logger(
            $settings->logSetting->apiName,
            [$handle],
            [$process1, $process2, $process3]
        );
    }]);
};
