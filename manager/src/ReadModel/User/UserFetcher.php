<?php

declare(strict_types = 1);

namespace App\ReadModel\User;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

/**
 * Class UserFetcher
 *
 * @package App\ReadModel\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class UserFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * UserFetcher constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {

        $this->connection = $connection;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
                    ->select('COUNT(*)')
                    ->from('user_users')
                    ->where('reset_token_token = :token')
                    ->setParameter(':token', $token)
                    ->execute()->fetchColumn(0) > 0;
    }

    public function findForAuthByEmail(string $email): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function findForAuthByNetwork(string $network, string $identity): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'u.id',
                'u.email',
                'u.password_hash',
                'u.role',
                'u.status'
            )
            ->from('user_users', 'u')
            ->innerJoin('u', 'user_user_networks', 'n', 'n.user_id = u.id')
            ->where('n.network = :network AND n.identity = :identity')
            ->setParameter(':network', $network)
            ->setParameter(':identity', $identity)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}