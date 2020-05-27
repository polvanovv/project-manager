<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class RoleType
 *
 * @package App\Model\User\Entity\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class RoleType extends StringType
{
    public const NAME = 'user_user_role';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Role ? $value->getName() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Role|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Role($value) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}