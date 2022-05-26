<?php

namespace App\Controller;

use App\Entity\Pez;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\PezRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

            $cartManager->save($cart);

            return $this->redirectToRoute('pez.detalles', ['id' => $pez->getId()]);
        }
        
        return $this->render('pez/detalles.html.twig', [
            'pez' => $pez,
            'form' => $form->createView()
        ]);
    }
}
