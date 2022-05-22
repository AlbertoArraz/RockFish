<?php

namespace App\DataFixtures;

use App\Entity\Pez;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PecesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $product = new Pez();
            $product
                ->setNombre('Product ' . $i)
                ->setDescripcion('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua')
                ->setPrecio(mt_rand(10, 600));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
