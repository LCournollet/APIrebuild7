<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $player = new Player;
            $player->setPlayerName($this->faker->realtext(10));
            $player->setPlayerAge($this->faker->numberBetween(18, 40));
            $player->setPlayerPrice($this->faker->numberBetween(100000, 10000000));
            $player->setPlayerPicture($this->faker->imageUrl(200, 200, 'sports'));
            $manager->persist($player);
        }       
        $manager->flush();

    }
}
