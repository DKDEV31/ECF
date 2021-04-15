<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Transfer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAccountController extends AbstractController
{
    #[Route('/client/{userId}/account/{accountId}', name: 'app_client_account_view')]
    public function viewAccount(EntityManagerInterface $entity, $accountId, $userId): Response
    {
        /** @var Client $user */
        $user = $this->getUser();
        if($user->getId() != $userId){
            return $this->redirectToRoute('app_client', ['userId'=>$user->getId()]);
        }
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
}
