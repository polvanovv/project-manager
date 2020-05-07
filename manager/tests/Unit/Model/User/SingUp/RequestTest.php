<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\SingUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 *
 * @package App\Tests\Unit\Model\User\SingUp
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class RequestTest extends TestCase
{

    public function testSuccess()
    {
        $user = new User(
            $id = Id::next(),
            $createdAt = new \DateTimeImmutable(),
        );

        $user->signUpByEmail(
            $email = new Email('test@test.com'),
            $hash = 'hash',
            $token = 'token'
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());


        self::assertEquals($email, $user->getEmail());
        self::assertEquals($createdAt, $user->getCreatedAt());
        self::assertEquals($id, $user->getId());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());
    }
}