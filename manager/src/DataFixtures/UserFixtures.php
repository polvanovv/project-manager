<?php

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const REFERENCE_ADMIN = 'user_user_admin';
    public const REFERENCE_USER = 'user_user_user';

    /**
     * @var PasswordHasher
     */
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('password');

        $admin = $this->createAdminByEmail(
            new Name('James', 'Bond'),
            new Email('james@app.com'),
            $hash
        );
        $manager->persist($admin);
        $this->setReference(self::REFERENCE_ADMIN, $admin);

        $confirmed = $this->createSignUpConfirmedByEmail(
            new Name('Shone', 'Bean'),
            new Email('shone@app.com'),
            $hash
        );
        $manager->persist($confirmed);
        $this->setReference(self::REFERENCE_USER, $confirmed);

        $network = $this->createSignedUpByNetwork(
            new Name('Tom', 'Ser'),
            'facebook',
            '10000001'
        );
        $manager->persist($network);

        $user = $this->createSignUpRequestedByEmail(
            new Name('Vasilyi', 'Alibabaevich'),
            new Email('vasia@app.com'),
            $hash
        );
        $manager->persist($user);

        $manager->flush();
    }

    private function createAdminByEmail(Name $name, Email $email, $hash)
    {
        $user = $this->createSignUpConfirmedByEmail($name, $email, $hash);
        $user->changeRole(Role::admin());

        return $user;
    }

    private function createSignUpConfirmedByEmail(Name $name, Email $email, $hash)
    {
        $user = $this->createSignUpRequestedByEmail($name, $email, $hash);
        $user->confirmSignUp();

        return $user;
    }

    private function createSignUpRequestedByEmail(Name $name, Email $email, $hash)
    {
        return User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $name,
            $email,
            $hash,
            'token');
    }

    private function createSignedUpByNetwork(Name $name, string $network, string $identity)
    {
        return User::signUpByNetwork(
            Id::next(),
            new \DateTimeImmutable(),
            $name,
            $network,
            $identity
        );
    }
}
