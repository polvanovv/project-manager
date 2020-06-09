<?php


namespace App\Model\Work\Entity\Members\Group;


use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GroupRepository
 * @package App\Model\Work\Entity\Members\Group
 */
class GroupRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository 
     */
    private $repo;

    /**
     * GroupRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Group::class);
    }

    /**
     * @param Id $id
     * @return Group|object
     */
    public function get(Id $id): Group
    {
        if (!$group = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Group is not found');
        }

        return $group;
    }

    /**
     * @param Group $group
     */
    public function add(Group $group): void
    {
        $this->em->persist($group);
    }

    /**
     * @param Group $group
     */
    public function remove(Group $group): void
    {
        $this->em->remove($group);
    }
}