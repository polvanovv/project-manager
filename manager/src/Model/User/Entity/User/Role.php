<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;


use Webmozart\Assert\Assert;

/**
 * Class Role
 *
 * @package App\Model\User\Entity\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Role
{
    public const USER = 'ROLE_USER';
    public const ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    private $name;

    /**
     * Role constructor.
     * @param string $name
     */
    public function __construct(string  $name)
    {
        Assert::oneOf($name,[
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    /**
     * @return static
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return static
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return  $this->name === self::USER;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }


}