<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankProductController extends AbstractController
{
    #[Route('/bankProduct', name: 'app_bank_product')]
    public function index(): Response
    {
        return $this->render('bank_product/index.html.twig', [

        ]);
    }
}
