<?php


namespace App\Model\Work\Entity\Members\Member;


use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class MemberRepository
 * @package App\Model\Work\Entity\Members\Member
 */
class MemberRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * MemberRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Member::class);
    }

    /**
     * @param Id $id
     * @return bool
     */
    public function has(Id $id): bool
    {
        return $this->repo->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.id = :id')
            ->setParameter(':id', $id->getValue())
            ->getQuery()->getScalarResult() > 0;
    }


    /**
     * @param Id $id
     * @return object|null
     */
    public function get(Id $id)
    {
        if (!$member = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Member is not found.');
        }

        return $member;
    }

    /**
     * @param Member $member
     */
    public function add(Member $member): void 
    {
        $this->em->persist($member);
    }

}