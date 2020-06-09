<?php


namespace App\Model\Work\UseCase\Members\Member\Edit;


use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\Email;
use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Members\Member\Name;

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

        $member->edit(
            new Name(
                $command->firstName,
                $command->lastName
            ),
            new Email($command->email)
        );

        $this->flusher->flush();
    }
}