<?php

declare(strict_types = 1);

namespace App\Model\User\Service;


use Ramsey\Uuid\Nonstandard\Uuid;

/**
 * Class EmailConfirmTokenizer
 *
 * @package App\Model\User\Service
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class EmailConfirmTokenizer
{
    /**
     * @return string
     */
    public function generete()
    {
        return Uuid::uuid4()->toString();
    }

}