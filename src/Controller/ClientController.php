<?php

namespace App\Controller;

use App\Classes\FindBankerTrait;
use App\Entity\Account;
use App\Entity\Benefit;
use App\Entity\Client;
use App\Entity\RequestAccount;
use App\Entity\RequestBenefit;
use App\Entity\RequestDelete;
use App\Form\BenefitAddFormType;
use App\Form\RequestAccountType;
use App\Form\RequestDeleteAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    use FindBankerTrait;

    #[Route('/client', name: 'app_client')]
    public function index(EntityManagerInterface $entity): Response
    {
        $accounts = $this->getUser()->getAccounts();

        return $this->render('client/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/client/account/add', name: 'app_account_add')]
    public function AddAccount(Request $request, EntityManagerInterface $entityManager): Response{
        $requestAccount = new RequestAccount();
        $form = $this->createForm(RequestAccountType::class, $requestAccount);
        $form->handleRequest($request);
        if($form -> isSubmitted() && $form->isValid()){
            $file = $form->get('idCard')->getData();
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move('./CNI', $filename);
            $requestAccount->setIdCard($filename);
            $requestAccount->setClient($this->getUser());
            $requestAccount->setBanker(FindBankerTrait::findBanker($entityManager));
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

    #[Route('/client/account/delete/{id}', name: 'app_account_delete_client')]
    public function deleteAccount(Request $req, EntityManagerInterface $entity, $id): Response{
        $request = new RequestDelete();
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(RequestDeleteAccountType::class, $request);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $file= $form->get('signature')->getData();
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move('./signature', $filename);
            $request->setCloseRequest($filename);
            $request->setState('En Attente');
            $request->setType('Suppression de compte');
            $request->setClient($this->getUser());
            $request->setBanker(FindBankerTrait::findBanker($entity));
            $request->setAccount($account);
            $entity->persist($request);
            $entity->flush();
            //notification a l'utilisateur pour lui confirmer le bon déroulé de l'action
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/delete-account-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/request', name: 'app_request_client')]
    public function requestClient(): Response{
        $accountRequests = $this->getUser()->getAccountRequest();
        $deleteRequests = $this->getUser()->getRequestDeletes();
        $benefitRequest = $this->getUser()->getRequestBenefits();
        return $this->render('client/request-client.html.twig', [
            'accountRequest' => $accountRequests,
            'deleteRequest' => $deleteRequests,
            'benefitRequest' => $benefitRequest,
        ]);
    }

    #[Route('/client/benefitView', name: 'app_benefit_client')]
    public function benefitClient(): Response{
        $account = $this->getUser()->getAccounts();
        if(empty($account)){
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/Benefit.html.twig', [
           'accounts' => $account,
        ]);
    }

    #[Route('/client/benefitView/{accountId}', name: 'app_benefit_account_client')]
    public function benefitAccountClient($accountId, EntityManagerInterface $entity): Response{
        $defaultBenefits = [];
        $accountId = (int)$accountId;
        foreach ($this->getUser()->getAccounts() as $account){
            if($account->getId() !== $accountId){
                $defaultBenefits[] = $account;
            }
        }
        $benefits = $entity->getRepository(Benefit::class)->findBy(['Account' => $accountId]);
        return $this->render('client/Benefit.html.twig', [
            'accountId' => $accountId,
            'benefits' => $benefits,
            'defaultBenefits' => $defaultBenefits,
        ]);
    }

    #[Route('/client/benefitAdd/{accountId}', name: 'app_benefit_add_client')]
    public function benefitAddClient($accountId, EntityManagerInterface $entity, Request $request): Response{
        $requestBenefit = new RequestBenefit();
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $accountId]);
        $form = $this->createForm(BenefitAddFormType::class, $requestBenefit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $requestBenefit
                ->setAccount($account)
                ->setState('En Attente')
                ->setBanker(FindBankerTrait::findBanker($entity))
                ->setClient($this->getUser())
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

    #[Route('/client/benefitDelete/{benefitId}', name: 'app_benefit_delete')]
    public function benefitDelete($benefitId, EntityManagerInterface $entity): Response{
        $benefit = $entity->getRepository(Benefit::class)->findOneBy(['id'=>$benefitId]);
        $entity->remove($benefit);
        $entity->flush();
        //Notification du bon déroulement a l'utilisateur
        return $this->redirectToRoute('app_benefit_client');
    }
}
