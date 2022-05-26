<?php

namespace App\Controller;

use App\Repository\PezRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PezRepository $pezRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'peces' => $pezRepository->findAll(),
        ]);
    }
}
