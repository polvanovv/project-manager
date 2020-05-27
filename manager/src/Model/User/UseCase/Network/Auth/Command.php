<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Network\Auth;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Network\Auth
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{
    /**
     * @var string
     */
    public $network;

    /**
     * @var string
     */
    public $identity;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * Command constructor.
     * @param string $network
     * @param string $id
     */
    public function __construct(string $network, string $id)
    {
        $this->network = $network;
        $this->identity = $id;
    }

}