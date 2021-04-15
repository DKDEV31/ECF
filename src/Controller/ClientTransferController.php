<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Benefit;
use App\Entity\Client;
use App\Entity\Transfer;
use App\Form\TranferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientTransferController extends AbstractController
{
    #[Route('/client/{userId}/transfer', name: 'app_client_transfer')]
    public function clientTransfer($userId): Response{
        /** @var Client $user */
        $user= $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        $accounts = $user->getAccounts();
        if ($accounts->count() === 0){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Transfer-Debiteur.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/client/{userId}/transfer/add/{accountId}', name: 'app_client_transfer_add')]
    public function clientDoTransfer(EntityManagerInterface $entity, Request $request, $accountId, $userId): Response{
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
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
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Form-Transfer.html.twig', [
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
