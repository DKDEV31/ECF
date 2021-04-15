<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Banker;
use App\Entity\Benefit;
use App\Entity\Client;
use App\Entity\RequestAccount;
use App\Entity\RequestBenefit;
use App\Entity\RequestDelete;
use App\Entity\Transfer;
use App\Entity\User;
use App\Form\BenefitAddFormType;
use App\Form\RequestAccountType;
use App\Form\RequestDeleteAccountType;
use App\Form\TranferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

    #[Route('/client', name: 'app_client')]
    public function index(EntityManagerInterface $entity): Response
    {
        $user = $this->getUser();
        $accounts = $entity->getRepository(Account::class)->findBy(['Client' => $user]);

        return $this->render('client/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/client/account/{accountId}', name: 'app_client_account_view')]
    public function viewAccount(EntityManagerInterface $entity, $accountId): Response
    {
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $accountId]);
        $transfers = $entity->getRepository(Transfer::class)->findBy(['Account'=>$account]);
        if (empty($account)){
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/Account-operations-list.html.twig', [
            'transfers' => $transfers,
            'account' => $account,
        ]);
    }

    #[Route('/client/account/add', name: 'app_account_add')]
    public function AddAccount(Request $request, EntityManagerInterface $entityManager): Response{
        $requestAccount = new RequestAccount();
//        /** @var  Client $user */
        $user = $this->getUser();
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

    #[Route('/client/account/{accountId}', name: 'app_client_account_view')]
    public function viewAccount(EntityManagerInterface $entity, $accountId): Response
    {
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $accountId]);
        $transfers = $entity->getRepository(Transfer::class)->findBy(['Account'=>$account]);
        if (empty($account)){
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/Account-operations-list.html.twig', [
            'transfers' => $transfers,
            'account' => $account,
        ]);
    }



    #[Route('/client/account/delete/{id}', name: 'app_account_delete_client')]
    public function deleteAccount(Request $req, EntityManagerInterface $entity, $id): Response{
        $request = new RequestDelete();
        $account = $entity->getRepository(Account::class)->findOneBy(['id' => $id]);
        /** @var Client $user */
        $user = $this->getUser();
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
        if(count($account) < 1){
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/Benefit.html.twig', [
           'accounts' => $account,
        ]);
    }

    #[Route('/client/benefitView/{accountId}', name: 'app_benefit_account_client')]
    public function benefitAccountClient($accountId, EntityManagerInterface $entity): Response{
        $this->getInternalBenefits($accountId, $entity);
        $benefits = $entity->getRepository(Benefit::class)->findBy(['Account' => $accountId]);
        return $this->render('client/Benefit.html.twig', [
            'accountId' => $accountId,
            'benefits' => $benefits,
        ]);
    }

    #[Route('/client/benefitAdd/{accountId}', name: 'app_benefit_add_client')]
    public function benefitAddClient($accountId, EntityManagerInterface $entity, Request $request): Response{
        $requestBenefit = new RequestBenefit();
        /** @var Client $user */
        $user = $this->getUser();
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

    #[Route('/client/benefitDelete/{benefitId}', name: 'app_benefit_delete')]
    public function benefitDelete($benefitId, EntityManagerInterface $entity): Response{
        $benefit = $entity->getRepository(Benefit::class)->findOneBy(['id'=>$benefitId]);
        $entity->remove($benefit);
        $entity->flush();
        //Notification du bon déroulement a l'utilisateur
        return $this->redirectToRoute('app_benefit_client');
    }

    #[Route('/client/transfer', name: 'app_client_transfer')]
    public function clientTransfer(): Response{
        $accounts = $this->getUser()->getAccounts();
        if(count($accounts) < 1 ){
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/Transfer.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/client/transfer/add/{accountId}', name: 'app_client_transfer_add')]
    public function clientDoTransfer(EntityManagerInterface $entity, Request $request, $accountId): Response{
        $this->getInternalBenefits($accountId, $entity);
        $transfer = new Transfer();
        $form = $this->createForm(TranferFormType::class, $transfer, [
            'accountId' => $accountId
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $accountToDebit = $entity->getRepository(Account::class)
                ->findOneBy(['id' => $accountId]);
            if($transfer->getType() === 'Virement Interne'){
                $accountToCredit = $entity->getRepository(Account::class)
                    ->findOneBy(['accountNumber' => $transfer->getBenefit()->getAccountNumber()]);
                $accountToCredit->setAmount($accountToCredit->getAmount() + $transfer->getAmount());
            }
            $accountToDebit->setAmount($accountToDebit->getAmount() - $transfer->getAmount());
            $transfer->setAccount($accountToDebit);
            $entity->persist($transfer);
            $entity->flush();
            //Notification a l'utilisateur.
            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/TransferAddForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Definit si les comptes internes sont des beneficiaires si non alors il les créé
     * @param $accountId
     * @param EntityManagerInterface $entity
     * @return void
     */
    private function getInternalBenefits($accountId, EntityManagerInterface $entity):void
    {
       $clientAccounts = $entity
           ->getRepository(Account::class)
           ->findWhereIdIsNot($accountId, $this->getUser());
        for ($i = 0; $i < count($clientAccounts); $i++){
            $accountNumber = $clientAccounts[$i]->getAccountNumber();
            $internalBenefits = $entity
                ->getRepository(Benefit::class)
                ->findOneBy(['AccountNumber' => $accountNumber, 'Account' => $accountId]);
            if(empty($internalBenefits)){
                $accountToLink = $entity
                    ->getRepository(Account::class)
                    ->findOneBy(['id' => $accountId]);
                $benefit = new Benefit();
                $benefit
                    ->setName($clientAccounts[$i]->getType())
                    ->setBankName('Banqio')
                    ->setAccountNumber($accountNumber)
                    ->setAccount($accountToLink);
                $entity->persist($benefit);
                $entity->flush();
            }
        }
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
