<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Email\Confirm;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Email\Confirm
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $token;

    /**
     * Command constructor.
     * @param string $id
     * @param string $token
     */
    public function __construct(string $id, string  $token)
    {
        $this->id = $id;
        $this->token = $token;
    }
}