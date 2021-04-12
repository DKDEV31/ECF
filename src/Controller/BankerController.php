<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\RequestAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerController extends AbstractController
{
    #[Route('/banker', name: 'app_banker')]
    public function index(): Response
    {
        $requests = $this->getUser()->getAccountRequest();
        $clients = [];
        foreach ($requests as $request){
            $clients[] = $request->getClient();
        }
        return $this->render('banker/index.html.twig', [
            'clients' => $clients
        ]);
    }

    #[Route('/banker/request', name: 'app_banker_request')]
    public function requestBanker(): Response{
        $requests = $this->getUser()->getAccountRequest();
        return $this->render('banker/Request.html.twig', [
           'requests' => $requests,
        ]);
    }

    #[Route('/banker/request/{id}', name: 'app_banker_request_view')]
    public function requestBankerView(EntityManagerInterface $entityManager, $id): Response{
        $request = $entityManager->getRepository(RequestAccount::class)->findOneBy(['id' => $id]);
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
       $entity->flush();
       $request->setState('Validé');
       $entity->persist($request);
       $entity->flush();
       return $this->redirectToRoute('app_banker_request');
       //notification du bon déroulement de l'operation.
       //envoie d'une notification a l'utilisateur pour l'informer de la création de son compte.
    }

}
