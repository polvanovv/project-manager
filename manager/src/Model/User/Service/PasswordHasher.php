<?php

declare(strict_types = 1);

namespace App\Model\User\Service;


/**
 * Class PasswordHasher
 *
 * @package App\Model\User\Service
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class PasswordHasher
{

    /**
     * @param string $password
     * @return false|string
     */
    public function hash(string $password)
    {

        $hash = password_hash($password, PASSWORD_BCRYPT);
        if ($hash === false) {
            throw new \RuntimeException('Unable to generate hash.');
        }

        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}