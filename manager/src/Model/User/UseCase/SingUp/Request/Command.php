<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\SingUp\Request;

use Symfony\Component\Validator\Constraints as Assert;
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
     * @var string
     * @Assert\NotBlank()
     *
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     */
    public $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public $password;

}