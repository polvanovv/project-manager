<?php


namespace App\DataFixtures\Work\Projects;

use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Project\Id;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProjectFixture
 * @package App\DataFixtures\Work\Projects
 */
class ProjectFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $active = $this->createProject('First Project', 1);
        $active->addDepartment(DepartmentId::next(), 'Development');
        $active->addDepartment(DepartmentId::next(), 'Marketing');
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
}