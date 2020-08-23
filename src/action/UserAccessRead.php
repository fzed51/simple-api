<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 23/08/2020
 * Time: 19:55
 */

namespace App\action;

class UserAccessRead extends UserAccess
{


    public function format(array $fetch): array
    {
        /*
        session => {
    ref       : string
    owner     : string
    name      : string
    email     : string
    role      : string[]
    created   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated   TIMESTAMP    NULL,
        }
        */
    }

}