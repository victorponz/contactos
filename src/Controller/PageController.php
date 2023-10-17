<?php

namespace App\Controller;

use App\Entity\Contacto;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);

    }
    #[Route('/', name: 'app_index')]
    public function inicio(ManagerRegistry $doctrine): Response{
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contactos = $repositorio->findAll();

        return $this->render('index.html.twig', ['contactos' => $contactos
        ]);

    }
}
