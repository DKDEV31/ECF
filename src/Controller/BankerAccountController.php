<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerAccountController extends AbstractController
{
    #[Route('/banker/account', name: 'banker_account')]
    public function index(): Response
    {
        return $this->render('banker_account/index.html.twig', [
            'controller_name' => 'BankerAccountController',
        ]);
    }
}
