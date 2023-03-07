<?php
declare(strict_types=1);

namespace Test;

use DateTime;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Helper\PDOFactory;
use InstanceResolver\ResolverClass;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use PDO;
use PHPUnit\Framework\TestCase as PuTestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use SimpleApi\Elements\Entity;
use SimpleApi\Elements\Resource;
use SimpleApi\Settings\LogSettings;
use SimpleApi\Settings\Settings;

/**
 * Class de base pour les tests de simple-api
 */
class TestCase extends PuTestCase
{

}
