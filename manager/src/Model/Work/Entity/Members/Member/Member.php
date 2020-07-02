<?php


namespace App\Model\Work\Entity\Members\Member;


use App\Model\Work\Entity\Members\Group\Group;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="work_members_members")
 */
class Member
{
    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="work_members_member_id")
     */
    private $id;

    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity="App\Model\Work\Entity\Members\Group\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * @var Name
     * @ORM\Embedded(class="App\Model\Work\Entity\Members\Member\Name")
     */
    private $name;

    /**
     * @var Email
     * @ORM\Column(type="work_members_member_email")
     */
    private $email;

    /**
     * @var Status
     * @ORM\Column(type="work_members_member_status", length=16)
     */
    private $status;

    /**
     * Member constructor.
     * @param Id $id
     * @param Group $group
     * @param Name $name
     * @param Email $email
     */
    public function __construct(Id $id, Group $group, Name $name, Email $email)
    {
        $this->id = $id;
        $this->group = $group;
        $this->name = $name;
        $this->email = $email;
        $this->status = Status::active();
    }

    /**
     * @param Name $name
     * @param Email $email
     */
    public function edit(Name $name, Email $email): void
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @param Group $group
     */
    public function move(Group $group): void
    {
        $this->group = $group;
    }

    public function archive(): void
    {
        if ($this->status->isArchived()) {
            throw new \DomainException('Member is already archived.');
        }

        $this->status = Status::archived();
    }

    public function reinstate(): void
    {
        if ($this->status->isActive()) {
            throw new \DomainException('Member is already active.');
        }

        $this->status = Status::active();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->status->isArchived();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

}