<?php


namespace App\Model\Work\UseCase\Projects\Project\Department\Create;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Project\Department\Id;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Project\Id as ProjectId;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Project\Department\Create
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
    public function handle(Command $command)
    {
        $project = $this->projectRepository->get(new ProjectId($command->project));

        $project->addDepartment(
            Id::next(),
            $command->name
        );

        $this->flusher->flush();
    }

}