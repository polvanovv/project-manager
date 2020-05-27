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
        $user = $this->loadUser($username);

        if (!$user) {
            throw new UsernameNotFoundException('');
        }

        return self::identityByUserName($user, $username);
    }

    /**
     * @param UserInterface $identity
     * @return UserInterface
     */
    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($identity));
        }

        $user = $this->loadUser($identity->getUsername());

        if (!$user) {
            throw new UsernameNotFoundException('');
        }

        return self::identityByUserName($user, $identity->getUsername());

    }

    private function loadUser($username): AuthView
    {
        $chunks = explode(':', $username);
        if (count($chunks) === 2 && $user = $this->users->findForAuthByNetwork($chunks[0], $chunks[1])) {
            return $user;
        }

        if ($user = $this->users->findForAuthByEmail($username)) {
            return $user;
        }

        throw new UsernameNotFoundException('');

    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    /**
     * @param AuthView $user
     * @param string $username
     * @return UserIdentity
     */
    private static function identityByUserName(AuthView $user, string $username): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email ?: $username,
            $user->password_hash ?: '',
            $user->name ?: $username,
            $user->role,
            $user->status
        );
    }
}