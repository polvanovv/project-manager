<?php

declare(strict_types = 1);

namespace App\Tests\Builder\User;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;

/**
 * Class UserBuilder
 *
 * @package App\Tests\Builder\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class UserBuilder
{
    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;

    private $confirmed;

    private $network;
    private $identity;

    private $role;

    /**
     * UserBuilder constructor.
     */
    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    /**
     * @param Email|null $email
     * @param string|null $hash
     * @param string|null $token
     * @return $this
     */
    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {

        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';

        return $clone;
    }

    /**
     * @param string|null $network
     * @param string|null $identity
     * @return $this
     */
    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'vk';
        $clone->identity = $identity ?? '0001';

        return $clone;
    }

    /**
     * @return self
     */
    public function confirmed():self
    {
        $clone = clone $this;
        $clone->confirmed = true;

        return $clone;
    }

    public function withRole(Role $role): self
    {
        $clone = clone $this;

        $clone->role = $role;
        return $clone;
    }

    public function build(): User
    {

        $user = null;

        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );
        }

        if ($this->confirmed) {
            $user->confirmSignUp();
        }

        if ($this->network) {
            $user = User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->network,
                $this->identity
            );
        }

        if ($this->role) {
            $user->changeRole($this->role);
        }

        return $user;
    }

}