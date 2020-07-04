<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 31/05/2020
 * Time: 22:30
 */

namespace App\action;


use App\ApiSecurity;
use App\Entity\NewUser;

class CreateUser extends UserAccess
{
    public function __invoke($json): string
    {
        if (!$this->isValidJson($json)) {
            throw new \InvalidArgumentException('Le JSON passé en paramètre à ' . __CLASS__ . ' n\'est pas  valide', 400);
        }
        $security = new ApiSecurity();
        $stm = $this->pdo->prepare(<<<SQL
INSERT INTO user 
    (ref, owner, name, email, pass, role) 
    values (?,?,?,?,?,?)
SQL
        );
        $ref = $security->getUid();
        $owner = $this->owner->getRef();
        $data = json_decode($json, true);
        $data['ref'] = $ref;
        $user = new NewUser($data);
        $name = $user->getName();
        $email = $user->getEmail();
        $pass = $security->hashPassWord($data['pass']);
        $roles = json_encode($data['roles']);
        $stm->execute([$ref, $owner, $name, $email, $pass, $roles]);
        return $ref;
    }
}