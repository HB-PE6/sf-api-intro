<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  private const COUNTRIES = ["France", "中國", "Australie", "台灣"];

  public function __construct(
    private UserPasswordHasherInterface $hasher
  ) {
  }

  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create();

    $regularUser = new User();
    $regularUser
      ->setEmail('bobby@bob.com')
      ->setPassword($this->hasher->hashPassword($regularUser, "test1234"));

    $manager->persist($regularUser);

    $adminUser = new User();
    $adminUser
      ->setEmail('admin@bob.com')
      ->setPassword($this->hasher->hashPassword($adminUser, "admin1234"))
      ->setRoles(['ROLE_ADMIN']);

    $manager->persist($adminUser);

    $countries = [];

    foreach (self::COUNTRIES as $countryName) {
      $country = new Country();
      $country->setName($countryName);

      $manager->persist($country);
      $countries[] = $country;
    }

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
        )
        ->setCountry($faker->randomElement($countries));

      $manager->persist($post);
    }

    $manager->flush();
  }
}
