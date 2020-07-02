<?php


namespace App\Model\Work\Entity\Projects\Role;


use Webmozart\Assert\Assert;

/**
 * Class Permission
 * @package App\Model\Work\Entity\Projects\Role
 */
class Permission
{
    public const MANAGE_PROJECT_MEMBERS = 'manage_project_members';

    /**
     * @var string
     */
    private $name;

    /**
     * Permission constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name,self::names());
        $this->name = $name;
    }

    public static function names(): array
    {
        return [
            self::MANAGE_PROJECT_MEMBERS,
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function isEqual(string $name): bool
    {
        return $this->name === $name;
    }
}