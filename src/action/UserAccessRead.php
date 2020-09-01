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
        }
        */
        $out = [];
        $out['ref'] = $fetch['ref'];
        $out['owner'] = $fetch['owner'];
        $out['name'] = $fetch['name'];
        $out['email'] = $fetch['email'];
        $out['role'] = json_decode($fetch['role']);
        return $out;
    }
}
