<?php

namespace App\Controller;

use App\Entity\Banker;
use App\Entity\Client;
use App\Entity\RequestAccount;
use App\Entity\RequestBenefit;
use App\Entity\RequestDelete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerRequestController extends AbstractController
{
    #[Route('/banker/{bankerId}/request', name: 'app_banker_request')]
    public function requestBanker($bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $accountRequests = $this->getUser()->getAccountRequest();
        $deleteRequests = $this->getUser()->getRequestDeletes();
        $benefitRequests = $this->getUser()->getRequestBenefits();
        return $this->render('banker/Request.html.twig', [
            'account' => $accountRequests,
            'delete' => $deleteRequests,
            'benefit' => $benefitRequests,

        ]);
    }

    #[Route('/banker/{bankerId}/request/create-account/{requestId}', name: 'app_banker_request_create_account')]
    public function requestBankerCreateView(EntityManagerInterface $entityManager, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entityManager->getRepository(RequestAccount::class)->findOneBy(['id' => $requestId]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-Valid.html.twig', [
            'request' => $request,
            'client' => $client,
        ]);
    }

    #[Route('/banker/{bankerId}/request/create-benefit/{requestId}', name: 'app_banker_request_create_benefit')]
    public function requestBankerCreateBenefitView(EntityManagerInterface $entityManager, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entityManager->getRepository(RequestBenefit::class)->findOneBy(['id' => $requestId]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-Valid.html.twig', [
            'request' => $request,
            'client' => $client,
        ]);
    }

    #[Route('/banker/{bankerId}/request/delete-account/{requestId}', name: 'app_banker_request_delete_account')]
    public function requestBankerDeleteView(EntityManagerInterface $entityManager, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entityManager->getRepository(RequestDelete::class)->findOneBy(['id' => $requestId]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-Valid.html.twig', [
            'request' => $request,
            'client' => $client,
        ]);
    }

    #[Route('/banker/{bankerId}/benefit/deny/{requestId}', name: 'app_banker_request_benefit_deny')]
    public function denyRequestBenefitBanker(EntityManagerInterface $entity, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestBenefit::class)->findOneBy(['id'=>$requestId]);
        $request->setState('Refusée');
        $entity->flush();
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
    }

    #[Route('/banker/{bankerId}/requestAdd/deny/{requestId}', name: 'app_banker_request_accountAdd_deny')]
    public function denyRequestAccountAddBanker(EntityManagerInterface $entity, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestAccount::class)->findOneBy(['id'=>$requestId]);
        $request->setState('Refusée');
        $entity->flush();
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
    }

    #[Route('/banker/{bankerId}/requestDelete/deny/{requestId}', name: 'app_banker_request_accountDelete_deny')]
    public function denyRequestAccountDeleteBanker(EntityManagerInterface $entity, $requestId, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestDelete::class)->findOneBy(['id'=>$requestId]);
        $request->setState('Refusée');
        $entity->flush();
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
    }
}
