<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Benefit;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientBenefitController extends AbstractController
{
    #[Route('/client/{userId}/benefit/view', name: 'app_client_benefit_view')]
    public function benefitClient($userId): Response{
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        $accounts = $user->getAccounts();
        if(count($accounts) < 1){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        return $this->render('client/Benefit.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/client/{userId}/benefit/{accountId}', name: 'app_client_benefit_account')]
    public function benefitAccountClient($accountId, EntityManagerInterface $entity, $userId): Response{
        /** @var Client $user */
        $user= $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
        $this->getInternalBenefits($accountId, $entity);
        $benefits = $entity->getRepository(Benefit::class)->findBy(['Account' => $accountId]);
        return $this->render('client/Benefit.html.twig', [
            'accountId' => $accountId,
            'benefits' => $benefits,
        ]);
    }



    #[Route('/client/{userId}/benefit/delete/{benefitId}', name: 'app_client_benefit_delete')]
    public function benefitDelete($benefitId, EntityManagerInterface $entity, $userId): Response{
        /** @var Client $user */
        $user=$this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId' => $user->getId()]);
        }
        $benefit = $entity->getRepository(Benefit::class)->findOneBy(['id'=>$benefitId]);
        $entity->remove($benefit);
        $entity->flush();
        //Notification du bon déroulement a l'utilisateur
        return $this->redirectToRoute('app_client_benefit_view', ['userId'=>$user->getId()]);
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
