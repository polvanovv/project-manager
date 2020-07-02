<?php


namespace App\ReadModel\Work\Projects\Project;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class DepartmentFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $project
     * @return mixed[]
     */
    public function allOfProject(string $project): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'd.id',
                'd.name',
                '(
                    SELECT COUNT(ms.member_id)
                    FROM work_projects_project_memberships ms
                    INNER JOIN work_projects_project_memberships_department md ON ms.id = md.membership_id
                    WHERE md.department_id = d.id AND ms.project_id = :project
                ) as members_count'
            )
            ->from('work_projects_project_departments', 'd')
            ->andWhere('d.project_id = :project')
            ->setParameter(':project', $project)
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }

    /**
     * @param string $project
     * @return array
     */
    public function listOfProject(string $project): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'name'
            )
            ->from('work_projects_project_departments')
            ->andWhere('project_id = :project')
            ->setParameter(':project', $project)
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function allOfMember(string $member)
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'p.id as project_id',
                'p.name as project_name',
                'd.id as department_id',
                'd.name as department_name'
            )
            ->from('work_projects_project_memberships', 'ms')
            ->innerJoin('ms', 'work_projects_project_memberships_department', 'msd', 'ms.id = msd.membership_id')
            ->innerJoin('msd', 'work_projects_project_departments', 'd', 'msd.department_id = d.id')
            ->innerJoin('d', 'work_projects_projects', 'p', 'd.project_id = p.id')
            ->andWhere('ms.member_id = :member')
            ->setParameter(':member', $member)
            ->orderBy('p.sort')->addOrderBy('d.name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}