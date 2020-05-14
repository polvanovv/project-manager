<?php

declare(strict_types = 1);

namespace App\Security;


use App\ReadModel\User\AuthView;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 *
 * @package App\Security
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class UserProvider implements UserProviderInterface
{

    /**
     * @var UserFetcher
     */
    private $users;

    /**
     * UserProvider constructor.
     * @param UserFetcher $users
     */
    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    /**
     * @param string $username
     * @return UserIdentity|UserInterface
     */
    public function loadUserByUsername(string $username)
    {
        $user = $this->users->findForAuth($username);

        if (!$user) {
            throw new UsernameNotFoundException('');
        }

        return self::userByUserName($user);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }

        $user = $this->users->findForAuth($user->getUsername());

        if (!$user) {
            throw new UsernameNotFoundException('');
        }

        return self::userByUserName($user);

    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return $class instanceof UserIdentity;
    }

    /**
     * @param AuthView $user
     * @return UserIdentity
     */
    private static function userByUserName(AuthView $user): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->role,
            $user->status
        );
    }
}