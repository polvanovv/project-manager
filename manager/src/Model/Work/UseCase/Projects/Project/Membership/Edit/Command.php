<?php


namespace App\Model\Work\UseCase\Projects\Project\Membership\Edit;


use App\Model\Work\Entity\Projects\Project\Department\Department;
use App\Model\Work\Entity\Projects\Project\Membership;
use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Role\Role;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Projects\Project\Membership\Edit
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $project;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $member;

    /**
     * @Assert\NotBlank()
     */
    public $departments;

    /**
     * @Assert\NotBlank()
     */
    public $roles;

    /**
     * Command constructor.
     * @param string $project
     * @param string $member
     */
    public function __construct(string $project,string $member)
    {
        $this->project = $project;
        $this->member = $member;
    }

    /**
     * @param Project $project
     * @param Membership $membership
     * @return Command
     */
    public static function fromMembership(Project $project, Membership $membership)
    {
        $command = new self($project->getId()->getValue(), $membership->getMember()->getId()->getValue());

        $command->departments = array_map(static function (Department $department): string {
            return $department->getId()->getValue();
        }, $membership->getDepartments());

        $command->roles = array_map(static function (Role $role): string {
            return $role->getId()->getValue();
        }, $membership->getRoles());

        return $command;
    }
}