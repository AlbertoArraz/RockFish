<?php

namespace App\DataFixtures;

use App\Entity\Pez;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tipo;

class PecesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tipo = new Tipo();
        $tipo->setNombre('Tipo de pez')
            ->setDescripcion('Esto es un tipo de prueba');
            $manager->persist($tipo);
        for ($i = 1; $i <= 10; $i++) {
            $product = new Pez();
            $product
                ->setNombre('Pez ' . $i)
                ->setDescripcion('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua')
                ->setPrecio(mt_rand(5, 500))
                ->setTipo($tipo)
                ->setImagen('');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
