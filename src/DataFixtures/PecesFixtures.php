<?php

namespace App\DataFixtures;

use App\Entity\Pez;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tipo;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class PecesFixtures extends Fixture
{

    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        
        $usuario = new User();
        
        $usuario->setEmail('admin@rockfish.com')->setRoles(['ROLE_ADMIN'])->setPassword($this->userPasswordHasherInterface->hashPassword(
            $usuario, "admin123"
        ));
        $manager->persist($usuario);

        $tipo = new Tipo();
        $tipo->setNombre('Pez de Fondo')
            ->setDescripcion('Esto es un tipo de prueba');
            $manager->persist($tipo);

        for ($i = 1; $i <= 5; $i++) {
            $product = new Pez();
            $product
                ->setNombre('Pez ' . $i)
                ->setDescripcion('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua')
                ->setPrecio(mt_rand(5, 500))
                ->setTipo($tipo)
                ->setImagen('2469851f8ac8a98c25e947e31892e834.png');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
