<?php


namespace App\Model\Work\UseCase\Projects\Project\Department\Create;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Projects\Project\Department\Create
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
    public $name;

    /**
     * Command constructor.
     * @param string $project
     */
    public function __construct(string $project)
    {
        $this->project = $project;
    }
}