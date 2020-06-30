<?php


namespace App\Model\Work\UseCase\Projects\Project\Membership\Remove;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Project\Membership\Remove
 */
class Handler
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;
    /**
     * @var MemberRepository
     */
    private $memberRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param ProjectRepository $projectRepository
     * @param MemberRepository $memberRepository
     * @param Flusher $flusher
     */
    public function __construct(
        ProjectRepository $projectRepository,
        MemberRepository $memberRepository,
        Flusher $flusher
    )
    {

        $this->projectRepository = $projectRepository;
        $this->memberRepository = $memberRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get(new Id($command->project));
        $member = $this->memberRepository->get(new MemberId($command->member));

        $project->removeMember($member->getId());

        $this->flusher->flush();
    }

}