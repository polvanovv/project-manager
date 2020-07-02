<?php


namespace App\Model\Work\Entity\Projects\Project;

use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Projects\Project\Department\Department;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Common\Collections\ArrayCollection;
use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Class Membership
 * @package App\Model\Work\Entity\Projects\Project
 *
 * @ORM\Entity()
 * @ORM\Table(name="work_projects_project_memberships")
 */
class Membership
{

    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Work\Entity\Projects\Project\Project", inversedBy="memberships")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Work\Entity\Members\Member\Member", inversedBy="memberships")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false)
     */
    private $member;

    /**
     * @var ArrayCollection|Department[]
     * @ORM\ManyToMany(targetEntity="App\Model\Work\Entity\Projects\Project\Department\Department")
     * @ORM\JoinTable(name="work_projects_project_memberships_department",
     *     joinColumns={@ORM\JoinColumn(name="membership_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="id")}
     * )
     */
    private $departments;

    /**
     * @var ArrayCollection|Role[]
     * @ORM\ManyToMany(targetEntity="App\Model\Work\Entity\Projects\Role\Role")
     * @ORM\JoinTable(name="work_projects_project_memberships_roles",
     *      joinColumns={@ORM\JoinColumn(name="membereship_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * Membership constructor.
     * @param Project $project
     * @param Member $member
     * @param array $departments
     * @param array $roles
     */
    public function __construct(Project $project, Member $member, array $departments, array $roles)
    {
        $this->guardDepartments($departments);
        $this->guardRoles($roles);

        $this->id = Uuid::uuid4()->toString();
        $this->project = $project;
        $this->member = $member;
        $this->departments = new ArrayCollection($departments);
        $this->roles = new ArrayCollection($roles);
    }

    /**
     * Removes unnecessary and adds missing departments
     *
     * @param array $departments
     */
    public function changeDepartments(array $departments): void
    {
        $this->guardDepartments($departments);

        $current = $this->departments->toArray();
        $new = $departments;

        $compare = static function (Department $a, Department $b): int {
            return $a->getId()->getValue() <=> $b->getId()->getValue();
        };

        foreach (array_udiff($current, $new, $compare) as $item) {
            $this->departments->removeElement($item);
        }

        foreach (array_udiff($new, $current, $compare) as $item) {
            $this->departments->add($item);
        }
    }

    /**
     * Removes unnecessary and adds missing departments
     *
     * @param array $roles
     */
    public function changeRoles(array $roles):void
    {
        $this->guardRoles($roles);

        $current = $this->roles->toArray();
        $new = $roles;

        $compare = static function (Role $a, Role $b) {
            return $a->getId()->getValue() <=> $b->getId()->getValue();
        };

        foreach (array_udiff($current, $new, $compare) as $item) {
            $this->roles->removeElement($item);
        }

        foreach (array_udiff($new, $current, $compare) as $item) {
            $this->roles->add($item);
        }
    }

    /**
     * @param MemberId $id
     * @return bool
     */
    public function isForMember(MemberId $id): bool
    {
      return  $this->member->getId()->isEqual($id);
    }

    /**
     * @param DepartmentId $id
     * @return bool
     */
    public function isForDepartment(DepartmentId $id): bool 
    {
        foreach ($this->departments as $department) {
            if ($department->getId()->isEqual($id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function isGranted(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * @return Department[]|ArrayCollection
     */
    public function getDepartments()
    {
        return $this->departments->toArray();
    }

    /**
     * @return Role[]|ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @param array $departments
     */
    public function guardDepartments(array $departments): void
    {
        if (count($departments) === 0) {
            throw new \DomainException('Set at least one department');
        }
    }

    /**
     * @param array $roles
     */
    public function guardRoles(array $roles): void
    {
        if (count($roles) === 0) {
            throw new \DomainException('Set at least one role');
        }
    }

}