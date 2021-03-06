<?php


namespace App\Model\Work\Entity\Members\Member;


use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Model\Work\Entity\Members\Group\Id as GroupId;

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
     * @param GroupId $id
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasByGroup(GroupId $id): bool
    {
        return  $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.group = :id')
            ->setParameter('id', $id->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }


    /**
     * @param Id $id
     * @return Member|object
     */
    public function get(Id $id): ?Member
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