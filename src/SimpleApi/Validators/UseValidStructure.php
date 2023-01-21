<?php
declare(strict_types=1);

namespace SimpleApi\Validators;

/**
 * Trait de validation
 */
trait UseValidStructure
{
    /**
     * Valide si la data est une entity
     * @param mixed $data
     * @param string $name
     * @return true|string
     */
    public static function isEntity(mixed $data, string $name): bool|string
    {
        $err = self::isArrayWithKey($data, $name, ['uid', 'title', 'resources']);
        if ($err !== true) {
            return $err;
        }
        $errs = [];
        $errs[] = self::isUid($data['uid'], "la clé uid");
        $errs[] = self::isText($data['title'], "la clé title", 4, 128);
        $errs[] = self::isArrayOf($data['resources'], "la clé ressources");
        $errs = self::cleanArrayError($errs);
        if (count($errs) > 0) {
            return sprintf("%s n'est pas valide : %s", $name, implode(", ", $errs));
        }
        return true;
    }

    /**
     * Valide si une data est un tableau avec des clés
     * @param mixed $data
     * @param string $name
     * @param array<string> $keys
     * @return true|string
     */
    public static function isArrayWithKey(mixed $data, string $name, array $keys): bool|string
    {
        if (!is_array($data)) {
            return $name . " n'est pas un tableau";
        }
        $dataKeys = array_keys($data);
        $lostKeys = [];
        foreach ($keys as $key) {
            if (!in_array($key, $dataKeys, true)) {
                $lostKeys[] = $key;
            }
        }
        if (count($lostKeys) > 0) {
            $message = "%s n'est pas valide, la clé %s n'est pas définie.";
            if (count($lostKeys) > 1) {
                $message = "%s n'est pas valide, les clés %s ne sont pas définies.";
            }
            return sprintf($message, $name, implode(", ", $lostKeys));
        }
        return true;
    }

    /**
     * Valide si une data est une UID
     * @param mixed $data
     * @param string $name
     * @return true|string
     */
    public static function isUid(mixed $data, string $name): bool|string
    {
        if (!is_string($data)
            || !self::testRegEx(
                "/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/",
                $data
            )) {
            return $name . " n'est pas une UID";
        }
        return true;
    }

    /**
     * Test un chaine sur un regex
     * @param string $regex
     * @param string $data
     * @return bool
     */
    private static function testRegEx(string $regex, string $data): bool
    {
        $result = preg_match($regex, $data);
        return ($result !== false && $result > 0);
    }

    /**
     * Valide si une data est un chaine de caractère valide
     * @param mixed $data
     * @param string $name
     * @param int $minMax max si max non defini si non min
     * @param int|null $max max si defini
     * @return true|string
     */
    public static function isText(mixed $data, string $name, int $minMax, ?int $max = null): bool|string
    {
        if (!is_string($data)) {
            return $name . " n'est pas du text";
        }
        $min = min($minMax, $max ?? 0);
        $max = max($minMax, $max ?? 0);
        if (strlen($data) < $min) {
            return $name . " n'est pas valide car trop court (< $min car)";
        }
        if (strlen($data) > $max) {
            return $name . " n'est pas valide car trop long (> $max car)";
        }
        return true;
    }

    /**
     * Valide si la data est un tableau d'éléments
     * @param callable|null $validator
     * @param mixed $data
     * @param string $name
     * @return true|string
     */
    public static function isArrayOf(mixed $data, string $name, callable|null $validator = null): bool|string
    {
        if (!is_array($data)) {
            return $name . " n'est pas un tableau";
        }
        $errors = [];
        if ($validator !== null) {
            foreach ($data as $key => $item) {
                $errors[] = $validator($item, "l'élément " . $key);
            }
            $errors = self::cleanArrayError($errors);
            if (count($errors) > 0) {
                return $name . " ne sont pas valide : " . implode(", ", $errors);
            }
        }
        return true;
    }

    /**
     * Netoy un tableau d'erreurs
     * @param array<bool|string> $errors
     * @return array<string>
     */
    private static function cleanArrayError(array $errors): array
    {
        return array_filter($errors, static fn($i) => ($i !== true));
    }

    /**
     * Valide si la data est une entity
     * @param mixed $data
     * @param string $name
     * @return true|string
     */
    public static function isResource(mixed $data, string $name): bool|string
    {
        $err = self::isArrayWithKey($data, $name, ['name', 'fields']);
        if ($err !== true) {
            return $err;
        }
        $errs = [];
        $errs[] = self::isText($data['name'], "la clé nom", 4, 128);
        $errs[] = self::isArrayOf($data['fields'], "la clé champs", fn($data, $name
        ) => self::isText($data, "le champ $name", 2, 32));
        $errs = self::cleanArrayError($errs);
        if (count($errs) > 0) {
            return sprintf("%s n'est pas valide : %s", $name, implode(", ", $errs));
        }
        return true;
    }
}
