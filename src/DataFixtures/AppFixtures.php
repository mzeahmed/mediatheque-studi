<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // On ajoute un employé
        $employee = new User();
        $employee
            ->setFirstname('Ahmed')
            ->setLastname('Mze')
            ->setEmail('houdjiva@gmail.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_EMPLOYEE'])
            ->setPassword($this->hasher->hashPassword($employee, 'password'))
            ->setIsBornAt(new \DateTime('1985-01-25'))
        ;

        $manager->persist($employee);

        // On ajoute des habitants
        $residents = [];
        $genres    = ['male', 'female'];

        for ($i = 1; $i <= 30; $i++) {
            $resident = new User();
            $genre    = $faker->randomElement($genres);
            $password = $this->hasher->hashPassword($resident, 'password');

            $resident
                ->setFirstname($faker->firstName($genre))
                ->setLastname($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setRoles(['ROLE_USER', 'ROLE_RESIDENT'])
                ->setPassword($password)
                ->setIsBornAt($faker->dateTimeBetween('-35 years'))
            ;

            $manager->persist($resident);
            $residents[] = $resident;
        }

        $manager->flush();
    }
}
