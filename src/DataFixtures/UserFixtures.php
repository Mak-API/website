<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail($faker->email())
                ->setPassword($faker->password())
                ->setLogin($faker->firstName)
                ->setFirstname($faker->firstName)
                ->setLastname($faker->Lastname)
                ->setStatus('1')
                ->setCreatedAt($faker->dateTime)
                ->setUpdatedAt($faker->dateTime)
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
