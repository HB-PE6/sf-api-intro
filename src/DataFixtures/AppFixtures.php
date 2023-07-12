<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create();

    for ($i = 0; $i < 150; $i++) {
      $post = new Post();

      $post
        ->setTitle($faker->text(25))
        ->setDescription($faker->text(200))
        ->setDateCreated($faker->dateTimeBetween('-3 years'))
        ->setContent(
          $faker->paragraphs(
            $faker->numberBetween(4, 9),
            true
          )
        );

      $manager->persist($post);
    }

    $manager->flush();
  }
}
