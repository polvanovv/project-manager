<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Activate;


/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Activate
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
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}