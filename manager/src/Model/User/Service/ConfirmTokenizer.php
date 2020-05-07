<?php

declare(strict_types = 1);

namespace App\Model\User\Service;


use Ramsey\Uuid\Uuid;

/**
 * Class ConfirmTokenizer
 *
 * @package App\Model\User\Service
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class ConfirmTokenizer
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}