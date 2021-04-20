<?php


namespace App\action;

use App\Entity\User;
use Exception;

/**
 * Class GetAllUsers
 * @package App\action
 */
class GetAllUsers extends UserAccessRead
{
    /**
     * retourne la liste des utilisateurs
     * @return User[]
     * @throws \Exception
     */
    public function __invoke(): array
    {
        $stm = $this->pdo->prepare("SELECT * FROM user WHERE client = ?");
        $ok = $stm->execute([
            $this->client->getRef()
        ]);
        if ($ok === false) {
            throw new Exception('Un problème est survenue pendant la lecture des informations stockées');
        }
        return array_map(
            [$this, 'format'],
            $stm->fetchAll(\PDO::FETCH_ASSOC)
        );
    }
}
