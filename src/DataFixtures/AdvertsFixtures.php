<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Adverts;
use App\Entity\Images;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Provider\Image;

class AdvertsFixtures extends Fixture implements DependentFixtureInterface
{
  private array $images;

    public function __construct()
    {
        // Chemin vers le dossier contenant les images
        $imageDirectory = __DIR__ . 'build/images';
        // Obtenir tous les fichiers d'image dans le dossier
        $this->images = glob($imageDirectory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    }

  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    $categories = Adverts::$CATEGORIES;
    $conditions = Adverts::$USECONDITIONS;
    $listOfStatus = Adverts::$STATUS;
    $brand = Adverts::$BRANDS;
    $region = Adverts::$REGIONS;

    for ($i = 0; $i < UserFixtures::NB_OF_USERS; $i++) {
      $user = $this->getReference('user_' . $i);


      $nbAdverts = rand(0, 40);
      for ($j = 0; $j < $nbAdverts; $j++) {
        $advert = new Adverts();
        $advert->setTitle($faker->sentence())
          ->setCategory($categories[array_rand($categories)])
          ->setPrice($faker->randomFloat(2, 20, 500))
          ->setDescription($faker->text())
          ->setBrand($brand[array_rand($brand)])
          ->setUseCondition($conditions[array_rand($conditions)])
          ->setRegion($region[array_rand($region)])
          ->setOwner($user)
          ->setCreatedAt($faker->dateTime())
          ->setUpdatedAt($faker->dateTime())
          ->setStatus($listOfStatus[array_rand($listOfStatus)]);

         // Ajouter une image aléatoire
         if (!empty($this->images)) {
          $randomImagePath = $this->images[array_rand($this->images)];
          $image = new Images();
          $image->setName(basename($randomImagePath)); // Utiliser le nom de fichier de l'image
          $advert->addImage($image);
      }
       
       // $advert->addImage((new Images)->setName($this->image[array_rand($this->image)]));

        $manager->persist($advert);
        
      }
    }
    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      UserFixtures::class,
    ];
  }
}
