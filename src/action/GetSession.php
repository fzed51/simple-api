<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 23/08/2020
 * Time: 19:50
 */

namespace App\action;

class GetSession extends UserAccessRead
{
    /**
     * @param string $ref
     * @return array|null
     */
    public function __invoke(string $ref): ?array
    {
        /*
            ref       : string
            owner     : string
            name      : string
            email     : string
            role      : string[]
         */
        $stm = $this->pdo->prepare(<<<SQL
SELECT ref, owner, name, email, role, session_public_token, session_expiration
FROM user 
WHERE owner = ? 
  AND ref = ?
SQL
        );
        $owner = $this->owner->getRef();
        $stm->execute([$owner, $ref]);
        $fetch = $stm->fetch(\PDO::FETCH_ASSOC);
        if ($fetch === false) {
            return null;
        }
        return $this->format($fetch);
    }

    public function format(array $fetch): array
    {
        $out = parent::format($fetch);
        $out['session_public_token'] = $fetch['session_public_token'];
        $out['session_expiration'] = $fetch['session_expiration'];
        return $out;
    }
}
