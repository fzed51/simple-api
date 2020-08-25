<?php

namespace Tests\Renderer;

use App\Renderer\ApiRenderer;
use Tests\Functional\ControllerTestCase;

/**
 * test de ApiRenderer
 * @package Tests\Renderer
 */
class ApiRendererTest extends ControllerTestCase
{
    /**
     * test de __construct
     */
    public function test__construct(): void
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $this->assertInstanceOf(ApiRenderer::class, $render);
    }

    /**
     * test de Success
     */
    public function testSuccess(): void
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $rep = $render->success();
        $this->assertSuccessResponse($rep);
    }

    /**
     * test de Error
     */
    public function testError(): void
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $rep = $render->error(500, 'err interne');
        $this->assertErrorResponse($rep);
        $this->assertErrorResponse($rep, 500);
    }
}
