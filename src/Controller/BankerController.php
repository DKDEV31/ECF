<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Benefit;
use App\Entity\Client;
use App\Entity\RequestAccount;
use App\Entity\RequestBenefit;
use App\Entity\RequestDelete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerController extends AbstractController
{
    #[Route('/banker', name: 'app_banker')]
    public function index(EntityManagerInterface $entity): Response
    {
        $accountRequests = $this->getUser()->getAccountRequest();
        $deleteRequests = $this->getUser()->getRequestDeletes();
        $benefitRequests = $this->getUser()->getRequestBenefits();
        $clientIds = [];
        foreach ($accountRequests as $request){
            $clientIds[] = $request->getClient()->getId();
        }
        foreach ($deleteRequests as $request){
            $clientIds[] = $request->getClient()->getId();
        }
        foreach ($benefitRequests as $request){
            $clientIds[] = $request->getClient()->getId();
        }
        $clients = $entity->getRepository(Client::class)->findBy(['id' => array_unique($clientIds)]);
        return $this->render('banker/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/banker/request', name: 'app_banker_request')]
    public function requestBanker(): Response{
        $accountRequests = $this->getUser()->getAccountRequest();
        $deleteRequests = $this->getUser()->getRequestDeletes();
        $benefitRequests = $this->getUser()->getRequestBenefits();
        return $this->render('banker/Request.html.twig', [
           'account' => $accountRequests,
            'delete' => $deleteRequests,
            'benefit' => $benefitRequests,

        ]);
    }

    #[Route('/banker/request/createAccount/{id}', name: 'app_banker_request_create_view')]
    public function requestBankerCreateView(EntityManagerInterface $entityManager, $id): Response{
        $request = $entityManager->getRepository(RequestAccount::class)->findOneBy(['id' => $id]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request');
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-view.html.twig', [
           'request' => $request,
           'client' => $client,
        ]);
    }

    #[Route('/banker/request/createBenefit/{id}', name: 'app_banker_request_createBenefit_view')]
    public function requestBankerCreateBenefitView(EntityManagerInterface $entityManager, $id): Response{
        $request = $entityManager->getRepository(RequestBenefit::class)->findOneBy(['id' => $id]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request');
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-view.html.twig', [
            'request' => $request,
            'client' => $client,
        ]);
    }

    #[Route('/banker/request/deleteAccount/{id}', name: 'app_banker_request_delete_view')]
    public function requestBankerDeleteView(EntityManagerInterface $entityManager, $id): Response{
        $request = $entityManager->getRepository(RequestDelete::class)->findOneBy(['id' => $id]);
        if($request->getState() === 'Validé'){
            return $this->redirectToRoute('app_banker_request');
        }
        $clientId = $request->getClient()->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
        return $this->render('banker/Request-view.html.twig', [
            'request' => $request,
            'client' => $client,
        ]);
    }

    #[Route('/banker/account/create/{id}', name: 'app_banker_account_create')]
    public function createAccount($id, EntityManagerInterface $entity): Response{
        $request = $entity->getRepository(RequestAccount::class)->findOneBy(['id' => $id]);
       $account = new Account();
       $account
           ->setClient($request->getClient())
           ->setAccountNumber(uniqid())
           ->setAmount(0)
           ->setType($request->getType());
       $entity->persist($account);
       $request->setState('Validé');
       $entity->flush();
       return $this->redirectToRoute('app_banker_request');
       //notification du bon déroulement de l'operation.
       //envoie d'une notification a l'utilisateur pour l'informer de la création de son compte.
    }
    
    #[Route('/banker/account/delete/{id}', name: 'app_banker_account_delete')]
    public function deleteAccount($id, EntityManagerInterface $entity): Response
    {
        $request = $entity->getRepository(RequestDelete::class)->findOneBy(['id' => $id]);
        $accountId = $request->getAccount()->getId();
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $accountId]);
        $request
            ->setState('Validé')
            ->setAccount(null);
        $entity->persist($request);
        $entity->flush();
        $entity->remove($account);
        $entity->flush();
        return $this->redirectToRoute('app_banker_request');
    }

    #[Route('/banker/benefit/create/{id}', name: 'app_banker_benefit_create')]
    public function createBenefit($id, EntityManagerInterface $entity): Response
    {
        $request = $entity->getRepository(RequestBenefit::class)->findOneBy(['id' => $id]);
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
        return $this->redirectToRoute('app_banker_request');
    }
}
