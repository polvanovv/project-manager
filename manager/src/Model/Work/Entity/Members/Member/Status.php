<?php


namespace App\Model\Work\Entity\Members\Member;


use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Status
 * @package App\Model\Work\Entity\Members\Member
 *
 */
class Status
{
    public const ACTIVE = 'active';
    public const ARCHIVER = 'archived';

    /**
     * @var string
     */
    private $name;

    /**
     * Status constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name,[
            self::ACTIVE,
            self::ARCHIVER,
        ]);

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Status $other
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getName() === $other->getName();
    }

    /**
     * @return static
     */
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    /**
     * @return static
     */
    public static function archived(): self
    {
        return new self(self::ARCHIVER);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->name === self::ARCHIVER;
    }

}