<?php


namespace App\Model\Work\Entity\Projects\Project;

use App\Model\EntityNotFoundException;
use App\Model\Work\Entity\Projects\Role\Id as RoleId;
use Doctrine\ORM\EntityManagerInterface;

class ProjectRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Project::class);
        $this->em = $em;
    }

    /**
     * @param RoleId $id
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasMemberWithRole(RoleId $id): bool
    {
        return $this->repo->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.memberships', 'ms')
            ->innerJoin('ms.roles', 'r')
            ->andWhere('r.id = :role')
            ->setParameter(':role', $id->getId()->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(Id $id): Project
    {
        /** @var Project $project */
        if (!$project = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Project is not found.');
        }
        return $project;
    }

    public function add(Project $project): void
    {
        $this->em->persist($project);
    }

    public function remove(Project $project): void
    {
        $this->em->remove($project);
    }
}