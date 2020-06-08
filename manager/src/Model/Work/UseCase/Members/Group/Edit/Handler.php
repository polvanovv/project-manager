<?php


namespace App\Model\Work\UseCase\Members\Group\Edit;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Members\Group\Edit
 */
class Handler
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param GroupRepository $groupRepository
     * @param Flusher $flusher
     */
    public function __construct(GroupRepository $groupRepository, Flusher $flusher)
    {
        $this->groupRepository = $groupRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $group = $this->groupRepository->get(new Id($command->id));

        $group->edit($command->name);

        $this->flusher->flush();
    }
}