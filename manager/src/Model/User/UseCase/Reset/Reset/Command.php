<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Reset\Reset;

use Symfony\Component\Validator\Constraints as Assets;

class Command
{

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     * @Assets\NotBlank()
     * @Assets\Length(min="6")
     */
    public $password;

    public function __construct(string $token)
    {

        $this->token = $token;
    }

}