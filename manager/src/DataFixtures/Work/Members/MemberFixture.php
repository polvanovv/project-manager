<?php


namespace App\DataFixtures\Work\Members;

use App\DataFixtures\UserFixtures;
use App\Model\User\Entity\User\User;
use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\Entity\Members\Member\Email;
use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Member\Name;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MemberFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        /**
         * @var User $admin
         * @var User $customer
         */
        $admin = $this->getReference(UserFixtures::REFERENCE_ADMIN);
        $customer = $this->getReference(UserFixtures::REFERENCE_USER);

        /**
         * @var Group $staffGroup
         * @var Group $customersGroup
         */
        $staffGroup = $this->getReference(GroupFixture::REFERENCE_STAFF);
        $customersGroup = $this->getReference(GroupFixture::REFERENCE_CUSTOMERS);

        $member = $this->createMember($admin, $staffGroup);
        $manager->persist($member);

        $member = $this->createMember($customer, $customersGroup);
        $manager->persist($member);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            GroupFixture::class,
        ];
    }

    private function createMember(User $user, Group $group)
    {
        return new Member(
            new Id($user->getId()),
            $group,
            new Name(
                $user->getName()->getFirst(),
                $user->getName()->getLast()
            ),
            new Email($user->getEmail() ? $user->getEmail()->getValue() : ' ')
        );
    }
}