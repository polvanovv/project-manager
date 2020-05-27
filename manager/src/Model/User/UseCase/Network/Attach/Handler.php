<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Network\Attach;


use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;

/**
 * Class Handler
 *
 * @package App\Model\User\UseCase\Network\Attach
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
    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        if ($this->userRepository->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new \DomainException('Profile is alredy in use.');
        }

        $user = $this->userRepository->get(new Id($command->user));

        $user->attachedNetwork(
            $command->network,
            $command->identity
        );

        $this->flusher->flush();
    }
}