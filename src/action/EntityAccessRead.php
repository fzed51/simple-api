<?php


namespace App\action;

class EntityAccessRead extends EntityAccess
{
    /**
     * @param array{ref:string,owner:string,created:string,updated:string,data:string} $fetch
     * @return mixed
     */
    protected function format(array $fetch)
    {
        /*
         * un fetch a cette structure
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
