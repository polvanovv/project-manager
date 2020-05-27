<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Network\Attach;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Network\Attach
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{
    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $network;

    /**
     * @var string
     */
    public $identity;

    /**
     * Command constructor.
     * @param string $user
     * @param string $network
     * @param string $identity
     */
    public function __construct(string $user, string $network, string $identity)
    {
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

}