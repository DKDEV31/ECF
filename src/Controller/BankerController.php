<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerController extends AbstractController
{
    #[Route('/banker', name: 'app_banker')]
    public function index(): Response
    {
        return $this->render('banker/index.html.twig', [
        ]);
    }
}
