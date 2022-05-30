<?php

namespace App\Controller;

use App\Entity\Pez;
use App\Form\AddToCartType;
use App\Form\PezType;
use App\Entity\Tipo;
use App\Manager\CartManager;
use App\Repository\PezRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PezController extends AbstractController
{
    #[Route("/pez/{id}",name: 'pez.detalles')]
    public function detalles(Pez $pez, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setPez($pez);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->guardar($cart);

            return $this->redirectToRoute('pez.detalles', ['id' => $pez->getId()]);
        }
        
        return $this->render('pez/detalles.html.twig', [
            'pez' => $pez,
            'form' => $form->createView()
        ]);
    }

    #[Route('/crear', name: 'pez.crear')]
    public function crearPez(ManagerRegistry $doctrine,Request $request)
    {
        $entityManager = $doctrine->getManager();
        $pez = new Pez();
    //las imagenes de 200x150
        $form = $this->createForm(PezType::class, $pez);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $imagen = $form->get('imagen')->getData();

            if ($imagen) {

                $filename = md5(uniqid()) . '.' . $imagen->guessClientExtension();
            }

            $imagen->move(
                $this->getParameter('img'),
                $filename
            );

            $pez->setImagen($filename);
            $entityManager->persist($pez);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('pez.editar'));
        }

        return $this->render('pez/crear.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/editar/{id}', name: 'pez.editar')]
    public function editarPez(ManagerRegistry $doctrine,Request $request, $id)
    {
        $entityManager = $doctrine->getManager();
        $pez = $entityManager->getRepository(Pez::class)->find($id);

        if (!$pez) {
            throw $this->createNotFoundException(
                'No existe ningun pez con id ' . $id
            );
        }
        $form = $this->createFormBuilder($pez)
            ->add('nombre')
            ->add('precio')
            ->add('descripcion')
            ->add('imagen', FileType::class)
            ->add('tipo', EntityType::class, ['class' => Tipo::class])
            ->add(
                'guardar',
                SubmitType::class,
                array('label' => 'Editar Pez')
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagen = $form->get('imagen')->getData();

            if ($imagen) {

                $filename = md5(uniqid()) . '.' . $imagen->guessClientExtension();
            }

            $imagen->move(
                $this->getParameter('img'),
                $filename
            );

            $pez->setImagen($filename);
            $entityManager->persist($pez);
            $entityManager->flush();

            return $this->redirectToRoute('pez.detalles', array('id' => $id));
        }

        return $this->render('pez/editar.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    #[Route('/borrar/{id}', name: 'pez.borrar')]
    public function borrarPez(ManagerRegistry $doctrine,$id)
    {
        $entityManager = $doctrine->getManager();
        $pez = $entityManager->getRepository(Pez::class)->find($id);

        if (!$pez){
            throw $this->createNotFoundException(
                'No existe ningun pez con id '.$id
            );
        }
        $entityManager->remove($pez);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}
