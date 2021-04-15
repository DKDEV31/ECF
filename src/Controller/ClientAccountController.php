<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAccountController extends AbstractController
{
    #[Route('/client/account', name: 'client_account')]
    public function index(): Response
    {
        return $this->render('client_account/index.html.twig', [
            'controller_name' => 'ClientAccountController',
        ]);
    }
}
