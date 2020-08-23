<?php

namespace Tests\action;

use App\action\GetSession;
use Tests\Functional\ActionTestCase;

/**
 * test de GetSessionTest
 * @package Tests\action
 */
class GetSessionTest extends ActionTestCase
{

    public function testConstructor(): void
    {
        $action = new GetSession($this->getPdo());
        self::assertInstanceOf(GetSession::class, $action);
    }

}
