<?php


namespace App\ReadModel\Work\Members\Member;


use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Members\Member\Status;
use App\ReadModel\Work\Members\Member\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class MemberFetcher
 * @package App\ReadModel\Work\Members\Member
 */
class MemberFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    private $repository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * MemberFetcher constructor.
     * @param Connection $connection
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        Connection $connection,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {

        $this->connection = $connection;
        $this->repository = $em->getRepository(Member::class);
        $this->paginator = $paginator;
    }

    /**
     * @param $id
     * @return Member|null
     */
    public function find($id): ?Member
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $limit
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $limit, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'm.id',
                'm.email',
                'TRIM(CONCAT(name_first, \' \', name_last)) as name',
                'g.name as group',
                'm.status',
                '(SELECT COUNT(*) FROM work_projects_project_memberships ms WHERE ms.member_id = m.id) as memberships_count'
            )
            ->from('work_members_members', 'm')
            ->innerJoin('m', 'work_members_group', 'g', 'm.group_id = g.id');

        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(CONCAT(name_first, \' \', name_last))', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }
        if ($filter->email) {
            $qb->andWhere($qb->expr()->like('LOWER(m.email)', ':email'));
            $qb->setParameter(':email', '%' . mb_strtolower($filter->email) . '%');
        }
        if ($filter->group) {
            $qb->andWhere('m.group_id = :group');
            $qb->setParameter(':group', $filter->group);
        }
        if ($filter->status) {
            $qb->andWhere('m.status = :status');
            $qb->setParameter(':status', $filter->status);
        }

        if (!in_array($sort, ['name', 'email', 'group', 'status'])) {
            throw new \UnexpectedValueException('Cannot sort by' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (id)')
                ->from('work_members_members')
                ->where('id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }


    public function activeGroupedList()
    {
        return  $this->connection->createQueryBuilder()
            ->select([
                'm.id',
                'CONCAT(m.name_first,\' \',m.name_last) as name',
                'g.name as group',
            ])
            ->from('work_members_members', 'm')
            ->leftJoin('m', 'work_members_group', 'g', 'g.id = m.group_id')
            ->where('m.status = :status')
            ->setParameter(':status', Status::ACTIVE)
            ->orderBy('g.name')->addOrderBy('name')
            ->execute()->fetchAll(FetchMode::ASSOCIATIVE);
    }
}