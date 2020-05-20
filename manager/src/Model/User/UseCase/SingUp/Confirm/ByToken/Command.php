<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\SingUp\Confirm\ByToken;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\SingUp\Confirm
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{
    /**
     * @var string
     */
    public $token;

    /**
     * Command constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

}