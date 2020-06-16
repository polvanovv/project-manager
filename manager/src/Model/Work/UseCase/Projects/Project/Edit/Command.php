<?php


namespace App\Model\Work\UseCase\Projects\Project\Edit;


use App\Model\Work\Entity\Projects\Project\Project;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Projects\Project\Edit
 */
class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @Assert\NotBlank()
     */
    public $name;
    /**
     * @Assert\NotBlank()
     */
    public $sort;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param Project $project
     * @return static
     */
    public static function fromProject(Project $project): self
    {
        $command = new self($project->getId()->getValue());
        $command->name = $project->getName();
        $command->sort = $project->getSort();
        return $command;
    }
}