<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    public const NB_OF_USERS = 30;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::NB_OF_USERS; $i++) {
            $user = new User();

            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            $user->setEmail($faker->email());
            $user->setbirthdate($faker->dateTime());
            $user->setPseudo($faker->word());
            $user->setPhoneNumber($faker->phoneNumber());
            $user->setPostalCode('69' . sprintf("%03s", rand(0, 999)));
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'toto123'));

            $user->setRegistrationDate($faker->dateTimeThisDecade());
            $user->setLastConnectionDate($faker->dateTimeThisDecade());

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
