<?php

namespace App\action;

use App\Entity\User;
use Exception;

/**
 * Class GetUser
 * @package App\action
 */
class GetUser extends UserAccessRead
{
    /**
     * retourne un utilisateur
     * @param string $refUser
     * @return User
     * @throws \Exception
     */
    public function __invoke(string $refUser): User
    {
        $stm = $this->pdo->prepare("SELECT * FROM user WHERE client = ? AND ref = ?");
        $ok = $stm->execute([
            $this->client->getRef(),
            $refUser
        ]);
        if ($ok === false) {
            throw new Exception('Un problème est survenue pendant la lecture des informations stockées');
        }
        $rawUser = $stm->fetch();
        if ($rawUser === false) {
            throw new Exception("L'utilisateur n'a pas été trouvé");
        }
        return $this->format($rawUser);
    }
}
