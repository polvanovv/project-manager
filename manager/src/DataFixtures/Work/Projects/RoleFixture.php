<?php


namespace App\DataFixtures\Work\Projects;


use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\Permission;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RoleFixture
 * @package App\DataFixtures\Work\Projects
 */
class RoleFixture extends Fixture
{
    /**
     *
     */
    public const REFENCE_MANAGER = 'work_project_role_manager';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $guest = $this->createRole('Guest', []);
        $manager->persist($guest);

        $manage = $this->createRole('Manager', [
            Permission::MANAGE_PROJECT_MEMBERS,
        ]);
        $manager->persist($manage);
        $this->setReference(self::REFENCE_MANAGER, $manage);


        $manager->flush();
    }

    /**
     * @param string $name
     * @param array $permissions
     * @return Role
     */
    private function createRole(string $name, array $permissions): Role
    {
        return new Role(
            Id::next(),
            $name,
            $permissions
        );
    }
}