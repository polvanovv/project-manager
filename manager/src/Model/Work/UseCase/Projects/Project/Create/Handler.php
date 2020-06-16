<?php


namespace App\Model\Work\UseCase\Projects\Project\Create;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Project\Create
 */
class Handler
{
    /**
     * @var ProjectRepository
     */
    private $projects;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param ProjectRepository $projects
     * @param Flusher $flusher
     */
    public function __construct(ProjectRepository $projects, Flusher $flusher)
    {
        $this->projects = $projects;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $project = new Project(
            Id::next(),
            $command->name,
            $command->sort
        );

        $this->projects->add($project);

        $this->flusher->flush();
    }
}