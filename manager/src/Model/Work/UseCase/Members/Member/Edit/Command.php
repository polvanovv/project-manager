<?php


namespace App\Model\Work\UseCase\Members\Member\Edit;


use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Member\Member;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Model\Work\UseCase\Members\Member\Edit
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
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $lastName;

    /**
     * @var string
     * @Assert\Email()
     */
    public $email;

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
        $command->firstName = $member->getName()->getFirst();
        $command->lastName = $member->getName()->getLast();
        $command->email = $member->getEmail()->getValue();

        return $command;
    }
}