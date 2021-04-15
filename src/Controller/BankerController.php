<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Banker;
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
    #[Route('/banker/{bankerId}', name: 'app_banker')]
    public function index(EntityManagerInterface $entity, $bankerId): Response
    {
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
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
        $account = $entity->getRepository(Account::class)
            ->findOneBy(['accountNumber' => $request->getAccountNumber()]);
        $request
            ->setState('Validé');
        $entity->persist($request);
        $entity->remove($account);
        $entity->flush();
        return $this->redirectToRoute('app_banker_request');
        //Notif
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
