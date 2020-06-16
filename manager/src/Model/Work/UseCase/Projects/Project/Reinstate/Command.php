<?php


namespace App\Model\Work\UseCase\Projects\Project\Reinstate;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Projects\Project\Reinstate
 */
class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}