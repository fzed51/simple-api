<?php
declare(strict_types=1);

namespace SimpleApi\Middlewares;

use Test\MiddlewareTestCase;

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
        $mid = $this->getMiddleware();
        $response = $mid->process(
            $this->makeGetRequest("/test", ['X-APIKEY' => "8aeb376f-5cb3-4a4e-a7f5-1abf65467deb"]),
            $this->makeRequestHandler()
        );
        self::assertResponseAndReturnData(200, $response);
        $container = $this->getContainer();
        self::assertTrue($container->has('currentSchema'));
        self::assertEquals("EntityTest", $container->get('currentSchema'));
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
