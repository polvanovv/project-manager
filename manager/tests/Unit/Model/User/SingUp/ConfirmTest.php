<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Model\User\SingUp;

use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess()
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed');

        $user->confirmSignUp();
    }

}