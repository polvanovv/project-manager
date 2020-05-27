<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Email\Confirm;


use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;

/**
 * Class Handler
 *
 * @package App\Model\User\UseCase\Email\Confirm
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
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
     * Handler constructor.
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     */
    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher
    ) {

        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $user = $this->userRepository->get(new Id($command->id));

        $user->confirmEmailChanging($command->token);

        $this->flusher->flush();
    }
}