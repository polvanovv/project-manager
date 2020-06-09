<?php


namespace App\Model\Work\UseCase\Members\Member\Create;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Member\Email;
use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Group\Id as GroupId;
use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Members\Member\Name;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Members\Member\Create
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
    public function __construct(MemberRepository $memberRepository, GroupRepository $groupRepository, Flusher $flusher)
    {
        $this->memberRepository = $memberRepository;
        $this->groupRepository = $groupRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $id = new Id($command->id);

        if (!$this->memberRepository->has($id)) {
            throw new \DomainException('Member already exists');
        }

        $group = $this->groupRepository->get(new GroupId($command->group));

        $member = new Member(
            $id,
            $group,
            new Name(
                $command->firstName,
                $command->lastName
            ),
            new Email($command->email)
        );

        $this->memberRepository->add($member);

        $this->flusher->flush();
    }
}