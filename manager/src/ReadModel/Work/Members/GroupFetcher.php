<?php


namespace App\ReadModel\Work\Members;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class GroupFetcher
{

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all()
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'g.id',
                'g.name'
            )
            ->from('work_members_group', 'g')
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);

    }
}