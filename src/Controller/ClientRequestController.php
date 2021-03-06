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
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client',['userId' => $user->getId()]);
        }
        $accountRequests = $entity->getRepository(RequestAccount::class)
            ->findBy(['Client' => $user]);
        $deleteRequests = $entity->getRepository(RequestDelete::class)
            ->findBy(['Client' => $user]);
        $benefitRequests = $entity->getRepository(RequestBenefit::class)
            ->findBy(['Client' => $user]);
        return $this->render('client/Request-Listing.html.twig', [
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
        if($user->getId() != $userId){
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
            $this->addFlash('success', 'Votre demande de compte a ??t?? envoy??e');
            return $this->redirectToRoute('app_client_request', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Form-Account.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/{userId}/request/account/delete/{accountId}', name: 'app_client_request_account_delete')]
    public function deleteAccount(Request $req, EntityManagerInterface $entity, $accountId,$userId): Response{
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=> $user->getId()]);
        }
        $account = $entity->getRepository(Account::class)
            ->findOneBy(['id' => $accountId]);
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
            $this->addFlash('success', 'Votre demande de supression de compte a ??t?? envoy??e');
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Form-Delete-Account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/{userId}/request/benefit/add/{accountId}', name: 'app_client_request_benefit_add')]
    public function benefitAddClient($accountId, EntityManagerInterface $entity, Request $request, $userId): Response{
        $requestBenefit = new RequestBenefit();
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() != $userId){
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
            $this->addFlash('success', 'Votre demande d\'ajout de beneficiare a ??t?? envoy??e');
            return $this->redirectToRoute('app_client_request', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Form-Benefit.html.twig', [
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
            $bankerInfo[$banker->getId()]= $requestAmount;
        }
        $availableBanker = array_keys($bankerInfo, min($bankerInfo));
        $requestedBankerId = $availableBanker[array_rand($availableBanker, 1)];
        $requestedBanker = $entityManager->getRepository(Banker::class)->findOneBy(['id'=>$requestedBankerId]);
        return $requestedBanker;
    }

}
