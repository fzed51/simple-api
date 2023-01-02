<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleApi\Settings\Settings;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            /** @var Settings $settings */
            $settings = $c->get(Settings::class);
            $logger = new Logger($settings->logSetting->apiName);
            $uiProcessor = new UidProcessor();
            $webProcess = new Monolog\Processor\WebProcessor();
            $interProcess = new Monolog\Processor\IntrospectionProcessor();
            $logger->pushProcessor($uiProcessor);
            $logger->pushProcessor($webProcess);
            $logger->pushProcessor($interProcess);
            $handler = new StreamHandler($settings->logSetting->path, $settings->logSetting->level);
            $logger->pushHandler($handler);
            return $logger;
        },
    ]);
};