<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Genre;
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
            ->setAdress($faker->address)
            ->setRoles(['ROLE_ADMIN', 'ROLE_EMPLOYEE'])
            ->setPassword($this->hasher->hashPassword($employee, 'password'))
            ->setIsBornAt(new \DateTime('1985-01-25'))
            ->setIsActivated(true)
        ;

        $manager->persist($employee);

        // On ajoute un habitant
        $resident = new User();
        $resident
            ->setFirstname('Louis')
            ->setLastname('Resident')
            ->setEmail('resident@resident.net')
            ->setAdress($faker->address)
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->hasher->hashPassword($employee, 'password'))
            ->setIsBornAt(new \DateTime('1994-01-08'))
            ->setIsActivated(true)
        ;

        $manager->persist($resident);

        for ($b = 1; $b <= 15; $b++) {
            // On ajoutes les genres
            for ($g = 1; $g <= 6; $g++) {
                $genre = new Genre();
                $genre
                    ->setName($faker->word())
                ;

                $manager->persist($genre);
            }

            $book        = new Book();
            $description = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $book
                ->setTitle($faker->sentence)
                ->setAuthor($faker->name)
                ->setDescription(strip_tags($description))
                ->setIsReleasedAt($faker->dateTimeBetween('-15 years'))
            ;

            $manager->persist($book);
        }

        $manager->flush();
    }
}
