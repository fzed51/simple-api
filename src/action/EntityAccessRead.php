<?php


namespace App\action;

class EntityAccessRead extends EntityAccess
{
    /**
     * @param array{ref:string,client:string,created:string,updated:string,data:string} $fetch
     * @return array<string,mixed>
     * @throws \JsonException
     */
    protected function format(array $fetch): array
    {
        /*
         * un fetch a cette structure
         * {
         *  ref,
         *  client,
         *  created,
         *  updated,
         *  data
         * }
         */
        $data = json_decode($fetch['data'], true, 512, JSON_THROW_ON_ERROR);
        $data['id'] = $fetch['ref'];
        return $data;
    }
}
