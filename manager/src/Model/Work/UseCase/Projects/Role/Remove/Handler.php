<?php


namespace App\Model\Work\UseCase\Projects\Role\Remove;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\RoleRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Role\Remove
 */
class Handler
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * Handler constructor.
     * @param RoleRepository $roleRepository
     * @param ProjectRepository $projectRepository
     * @param Flusher $flusher
     */
    public function __construct(RoleRepository $roleRepository, ProjectRepository $projectRepository, Flusher $flusher)
    {
        $this->roleRepository = $roleRepository;
        $this->flusher = $flusher;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $role = $this->roleRepository->get(new Id($command->id));

        if ($this->projectRepository->hasMemberWithRole($role->getId())) {
            throw new \DomainException('Role contains member.');
        }

        $this->roleRepository->remove($role);

        $this->flusher->flush();
    }
}