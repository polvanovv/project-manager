<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Model\User\Email;


use App\Model\User\Entity\User\Email;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 *
 * @package App\Tests\Unit\Model\User\Email
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class RequestTest extends TestCase
{

    public function testSuccess()
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $user->requestEmailChanging(
            $email = new Email('new@app.test'),
            $token = 'token'
        );

        self::assertEquals($email, $user->getNewEmail());
        self::assertEquals($token, $user->getNewEmailToken());
    }

    public function testSame()
    {
        $user = (new UserBuilder())
            ->viaEmail($email = new Email('new@app.test'))
            ->confirmed()
            ->build();

        $this->expectExceptionMessage('Email is already same.');
        $user->requestEmailChanging($email, 'token');
    }

    public function testNotConfirmed()
    {
        $user = (new UserBuilder())
            ->viaEmail()
            ->build();

        $this->expectExceptionMessage('User is not active.');
        $user->requestEmailChanging(new Email('new@app.test'), 'token');
    }


}