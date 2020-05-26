<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Network\Detach;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Network\Detach
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
    public $network;

    /**
     * @var string
     */
    public $identity;

    /**
     * Command constructor.
     * @param string $id
     * @param string $network
     * @param string $identity
     */
    public function __construct(string $id, string $network, string $identity)
    {
        $this->id = $id;
        $this->network = $network;
        $this->identity = $identity;
    }

}