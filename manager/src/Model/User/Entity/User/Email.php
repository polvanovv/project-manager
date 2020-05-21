<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;


use Webmozart\Assert\Assert;

/**
 * Class Email
 *
 * @package App\Model\User\Entity\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Email
{

    /**
     * @var string
     */
    private $value;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException('Incorrect Email');
        }

        $this->value = mb_strtolower($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Email $other
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}