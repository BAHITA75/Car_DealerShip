<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categories = [
            'Peugeot', 
            'Renault', 
            'Honda', 
            'Toyota', 
            'Fiat', 
            'Seat', 
            'Dacia'];
    
            foreach ($categories as $cat){
                $category = new Category();
    
                $category->setName($cat);
        
                $manager->persist($category);  

                for ($i=0; $i < 6 ; $i++) { 
                    $car = new Car();

                    $car->setName($faker->numerify('vehicle-####'));
                    $car->setNbSeats($faker->randomElement([4, 5, 6]));
                    $car->setNbDoors($faker->randomElement([3, 5]));
                    $car->setCost($faker->numberBetween(10000, 50000));
                    $car->setPicture($faker->imageUrl(640, 480, $car->getName(), false,'', false));
                    $car->setCategory($category);

                        $manager->persist($car); 
                }
            }
            $manager->flush();
    }
}
