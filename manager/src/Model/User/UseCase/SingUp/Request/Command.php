<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\SingUp\Request;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\SingUp\Request
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{

    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $password;

}