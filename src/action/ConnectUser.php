<?php /** @noinspection SqlResolve */


namespace App\action;


use App\ApiSecurity;
use App\Entity\LoginUser;
use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;

class ConnectUser extends UserAccess
{
    /**
     * @param $json
     * @return string
     * @throws \Exception
     */
    public function __invoke(string $json): string
    {
        if (!$this->isValidJson($json)) {
            throw new InvalidArgumentException('Le JSON passé en paramètre à ' . __CLASS__ . ' n\'est pas  valide', 400);
        }
        $security = new ApiSecurity();
        $data = json_decode($json, true);
        $login = new LoginUser($data);
        $stm = $this->pdo->prepare("select * from user where owner = ? and email = ?");
        $owner = $this->owner->getRef();
        $email = $login->getEmail();
        if ($stm->execute([$owner, $email]) === false || ($user = $stm->fetch(PDO::FETCH_ASSOC)) === false) {
            throw new Exception("l'email ou le mot de passe ne sont pas valide");
        }
        if (!$security->testPassWord($login->getPass(), $user['pass'])) {
            throw new Exception("l'email ou le mot de passe ne sont pas valide");
        }
        $ref = $user['ref'];
        $privateToken = $security->getUid();
        $idClient = $security->getIdClient();
        $publicToken = $security->getPublicToken($privateToken, $idClient);
        $expiration = (new DateTime())->add(new DateInterval('PT6H'));
        $stm = $this->pdo->prepare(<<<SQL
UPDATE user 
    SET 
        session_private_token = ?,
        session_public_token = ?,
        session_expiration = ?
    WHERE
        ref = ?
SQL
        );
        $stm->execute([
            $privateToken,
            $publicToken,
            $expiration->setTimezone(new \DateTimeZone('UTC'))->format(DATE_ATOM),
            $ref
        ]);
        return $ref;
    }
}