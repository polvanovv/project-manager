<?php


namespace App\DataFixtures\Work\Projects;

use App\DataFixtures\Work\Members\MemberFixture;
use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProjectFixture
 * @package App\DataFixtures\Work\Projects
 */
class ProjectFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Member $admin */
        $admin = $this->getReference(MemberFixture::REFERENCE_ADMIN);

        /** @var Role $manage */
        $manage = $this->getReference(RoleFixture::REFENCE_MANAGER);

        $active = $this->createProject('First Project', 1);
        $active->addDepartment($development = DepartmentId::next(), 'Development');
        $active->addDepartment(DepartmentId::next(), 'Marketing');
        $active->addMember($admin, [$development], [$manage]);
        $manager->persist($active);

        $active = $this->createProject('Second Project', 2);
        $manager->persist($active);

        $archived = $this->createArchivedProject('Third Project', 3);
        $manager->persist($archived);

        $manager->flush();
    }

    /**
     * @param string $name
     * @param int $sort
     * @return Project
     */
    private function createArchivedProject(string $name, int $sort): Project
    {
        $project = $this->createProject($name, $sort);
        $project->archive();
        return $project;
    }

    /**
     * @param string $name
     * @param int $sort
     * @return Project
     */
    private function createProject(string $name, int $sort): Project
    {
        return new Project(
            Id::next(),
            $name,
            $sort
        );
    }

    public function getDependencies()
    {
        return [
            MemberFixture::class,
            RoleFixture::class,
        ];
    }
}