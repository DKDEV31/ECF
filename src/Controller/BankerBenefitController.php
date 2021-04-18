<?php

namespace App\Controller;

use App\Entity\Banker;
use App\Entity\Benefit;
use App\Entity\RequestBenefit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerBenefitController extends AbstractController
{
    #[Route('/banker/{bankerId}/benefit/create/{requestId}', name: 'app_banker_benefit_create')]
    public function createBenefit($requestId, EntityManagerInterface $entity, $bankerId): Response
    {
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestBenefit::class)->findOneBy(['id' => $requestId]);
        $benefit = new Benefit();
        $benefit
            ->setAccount($request->getAccount())
            ->setAccountNumber($request->getAccountNumber())
            ->setBankName($request->getBankName())
            ->setName($request->getName());
        $request
            ->setState('Validé');
        $entity->persist($benefit);
        $entity->flush();
        $this->addFlash('success', 'Le beneficiaire du client a été créé');
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
    }
}
