<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientBenefitController extends AbstractController
{
    #[Route('/client/benefit', name: 'client_benefit')]
    public function index(): Response
    {
        return $this->render('client_benefit/index.html.twig', [
            'controller_name' => 'ClientBenefitController',
        ]);
    }
}
