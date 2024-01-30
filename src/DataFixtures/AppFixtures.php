<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Player;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Faker
     *
     * @var Generator
     */
    private Generator $faker;

    /**
     * User Password Hasher
     *
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * Constructeur des Fixtures
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     */

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create("fr_FR");
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $users = [];

        $publicUser = new User();
        $publicUser->setUsername("public@public");
        $publicUser->setRoles(["ROLE_PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $manager->persist($publicUser);
        // $users[] = $publicUser;

        $adminUser = new User();
        $adminUser->setUsername("admin");
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
        $manager->persist($adminUser);
        $users[] = $adminUser;


        for ($i=0; $i < 5; $i++) { 
            $userUser = new User();
            $password = $this->faker->password(5,10);
            $username = $this->faker->userName();
            $userUser->setUsername($username . "@" . $password);
            $userUser->setRoles(["ROLE_USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $manager->persist($userUser);
            $users[] = $userUser;  
        }




        $listTeam = [];
        for ($i = 0; $i < 10; $i++) {
            $team = new Team();
            $team->setTeamName($this->faker->realtext(10));
            $manager->persist($team);
            $listTeam[] = $team;
        }

        for ($i = 0; $i < 20; $i++) {
            $player = new Player();
            $player->setPlayerName($this->faker->realtext(10));
            $player->setPlayerAge($this->faker->numberBetween(18, 40));
            $player->setPlayerPrice($this->faker->numberBetween(100000, 10000000));
            $player->setPlayerPicture($this->faker->imageUrl(200, 200, 'sports'));
            $player->setTeam($listTeam[array_rand($listTeam)]);
            $manager->persist($player);
        }       
        $manager->flush();
    }
}
