<?php


namespace App\Model\Work\UseCase\Members\Member\Move;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Member\Id;
use \App\Model\Work\Entity\Members\Group\Id as GroupId;
use App\Model\Work\Entity\Members\Member\MemberRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Members\Member\Move
 */
class Handler
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;
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
     * @param MemberRepository $memberRepository
     * @param GroupRepository $groupRepository
     * @param Flusher $flusher
     */
    public function __construct(MemberRepository $memberRepository, GroupRepository $groupRepository , Flusher $flusher)
    {
        $this->memberRepository = $memberRepository;
        $this->groupRepository = $groupRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $member = $this->memberRepository->get(new Id($command->id));
        $group = $this->groupRepository->get(new GroupId($command->group));

        $member->move($group);

        $this->flusher->flush();
    }
}