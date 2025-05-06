<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly  UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("root@gmail.com");
        $user->setPassword($this->passwordHasher->hashPassword($user, "root"));
        $user->setRoles(["ROLE_USER",'ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
