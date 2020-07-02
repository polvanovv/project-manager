<?php


namespace App\Model\Work\UseCase\Members\Group\Remove;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;
use App\Model\Work\Entity\Members\Member\MemberRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Members\Group\Remove
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
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * Handler constructor.
     * @param GroupRepository $groupRepository
     * @param MemberRepository $memberRepository
     * @param Flusher $flusher
     */
    public function __construct(GroupRepository $groupRepository, MemberRepository $memberRepository, Flusher $flusher)
    {
        $this->groupRepository = $groupRepository;
        $this->flusher = $flusher;
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $group = $this->groupRepository->get(new Id($command->id));

        if ($this->memberRepository->hasByGroup($group->getId())) {
            throw new \DomainException('Group is not empty');
        }

        $this->groupRepository->remove($group);

        $this->flusher->flush();
    }
}