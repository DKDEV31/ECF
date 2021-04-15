<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Banker;
use App\Entity\RequestAccount;
use App\Entity\RequestDelete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerAccountController extends AbstractController
{
    #[Route('/banker/{bankerId}/account/create/{requestId}', name: 'app_banker_account_create')]
    public function createAccount($requestId, EntityManagerInterface $entity, $bankerId): Response{
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestAccount::class)->findOneBy(['id' => $requestId]);
        $account = new Account();
        $account
            ->setClient($request->getClient())
            ->setAccountNumber(uniqid())
            ->setAmount(0)
            ->setType($request->getType());
        $entity->persist($account);
        $request->setState('Validé');
        $entity->flush();
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
        //notification du bon déroulement de l'operation.
        //envoie d'une notification a l'utilisateur pour l'informer de la création de son compte.
    }

    #[Route('/banker/{bankerId}/account/delete/{requestId}', name: 'app_banker_account_delete')]
    public function deleteAccount($requestId, EntityManagerInterface $entity, $bankerId): Response
    {
        /** @var Banker $banker */
        $banker = $this->getUser();
        if($banker->getId() != $bankerId){
            return $this->redirectToRoute('app_banker', ['bankerId'=>$banker->getId()]);
        }
        $request = $entity->getRepository(RequestDelete::class)->findOneBy(['id' => $requestId]);
        $account = $entity->getRepository(Account::class)
            ->findOneBy(['accountNumber' => $request->getAccountNumber()]);
        $request
            ->setState('Validé');
        $entity->persist($request);
        $entity->remove($account);
        $entity->flush();
        return $this->redirectToRoute('app_banker_request', ['bankerId'=>$banker->getId()]);
        //Notif
    }
}
