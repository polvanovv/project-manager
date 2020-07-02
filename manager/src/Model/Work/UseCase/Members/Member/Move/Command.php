<?php


namespace App\Model\Work\UseCase\Members\Member\Move;


use App\Model\Work\Entity\Members\Member\Member;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Members\Member\Move
 */
class Command
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $group;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param Member $member
     * @return static
     */
    public static function fromMember(Member $member): self
    {
        $command = new self($member->getId()->getValue());

        $command->group = $member->getGroup()->getName();

        return $command;
    }
}