<?php

// src/DataFixtures/UserFixture.php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("test1@test.com");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'adminpass'));

        $manager->persist($user);
        $manager->flush();
    }
}
