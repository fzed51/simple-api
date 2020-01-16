<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 16/01/2020
 * Time: 17:50
 */

namespace Tests;

use Tests\Functional\AppTestCase;

class End2EndTest extends AppTestCase
{

    public function test_AjoutDUneRessource(): void
    {
        $response = $this->runApp(
            'GET',
            'item',
            [
                'Authorization' => $this->getOwnerBearerToken()
            ]
        );
        $this->assertSuccessResponse($response);
    }

}
