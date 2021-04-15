<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerBenefitController extends AbstractController
{
    #[Route('/banker/benefit', name: 'banker_benefit')]
    public function index(): Response
    {
        return $this->render('banker_benefit/index.html.twig', [
            'controller_name' => 'BankerBenefitController',
        ]);
    }
}
