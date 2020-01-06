<?php


namespace App\action;


class EntityAccessRead extends EntityAccess
{

    protected function format(array $fetch)
    {
        /*
         * un fetche a cette structure
         * {
         *  ref,
         *  owner,
         *  created,
         *  updated,
         *  data
         * }
         */
        $data = json_decode($fetch['data'], true);
        $data['id'] = $fetch['ref'];
        return $data;
    }

}