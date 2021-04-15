<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Benefit;
use App\Entity\Transfer;
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

}
