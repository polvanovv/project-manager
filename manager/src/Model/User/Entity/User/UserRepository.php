<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;


/**
 * Interface UserRepository
 * @package App\Model\User\Entity\User
 */
interface UserRepository
{

    public function hasByEmail(Email $email): bool;

    public function hasByNetworkIdentity(string $network, string $identity);

    public function add(User $user): void;

    public function findByConfirmToken(string $token): ?User;

    public function getByEmail(Email $email): ?User;

    public function findByResetToken(string $token): ?User;

}