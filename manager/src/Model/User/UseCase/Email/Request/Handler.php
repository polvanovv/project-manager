<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Email\Request;


use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\EmailConfirmTokenizer;
use App\Model\User\Service\EmailConfirmTokenSender;

/**
 * Class Handler
 *
 * @package App\Model\User\UseCase\Email\Request
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Handler
{

    /**
     * @var EmailConfirmTokenizer
     */
    private $tokenizer;

    /**
     * @var EmailConfirmTokenSender
     */
    private $sender;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param EmailConfirmTokenizer $tokenizer
     * @param EmailConfirmTokenSender $sender
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     */
    public function __construct(
        EmailConfirmTokenizer $tokenizer,
        EmailConfirmTokenSender $sender,
        UserRepository $userRepository,
        Flusher $flusher
    )
    {
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function handle(Command $command): void
    {
        $user = $this->userRepository->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('Email is already in use.');
        }

        $user->requestEmailChanging(
            $email,
            $token = $this->tokenizer->generete()
        );

        $this->flusher->flush();
        $this->sender->send($email, $token);
    }
}