<?php
declare(strict_types=1);

namespace Test;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use InstanceResolver\ResolverClass;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;
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

    /** @var \DI\Container|null */
    private Container|null $container = null;
    /** @var \InstanceResolver\ResolverClass|null */
    private ResolverClass|null $instanceResolver = null;

    /**
     * Résoud une instance de class
     * @param class-string $className
     * @return mixed
     */
    protected function resolve(string $className): mixed
    {
        if ($this->instanceResolver === null) {
            $this->instanceResolver = new ResolverClass($this->getContainer());
        }
        $resolver = $this->instanceResolver;
        try {
            return $resolver($className);
        } catch (Exception $e) {
            throw new RuntimeException("Impossible de résoudre $className : {$e->getMessage()}");
        }
    }

    protected function getContainer(): Container
    {
        if ($this->container === null) {
            $containerBuilder = new ContainerBuilder();
            $containerBuilder->addDefinitions([
                Settings::class => fn() => new Settings("", new LogSettings("", "", Level::Debug))
            ]);
            $dependencies = require __DIR__ . '/../src/dependencies.php';
            $dependencies($containerBuilder);
            $containerBuilder->addDefinitions([
                LoggerInterface::class => fn() => new Logger(
                    "test_simple-api",
                    [new RotatingFileHandler(__DIR__ . "/log/test.log", 1, Level::Debug)],
                    [new UidProcessor(), new IntrospectionProcessor()]
                ),
                "Entities" => function () {
                    $entity = $this->getEntity();
                    return [$entity->uuid => $entity];
                }
            ]);
            try {
                $this->container = $containerBuilder->build();
            } catch (Exception) {
                throw new RuntimeException("Impossible d'initialiser le container");
            }
        }
        return $this->container;
    }

    protected function getEntity(): Entity
    {
        return new Entity(
            '8aeb376f-5cb3-4a4e-a7f5-1abf65467deb',
            'EntityTest',
            [
                new Resource('test', ['data1', 'data2'])
            ]
        );
    }
}
