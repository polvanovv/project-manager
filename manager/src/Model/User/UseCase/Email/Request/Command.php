<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Email\Request;

use Symfony\Component\Validator\Constraints as Asserts;

/**
 * Class Command
 *
 * @package App\Model\User\UseCase\Email\Request
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Command
{

    /**
     * @var string
     * @Asserts\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Asserts\NotBlank()
     * @Asserts\Email()
     */
    public $email;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {

        $this->id = $id;
    }
}