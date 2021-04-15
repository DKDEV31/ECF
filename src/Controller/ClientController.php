<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Transfer;
use App\Form\TranferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

    #[Route('/client/{userId}', name: 'app_client')]
    public function index(EntityManagerInterface $entity, $userId): Response
    {
        $user = $this->getUser();
        if($user->getId() != $userId){
            $this->redirectToRoute('app_home');
            dump('ok');
        }
        $accounts = $entity->getRepository(Account::class)->findBy(['Client' => $user]);

        return $this->render('client/Acceuil-Client.html.twig', [
            'accounts' => $accounts,
        ]);
    }

}
