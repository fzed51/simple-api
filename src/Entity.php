<?php


namespace App;


class Entity
{
    /**
     * @var string
     */
    private $ownerRef;
    /**
     * @var string
     */
    private $ref;
    /**
     * @var mixed
     */
    private $data;

    public function __construct(string $ownerRef, string $ref, $data)
    {
        $this->ownerRef = $ownerRef;
        $this->ref = $ref;
        $this->data = $data;
    }
}