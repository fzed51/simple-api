<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 28/05/2020
 * Time: 11:23
 */

namespace App\Entity;

/**
 * Class User
 * @package App\Entity
 */
class User extends BaseUser
{

    /** @var string uid */
    protected $ref;



    protected function hydrate($data)
    {
        parent::hydrate($data);
        $this->controleUser($data);
        $this->ref = $data['ref'];
    }


    public static function controleUser($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Un user ne peut pas être initialisé avec un ' . gettype($data));
        }
        if (!array_key_exists('ref', $data) || !self::isValidString($data['ref'])) {
            throw new \Exception("La ref du user n'est pas valide");
        }
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

}