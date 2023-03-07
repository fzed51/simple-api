<?php
declare(strict_types=1);

namespace Test;

use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Helper\PDOFactory;
use InstanceResolver\ResolverClass;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use SimpleApi\Elements\Entity;
use SimpleApi\Elements\Resource;
use SimpleApi\Settings\LogSettings;
use SimpleApi\Settings\Settings;

/**
 * Class de test de base pour les actions
 */
class ActionTestCase extends TestCase
{

    private PDO|null $pdo = null;
    private Container|null $container = null;
    private Settings|null $settings = null;
    private Entity|null $entity = null;

    /**
     * Resoud une instance de class ou retourne un élément du container
     * @template T
     * @param string|class-string<T> $className
     * @return mixed|T
     */
    protected function resolve($className)
    {
        $container = $this->getContainer();
        try {
            /** @var ResolverClass $resolver */
            $resolver = $container->get(ResolverClass::class);
            return $resolver($className);
        } catch (DependencyException|NotFoundException $e) {
            throw new RuntimeException("ResolverClass non initialisé dans le container", $e->getCode(), $e);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function getContainer(): Container
    {
        if ($this->container === null) {
            $containerBuilder = new ContainerBuilder();
            $containerBuilder->addDefinitions([
                Settings::class => function () {
                    return $this->getSettings();
                },
                LoggerInterface::class => function (ContainerInterface $c) {
                    $logger = new Logger("test-simple-api");
                    $logPath = __DIR__ . "/log/events.log";
                    $logger->pushProcessor(new UidProcessor());
                    $logger->pushProcessor(new IntrospectionProcessor());
                    $steam = new RotatingFileHandler($logPath, 15, Level::Debug);
                    $logger->pushHandler($steam);
                    return $logger;
                },
                ResolverClass::class => function (ContainerInterface $container) {
                    return new ResolverClass($container);
                },
                PDO::class => function () {
                    return $this->getPdo();
                }
            ]);
            try {
                $this->container = $containerBuilder->build();
            } catch (Exception $e) {
                throw new RuntimeException(
                    "Impossible d'initialiser le container",
                    $e->getCode(),
                    $e
                );
            }
        }
        return $this->container;
    }

    protected function getSettings(): Settings
    {
        if ($this->settings === null) {
            $this->settings = new Settings(
                __DIR__ . '/entity.json',
                new LogSettings(
                    'simple-api',
                    isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    Level::Debug
                )
            );
        }
        return $this->settings;
    }

    /**
     * Retourne une instance de PDO pour les tests
     * @return \PDO
     */
    protected function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = PDOFactory::mysql(
                '172.26.208.1',
                'test-data',
                'root',
                'root-pass',
                3306
            );
        }
        return $this->pdo;
    }

    protected function getEntity(): Entity
    {
        if ($this->entity === null) {
            $this->entity = new Entity(
                '8aeb376f-5cb3-4a4e-a7f5-1abf65467deb',
                'EntityTest',
                [
                    new Resource('test', ['data1', 'data2'])
                ]
            );
        }
        return $this->entity;
    }
}
