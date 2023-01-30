<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use InstanceResolver\ResolverClass;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleApi\Elements\Entity;
use SimpleApi\Settings\Settings;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        "Entities" => function (ContainerInterface $c) {
            $entitiesJson = file_get_contents(__DIR__ . "/../config/entity.json");
            $entitiesData = json_decode($entitiesJson, false, 512, JSON_THROW_ON_ERROR);
            $entities = [];
            foreach ($entitiesData as $entity) {
                $e = Entity::fromArray($entity);
                $entities[$e->uuid] = $e;
            }
            return $entities;
        },
        LoggerInterface::class => function (ContainerInterface $c) {
            /** @var Settings $settings */
            $settings = $c->get(Settings::class);
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ipClient = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipClient = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ipClient = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            }
            $logger = new Logger($settings->logSetting->apiName);
            $logPath = $settings->logSetting->path;
            $logError = rtrim($logPath, '/') . '/events-' . (new DateTime())->format('Y-m-d') . '.log';
            $logDebug = rtrim($logPath, '/') . '/events_json.log';
            $logger->pushProcessor(new UidProcessor());
            $logger->pushProcessor(new IntrospectionProcessor());
            $logger->pushProcessor(static function (LogRecord $record) use ($ipClient): LogRecord {
                $record['extra']['info'] = [
                    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? '?',
                    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? '?',
                    'IP' => $ipClient,
                    'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '?'
                ];
                return $record;
            });
            $steam = new StreamHandler($logError, Level::Warning);
            $jsonSteam = new RotatingFileHandler($logDebug, 15, $settings->logSetting->level);
            $jsonSteam->setFormatter(new JsonFormatter());
            $logger->pushHandler($steam);
            $logger->pushHandler($jsonSteam);
            return $logger;
        },
        ResolverClass::class => function (ContainerInterface $container) {
            return new ResolverClass($container);
        },
    ]);
};
