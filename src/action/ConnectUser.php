<?php


namespace App\action;


use App\ApiSecurity;
use App\Entity\LoginUser;

class ConnectUser extends UserAccess
{
    public function __invoke($json): string
    {
        if (!$this->isValidJson($json)) {
            throw new \InvalidArgumentException('Le JSON passé en paramètre à ' . __CLASS__ . ' n\'est pas  valide', 400);
        }
        $security = new ApiSecurity();
        $data = json_decode($json, true);
        $login = new LoginUser($data);
        $stm = $this->pdo->prepare("select * from user where owner = ? and email = ?");
        $owner = $this->owner->getRef();
        $email = $login->getEmail();
        if ($stm->execute([$owner, $email]) === false || ($user = $stm->fetch(\PDO::FETCH_ASSOC)) === false) {
            throw new \Exception("l'email ou le mot de passe ne sont pas valide");
        }
        if (!$security->testPassWord($login->getPass(), $user['pass'])) {
            throw new \Exception("l'email ou le mot de passe ne sont pas valide");
        }
        $ref = $user['ref'];
        // TODO: Creation de la session
    }
}