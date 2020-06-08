<?php


namespace App\Model\Work\Entity\Members\Group;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Group
 * @package App\Model\Work\Entity\Members\Group
 *
 * @ORM\Entity()
 * @ORM\Table(name="work_members_group")
 */
class Group
{
    /**
     * @var Id
     * @ORM\Column(type="work_members_group_id")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Group constructor.
     * @param Id $id
     * @param string $name
     */
    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param string $name
     */
    public function edit(string $name): void
    {
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
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }
}