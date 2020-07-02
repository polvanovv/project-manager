<?php


namespace App\Model\Work\UseCase\Members\Member\Reinstate;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Member\MemberRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Members\Member\Reinstate
 */
class Handler
{
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
     * @param MemberRepository $memberRepository
     * @param Flusher $flusher
     */
    public function __construct(MemberRepository $memberRepository, Flusher $flusher)
    {
        $this->memberRepository = $memberRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $member = $this->memberRepository->get(new Id($command->id));

        $member->reinstate();

        $this->flusher->flush();
    }
}