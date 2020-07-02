<?php


namespace App\Model\Work\Entity\Projects\Role;


use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class RoleRepository
 * @package App\Model\Work\Entity\Projects\Role
 */
class RoleRepository
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
     * RoleRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Role::class);
    }


    /**
     * @param string $name
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.name)')
            ->andWhere('t.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Id $id
     * @return Role
     */
    public function get(Id $id): Role
    {
        /** @var Role $role */
        if (!$role = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Role is not found.');
        }
        return $role;
    }

    /**
     * @param Role $role
     */
    public function add(Role $role): void
    {
        $this->em->persist($role);
    }

    /**
     * @param Role $role
     */
    public function remove(Role $role): void
    {
        $this->em->remove($role);
    }
}