<?php
declare(strict_types=1);

namespace SimpleApi\Middlewares;

use SimpleApi\Elements\Entity;
use Test\MiddlewareTestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * Test de EntityMiddleware
 */
class EntityMiddlewareTest extends MiddlewareTestCase
{

    /**
     * test de Process
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \HttpException\ForbiddenException
     */
    public function testProcess(): void
    {
        $refEntity = $this->getEntity();
        $mid = $this->getMiddleware();
        $request = $this->makeGetRequest("/test", ['X-APIKEY' => "8aeb376f-5cb3-4a4e-a7f5-1abf65467deb"]);
        $handler = $this->makeRequestHandler();
        $response = $mid->process($request, $handler);
        self::assertResponseAndReturnData(200, $response);
        $container = $this->getContainer();
        self::assertTrue($container->has(Entity::class));
        $entity = $container->get(Entity::class);
        self::assertInstanceOf(Entity::class, $entity);
        assertEquals($refEntity->title, $entity->title);
    }

    private function getMiddleware(): EntityMiddleware
    {
        return $this->resolve(EntityMiddleware::class);
    }

    /**
     * test de Construc
     */
    public function testConstruc(): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(EntityMiddleware::class, $this->getMiddleware());
    }
}
