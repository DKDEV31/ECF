<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Banker;
use App\Entity\Client;
use App\Entity\RequestAccount;
use App\Entity\RequestBenefit;
use App\Entity\RequestDelete;
use App\Form\BenefitAddFormType;
use App\Form\RequestAccountType;
use App\Form\RequestDeleteAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientRequestController extends AbstractController
{

    #[Route('/client/{userId}/request', name: 'app_client_request')]
    public function requestClient(EntityManagerInterface $entity, $userId): Response{
        $user = $this->getUser();
        if($user->getId() !== $userId){
            return $this->redirectToRoute('app_client',['userId' => $user->getId()]);
        }
        $accountRequests = $entity->getRepository(RequestAccount::class)
            ->findBy(['Client' => $user]);
        $deleteRequests = $entity->getRepository(RequestDelete::class)
            ->findBy(['Client' => $user]);
        $benefitRequests = $entity->getRepository(RequestBenefit::class)
            ->findBy(['Client' => $user]);
        return $this->render('client/request-client.html.twig', [
            'accountRequest' => $accountRequests,
            'deleteRequest' => $deleteRequests,
            'benefitRequest' => $benefitRequests,
        ]);
    }

    #[Route('/client/{userId}/request/account/add', name: 'app_client_request_account_add')]
    public function AddAccount(Request $request, EntityManagerInterface $entityManager, $userId): Response{
        $requestAccount = new RequestAccount();
        /** @var  Client $user */
        $user = $this->getUser();
        if($user->getId() !== $userId){
            return $this->redirectToRoute('app_client', ['userId' => $user->getId()]);
        }
        $form = $this->createForm(RequestAccountType::class, $requestAccount);
        $form->handleRequest($request);
        if($form -> isSubmitted() && $form->isValid()){
            $file = $form->get('idCard')->getData();
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move('./CNI', $filename);
            $requestAccount->setIdCard($filename);
            $requestAccount->setClient($user);
            $requestAccount->setBanker($this->findBanker($entityManager));
            $requestAccount->setState('En Attente');
            $entityManager->persist($requestAccount);
            $entityManager->flush();
            //Envoie d'une notif à l'utilisateur
            return $this->redirectToRoute('app_request_client');
        }
        return $this->render('client/addAccount.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/{userId}/request/account/delete/{accountId}', name: 'app_client_request_account_delete')]
    public function deleteAccount(Request $req, EntityManagerInterface $entity, $accountId,$userId): Response{
        /** @var Client $user */
        $user = $this->getUser();
        $account = $entity->getRepository(Account::class)
            ->findOneBy(['id' => $accountId]);
        if($user->getId() !== $userId){
            return $this->redirectToRoute('app_client', ['userId'=> $user->getId()]);
        }
        $request = new RequestDelete();
        $form = $this->createForm(RequestDeleteAccountType::class, $request);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $file= $form->get('signature')->getData();
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move('./signature', $filename);
            $request->setCloseRequest($filename);
            $request->setState('En Attente');
            $request->setType('Suppression de compte');
            $request->setClient($user);
            $request->setBanker($this->findBanker($entity));
            $request->setAccountNumber($account->getAccountNumber());
            $entity->persist($request);
            $entity->flush();
            //notification a l'utilisateur pour lui confirmer le bon déroulé de l'action
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/delete-account-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/{userId}/request/benefit/add/{accountId}', name: 'app_client_request_benefit_add')]
    public function benefitAddClient($accountId, EntityManagerInterface $entity, Request $request, $userId): Response{
        $requestBenefit = new RequestBenefit();
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() !== $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $accountId]);
        $form = $this->createForm(BenefitAddFormType::class, $requestBenefit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $requestBenefit
                ->setAccount($account)
                ->setState('En Attente')
                ->setBanker($this->findBanker($entity))
                ->setClient($user)
                ->setType('Ajout de beneficiaire');
            $entity->persist($requestBenefit);
            $entity->flush();
            //notification du bon deroulement et creation d'une notif banquier
            return $this->redirectToRoute('app_request_client');
        }
        return $this->render('client/BenefitForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Definie le banquier a qui confier la demande en fonction du nombre de demande qu'il a deja
     * @param EntityManagerInterface $entityManager
     * @return Banker|object|null
     */
    private function findBanker(EntityManagerInterface $entityManager){
        $bankers = $entityManager->getRepository(Banker::class)->findAll();
        $bankerInfo = [];
        foreach ($bankers as $banker){
            $requestAmount = count($banker->getRequestDeletes()) +
                count($banker->getRequestBenefits()) +
                count($banker->getAccountRequest());
            $bankerInfo[] = [$banker->getId() => $requestAmount];
        }
        $arrayIndex = array_rand(array_keys($bankerInfo,min($bankerInfo)));
        $id = array_keys($bankerInfo,min($bankerInfo));
        $requestedBankerId = count($id) > 1 ? $id[$arrayIndex] : $id;
        $requestedBanker = $entityManager->getRepository(Banker::class)->findOneBy(['id'=>$requestedBankerId]);
        return $requestedBanker;
    }

}
