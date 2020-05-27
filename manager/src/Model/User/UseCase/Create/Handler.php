<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Create;


use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordGenerator;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * @var PasswordHasher
     */
    private $hasher;

    /**
     * @var PasswordGenerator
     */
    private $generator;

    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher,
        PasswordHasher $hasher,
        PasswordGenerator $generator
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->hasher = $hasher;
        $this->generator = $generator;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('User with this email already exists.');
        }

        $user = User::createUser(
            Id::next(),
            new \DateTimeImmutable(),
            new Name(
                $command->firstName,
                $command->lastName
            ),
            $email,
            $this->hasher->hash($this->generator->generate())
        );

        $this->userRepository->add($user);

        $this->flusher->flush();

    }
}