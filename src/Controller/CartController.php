<?php

namespace App\Controller;

use App\Form\CartType;
use App\Entity\OrderItem;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart')]
    public function index(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTime());
            $cartManager->save($cart);

            return $this->redirectToRoute('cart');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/borrar/item/{id}', name: 'item.borrar')]
    public function borrarItem(ManagerRegistry $doctrine,$id)
    {
        $entityManager = $doctrine->getManager();
        $pez = $entityManager->getRepository(OrderItem::class)->find($id);

        if (!$pez){
            throw $this->createNotFoundException(
                'No existe ningun item con id '.$id
            );
        }
        $entityManager->remove($pez);
        $entityManager->flush();
        return $this->redirectToRoute('cart');
    }

    #[Route('/borrar/items{id}', name: 'items.borrar')]
    public function borrarItems(ManagerRegistry $doctrine,$id)
    {
        $entityManager = $doctrine->getManager();
        $items = $entityManager->getRepository(OrderItem::class)->findByOrderId($id);

        if (!$items){
            throw $this->createNotFoundException(
                'No existe ningun order con id '.$id
            );
        }
        foreach ($items as $item) {
            $entityManager->remove($item);
        }
        
        $entityManager->flush();
        return $this->redirectToRoute('cart');
    }
}
