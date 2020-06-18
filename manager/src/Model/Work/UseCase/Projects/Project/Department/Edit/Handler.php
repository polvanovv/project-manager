<?php


namespace App\Model\Work\UseCase\Projects\Project\Department\Edit;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Project\Department\Edit
 */
class Handler
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param ProjectRepository $projectRepository
     * @param Flusher $flusher
     */
    public function __construct(ProjectRepository $projectRepository, Flusher $flusher)
    {
        $this->projectRepository = $projectRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get(new Id($command->project));

        $project->editDepartment(new DepartmentId($command->id), $command->name);

        $this->flusher->flush();
    }

}