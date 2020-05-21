<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\SingUp\Request;


use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\SignUpConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;

/**
 * Class Handler
 *
 * @package App\Model\User\UseCase\SingUp\Request
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Handler
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var PasswordHasher
     */
    private $hasher;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var SignUpConfirmTokenizer
     */
    private $tokenizer;
    /**
     * @var SignUpConfirmTokenSender
     */
    private $tokenSender;


    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param PasswordHasher $hasher
     * @param Flusher $flusher
     * @param SignUpConfirmTokenizer $tokenizer
     * @param SignUpConfirmTokenSender $tokenSender
     */
    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher,
        SignUpConfirmTokenizer $tokenizer,
        SignUpConfirmTokenSender $tokenSender)
    {

        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);

        $this->tokenSender->send($email, $token);

        $this->flusher->flush();
    }

}