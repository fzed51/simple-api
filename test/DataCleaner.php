<?php
declare(strict_types=1);

namespace Test;

use Helper\DbQuickUse;
use PDO;

class DataCleaner
{
    private DbQuickUse $query;

    public function __construct(private PDO $pdo)
    {
        $this->query = new DbQuickUse($this->pdo);
    }

    public function allUser(string $entity)
    {
        $this->query->delete('user', ['entity' => $entity]);
    }


    public function user(string $entity, string $ref)
    {
        $this->query->delete('user', ['entity' => $entity, 'ref' => $ref]);
    }

}