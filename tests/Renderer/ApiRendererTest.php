<?php

namespace Tests\Renderer;

use App\Renderer\ApiRenderer;
use Tests\Functional\ControllerTestCase;

class ApiRendererTest extends ControllerTestCase
{
    public function test__construct()
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $this->assertInstanceOf(ApiRenderer::class, $render);
    }

    public function testSuccess()
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $rep = $render->success();
        $this->assertSuccessResponse($rep);
    }

    public function testError()
    {
        $rep = $this->getResponse();
        $render = new ApiRenderer($rep);
        $rep = $render->error(500, 'err interne');
        $this->assertErrorResponse($rep);
        $this->assertErrorResponse($rep, 500);
    }
}
