<?php


namespace App\Model\Work\UseCase\Projects\Project\Membership\Edit;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;
use App\Model\Work\Entity\Projects\Role\Id as RoleId;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Role\Role;
use App\Model\Work\Entity\Projects\Role\RoleRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Project\Membership\Edit
 */
class Handler
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;
    /**
     * @var MemberRepository
     */
    private $memberRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param ProjectRepository $projectRepository
     * @param MemberRepository $memberRepository
     * @param RoleRepository $roleRepository
     * @param Flusher $flusher
     */
    public function __construct(
        ProjectRepository $projectRepository,
        MemberRepository $memberRepository,
        RoleRepository $roleRepository,
        Flusher $flusher
    )
    {
        $this->projectRepository = $projectRepository;
        $this->memberRepository = $memberRepository;
        $this->roleRepository = $roleRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get(new Id($command->project));
        $member = $this->memberRepository->get(new  MemberId($command->member));

        $departments = array_map(static function (string $id): DepartmentId {
            return new DepartmentId($id);
        }, $command->departments);

        $roles = array_map(function (string $id): Role {
            return $this->roleRepository->get(new RoleId($id));
        }, $command->roles);

        $project->editMember($member->getId(), $departments, $roles);

        $this->flusher->flush();
    }
}